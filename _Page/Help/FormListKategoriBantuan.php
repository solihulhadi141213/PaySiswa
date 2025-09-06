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
        //Hitung Data
        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT kategori FROM help"));
        if(empty($jml_data)){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center text-danger">';
            echo '      Belum Ada Data Yang Bisa Ditampilkan';
            echo '  </div>';
            echo '</div>';
        }else{
            $query = mysqli_query($Conn, "SELECT DISTINCT kategori FROM help ORDER BY kategori");
            while ($data = mysqli_fetch_array($query)) {
                $kategori= $data['kategori'];
                //Hitung Data
                $jumlah_item = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM help WHERE kategori='$kategori'"));

?>
    <div class="row mb-3 border-1 border-bottom">
        <div class="col col-md-9">
            <small class="credit text-dark">
                <b><?php echo "$kategori"; ?></b><br>
                <small class="credit text-grayish">
                    <?php echo "$jumlah_item Konten"; ?>
                </small>
            </small>
        </div>
        <div class="col col-md-3 mb-3 text-end">
            <a href="javascript:void(0);" data-bs-toggle="dropdown">
                <small>
                    <code class="text text-info"><i class="bi bi-three-dots"></i> Option</code>
                </small> 
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li>
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalEditKategori" data-id="<?php echo "$kategori"; ?>">
                        <i class="bi bi-pencil-square"></i> Ubah/Edit
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalHapusKategori" data-id="<?php echo "$kategori"; ?>">
                        <i class="bi bi-x"></i> Hapus
                    </a>
                </li>
            </ul>
        </div>
    </div>
<?php 
            }
        }
    } 
?>