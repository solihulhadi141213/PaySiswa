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
    //Tangkap id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Rincian Tagihan Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka Data fee_by_student
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
    $fee_discount           = $Data['fee_discount'];

    $fee_nominal            = round($fee_nominal);
    $fee_discount           = round($fee_discount);

    //Buka Nama Komponen
    $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

    //Tampilkan Data
    echo '<input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">';
    echo '<input type="hidden" name="id_organization_class" id="put_id_organization_class5" value="'.$id_organization_class.'">';
    echo '<input type="hidden" name="id_student" id="put_id_student5" value="'.$id_student.'">';
    echo '
         <div class="row mb-3">
            <div class="col-12">
                <label for="component_name"><small>Komponen Biaya</small></label>
                <input type="text" disabled class="form-control" id="component_name" name="component_name" value="'.$component_name.'">
            </div>
        </div>
    ';
    echo '
         <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_tagihan_siswa3"><small>Nominal Tagihan</small></label>
                <input type="text" class="form-control form-money" id="nominal_tagihan_siswa3" name="fee_nominal" placeholder="Rp" value="'.$fee_nominal.'">
            </div>
        </div>
    ';
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_diskon_siswa3"><small>Diskon</small></label>
                <input type="text" class="form-control form-money" id="nominal_diskon_siswa3" name="fee_discount" placeholder="Rp" value="'.$fee_discount.'">
            </div>
        </div>
    ';
    echo '
        <script>
            $(".kembali_ke_rincian_tagihan").attr("data-id_organization_class", "'.$id_organization_class.'");
            $(".kembali_ke_rincian_tagihan").attr("data-id_student", "'.$id_student.'");
        </script>
    ';
?>

