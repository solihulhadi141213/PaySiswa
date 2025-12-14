<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Menangkap 'id_fee_by_student'
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Tagihan Tidak Boleh Kosong!</small>
            </div>
            <script>
                $("#button_request_payment").prop("disabled", true);
            </script>
        ';
        exit;
    }

    // Membuat Variabel Dan Sanitasi
    $id_fee_by_student = validateAndSanitizeInput($_POST['id_fee_by_student']);

    // Buka Jumlah Tagihan 'fee_by_student'
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("i", $id_fee_by_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
            <script>
                $("#button_request_payment").prop("disabled", true);
            </script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $fee_nominal    = $Data['fee_nominal'] ?? 0;
    $fee_discount   = $Data['fee_discount'] ?? 0;
    $jumlah_tagihan =$fee_nominal-$fee_discount;

    //Hitung Pembayaran Yang Sudah Masuk
    $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
    $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

    //Hitung sisa pembayaran
    $sisa_pembayaran=$jumlah_tagihan-$JumlahPembayaranMasuk;

    // Jika Tidak Ada Tunggakan
    if($sisa_pembayaran<=0){
        echo '
            <div class="alert alert-danger">
                <small>Tagihan tersebut sudah lunas, anda tidak bisa menambahkan permintaan pembayaran.</small>
            </div>
            <script>
                $("#button_request_payment").prop("disabled", true);
            </script>
        ';
        exit;
    }

    //Buka Rincian SIswa
    $id_student = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');

    //Buka Identitas Siswa
    $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

    //Buka Komponen Biaya
    $id_fee_component   = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Format Rp
    $sisa_pembayaran_format   = "Rp " . number_format($sisa_pembayaran,0,',','.');

    //Tampilkan Form
    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row mb-2">
            <div class="col-5"><small>ID Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <small class="text text-grayish">'.$id_fee_by_student.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <small class="text text-grayish">'.$student_nis.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Nama Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <small class="text text-grayish">'.$student_name.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Komponen Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <small class="text text-grayish">'.$component_name.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Nominal Pembayaran</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <small class="text text-grayish">'.$sisa_pembayaran_format.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Tanggal Expired</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6">
                <input type="date" name="expired_date" class="form-control">
            </div>
        </div>
        <script>
            $("#button_request_payment").prop("disabled", false);
        </script>
    ';

?>