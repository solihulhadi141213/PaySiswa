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
    //Tangkap id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Tagihan Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka Data sISWA
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("i", $id_fee_by_student);
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
        $id_student             =$Data['id_student'];
        $id_fee_component       =$Data['id_fee_component'];
        $fee_nominal            =$Data['fee_nominal'];
        $fee_discount            =$Data['fee_discount'];

        //Buat dalam format rupiah
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
        $fee_discount_format="Rp " . number_format($fee_discount,0,',','.');

        //Buka Detail siswa
        $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
        $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

        //Buka Detaail fee_component
        $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
        $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
        $periode_start=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_start');
        $periode_end=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_end');

        //Tampilkan Data
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama Siswa</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>NIS</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Komponen Biaya</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$component_category.'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Periode</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.date('d/m/Y',strtotime($periode_start)).' - '.date('d/m/Y', strtotime($periode_end)).'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Nominal Tagihan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$fee_nominal_format.'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Diskon/Potongan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$fee_discount_format.'</small></div>
            </div>
        ';
    }
?>
<script>
    var id_siswa="<?php echo $id_student; ?>";
    // Tempelkan ke atribut data-id tombol
    $(".kembali_ke_komponen_biaya").attr("data-id", id_siswa);
</script>