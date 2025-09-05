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

    //Buat variabel
    $id_student=validateAndSanitizeInput($_POST['id_student']);

    //Buka data foto
    $student_foto=GetDetailData($Conn,'student','id_student',$id_student,'student_foto');
    
    //Proses hapus data
    $HapusSiswa = mysqli_query($Conn, "DELETE FROM student WHERE id_student='$id_student'") or die(mysqli_error($Conn));
    if ($HapusSiswa) {

        //Jika Ada File Foto
        if(!empty($student_foto)){

            //Tentukan Path Foto
            $file = '../../assets/img/Siswa/'.$student_foto.'';

            //Jika File ADa
            if (file_exists($file)) {
                if (unlink($file)) {
                    echo '<span class="text-success" id="NotifikasisHapusBerhasil">Success</span>';
                } else {
                    echo '
                        <div class="alert alert-danger">
                            <small>
                                Terjadi kesalahan pada saat menghapus file!
                            </small>
                        </div>
                    ';
                }
            }else{
                echo '<span class="text-success" id="NotifikasisHapusBerhasil">Success</span>';
            }
        }else{
            echo '<span class="text-success" id="NotifikasisHapusBerhasil">Success</span>';
        }       
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