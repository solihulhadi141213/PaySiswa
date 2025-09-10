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
    //Tangkap id_student
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Data Yang Dipilih
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
    $no=1;
    foreach ($_POST['id_student'] as $id_student) {
        //Buka Data Siswa
        $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
        $Qry->bind_param("i", $id_student);
        if (!$Qry->execute()) {
            $error=$Conn->error;
            echo '
                <tr>
                    <td colspan="4">
                        <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                    </td>
                </tr>
            ';
        }else{
            $Result = $Qry->get_result();
            $Data = $Result->fetch_assoc();
            $Qry->close();

            //Buat Variabel
            $student_nis            =$Data['student_nis'] ?? '-';
            $student_name           =$Data['student_name'];
            $student_gender         =$Data['student_gender'];
            $student_parent         =$Data['student_parent'];
            $student_registered     =$Data['student_registered'];
            $student_status         =$Data['student_status'];
            
            //Buka Kelas
            if(empty($Data['id_organization_class'])){
                $label_kelas='-';
            }else{
                $id_organization_class  =$Data['id_organization_class'];
                $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                $label_kelas="$level-$kelas";
            }

            //Tampilkan Pada Baris Tabel
            echo '
                <input type="hidden" name="id_student[]" value="'.$id_student .'">
            ';
        }
        $no++;
    }

    //Jumlah Data Yang Dipilih
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <small>
                        Anda memilih <b>'.count($_POST['id_student']).'</b> data siswa yang akan diupdate.<br>
                        Selanjutnya silahkan pilih <b>Periode Akademik</b> dan kelompok <b>Kelas</b>
                    </small>
                </div>
            </div>
        </div>
    ';

    //Form Periode Akademik
    echo <<<HTML
    <div class="row mb-3">
        <div class="col-12">
            <label for="id_academic_period">
                <small>Periode Akademik</small>
            </label>
            <select name="id_academic_period" id="id_academic_period_update" class="form-control">
                <option value="">Pilih</option>
    HTML;

    $QryTahunAkademik = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period ORDER BY academic_period_start ASC");
    while ($DataTahunAkademik = mysqli_fetch_array($QryTahunAkademik)) {
        $id_academic_period = $DataTahunAkademik['id_academic_period'];
        $academic_period = $DataTahunAkademik['academic_period'];
        echo '<option value="' . $id_academic_period . '">' . $academic_period . '</option>';
    }

    echo <<<HTML
            </select>
        </div>
    </div>
    HTML;
    
    //Form Kelas Dinamis
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <label for="id_organization_class_update">
                    <small>Kelas</small>
                </label>
                <select name="id_organization_class" id="id_organization_class_update" class="form-control">
                    <option value="">Pilih</option>
                </select>
            </div>
        </div>
    ';
?>

<script>
    //Ketika id_academic_period_update Diubah
    $('#id_academic_period_update').change(function(){
        var id_academic_period = $('#id_academic_period_update').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ListKelas.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#id_organization_class_update').html(data);
            }
        });
    });
</script>