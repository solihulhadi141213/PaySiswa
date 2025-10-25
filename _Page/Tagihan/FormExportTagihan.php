<?php
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Periiode Akademik Tidak Boleh Kosong!</small>
            </div>
            <script>$("#ButtonExportTagihan").prop("disabled", true);</script>
        ';
        exit;
    }
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Kelas Tidak Boleh Kosong!</small>
            </div>
            <script>$("#ButtonExportTagihan").prop("disabled", true);</script>
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period=$_POST['id_academic_period'];
    $id_organization_class=$_POST['id_organization_class'];

    //kelompok_status_siswa
    if(!empty($_POST['kelompok_status_siswa'])){
        $status_siswa=$_POST['kelompok_status_siswa'];
    }else{
        $status_siswa="";
    }

    //Enable Button
    echo '
        <script>$("#ButtonExportTagihan").prop("disabled", false);</script>
    ';

    //Form Hidden
    echo '
        <input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="status_siswa" value="'.$status_siswa.'">
    ';

    //Form Option Type File
    echo '
        <div class="row">
            <div class="col-md-12 mb-3">
                Pilih Format Data :
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipe_file" id="tipe_file_pdf" value="PDF" checked>
                    <label class="form-check-label" for="tipe_file_pdf">
                        <small>PDF</small>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipe_file" id="tipe_file_html" value="HTML">
                    <label class="form-check-label" for="tipe_file_html">
                        <small>HTML</small>
                    </label>
                </div>
            </div>
        </div>
    ';

?>