<?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($_POST['uuid_akses_entitas'])){
        echo '<code>ID Entitias Tidak Boleh Kosong!</code>';
    }else{
        $uuid_akses_entitas=$_POST['uuid_akses_entitas'];
        $uuid_akses_entitas=validateAndSanitizeInput($uuid_akses_entitas);
        //Bersihkan Data
        $uuid_akses_entitas=GetDetailData($Conn,'akses_entitas','uuid_akses_entitas',$uuid_akses_entitas,'uuid_akses_entitas');
        if(empty($uuid_akses_entitas)){
            echo '<code>ID Entitias Tidak Valid, Atau Tidak Ditemukan Pada Database!</code>';
        }else{
            $NamaAkses=GetDetailData($Conn,'akses_entitas','uuid_akses_entitas',$uuid_akses_entitas,'akses');
            $KeteranganEntitias=GetDetailData($Conn,'akses_entitas','uuid_akses_entitas',$uuid_akses_entitas,'keterangan');
?>
        <div class="row mb-3">
            <div class="col col-md-4">Nama Entitias</div>
            <div class="col col-md-8">
                <small class="credit">
                    <code class="text text-grayish"><?php echo "$NamaAkses"; ?></code>
                </small>
            </div>
        </div>
        <div class="row mb-3 border-1 border-bottom">
            <div class="col col-md-4 mb-3">Keterangan</div>
            <div class="col col-md-8 mb-3">
                <small class="credit">
                    <code class="text text-grayish"><?php echo "$KeteranganEntitias"; ?></code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <?php
                $no=1;
                $QryKategori = mysqli_query($Conn, "SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                while ($DataKategori = mysqli_fetch_array($QryKategori)) {
                    $KategoriList= $DataKategori['kategori'];
                    echo '  <div class="col-md-12 mb-3">';
                    echo '     <small class="credit">'.$no.'. '.$KategoriList.'</small><br>';
                    echo '      <ul>';
                    $QryFitur = mysqli_query($Conn, "SELECT * FROM akses_fitur WHERE kategori='$KategoriList' ORDER BY nama ASC");
                    while ($DataFitur = mysqli_fetch_array($QryFitur)) {
                        $id_akses_fitur= $DataFitur['id_akses_fitur'];
                        $KodeFitur= $DataFitur['kode'];
                        $NamaFitur= $DataFitur['nama'];
                        $KeteranganFitur= $DataFitur['keterangan'];
                        //Validasi Apakah Bersangkutan Punya Akses Ini
                        $Validasi=CekFiturEntitias($Conn,$uuid_akses_entitas,$id_akses_fitur);
                        if($Validasi=="Ada"){
                            echo '<li><code class="text text-grayish">'.$NamaFitur.' <i class="bi bi-check text-success"></i></code></li>';
                        }else{
                            echo '<li><code class="text text-grayish">'.$NamaFitur.'</code></li>';
                        }
                    }
                    echo '      </ul>';
                    echo '  </div>';
                    $no++;
                }
            ?>
        </div>
<?php
        }
    }
?>