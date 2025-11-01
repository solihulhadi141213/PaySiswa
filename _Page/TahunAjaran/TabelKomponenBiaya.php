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
            <tr><td colspan="6" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
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
            <tr><td colspan="6" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Menampilkan Data Siswa
    $no=1;
    $QeryKomponenBiaya = mysqli_query($Conn, "SELECT * FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY periode_month ASC");
    while ($DataKomponenBiaya = mysqli_fetch_array($QeryKomponenBiaya)) {
        $component_name         = $DataKomponenBiaya['component_name'];
        $component_category     = $DataKomponenBiaya['component_category'];
        $periode_month          = $DataKomponenBiaya['periode_month'];
        $periode_year           = $DataKomponenBiaya['periode_year'];
        $fee_nominal            = $DataKomponenBiaya['fee_nominal'];

        //Format Nominal
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

        //Buka List Sswa
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$component_category.'</small></td>
                <td><small>'.$periode_month.'</small></td>
                <td><small>'.$periode_year.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
            </tr>
        ';
        $no++;
    }
    echo '<script>$("#title_komponen_biaya").html("PERIODE '.$academic_period.'");</script>';

?>