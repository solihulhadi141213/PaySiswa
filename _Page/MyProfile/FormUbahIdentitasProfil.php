<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <code>Sesi Login Sudah Berakhir, Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        $id_akses=$SessionIdAkses;
        //Bersihkan Variabel
        $id_akses=validateAndSanitizeInput($id_akses);
        //Buka data askes
        $nama_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'nama_akses');
        $kontak_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'kontak_akses');
        $email_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'email_akses');
        $image_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'image_akses');
        $akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'akses');
        $datetime_daftar=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_daftar');
        $datetime_update=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_update');
        //Jumlah
        $JumlahAktivitas =mysqli_num_rows(mysqli_query($Conn, "SELECT id_akses FROM log WHERE id_akses='$id_akses'"));
        $JumlahRole =mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM akses_ijin WHERE id_akses='$id_akses'"));
        //Format Tanggal
        $strtotime1=strtotime($datetime_daftar);
        $strtotime2=strtotime($datetime_update);
        //Menampilkan Tanggal
        $DateDaftar=date('d/m/Y H:i:s T', $strtotime1);
        $DateUpdate=date('d/m/Y H:i:s T', $strtotime2);
        if(!empty($image_akses)){
            $image_akses=$image_akses;
        }else{
            $image_akses="No-Image.png";
        }
?>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="nama_akses_profil">Nama Lengkap</label>
            </div>
            <div class="col col-md-8">
                <input type="text" name="nama_akses" id="nama_akses_profil" class="form-control" value="<?php echo "$nama_akses"; ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="kontak_akses_profil">Nomor Kontak</label>
            </div>
            <div class="col col-md-8">
                <input type="text" name="kontak_akses" id="kontak_akses_profil" class="form-control" value="<?php echo "$kontak_akses"; ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="email_akses_profil">Alamat Email</label>
            </div>
            <div class="col col-md-8">
                <input type="email" name="email_akses" id="email_akses_profil" class="form-control" value="<?php echo "$email_akses"; ?>">
            </div>
        </div>
<?php } ?>