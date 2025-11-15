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

    //valdasi 'id_organization_class' tidak boleh kosong
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong
                </small>
            </div>
        ';
        exit;
    }

    //Membuat Variabel 'id_organization_class' dan sanitasi
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Membuka tabel 'organization_class'
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    
    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Tampilkan Form
    echo '<input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">';
    echo '
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Alademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Jenjang/Level</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="select_siswa"><small>Siswa</small></label>
                <select id="select_siswa" name="id_student" class="form-select" style="width:100%">
                    <option value="">Pilih Siswa</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="selest_kbp"><small>Komponen Biaya</small></label>
                <select id="selest_kbp" name="id_fee_component" class="form-control" style="width:100%">
                    <option value="">Pilih Komponen</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_tagihan_siswa"><small>Nominal Tagihan</small></label>
                <input type="text" class="form-control form-money" id="nominal_tagihan_siswa" name="fee_nominal" placeholder="Rp">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_diskon_siswa"><small>Diskon</small></label>
                <input type="text" class="form-control form-money" id="nominal_diskon_siswa" name="fee_discount" placeholder="Rp">
            </div>
        </div>
    ';
    echo '
        <script>
            
        </script>
    ';
?>