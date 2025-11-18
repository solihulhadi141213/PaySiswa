<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel
    $jml_data   = 0;

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang</small>
                </td>
            </div>
        ';
        exit;
    }

    //Validasi id_student
    if(empty($_POST['id_student'])){
        echo '
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-danger">ID Siswa Tidak Boleh Kosong!</small>
                </td>
            </div>
        ';
        exit;
    }

    //Validasi id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-danger">ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </div>
        ';
        exit;
    }

    //id_student
    $id_student             = $_POST['id_student'];
    $id_organization_class  = $_POST['id_organization_class'];

    //Buka Nama Siswa
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_gender         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');

    //Routing label gender
    if($student_gender=="Male"){
        $label_gender="Laki-laki";
    }else{
        if($student_gender=="Female"){
            $label_gender="Perempuan";
        }else{
            $label_gender="-";
        }
    }

    //Buka 'id_academic_period' dari tabel 'organization_class'
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Tampilkan Form

    echo '
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
        <div class="row mb-2">
            <div class="col-5"><small>Nama Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$student_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$student_nis.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Gender</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$label_gender.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Jenjang/Kelas</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Kelas/Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
        </div>
    ';
    echo '<div class="row mb-2">';
    echo '  <div class="col-12">';
    echo '      <label for="selest_kbp2"><small>Komponen Biaya</small></label>';
    echo '      <select id="selest_kbp2" name="id_fee_component" class="form-control" style="width:100%">';
    echo '          <option value="">Pilih Komponen</option>';
    //Menampilkan komponen biaya siswa
    $query = mysqli_query($Conn, "SELECT id_fee_by_class, id_fee_component FROM fee_by_class WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_class            = $data['id_fee_by_class'];
        $id_fee_component           = $data['id_fee_component'];

        //Buka Data Komponen
        $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
        $Qry->bind_param("i", $id_fee_component);
        if (!$Qry->execute()) {
            $error=$Conn->error;
            echo '<option value="">'.$error.'</option>';
            exit;
        }
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $component_name     = $Data['component_name'] ?? '';
        $fee_nominal        = $Data['fee_nominal'] ?? '0';
        echo '<option value="'.$id_fee_component.'" nominal="'.$fee_nominal.'">'.$component_name.'</option>';
    }
    echo '      </select>';
    echo '  </div>';
    echo '</div>';
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_tagihan_siswa2"><small>Nominal Tagihan</small></label>
                <input type="text" class="form-control form-money" id="nominal_tagihan_siswa2" name="fee_nominal" placeholder="Rp">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="nominal_diskon_siswa2"><small>Diskon</small></label>
                <input type="text" class="form-control form-money" id="nominal_diskon_siswa2" name="fee_discount" placeholder="Rp">
            </div>
        </div>
    ';
?>
<script>
    //Menambahkan tombol kembali dan tutup pada footer modal
    
    //Event Pendelegasiian ketika 'selest_kbp2' di ubah (change)
    $('#selest_kbp2').on('change', function() {
        // ambil atribut
        var nominal = $(this).find(':selected').attr('nominal');

        // tampilkan ke input
        $('#nominal_tagihan_siswa2').val(nominal); 

        initializeMoneyInputs();
    });
</script>