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
                    ID Siswa Tidak Boleh Kosong!
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
    $id_organization_class  = $Data['id_organization_class'];
    $student_nis            = $Data['student_nis'] ?? '-';
    $student_name           = $Data['student_name'];
    $student_gender         = $Data['student_gender'];
    $student_status         = $Data['student_status'];

    //Routing Gender
    if($student_gender=="Male"){
        $student_gender = "Laki-laki";
    }else{
        if($student_gender=="Female"){
            $student_gender = "Perempuan";
        }else{
            $student_gender = "None";
        }
    }
    echo '
        <input type="hidden" name="Page" value="Siswa">
        <input type="hidden" name="Sub" value="Detail">
        <input type="hidden" name="id" value="'.$id_student.'">
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Nama Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Jenis Kelamin</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_gender.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_status.'</small></div>
                </div>
            </div>
        </div>
    ';
    echo '<div class="row">';
    echo '  <div class="col-md-12">';
    echo '      <div class="table table-responsive border-1 border-top">';
    echo '          <table class="table table-striped table-hover">';
    echo '              
                        <thead>
                            <tr>
                                <td align="center"><small><b>No</b></small></td>
                                <td align="center"><small><b>Periode Akademik</b></small></td>
                                <td align="center"><small><b>Jenjang</b></small></td>
                                <td align="center"><small><b>Kelas</b></small></td>
                                <td align="center"><small><b>Nominal</b></small></td>
                                <td align="center"><small><b>Diskon</b></small></td>
                                <td align="center"><small><b>Tagihan</b></small></td>
                                <td align="center"><small><b>Pembayaran</b></small></td>
                                <td align="center"><small><b>Sisa/Tunggakan</b></small></td>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
    //Atur Nomor
    $no = 1;

    //Inisialisasi Jumlah Total
    $subtotal_komonen           = 0;
    $subtotal_tagihan           = 0;
    $subtotal_diskon            = 0;
    $subtotal_tagihan_netto     = 0;
    $subtotal_pembayaran        = 0;
    $subtotal_tunggakan         = 0;
    //Looping Query
    $query = mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student'");
    while ($data = mysqli_fetch_array($query)) {
        $id_organization_class = $data['id_organization_class'];

        //Buka Informasi Kelas pada tabel 'organization_class'
        $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
        $level_student      = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $kelas_student      = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

        //Buka Periode Akademik
        $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

        //Menghitung Komponen Biaya Pendidikan
        $jumlah_komponen    = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));

        //Menghitung jumlah tagihan
        $SumTagihan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS jumlah_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
        $jumlah_tagihan         = $SumTagihan['jumlah_tagihan'];
        $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

        //Menghitung Jumlah Diskon
        $SumDiskon             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
        $jumlah_diskon         = $SumDiskon['jumlah_diskon'];
        $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');

        //Jumlah Tagihan Netto
        $jumlah_tagihan_netto           = $jumlah_tagihan-$jumlah_diskon;
        $jumlah_tagihan_netto_format    = "Rp " . number_format($jumlah_tagihan_netto,0,',','.');

        //Hitung Jumlah Pembayaran
        $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
        $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');

        //Menghitung Sisa Tagihan
        $jumlah_sisa_tagihan        = $jumlah_tagihan_netto-$jumlah_pembayaran;
        $jumlah_sisa_tagihan_format = "Rp " . number_format($jumlah_sisa_tagihan,0,',','.');

        //menampilkan data pada baris tabel
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$academic_period.'</small></td>
                <td><small>'.$level_student.'</small></td>
                <td><small>'.$kelas_student.'</small></td>
                <td><small>'.$jumlah_tagihan_format.'</small></td>
                <td><small>'.$jumlah_diskon_format.'</small></td>
                <td><small>'.$jumlah_tagihan_netto_format.'</small></td>
                <td><small>'.$jumlah_pembayaran_format.'</small></td>
                <td><small>'.$jumlah_sisa_tagihan_format.'</small></td>
            </tr>
        ';
        $no++;

        //Menghitung Subtotal
        $subtotal_komonen           = $subtotal_komonen + $jumlah_komponen;
        $subtotal_tagihan           = $subtotal_tagihan + $jumlah_tagihan;
        $subtotal_diskon            = $subtotal_diskon + $jumlah_diskon;
        $subtotal_tagihan_netto     = $subtotal_tagihan_netto + $jumlah_tagihan_netto;
        $subtotal_pembayaran        = $subtotal_pembayaran + $jumlah_pembayaran;
        $subtotal_tunggakan         = $subtotal_tunggakan + $jumlah_sisa_tagihan;
    }
    //Format Rupiah
    $subtotal_tagihan_format        = "Rp " . number_format($subtotal_tagihan,0,',','.');
    $subtotal_diskon_format         = "Rp " . number_format($subtotal_diskon,0,',','.');
    $subtotal_tagihan_netto_format  = "Rp " . number_format($subtotal_tagihan_netto,0,',','.');
    $subtotal_pembayaran_format     = "Rp " . number_format($subtotal_pembayaran,0,',','.');
    $subtotal_tunggakan_format      = "Rp " . number_format($subtotal_tunggakan,0,',','.');

    //Menampilkan Total
    echo '
        <tr>
            <td></td>
            <td colspan="3"><b>JUMLAH/TOTAL</b></td>
            <td><b>'.$subtotal_tagihan_format.'</b></td>
            <td><b>'.$subtotal_diskon_format.'</b></td>
            <td><b>'.$subtotal_tagihan_netto_format.'</b></td>
            <td><b>'.$subtotal_pembayaran_format.'</b></td>
            <td><b>'.$subtotal_tunggakan_format.'</b></td>
        </tr>
    ';
    echo '              </tbody>';
    echo '          </table>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
?>