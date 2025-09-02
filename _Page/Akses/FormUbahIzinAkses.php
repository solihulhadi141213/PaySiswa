<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
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
    $id_access=$_POST['id_access'];
    $id_access=validateAndSanitizeInput($id_access);
?>
    <input type="hidden" name="id_access" value="<?php echo "$id_access"; ?>">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="row">';
                $no_kategori=1;
                $QryKategoriFitur = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
                while ($DataKategori = mysqli_fetch_array($QryKategoriFitur)) {
                    $feature_category= $DataKategori['feature_category'];
                    echo '<div class="col-md-12 mb-2">';
                    echo '  <small class="credit">'.$no_kategori.'. '.$feature_category.'</small>';
                    echo '  <ul>';
                    $QryFitur = mysqli_query($Conn, "SELECT * FROM access_feature WHERE feature_category='$feature_category' ORDER BY feature_name ASC");
                    while ($DataFitur = mysqli_fetch_array($QryFitur)) {
                        $id_access_feature= $DataFitur['id_access_feature'];
                        $feature_name= $DataFitur['feature_name'];

                        echo '<li>';
                        //Validasi Apakah Bersangkutan Punya Akses Ini
                        $Validasi=IjinAksesSaya($Conn,$id_access,$id_access_feature);
                        if($Validasi=="Ada"){
                            echo '<input type="checkbox" checked name="rules[]" id="IdFiturEdit'.$id_access_feature.'" value="'.$id_access_feature.'"> ';
                            echo '<label for="IdFiturEdit'.$id_access_feature.'"><small class="credit"><code class="text text-grayish">'.$feature_name.'</code></small></label>';
                        }else{
                            echo '<input type="checkbox" name="rules[]" id="IdFiturEdit'.$id_access_feature.'" value="'.$id_access_feature.'"> ';
                            echo '<label for="IdFiturEdit'.$id_access_feature.'"><small class="credit"><code class="text text-grayish">'.$feature_name.'</code></small></label>';
                        }
                        echo '  </td>';
                        echo '</li>';
                    }
                    echo '  </ul>';
                    echo '</div>';
                    $no_kategori++;
                }
                echo '</div>';
            ?>
        </div>
    </div>