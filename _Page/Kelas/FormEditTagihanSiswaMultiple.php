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
            <script>$("#konfirmasi_edit_tagihan_siswa_multiple").prop("disabled", true);</script>
        ';
        exit;
    }

    // Menangkap 'id_fee_by_student'
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>Tidak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.</small>
            </div>
            <script>$("#konfirmasi_edit_tagihan_siswa_multiple").prop("disabled", true);</script>
        ';
        exit;
    }

    if(empty(count($_POST['id_fee_by_student']))){
        echo '
            <div class="alert alert-danger">
                Tidak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.
            </div>
            <script>$("#konfirmasi_edit_tagihan_siswa_multiple").prop("disabled", true);</script>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_by_student = $_POST['id_fee_by_student'];

    //Looping Form Multiple
    foreach ($id_fee_by_student as $id_fee_by_student_list) {
        echo '<input type="hidden" name="id_fee_by_student[]" value="'.$id_fee_by_student_list.'">';
    }

    echo '
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="fee_nominal_edit_multiple">Nominal Tagihan</label>
                <input type="number" min="0" step="1" class="form-control" name="fee_nominal" id="fee_nominal_edit_multiple">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="fee_discount_edit_multiple">Diskon (Potongan)</label>
                <input type="number" min="0" max="100" step="0.01" class="form-control" name="fee_discount" id="fee_discount_edit_multiple">
            </div>
        </div>
        <script>$("#konfirmasi_edit_tagihan_siswa_multiple").prop("disabled", false);</script>
    ';

?>