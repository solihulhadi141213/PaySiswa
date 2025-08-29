<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        //Buka data askes
        $email=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'email');
?>
        <input type="hidden" name="id_akses" value="<?php echo "$SessionIdAkses"; ?>">
        <div class="row mb-3">
            <div class="col col-md-12">
                <label for="email_akses_edit">Alamat Email</label>
                <input type="email" name="email" id="email_akses_edit" class="form-control" value="<?php echo "$email"; ?>">
            </div>
        </div>
<?php 
    } 
?>