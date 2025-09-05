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
    //Tangkap id_organization_class
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID sISWA Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_student=validateAndSanitizeInput($_POST['id_student']);

    //Buka Data sISWA
    $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
    $Qry->bind_param("i", $id_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
    }else{
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $id_organization_class  =$Data['id_organization_class'];
        $student_nis            =$Data['student_nis'] ?? '-';
        $student_nisn           =$Data['student_nisn'] ?? '-';
        $student_name           =$Data['student_name'];
        $student_gender         =$Data['student_gender'];
        $student_parent         =$Data['student_parent'];
        $student_registered     =$Data['student_registered'];
        $student_status         =$Data['student_status'];

        //Parent
        $parent_arry=json_decode($student_parent, true);
        if(empty($parent_arry['nama'])){
            $parent_nama="-";
        }else{
            $parent_nama=$parent_arry['nama'];
        }
        if(empty($parent_arry['kontak'])){
            $parent_kontak="-";
        }else{
            $parent_kontak=$parent_arry['kontak'];
        }

        //Tempat lahir
        if(empty($place_of_birth)){
            $place_of_birth ="-";
        }else{
            $place_of_birth =$Data['place_of_birth'];
        }

        //Tanggal Lahir
        if($Data['date_of_birth']=="0000-00-00"){
            $date_of_birth="-";
        }else{
            $date_of_birth =date('d F Y', strtotime($Data['date_of_birth']));
        }

        //Kontak
        if(empty($Data['student_contact'])){
            $student_contact ="-";
        }else{
            $student_contact =$Data['student_contact'];
        }

        //Kontak
        if(empty($Data['student_email'])){
            $student_email ="-";
        }else{
            $student_email =$Data['student_email'];
        }

        //student_address
        if(empty($Data['student_address'])){
            $student_address ="-";
        }else{
            $student_address =$Data['student_address'];
        }

        //student_foto
        if(empty($Data['student_foto'])){
            $student_foto ="No-Image.png";
        }else{
            $student_foto =$Data['student_foto'];
        }

        //Format Tanggal Daftar
        $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

        //Status
        if($student_status=="Terdaftar"){
            $label_status='<span class="badge badge-success">Terdaftar</span>';
        }else{
            if($student_status=="Lulus"){
                $label_status='<span class="badge badge-warning">Lulus</span>';
            }else{
                $label_status='<span class="badge badge-danger">Keluar</span>';
            }
        }

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_student" value="'.$id_student.'">
        ';
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>NIS</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_nis.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>NISN</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_nisn.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Gender</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$student_gender.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Tgl.Daftar</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$tanggal_daftar.'</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4"><small>Status</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    '.$label_status.'
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h2>Penting!</h2>
                        <small>
                            Menghapus data siswa akan menghapus data turunannya, termasuk riwayat pembayaran.<br>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>