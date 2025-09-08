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
    //Tangkap id_payment
    if(empty($_POST['id_payment'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Komponent Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_payment=validateAndSanitizeInput($_POST['id_payment']);

    //Buka Data payment
    $Qry = $Conn->prepare("SELECT * FROM payment WHERE id_payment = ?");
    $Qry->bind_param("s", $id_payment);
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
        $id_payment             = $Data['id_payment'];
        $id_student             = $Data['id_student'];
        $id_organization_class  = $Data['id_organization_class'];
        $id_fee_component       = $Data['id_fee_component'];
        $payment_datetime       = $Data['payment_datetime'];
        $payment_nominal        = $Data['payment_nominal'];
        $payment_method         = $Data['payment_method'];

        //Format
        $payment_nominal=round($payment_nominal);
        $payment_datetime=date('Y-m-d', strtotime($payment_datetime));
        

        //Buka detail siswa
        $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
        $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

        //Buka Komponen Biaya
        $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
        $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

        //Routing status
        $method_cash="";
        $method_transfer="";
        $method_wallete="";
        if($payment_method=="Cash"){
            $method_cash="selected";
        }
        if($payment_method=="Transfer"){
            $method_transfer="selected";
        }
        if($payment_method=="E-wallet"){
            $method_wallete="selected";
        }

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_payment" value="'.$id_payment.'">
            <div class="row mb-2">
                <div class="col-4"><small>NIS</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_nis.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Nama Siswa</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Uraian Biaya</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$component_name.'</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$component_category.'</small>
                </div>
            </div>
            
             <div class="row mb-3">
                <div class="col-4">
                    <label for="payment_datetime_edit">
                        <small>Tanggal Bayar</small>
                    </label>
                </div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <input type="date" name="payment_datetime" id="payment_datetime" class="form-control" value="'.$payment_datetime.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="payment_nominal_edit"><small>Nominal Bayar</small></label>
                </div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <input type="text" class="form-control form-money" name="payment_nominal" id="payment_nominal_edit" value="'.$payment_nominal.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="payment_method_edit">
                        <small>Metode Pembayaran</small>
                    </label>
                </div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <select name="payment_method" id="payment_method_edit" class="form-control">
                        <option value="">Pilih</option>
                        <option '.$method_cash.' value="Cash">Cash</option>
                        <option '.$method_transfer.' value="Transfer">Transfer</option>
                        <option '.$method_wallete.' value="E-wallet">E-wallet</option>
                    </select>
                </div>
            </div>
        ';
    }
?>