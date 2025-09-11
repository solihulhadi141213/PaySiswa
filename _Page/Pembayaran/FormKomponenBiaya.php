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
    //Tangkap id_organization_class
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID sISWA Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_student=validateAndSanitizeInput($_POST['id_student']);

    //Buka Data sISWA
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
    $student_nisn           =$Data['student_nisn'] ?? '-';
    $student_name           =$Data['student_name'];
    $student_gender         =$Data['student_gender'];
    $student_parent         =$Data['student_parent'];
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

    //Tampilkan Data
    echo '
        <div class="row mb-2">
            <div class="col-12">
                <small>
                    <b>A. Identitas Siswa</b>
                </small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Nama Siswa</small></div>
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
                    <div class="col-4"><small>Gender</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$student_gender.'</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Group/Kelas</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">'.$label_kelas.'</small>
                    </div>
                </div>
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
                    <b>B. Biaya Pendidikan Siswa</b>
                </small>
            </div>
        </div>
    ';

    //Hitung Periode Pendidikan
    $jml_data_periode_akademik = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period"));

    //Jika Tidak ada Periode akademik
    if(empty($jml_data_periode_akademik)){
        echo '
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <div class="alert alert-danger"><small>Belum ada periode akademik yang terdaftar!</small></div>
                </div>
            </div>
        ';
        exit;
    }
    
    //Looping periode akademik
    echo '<div class="accordion accordion-flush" id="accordionFlushExample">';
        $no_tahun_akademik=1;
        $query_periode_akademik = mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student ORDER BY id_organization_class ASC");
        while ($data_periode_akademik = mysqli_fetch_array($query_periode_akademik)) {
            $id_organization_class = $data_periode_akademik['id_organization_class'];
            //Buka Periode Akademik
            $id_academic_period=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
            $academic_period=GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading<?php echo $no_tahun_akademik;?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $no_tahun_akademik;?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $no_tahun_akademik;?>">
                        <small><?php echo "$no_tahun_akademik. Tahun Akademik $academic_period";?></small>
                    </button>
                </h2>
                <div id="flush-collapse<?php echo $no_tahun_akademik;?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $no_tahun_akademik;?>" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped ">
                                <thead>
                                    <tr>
                                        <th><small>No</small></th>
                                        <th><small>Uraian</small></th>
                                        <th><small>Kategori</small></th>
                                        <th><small>Bulan</small></th>
                                        <th><small>Tahun</small></th>
                                        <th><small>Tagihan</small></th>
                                        <th><small>Bayar</small></th>
                                        <th><small>Sisa</small></th>
                                        <th><small>Opsi</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no=1;
                                        $JumlahKomponen = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
                                        if(empty($JumlahKomponen)){
                                            echo '
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <small>Tidak Ada Data Komponen Tagihan</small>
                                                    </td>
                                                </tr>
                                            ';
                                            exit;
                                        }
                                        $query = mysqli_query($Conn, "SELECT*FROM fee_by_student  WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' ORDER BY id_fee_by_student ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_fee_by_student = $data['id_fee_by_student'];
                                            $id_organization_class= $data['id_organization_class'];
                                            $id_fee_component = $data['id_fee_component'];
                                            $fee_nominal= $data['fee_nominal'];
                                            $fee_discount= $data['fee_discount'];
                                            $fee_nominal= $fee_nominal-$fee_discount;
                                            
                                            //Buka Detail Komponen
                                            $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                            $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
                                            $periode_month=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                                            $periode_year=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
                                            $nama_bulan=getNamaBulan($periode_month);

                                            //Format Rupiah
                                            $fee_nominal_format="" . number_format($fee_nominal,0,',','.');

                                            //Hitung Pembayaran Yang Sudah Masuk
                                            $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
                                            $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

                                            //Format Rupiah
                                            $JumlahPembayaranMasukFormat="" . number_format($JumlahPembayaranMasuk,0,',','.');

                                            //Hitung sisa pembayaran
                                            $sisa_pembayaran=$fee_nominal-$JumlahPembayaranMasuk;

                                            //Format Rupiah
                                            $sisa_pembayaran_format="" . number_format($sisa_pembayaran,0,',','.');

                                            //Routing Tombol Berdasarkan Sisa Pembayaran
                                            if($sisa_pembayaran>0){
                                                $tomol_bayar='<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id_fee_component="'.$id_fee_component .'" data-id_student="'.$id_student .'">Bayar</button>';
                                            }else{
                                                $tomol_bayar='<button type="button" disabled class="btn btn-sm btn-success">Lunas</button>';
                                            }
                                            if(empty($sisa_pembayaran)){
                                                $color_text="text-success";
                                            }else{
                                                if(empty($JumlahPembayaranMasuk)){
                                                    $color_text="text-grayish";
                                                }else{
                                                    $color_text="text-dark";
                                                }
                                            }
                                            echo '
                                                <tr>
                                                    <td><small class="text '.$color_text.'">'.$no.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$component_name.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$component_category.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$nama_bulan.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$periode_year.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$fee_nominal_format.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$JumlahPembayaranMasukFormat.'</small></td>
                                                    <td><small class="text '.$color_text.'">'.$sisa_pembayaran_format.'</small></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                            <li class="dropdown-header text-start">
                                                                <h6>Option</h6>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalBayar" data-id="'.$id_fee_by_student .'">
                                                                    <i class="bi bi-plus"></i> Bayar
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" data-id="'.$id_fee_by_student .'">
                                                                    <i class="bi bi-info-circle"></i> Detail Tagihan
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditTagihan" data-id="'.$id_fee_by_student .'">
                                                                    <i class="bi bi-pencil"></i> Edit Tagihan
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihan" data-id="'.$id_fee_by_student .'">
                                                                    <i class="bi bi-x"></i> Hapus Tagihan
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            ';
                                            $no++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<?php
        $no_tahun_akademik++;
    }
    echo '</div>';
?>