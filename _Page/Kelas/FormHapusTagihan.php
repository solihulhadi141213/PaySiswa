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
    //Validasi Form Required
    $required = ['id_organization_class','id_student','id_fee_component'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

    //Buka Nominal dan diskon
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student  WHERE id_student = ? AND id_organization_class = ? AND id_fee_component = ?");
    $Qry->bind_param("iii", $id_student,$id_organization_class,$id_fee_component);
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
    $id_fee_by_student      = $Data['id_fee_by_student']?? NULL;

    //Buka Nominal Dan Diskon (jika tidak ada buka dari fee_component)
    if(empty($Data['fee_nominal'])){
        $fee_nominal=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');
    }else{
       $fee_nominal = $Data['fee_nominal'];
    }
    $fee_nominal = round($fee_nominal);
    if(empty($Data['fee_discount'])){
        $fee_discount="";
    }else{
       $fee_discount = $Data['fee_discount'];
       $fee_discount = round($fee_discount);
    }

    //Buka Nama dan NIS siswa
    $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');


    //Buka Detail Informasi Kelas
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka Biaya Pendidikan
    $component_name    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $periode_month    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Nama Bulan
    $nama_bulan=getNamaBulan($periode_month);

    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
    ';
    echo '
        <div class="row mb-3">
            <div class="col-4"><small>Nama Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Level/Kelas</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$class_level.'/'.$class_name.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Biaya Pendidiikan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Periode Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Nominal</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_nominal.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Diskon</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_discount.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <h3>PERHATIAN!</h3>
                    <small>
                        Menghapus data tagihan akan menyebabkan siswa bersangkutan tidak dapat melakukan pembayaran untuk komponen biaya pendidikan yang dipilih.
                        <p>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </p>
                    </small>
                </div>
            </div>
        </div>
    ';
?>

<script>
    var id_organization_class="<?php echo $id_organization_class; ?>";
    $(".kembali_ke_modal_metrik").attr("data-id", id_organization_class);
</script>