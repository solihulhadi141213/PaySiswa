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
            echo '      Kategori Tidak Boleh Kosong';
            echo '  </div>';
            echo '</div>';
        }else{
            $kategori=$_POST['kategori'];
            //Bersihkan Variabel
            $kategori=validateAndSanitizeInput($kategori);
?>
            <input type="hidden" name="kategori-before" value="<?php echo "$kategori"; ?>">
            <input type="hidden" name="kategori" value="<?php echo "$kategori"; ?>">
            <div class="row mb-3">
                <div class="col col-md-12 text-center">
                    <b class="credit text-danger">
                        "<?php echo "$kategori"; ?>"
                    </b>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col col-md-12 text-center">
                    <small class="credit text-dark">
                        Apabila anda memutuskan untuk menghapus data kategori ini maka seluruh konten yang terhubung dengan kategori tersebut akan dihapus.
                    </small>
                    <small class="credit text-primary">
                        Apakah anda yakin akan menghapus kategori ini?
                    </small>
                </div>
            </div>
<?php 
        }
    } 
?>