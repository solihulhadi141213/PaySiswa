<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Validasi Sesi Akses
    if(empty($SessionIdAccess)){
        echo '
           <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
           </div>
        ';
        exit;
    }

    //Validasi Komponen Pembayaran
    if(empty($_POST['id_fee_by_student'])){
        echo '
           <div class="alert alert-danger">
                <small>Komponen Pembayaran Tidak Boleh Kosong!</small>
           </div>
        ';
        exit;
    }

    //Buat Variabel dan sanitasi
    $id_fee_by_student = validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka Data fee_by_student
    $QryFee = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $QryFee->bind_param("i",$id_fee_by_student);
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
    $DataFee= $ResultFee->fetch_assoc();
    $QryFee->close();

    if(empty($DataFee['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Tagihan <b>'.$id_fee_by_student.'</b> Tidak Valid (tidak ditemukan pada database)</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_student             = $DataFee['id_student'];
    $id_organization_class  = $DataFee['id_organization_class'];
    $id_fee_component       = $DataFee['id_fee_component'];
    $fee_nominal            = $DataFee['fee_nominal'];
    $fee_discount           = $DataFee['fee_discount'];

    //Buka Student
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

    //Buka Data Komponen
    $component_name         = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month          = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year           = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Hitung Pembayaran Yang Sudah Masuk
    $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
    $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

    //Menghitung sisa
    $sisa=$fee_nominal-$fee_discount-$JumlahPembayaranMasuk;

    //Format
    $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format="Rp " . number_format($fee_discount,0,',','.');
    $JumlahPembayaranMasuk_format="Rp " . number_format($JumlahPembayaranMasuk,0,',','.');
    $sisa_format="Rp " . number_format($sisa,0,',','.');

    //Tampilkan Form
    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row mb-2">
            <div class="col-4"><small>Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kategori Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_category.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kompnen Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_nominal_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Diskon/Potongan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_discount_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Pembayaran Masuk</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$JumlahPembayaranMasuk_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Sisa Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$sisa_format.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-12 border-1 border-bottom"><small><br></small></div>
        </div>
    ';

    if(!empty($sisa)){
        echo '
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="payment_datetime"><small>Tanggal</small></label>
                </div>
                <div class="col-md-8">
                    <input type="date" name="payment_datetime" id="payment_datetime" class="form-control" value="'.date('Y-m-d').'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="payment_time"><small>Jam</small></label>
                </div>
                <div class="col-md-8">
                    <input type="time" name="payment_time" id="payment_time" class="form-control" value="'.date('H:i').'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="payment_nominal"><small>Nominal Bayar</small></label>
                </div>
                <div class="col-8">
                    <input type="text" name="payment_nominal" id="payment_nominal" class="form-control form-money" value="'.$sisa.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="payment_method"><small>Metode Pembayaran</small></label>
                </div>
                <div class="col-md-8">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="">Pilih</option>
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                        <option value="E-wallet">E-wallet</option>
                    </select>
                </div>
            </div>
            <script>
                $("#button_tambah_pembayaran").prop("disabled", false);
            </script>
        ';
    }else{
        echo '
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        Tagihan sudah lunas, anda tidak bisa menambahkan pembayaran untuk tagihan ini.
                    </div>
                </div>
            </div>
            <script>
                $("#button_tambah_pembayaran").prop("disabled", true);
            </script>
        ';
    }
?>