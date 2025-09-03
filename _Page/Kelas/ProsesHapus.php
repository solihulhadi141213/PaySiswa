<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

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
    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);
    
    //Proses hapus data
    $HapusKelas = mysqli_query($Conn, "DELETE FROM organization_class WHERE id_organization_class='$id_organization_class'") or die(mysqli_error($Conn));
    if ($HapusKelas) {
        echo '<span class="text-success" id="NotifikasisHapusBerhasil">Success</span>';
    }else{

        //Jika menghapus gagal
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menghapus data!
                </small>
            </div>
        ';
    }
?>