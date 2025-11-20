<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi 'id_academic_period'
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="4" class="text-center">
                    <small class="text-danger">Periode Akademik Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Buat Variabel 'id_academic_period'
    $id_academic_period=$_POST['id_academic_period'];

    //Nomor Urut 'class_level'
    $no_class_level = 1;

    //Menampilkan 'class_level' Secara Distinct Berdasarkan 'id_academic_period'
    $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($data_level = mysqli_fetch_array($query_level)) {
        $class_level = $data_level['class_level'];

        //Menampilkan baris Level/Jenjang
        echo '
            <tr>
                <td><small><b>'.$no_class_level.'</b></small></td>
                <td colspan="3"><small><b>'.$class_level.'</b></small></td>
            </tr>
        ';

        //Nomor urut kelas
        $no_kelas = 1;

        //Tampilkan Kelas
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class_list = $data_kelas['id_organization_class'];
            $class_name = $data_kelas['class_name'];
            
            //hitung jumlah siswa
            $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class_list'"));

            //Kondisi cheked
            if($no_class_level==1&&$no_kelas==1){
                $condition_check = "checked";
            }else{
                $condition_check = "";
            }

            //Menampilkan Baris Kelas/Rombel
            echo '
                <tr>
                    <td>
                        <input type="radio" class="form-check-input" name="id_organization_class" value="'.$id_organization_class_list.'" '.$condition_check.'>
                    </td>
                    <td><small>'.$class_level.'</small></td>
                    <td><small>'.$class_name.'</small></td>
                    <td><small>'.$jumlah_siswa.'</small></td>
                </tr>
            ';

            $no_kelas++;
        }
        $no_class_level++;
    }

?>