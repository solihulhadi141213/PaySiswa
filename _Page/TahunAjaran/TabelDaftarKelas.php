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
            <tr><td colspan="3" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="3" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Informasi Periode Akdemik
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr><td colspan="3" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    //Inisiasi $title_daftar_kelas
    $title_daftar_kelas = '
        <b>
            DAFTAR KELAS (ROMBEL) <br>
            PERIODE AKADEMIK <span class="text-primary underscore_doted">'.$academic_period.'</span>
        </b>
    ';
    if(empty($jml_data)){
        echo '
            <tr><td colspan="3" class="text-center"><small class="text-danger">Tidak Ada Kelas Untuk Periode Ini </small></td></tr>
            <script> $("#title_daftar_kelas").html(' . json_encode($title_daftar_kelas) . ');</script>
        ';
        exit;
    }

    //Inisialisasi Nomor Level
    $no_class_level       = 1;
    
    //Looping tabel 'class_level'
    $QryClassLevel = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($DataClassLevel = mysqli_fetch_array($QryClassLevel)) {
        $class_level = $DataClassLevel['class_level'];

        //Tampilkan Data class_level
        echo '
            <tr>
                <td class="bg bg-body-secondary"><small><b>'.$no_class_level.'</b></small></td>
                <td class="bg bg-body-secondary" colspan="2"><small><b>'.$class_level.'</b></small></td>
            </tr>
        ';

        //Inisialisasi 'no_class_name'
        $no_class_name       = 1;
        
        //Looping tabel 'class_name'
        $QryClassName = mysqli_query($Conn, "SELECT class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
        while ($DataClassName = mysqli_fetch_array($QryClassName)) {
            $class_name = $DataClassName['class_name'];

            //Tampilkan Data class_name
            echo '
                <tr>
                    <td align="left"><small></small></td>
                    <td align="left"><small>'.$no_class_level.'. '.$no_class_name.'</small></td>
                    <td align="left" colspan="2"><small>'.$class_name.'</small></td>
                </tr>
            ';

            $no_class_name++;
        }


        $no_class_level++;
    }

    //Tampilkan Di Atas Tabel
    echo '
        <script>
            $("#title_daftar_kelas").html(' . json_encode($title_daftar_kelas) . ');
        </script>
    ';
?>