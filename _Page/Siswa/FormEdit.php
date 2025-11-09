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
            $parent_nama="";
        }else{
            $parent_nama=$parent_arry['nama'];
        }
        if(empty($parent_arry['kontak'])){
            $parent_kontak="";
        }else{
            $parent_kontak=$parent_arry['kontak'];
        }

        //Tempat lahir
        if(empty($Data['place_of_birth'])){
            $place_of_birth ="";
        }else{
            $place_of_birth =$Data['place_of_birth'];
        }

        //Tanggal Lahir
        if($Data['date_of_birth']=="0000-00-00"){
            $date_of_birth="";
        }else{
            $date_of_birth =date('Y-m-d', strtotime($Data['date_of_birth']));
        }

        //Kontak
        if(empty($Data['student_contact'])){
            $student_contact ="";
        }else{
            $student_contact =$Data['student_contact'];
        }

        //Kontak
        if(empty($Data['student_email'])){
            $student_email ="";
        }else{
            $student_email =$Data['student_email'];
        }

        //student_address
        if(empty($Data['student_address'])){
            $student_address ="";
        }else{
            $student_address =$Data['student_address'];
        }

        //student_foto
        if(empty($Data['student_foto'])){
            $student_foto ="No-Image.png";
        }else{
            $student_foto =$Data['student_foto'];
        }

        //Routing Gender
        if($student_gender=="Male"){
            $label_gender_male="selected";
            $label_gender_female="";
        }else{
            if($student_gender=="Female"){
                $label_gender_male="";
                $label_gender_female="selected";
            }else{
                $label_gender_male="";
                $label_gender_female="";
            }
        }

        //Routing Status
        if($student_status=="Terdaftar"){
            $student_status_terdaftar="selected";
            $student_status_lulus="";
            $student_status_keluar="";
        }else{
            if($student_status=="Lulus"){
                $student_status_terdaftar="";
                $student_status_lulus="selected";
                $student_status_keluar="";
            }else{
                $student_status_terdaftar="";
                $student_status_lulus="";
                $student_status_keluar="selected";
            }
        }


        //Tampilkan Data
        echo '
            <input type="hidden" name="id_student" value="'.$id_student.'">
        ';
        echo '
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_nis_edit">
                        <small>NIS <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="student_nis" id="student_nis_edit" class="form-control" value="'.$student_nis.'" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_nisn_edit">
                        <small>NISN</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="student_nisn" id="student_nisn_edit" class="form-control" value="'.$student_nisn.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_name_edit">
                        <small>Nama <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="student_name" id="student_name_edit" class="form-control" value="'.$student_name.'" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_gender_edit">
                        <small>Gender <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <select name="student_gender" id="student_gender_edit" class="form-control" required>
                        <option value="">Pilih</option>
                        <option '.$label_gender_male.' value="Male">Laki-laki</option>
                        <option '.$label_gender_female.' value="Female">Perempuan</option>
                    </select>
                </div>
            </div>
        ';
        echo '
            <div class="row mb-3">
                <div class="col-4">
                    <label for="place_of_birth_edit">
                        <small>Tempat Lahir</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="place_of_birth" id="place_of_birth_edit" class="form-control" value="'.$place_of_birth.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="date_of_birth_edit">
                        <small>Tanggal Lahir</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="date" name="date_of_birth" id="date_of_birth_edit" class="form-control" value="'.$date_of_birth.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_contact_edit">
                        <small>No.Kontak</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="student_contact" id="student_contact_edit" class="form-control" placeholder="+62" value="'.$student_contact.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_email_edit">
                        <small>Email</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="email" name="student_email" id="student_email_edit" class="form-control" placeholder="alamat_email@domain.com" value="'.$student_email.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_address_edit">
                        <small>Alamat</small>
                    </label>
                </div>
                <div class="col-8">
                    <textarea name="student_address" id="student_address_edit" class="form-control">'.$student_address.'</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="nama_ortu_edit">
                        <small>Nama Orang Tua/Wali</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="nama_ortu" id="nama_ortu_edit" class="form-control" value="'.$parent_nama.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="kontak_ortu_edit">
                        <small>Kontak Orang Tua/Wali</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="kontak_ortu" id="kontak_ortu_edit" class="form-control" placeholder="+62" value="'.$parent_kontak.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_foto_edit">
                        <small>Foto</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="file" name="student_foto" id="student_foto_edit" class="form-control">
                    <small>
                        <small class="text text-grayish">Maximum 2 Mb (File Type: PNG, JPG, GIF)</small>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_registered_edit">
                        <small>Tanggal Masuk <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="date" name="student_registered" id="student_registered_edit" class="form-control" value="'.$student_registered.'" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="student_status_edit">
                        <small>Status <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <select name="student_status" id="student_status_edit" class="form-control" required>
                        <option value="">Pilih</option>
                        <option '.$student_status_terdaftar.' value="Terdaftar">Terdaftar</option>
                        <option '.$student_status_lulus.' value="Lulus">Lulus</option>
                        <option '.$student_status_keluar.' value="Keluar">Keluar</option>
                    </select>
                </div>
            </div>
        ';

    }
?>