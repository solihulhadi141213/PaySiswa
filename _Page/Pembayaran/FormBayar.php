<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    $id_student="";
    //Validasi Sesi Akses
    if(empty($SessionIdAccess)){
        echo '
           <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
           </div>
        ';
        exit;
    }

    //Validasi id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
        echo '
           <div class="alert alert-danger">
                <small>ID Tagihan Siswa Tidak Boleh Kosong!</small>
           </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_by_student=$_POST['id_fee_by_student'];

    //Buka id_fee_component dan id_student
    $id_fee_component=GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $id_student=GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');

    //Buka Detail fee_component
    $QryComponent = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
    $QryComponent->bind_param("i", $id_fee_component);
    if (!$QryComponent->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultComponent = $QryComponent->get_result();
    $DataComponent = $ResultComponent->fetch_assoc();
    $QryComponent->close();

    //Buat Variabel
    $id_fee_component   =$DataComponent['id_fee_component'];
    $component_name     =$DataComponent['component_name'] ?? '-';
    $component_category =$DataComponent['component_category'] ?? '-';
    $periode_start      =$DataComponent['periode_start'] ?? '-';
    $periode_end        =$DataComponent['periode_end'] ?? '-';
    $fee_nominal        =$DataComponent['fee_nominal'] ?? '-';
    
    //Format Rupiah
    $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

    //Buka Detail Siswa
    $QrySiswa = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
    $QrySiswa->bind_param("i", $id_student);
    if (!$QrySiswa->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultSiswa = $QrySiswa->get_result();
    $DataSswa = $ResultSiswa->fetch_assoc();
    $QrySiswa->close();

    //Buat Variabel
    $id_organization_class  =$DataSswa['id_organization_class'];
    $student_nis            =$DataSswa['student_nis'] ?? '-';
    $student_nisn           =$DataSswa['student_nisn'] ?? '-';
    $student_name           =$DataSswa['student_name'];
    $student_gender         =$DataSswa['student_gender'];
    $student_parent         =$DataSswa['student_parent'];
    $student_registered     =$DataSswa['student_registered'];
    $student_status         =$DataSswa['student_status'];

    //Buka Data fee_by_student
    $QryFee = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_student = ? AND id_fee_component = ?");
    $QryFee->bind_param("ii", $id_student, $id_fee_component);
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

    //Buat Variabel
    $fee_nominal            =$DataFee['fee_nominal'];
    $fee_discount           =$DataFee['fee_discount'];
    $id_organization_class  =$DataFee['id_organization_class'];

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
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
    ';

    echo '
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
            <div class="col-12"><small><br></small></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="payment_datetime">Tgl.Bayar</label>
            </div>
            <div class="col-md-8">
                <input type="date" name="payment_datetime" id="payment_datetime" class="form-control" value="'.date('Y-m-d').'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="payment_nominal">Nominal</label>
            </div>
            <div class="col-8">
                <input type="text" name="payment_nominal" id="payment_nominal" class="form-control form-money" value="'.$sisa.'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="payment_method">Metode</label>
            </div>
            <div class="col-md-8">
                <select name="payment_method" class="form-control">
                    <option value="">Pilih</option>
                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                    <option value="E-wallet">E-wallet</option>
                </select>
            </div>
        </div>
    ';
?>
<script>
    var id_siswa="<?php echo $id_student; ?>";
    // Tempelkan ke atribut data-id tombol
    $(".kembali_ke_komponen_biaya").attr("data-id", id_siswa);
</script>