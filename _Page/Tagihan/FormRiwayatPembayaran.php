<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }
    //Tangkap id_fee_component
    if(empty($_POST['id_fee_component'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Komponen Biaya Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Tangkap id_student
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_component=validateAndSanitizeInput($_POST['id_fee_component']);
    $id_student=validateAndSanitizeInput($_POST['id_student']);

    //Buka Detail Siswa
    $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
    $Qry->bind_param("i", $id_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $id_organization_class  =$Data['id_organization_class'];
    $student_nis            =$Data['student_nis'] ?? '-';
    $student_name           =$Data['student_name'];
    $student_gender         =$Data['student_gender'];
    $student_registered     =$Data['student_registered'];
    $student_status         =$Data['student_status'];

    //Format Tanggal Daftar
    $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

    //Status
    if($student_status=="Terdaftar"){
        $label_status='<span class="badge badge-success">Terdaftar</span>';
    }else{
        if($student_status=="Lulus"){
            $label_status='<span class="badge badge-warning">Lulus</span>';
        }else{
            $label_status='<span class="badge badge-danger">Keluar</span>';
        }
    }

    //Buka Kelas
    if(empty($Data['id_organization_class'])){
        $label_kelas='-';
    }else{
        $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
        $label_kelas="$level-$kelas";
    }

    //Buka Detail Komponen Biaya
    $QryFee = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_component = ? AND id_student = ?");
    $QryFee->bind_param("ii", $id_fee_component,$id_student);
    if (!$QryFee->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultFee = $QryFee->get_result();
    $DataFee = $ResultFee->fetch_assoc();
    $QryFee->close();

    //Buat Variabel
    $fee_nominal    = $DataFee['fee_nominal'];
    $fee_discount   = $DataFee['fee_discount'];
    
    //Format Rupiah
    $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format="Rp " . number_format($fee_discount,0,',','.');

    //Buka id_fee_component
    $component_name=GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category=GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_start=GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'periode_start');
    $periode_end=GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'periode_end');

    $jumlah_tagihan=$fee_nominal- $fee_discount;
    $jumlah_tagihan_format="Rp " . number_format($jumlah_tagihan,0,',','.');

    //Tampilkan Data
    echo '
        <div class="row mb-2">
            <div class="col-12">
                <small>
                    <b>1. Identitas Siswa</b>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Nama</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$student_name.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$student_nis.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Kelas</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$label_kelas.'</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Tgl.Daftar</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$tanggal_daftar.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        '.$label_status.'
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <small>
                    <b>2. Komponen Biaya</b>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Nama Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$component_name.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Kategori Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$component_category.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Nominal</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$fee_nominal_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Dikon/Potongan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$fee_discount_format.'</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Dikon/Potongan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$fee_discount_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Mulai Tempo</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.date('d/m/Y', strtotime($periode_start)).'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Mulai Tempo</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.date('d/m/Y', strtotime($periode_end)).'</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2 border-1 border-top">
            <div class="col-12 mt-3">
                <small>
                    <b>3. Riwayat Pembayaran</b>
                </small>
            </div>
        </div>
    ';
    //Menampilkan Komponen Biaya/Tagihan
    echo '<div class="row mb-2">';
    echo '  <div class="col-12">';
    echo '      <div class="table table-responsive">';
    echo '          <table class="table table-hover table-striped ">';
    echo '              
                        <thead>
                            <tr>
                                <th><b>No</b></th>
                                <th><b>Tgl.Bayar</b></th>
                                <th><b>Uraian</b></th>
                                <th><b>Metode</b></th>
                                <th><b>Nominal</b></th>
                                <th><b>Opsi</b></th>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
                            $no=1;
                            $JumlahPembayaran = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
                            if(empty($JumlahPembayaran)){
                                echo '
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <small>Tidak Ada Riwayat Pembayaran</small>
                                        </td>
                                    </tr>
                                ';
                            }else{
                                $query = mysqli_query($Conn, "SELECT*FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component' ORDER BY id_payment ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_payment = $data['id_payment'];
                                    $id_organization_class= $data['id_organization_class'];
                                    $id_fee_component = $data['id_fee_component'];
                                    $payment_datetime= $data['payment_datetime'];
                                    $payment_nominal= $data['payment_nominal'];
                                    $payment_method= $data['payment_method'];
                                    
                                    //Buka Detail Komponen
                                    $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                    $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

                                    //Format Rupiah
                                    $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

                                    //Format tanggal bayar
                                    $tanggal_bayar=date('d/m/Y',strtotime($payment_datetime));
                                    echo '
                                        <tr>
                                            <td><small>'.$no.'</small></td>
                                            <td><small>'.$tanggal_bayar.'</small></td>
                                            <td><small>'.$component_name.' | '.$component_category.'</small></td>
                                            <td><small>'.$payment_method.'</small></td>
                                            <td><small>'.$payment_nominal_format.'</small></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ModalDetailPembayaran" data-id="'.$id_payment .'"  title="Detail Pembayaran">
                                                        <i class="bi bi-info-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#ModalHapusPembayaran" data-id="'.$id_payment .'" title="Hapus Pembayaran">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ';
                                    $no++;
                                }
                            }
    echo '';
    echo '              </tbody>';
    echo '          </div>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
?>
<script>
    var id_siswa="<?php echo $id_student; ?>";
    // Tempelkan ke atribut data-id tombol
    $(".kembali_ke_modal_tagihan").attr("data-id", id_siswa);
</script>