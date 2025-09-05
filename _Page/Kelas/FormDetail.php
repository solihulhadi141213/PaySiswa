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

    //Buka Data access
    $Qry = $Conn->prepare("SELECT * FROM organization_class WHERE id_organization_class = ?");
    $Qry->bind_param("i", $id_organization_class);
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
        $class_level            =$Data['class_level'];
        $class_name             =$Data['class_name'];

        //Hitung Jumlah Siswa
        $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class  FROM  student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'"));

        //Hitung Komponen Biaya
        $jumlah_komponen=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));

        //Tampilkan Data
        echo '
            <input type="hidden" name="Page" value="Kelas">
            <input type="hidden" name="Sub" value="Detail">
            <input type="hidden" name="id" value="'.$id_organization_class.'">
            <div class="row mb-2">
                <div class="col-4"><small>ID</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$id_organization_class.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Level</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$class_level.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kelas</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$class_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Jumlah Siswa</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$jumlah_siswa.' Orang</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Komponen Biaya</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$jumlah_komponen.' Component</small>
                </div>
            </div>
        ';
    }
?>