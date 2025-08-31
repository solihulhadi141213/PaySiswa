<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/FungsiAkses.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>
            </div>
        ';
    }else{
?>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="nama">Nama Lengkap</label>
            </div>
            <div class="col col-md-8">
                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo "$access_name"; ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="kontak">Nomor Kontak</label>
            </div>
            <div class="col col-md-8">
                <input type="text" name="kontak" id="kontak" class="form-control" value="<?php echo "$access_contact"; ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="email">Alamat Email</label>
            </div>
            <div class="col col-md-8">
                <input type="email" name="email" id="email" class="form-control" value="<?php echo "$access_email"; ?>">
            </div>
        </div>
<?php } ?>