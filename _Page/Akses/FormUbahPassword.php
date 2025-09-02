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

    //Tangkap id_access
    if(empty($_POST['id_access'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Access Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    $id_access=validateAndSanitizeInput($_POST['id_access']);

    //Tampilkan Form
    echo '
        <input type="hidden" name="id_access" value="'.$id_access.'">
        <div class="row mb-3">
            <div class="col-12">
                <label for="password1_edit">
                    <small>Password Baru</small>
                </label>
                <input type="password" name="password1" id="password1_edit" class="form-control">
                <small class="credit">
                    <small class="text-grayish">Terdiri dari 6-20 karakter angka dan huruf</small>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="password2_edit">
                    <small>Ulangi Password</small>
                </label>
                <input type="password" name="password2" id="password2_edit" class="form-control">
                <small class="credit">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword2" name="TampilkanPassword2">
                        <label class="form-check-label" for="TampilkanPassword2">
                            <small class="text text-grayish">Tampilkan Password</small>
                        </label>
                    </div>
                </small>
            </div>
        </div>
    ';
?>
<script>
    //Kondisi saat tampilkan password
    $('#TampilkanPassword2').click(function(){
        if($(this).is(':checked')){
            $('#password1_edit').attr('type','text');
            $('#password2_edit').attr('type','text');
        }else{
            $('#password1_edit').attr('type','password');
            $('#password2_edit').attr('type','password');
        }
    });
</script>