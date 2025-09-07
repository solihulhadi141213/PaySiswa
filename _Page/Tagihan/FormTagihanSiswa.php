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
    }else{
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

        //Parent
        $parent_arry=json_decode($student_parent, true);
        if(empty($parent_arry['nama'])){
            $parent_nama="-";
        }else{
            $parent_nama=$parent_arry['nama'];
        }
        if(empty($parent_arry['kontak'])){
            $parent_kontak="-";
        }else{
            $parent_kontak=$parent_arry['kontak'];
        }

        //Tempat lahir
        if(empty($place_of_birth)){
            $place_of_birth ="-";
        }else{
            $place_of_birth =$Data['place_of_birth'];
        }

        //Tanggal Lahir
        if($Data['date_of_birth']=="0000-00-00"){
            $date_of_birth="-";
        }else{
            $date_of_birth =date('d F Y', strtotime($Data['date_of_birth']));
        }

        //Kontak
        if(empty($Data['student_contact'])){
            $student_contact ="-";
        }else{
            $student_contact =$Data['student_contact'];
        }

        //Kontak
        if(empty($Data['student_email'])){
            $student_email ="-";
        }else{
            $student_email =$Data['student_email'];
        }

        //student_address
        if(empty($Data['student_address'])){
            $student_address ="-";
        }else{
            $student_address =$Data['student_address'];
        }

        //student_foto
        if(empty($Data['student_foto'])){
            $student_foto ="No-Image.png";
        }else{
            $student_foto =$Data['student_foto'];
        }

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
            <div class="row mb-2 border-1 border-top">
                <div class="col-12 mt-3">
                    <small>
                        <b>2. Uraian Tagihan</b>
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
                                    <th><b>Keterangan</b></th>
                                    <th><b>Tagihan</b></th>
                                    <th><b>Diskon</b></th>
                                    <th><b>Bayar</b></th>
                                    <th><b>Sisa</b></th>
                                    <th><b>Opsi</b></th>
                                </tr>
                            </thead>
        ';
        echo '              <tbody>';
                                $no=1;
                                $JumlahKomponen = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student'"));
                                if(empty($JumlahKomponen)){
                                    echo '
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <small>Tidak Ada Data Komponen Tagihan</small>
                                            </td>
                                        </tr>
                                    ';
                                }else{
                                    $jumlah_fee_nominal=0;
                                    $jumlah_fee_discount=0;
                                    $jumlah_pembayaran_masuk=0;
                                    $jumlah_sisa_pembayaran=0;
                                    $query = mysqli_query($Conn, "SELECT*FROM fee_by_student  WHERE id_student='$id_student' ORDER BY id_fee_by_student ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_fee_by_student = $data['id_fee_by_student'];
                                        $id_organization_class= $data['id_organization_class'];
                                        $id_fee_component = $data['id_fee_component'];
                                        $fee_nominal= $data['fee_nominal'];
                                        $fee_discount= $data['fee_discount'];

                                        $jumlah_tagihan=$fee_nominal-$fee_discount;
                                        
                                        //Buka Detail Komponen
                                        $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

                                        //Format Rupiah
                                        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
                                        $fee_discount_format="Rp " . number_format($fee_discount,0,',','.');

                                        //Hitung Pembayaran Yang Sudah Masuk
                                        $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
                                        $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

                                        //Format Rupiah
                                        $JumlahPembayaranMasukFormat="Rp " . number_format($JumlahPembayaranMasuk,0,',','.');

                                        //Hitung sisa pembayaran
                                        $sisa_pembayaran=$jumlah_tagihan-$JumlahPembayaranMasuk;

                                        //Format Rupiah
                                        $sisa_pembayaran_format="Rp " . number_format($sisa_pembayaran,0,',','.');

                                        //Routing Tombol Berdasarkan Sisa Pembayaran
                                        if($sisa_pembayaran>0){
                                            $tomol_bayar='<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id_fee_component="'.$id_fee_component .'" data-id_student="'.$id_student .'">Bayar</button>';
                                        }else{
                                            $tomol_bayar='<button type="button" disabled class="btn btn-sm btn-success">Lunas</button>';
                                        }

                                        //Hitung Total
                                        $jumlah_fee_nominal=$jumlah_fee_nominal+$fee_nominal;
                                        $jumlah_fee_discount=$jumlah_fee_discount+$fee_discount;
                                        $jumlah_pembayaran_masuk=$jumlah_pembayaran_masuk+$JumlahPembayaranMasuk;
                                        $jumlah_sisa_pembayaran=$jumlah_sisa_pembayaran+$sisa_pembayaran;
                                        echo '
                                            <tr>
                                                <td><small>'.$no.'</small></td>
                                                <td><small>'.$component_name.' | '.$component_category.'</small></td>
                                                <td><small>'.$fee_nominal_format.'</small></td>
                                                <td><small>'.$fee_discount_format.'</small></td>
                                                <td><small>'.$JumlahPembayaranMasukFormat.'</small></td>
                                                <td><small>'.$sisa_pembayaran_format.'</small></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                        <li class="dropdown-header text-start">
                                                            <h6>Option</h6>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalBayar" data-id1="'.$id_fee_component .'" data-id2="'.$id_student .'" title="Tambah Pembayaran">
                                                                <i class="bi bi-cash-coin"></i> Bayar Tagihan
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahTagihan" data-id="'.$id_fee_by_student .'" title="Ubah Tagihan">
                                                                <i class="bi bi-pencil"></i> Ubah Tagihan
                                                            </a>
                                                        </li>
                                                         <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id1="'.$id_fee_component .'" data-id2="'.$id_student .'" title="Riwayat Pembayaran">
                                                                <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        ';
                                        $no++;
                                    }
                                    $jumlah_fee_nominal="Rp " . number_format($jumlah_fee_nominal,0,',','.');
                                    $jumlah_fee_discount="Rp " . number_format($jumlah_fee_discount,0,',','.');
                                    $jumlah_pembayaran_masuk="Rp " . number_format($jumlah_pembayaran_masuk,0,',','.');
                                    $jumlah_sisa_pembayaran="Rp " . number_format($jumlah_sisa_pembayaran,0,',','.');
                                    echo '
                                        <tr>
                                            <td></td>
                                            <td><b>Jumlah</b></td>
                                            <td><b>'.$jumlah_fee_nominal.'</b></td>
                                            <td><b>'.$jumlah_fee_discount.'</b></td>
                                            <td><b>'.$jumlah_pembayaran_masuk.'</b></td>
                                            <td><b>'.$jumlah_sisa_pembayaran.'</b></td>
                                            <td></td>
                                        </tr>
                                    ';
                                }
        echo '';
        echo '              </tbody>';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }
?>