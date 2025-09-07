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
        
        //Format Rupiah
        $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

        //Buka detail siswa
        $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
        $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

        //Buka Komponen Biaya
        $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
        $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

        //Tampilkan Data
        echo '
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
            <div class="row mb-2">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$component_category.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Tgl.Bayar</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.date('d/m/Y',strtotime($payment_datetime)).'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Metode Pembayaran</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$payment_method.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Nominal Bayar</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$payment_nominal_format.'</small>
                </div>
            </div>
        ';
    }
?>

<script>
    var id_student="<?php echo $id_student; ?>";
    var id_fee_component="<?php echo $id_fee_component; ?>";
    // Tempelkan ke atribut data-id tombol
    $(".kembali_ke_riwayat_pembayaran").attr("data-id1", id_fee_component);
    $(".kembali_ke_riwayat_pembayaran").attr("data-id2", id_student);
</script>