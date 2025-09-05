<?php
    // Koneksi & util
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

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

    //Tangkap id_student
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    if(empty(count($_POST['id_student']))){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Data Yang Dipilih
                </small>
            </div>
        ';
        exit;
    }

    //Validasi status_siswa
    if(empty($_POST['status_siswa'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    Status Siswa Tidak Boleh Kosong
                </small>
            </div>
        ';
        exit;
    }
    
    //Variabel Status
    $status=$_POST['status_siswa'];

    //Jumlah id_student yang ditangkap
    $jumlah_siswa=count($_POST['id_student']);

    //Jumlah Yang Berhasil
    $jumlah_berhasil=0;
    foreach ($_POST['id_student'] as $id_student) {
        // === UPDATE status student  ===
        $sql = "UPDATE student SET student_status = ? WHERE id_student = ?";
        $stmt = $Conn->prepare($sql);
        if (!$stmt) {
           $jumlah_berhasil=$jumlah_berhasil+0;
        }else{
            $stmt->bind_param(
                "si",
                $status,
                $id_student
            );

            $Input = $stmt->execute();
            $err   = $stmt->error;
            $stmt->close();

            if ($Input) {
                $jumlah_berhasil=$jumlah_berhasil+1;
            } else {
                $jumlah_berhasil=$jumlah_berhasil+0;
            }
        }
    }

    //Apabila semua data berhasil di update
    if($jumlah_berhasil==$jumlah_siswa){
        echo '<code class="text-success" id="NotifikasiUpdateStatusBerhasil">Success</code>';
    }else{
         echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat prosdes update status siswa!
                </small>
            </div>
        ';
    }
?>