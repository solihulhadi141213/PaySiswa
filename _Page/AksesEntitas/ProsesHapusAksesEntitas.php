<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now=date('Y-m-d H:i:s');
    //Validasi uuid_akses_entitas tidak boleh kosong
    if(empty($_POST['uuid_akses_entitas'])){
        echo '<code class="text-danger">ID Akses Entitias Tidak Boleh Kosong</code>';
    }else{
        $uuid_akses_entitas=$_POST['uuid_akses_entitas'];
        $uuid_akses_entitas=validateAndSanitizeInput($uuid_akses_entitas);
        $HapusEntitias = mysqli_query($Conn, "DELETE FROM akses_entitas WHERE uuid_akses_entitas='$uuid_akses_entitas'") or die(mysqli_error($Conn));
        if($HapusEntitias){
            $HapusReferensi = mysqli_query($Conn, "DELETE FROM akses_referensi WHERE uuid_akses_entitas='$uuid_akses_entitas'") or die(mysqli_error($Conn));
            if($HapusReferensi){
                $kategori_log="Entitas Akses";
                $deskripsi_log="Hapus Entitas Akses";
                $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                if($InputLog=="Success"){
                    echo '<small class="text-success" id="NotifikasiHapusAksesEntitasBerhasil">Success</small>';
                }else{
                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
                }
            }else{
                echo '<small class="text-danger">Terjadi kesalahan pada saat menghapus referensi entitias</small>';
            }
        }else{
            echo '<small class="text-danger">Terjadi kesalahan pada saat menghapus entitias</small>';
        }
    }
?>