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
    
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Periode Akademik Tujuan Perubahan Tidak Ditemukan!
                </small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $curent_id_academic_period = $_POST['id_academic_period'];

?>
<input type="hidden" name="curent_id_academic_period" value="<?php echo "$curent_id_academic_period"; ?>">
<div class="row">
    <div class="col-12">
        <label for="id_academic_period_sumber">Copy Dari</label>
        <select name="id_academic_period_sumber" id="id_academic_period_sumber" class="form-control">
            <option value="">Pilih</option>
            <?php
                //Buka Periode Akademik Referensii
                $query = mysqli_query($Conn, "SELECT*FROM academic_period ORDER BY academic_period ASC");
                while ($data = mysqli_fetch_array($query)) {
                    $id_academic_period = $data['id_academic_period'];
                    $academic_period = $data['academic_period'];

                    if($curent_id_academic_period!==$id_academic_period){
                        echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>