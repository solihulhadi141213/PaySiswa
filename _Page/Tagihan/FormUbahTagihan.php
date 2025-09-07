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
    if(empty($_POST['id_fee_by_student'])){
            echo '
            <div class="alert alert-danger">
                <small>
                    ID Tagihan Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka data fee_by_student
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("i", $id_fee_by_student);
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
    $id_student             = $Data['id_student'];
    $id_fee_component       = $Data['id_fee_component'];
    $fee_nominal            = $Data['fee_nominal'];
    $fee_nominal            = round($fee_nominal);
    $fee_nominal            = str_replace('.', '', $fee_nominal);
    if($Data['fee_discount']=="0.00"){
        $fee_discount="";
    }else{
        $fee_discount= $Data['fee_discount'];
    }
    

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
    $id_fee_component       =$DataComponent['id_fee_component'];
    $component_name         =$DataComponent['component_name'] ?? '-';
    $component_category     =$DataComponent['component_category'] ?? '-';
    $periode_start          =$DataComponent['periode_start'] ?? '-';
    $periode_end            =$DataComponent['periode_end'] ?? '-';
    $fee_nominal_component  =$DataComponent['fee_nominal'] ?? '-';
    
    //Format Rupiah
    $fee_nominal_format="Rp " . number_format($fee_nominal_component,0,',','.');

    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
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
        
        <div class="row mb-3">
            <div class="col-12"><small><br></small></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fee_nominal">Nominal Tagihan</label>
            </div>
            <div class="col-8">
                <input type="text" name="fee_nominal" id="fee_nominal" class="form-control form-money" value="'.$fee_nominal.'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fee_discount">Diskon/Potongan</label>
            </div>
            <div class="col-8">
                <input type="text" name="fee_discount" id="fee_discount" class="form-control form-money" value="'.$fee_discount.'">
            </div>
        </div>
    ';

?>

<script>
    var id_siswa="<?php echo $id_student; ?>";
    // Tempelkan ke atribut data-id tombol
    $(".kembali_ke_modal_tagihan").attr("data-id", id_siswa);
</script>