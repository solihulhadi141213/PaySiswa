<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Datetime Sekarang
    $now=date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Tagihan Siswa Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka id_student dari fee_by_student
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("s", $id_fee_by_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $id_student             = $Data['id_student'];

    //Hapus Data
    $HapusTagihan = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn));
    if($HapusTagihan){

        //Simpan Log
        $kategori_log="Tagihan";
        $deskripsi_log="Hapus Tagihan Berhasil";
        $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
        if($InputLog=="Success"){
            echo '
                <input type="hidden" name="get_id_student_hapus_tagihan" id="get_id_student_hapus_tagihan" value="'.$id_student.'">
                <small class="text-success" id="NotifikasiHapusTagihanBerhasil">Success</small>
            ';
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
                <small>Terjadi kesalahan pada saat menghapus data pada database!</small>
            </div>
        ';
    }
?>