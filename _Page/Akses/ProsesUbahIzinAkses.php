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
    
    //Tangkap rules
    if(empty($_POST['rules'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Rules Yang Dipilh!
                </small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_access=validateAndSanitizeInput($_POST['id_access']);
    $rules=$_POST['rules'];

    //Hitung Jumlah Rules Yang Dipilih
    $JumlahFitur=count($rules);

    //Validasi Jumlah Rules Yang Dipilih
    if(empty($JumlahFitur)){
        echo '
            <div class="alert alert-danger">
                <small>
                    Ijin Akses Tidak Boleh Kosong! Setidaknya yang bersangkutan memiliki 1 fitur yang bisa diakses.
                </small>
            </div>
        ';
        exit;
    }

    //Hapus Permission Lama
    $HapusPermissionLama = mysqli_query($Conn, "DELETE FROM  access_permission  WHERE id_access='$id_access'") or die(mysqli_error($Conn));
    if($HapusPermissionLama){
                    
        //Melakukan Looping Berdasarkan Rules Yang Di Pilih
        $JumlahRoleValid =0;
        foreach($rules as $id_access_feature) {
           $EntryIjinAkses="INSERT INTO access_permission (
                id_access,
                id_access_feature
            ) VALUES (
                '$id_access',
                '$id_access_feature'
            )";
            $InputIjinAkses=mysqli_query($Conn, $EntryIjinAkses);
            if($InputIjinAkses){
                $JumlahRoleValid=$JumlahRoleValid+1;
            }else{
                $JumlahRoleValid=$JumlahRoleValid+0;
            }
        }
        if($JumlahRoleValid==$JumlahFitur){
            echo '<small class="text-success" id="NotifikasiUbahIzinAksesBerhasil">Success</small>';
        }else{
            echo '
                <div class="alert alert-danger">
                    <small class="text-danger">Terjadi kesalahan pada saat menyimpan ijin akses</small>
                </div>
            ';
        }
    }else{
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menghapus ijin akses lama
                </small>
            </div>
        ';
    }
?>