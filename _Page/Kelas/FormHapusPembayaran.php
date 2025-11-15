<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/FungsiAkses.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</div>';
        exit;
    }

    //Validasi Data tidak boleh kosong
    if(empty($_POST['id_payment'])){
        echo '<div class="alert alert-danger">ID Pembayaran Tidak Boleh Kosong! </div>';
        exit;
    }

    //Buat variabel
    $id_payment = validateAndSanitizeInput($_POST['id_payment']);

    //Buka Data Pembayaran
    $id_fee_by_student      = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_fee_by_student');
    $payment_datetime       = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_datetime');
    $payment_nominal        = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_nominal');
    $payment_method         = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_method');
    $tanggal_bayar          = date('d F Y',strtotime($payment_datetime));
    $jam_bayar              = date('H:i T',strtotime($payment_datetime));
    $payment_nominal_format = "Rp " . number_format($payment_nominal,0,',','.');

    echo '
        <input type="hidden" name="id_payment" value="'.$id_payment.'">
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row mb-2">
            <div class="col-5"><small>Tanggal</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.date('d F Y', strtotime($payment_datetime)).'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Jam</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.date('H:i T', strtotime($payment_datetime)).'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Metode Pembayaran</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$payment_method.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Nominal Pembayaran</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$payment_nominal_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <small>Apakah Anda Yakin Akan Menghapus Data Pembayaran Inii?</small>
                </div>
            </div>
        </div>
    ';
?>