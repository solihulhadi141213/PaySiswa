<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center text-danger">';
        echo '      Sesi Akses Sudah Berakhir, Silahkan Login Ulang';
        echo '  </div>';
        echo '</div>';
    }else{
        if(empty($_POST['kategori'])){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center text-danger">';
            echo '      Kategori Bantuan Tidak Boleh Kosong';
            echo '  </div>';
            echo '</div>';
        }else{
            $kategori=$_POST['kategori'];
?>
            <input type="hidden" name="kategori-before" value="<?php echo $kategori; ?>">
            <div class="row mb-3">
                <div class="col col-md-12">
                    <label for="kategori">
                        <small class="">Ubah Kategori Disini</small>
                    </label>
                    <input type="text" name="kategori" class="form-control" value="<?php echo $kategori; ?>">
                </div>
            </div>
<?php 
        }
    } 
?>