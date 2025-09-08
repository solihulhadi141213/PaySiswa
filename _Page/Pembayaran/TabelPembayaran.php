<?php
//koneksi dan session
include "../../_Config/Connection.php";
include "../../_Config/GlobalFunction.php";
include "../../_Config/Session.php";
date_default_timezone_set("Asia/Jakarta");
$JmlHalaman=0;
$page=0;

//Validasi Akses
if(empty($SessionIdAccess)){
    echo '
        <tr>
            <td colspan="9" class="text-center">
                <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </td>
        </tr>
    ';
}else{
    //Keyword_by
    $keyword_by = !empty($_POST['keyword_by']) ? $_POST['keyword_by'] : "";
    //keyword
    $keyword = !empty($_POST['keyword']) ? $_POST['keyword'] : "";
    //batas
    $batas = !empty($_POST['batas']) ? $_POST['batas'] : 10;
    //ShortBy
    $ShortBy = !empty($_POST['ShortBy']) ? $_POST['ShortBy'] : "DESC";
    //OrderBy
    $OrderBy = !empty($_POST['OrderBy']) ? $_POST['OrderBy'] : "id_payment";
    //Atur Page
    if(!empty($_POST['page'])){
        $page=$_POST['page'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page=1;
        $posisi = 0;
    }

    //Query dasar dengan JOIN
    $sql_base = "
        FROM payment p
        LEFT JOIN student s ON p.id_student = s.id_student
        LEFT JOIN organization_class oc ON p.id_organization_class = oc.id_organization_class
        LEFT JOIN fee_component fc ON p.id_fee_component = fc.id_fee_component
    ";

    //Filter pencarian
    $where = " WHERE 1=1 ";
    if(!empty($keyword)){
        if(empty($keyword_by)){
            $where .= " AND (
                s.student_name LIKE '%$keyword%' OR
                s.student_nis LIKE '%$keyword%' OR
                oc.class_name LIKE '%$keyword%' OR
                oc.class_level LIKE '%$keyword%' OR
                fc.component_name LIKE '%$keyword%' OR
                fc.component_category LIKE '%$keyword%' OR
                p.payment_datetime LIKE '%$keyword%' OR
                p.payment_method LIKE '%$keyword%'
            )";
        }else{
            //Mapping supaya aman
            $allowed_fields = [
                'student_name' => 's.student_name',
                'student_nis' => 's.student_nis',
                'id_organization_class' => 'oc.class_name',
                'id_fee_component' => 'fc.component_name',
                'payment_datetime' => 'p.payment_datetime',
                'payment_method' => 'p.payment_method'
            ];
            if(array_key_exists($keyword_by, $allowed_fields)){
                $where .= " AND ".$allowed_fields[$keyword_by]." LIKE '%$keyword%'";
            }
        }
    }

    //Hitung total data
    $q_count = mysqli_query($Conn, "SELECT COUNT(p.id_payment) as jml ".$sql_base.$where);
    $h_count = mysqli_fetch_assoc($q_count);
    $jml_data = $h_count['jml'];

    //Mengatur Halaman
    $JmlHalaman = ceil($jml_data/$batas); 
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                </td>
            </tr>
        ';
    }else{
        $no = 1+$posisi;
        $query = mysqli_query($Conn, "
            SELECT p.*, s.student_name, s.student_nis, 
                   oc.class_level, oc.class_name,
                   fc.component_name, fc.component_category
            ".$sql_base.$where."
            ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas
        ");

        while ($data = mysqli_fetch_array($query)) {
            $id_payment = $data['id_payment'];
            $student_nis = $data['student_nis'];
            $student_name = $data['student_name'];
            $label_kelas = $data['class_level']."-".$data['class_name'];
            $component_name = $data['component_name'];
            $component_category = $data['component_category'];
            $payment_datetime_format = date('d/m/Y', strtotime($data['payment_datetime']));
            $payment_nominal_format = "Rp" . number_format($data['payment_nominal'],0,',','.');
            $payment_method = $data['payment_method'];

            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td><small>'.$payment_datetime_format.'</small></td>
                    <td><small>'.$student_nis.'</small></td>
                    <td><small>'.$student_name.'</small></td>
                    <td><small>'.$label_kelas.'</small></td>
                    <td><small>'.$component_name.' ('.$component_category.')</small></td>
                    <td><small>'.$payment_nominal_format.'</small></td>
                    <td><small>'.$payment_method.'</small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_payment .'">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_payment .'">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_payment .'">
                                    <i class="bi bi-x"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }
    }
}
?>
<script>
    //Creat Javascript Variabel
    var page_count=<?php echo $JmlHalaman; ?>;
    var curent_page=<?php echo $page; ?>;
    
    //Put Into Pagging Element
    $('#page_info').html('Page '+curent_page+' Of '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button').prop('disabled', true);
    }else{
        $('#prev_button').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button').prop('disabled', true);
    }else{
        $('#next_button').prop('disabled', false);
    }
</script>
