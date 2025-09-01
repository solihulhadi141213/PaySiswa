<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now=date('Y-m-d H:i:s');

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_access_group
    if(empty($_POST['id_access_group'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_access_group
    if(empty($_POST['akses'])){
        echo '
            <div class="alert alert-danger">
                <small>Nama Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_access_group
    if(empty($_POST['keterangan'])){
        echo '
            <div class="alert alert-danger">
                <small>Keterangan Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }
    
    //Validasi rules
    if(empty($_POST['rules'])){
        echo '
            <div class="alert alert-danger">
                <small>List Referensi Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_access_group    = trim($_POST['id_access_group'] ?? '');
    $akses              = trim($_POST['akses'] ?? '');
    $keterangan         = trim($_POST['keterangan'] ?? '');
    $rules              = $_POST['rules'] ?? [];
    
    if (strlen($akses) > 250) {
        echo '<div class="alert alert-danger"><small>Nama Entitas Akses Tidak Boleh Lebih Dari 250 Karakter!</small></div>';
        exit;
    }
           
    //Bersihkan Variabel
    $id_access_group    =validateAndSanitizeInput($id_access_group);
    $akses              =validateAndSanitizeInput($akses);
    $keterangan         =validateAndSanitizeInput($keterangan);
    $jumlah_rules       =count($rules);
    if(empty($jumlah_rules)){
        echo '
            <div class="alert alert-danger">
                <small>Ijin Akses Entitas Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buka Nama Akses Lama
    $akses_lama=GetDetailData($Conn, 'access_group', 'id_access_group', $id_access_group, 'group_name');

    //Validasi Nama Akses Tidak Boleh Duplikat
    if($akses_lama==$akses){
        $validasi_duplikat=0;
    }else{
        $validasi_duplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT id_access_group  FROM access_group WHERE group_name='$akses'"));
    }
    if(!empty($validasi_duplikat)){
        echo '
            <div class="alert alert-danger">
                <small>Nama group/entitas akses tersebut sudah terdaftar!</small>
            </div>
        ';
        exit;
    }

    //Update Access Group
    $UpdateEntitias = mysqli_query($Conn,"UPDATE access_group SET 
        group_name='$akses',
        group_description='$keterangan'
    WHERE id_access_group='$id_access_group'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){

        //Hapus Semua referensi
        $HapusReferensi = mysqli_query($Conn, "DELETE FROM access_reference  WHERE id_access_group='$id_access_group'") or die(mysqli_error($Conn));
        if($HapusReferensi){
            
            //Input ke access_reference
            $JumlahYangBerhasil =0;
            foreach($rules as $id_access_feature) {
                
                //Simpan Ke Database access_reference
                $entry2="INSERT INTO access_reference (
                    id_access_group,
                    id_access_feature
                ) VALUES (
                    '$id_access_group',
                    '$id_access_feature'
                )";
                $Input2=mysqli_query($Conn, $entry2);
                if($Input2){
                    $JumlahYangBerhasil=$JumlahYangBerhasil+1;
                }else{
                    $JumlahYangBerhasil=$JumlahYangBerhasil+0;
                }
            }

            //Jika Jumlah Yang berhasil Sama Dengan jumlah_rules
            if($JumlahYangBerhasil==$jumlah_rules){

                //Simpan Log
                $kategori_log="Entitas Akses";
                $deskripsi_log="Edit Entitas Akses";
                $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
                if($InputLog=="Success"){
                    echo '<small class="text-success" id="NotifikasiEditAksesEntitasBerhasil">Success</small>';
                }else{
                    echo '
                        <div class="alert alert-danger">
                            <small>Terjadi kesalahan pada saat menyimpan Log!</small>
                        </div>
                    '; 
                }
            }else{
                echo '
                    <div class="alert alert-danger">
                        <small>Terjadi kesalahan pada saat menyimpan data referensi!</small>
                    </div>
                '; 
            }
        }else{
            echo '
                <div class="alert alert-danger">
                    <small>Terjadi kesalahan pada saat menghapus referensi akses yang lama!</small>
                </div>
            '; 
        }
    }else{
        echo '
            <div class="alert alert-danger">
                <small>Update Ke Database Group Akses Gagal!</small>
            </div>
        ';
    }
?>