<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    
    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel
    $JmlHalaman = 0;
    $page       = 0;
    $jml_data   = 0;
    $class_name = "";
    $academic_period ="";

    //id_organization_class wajib terisi
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
    }
    
    //Buat variabel id_organization_class
    $id_organization_class=$_POST['id_organization_class'];

    //Buka class_name
    $class_name = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka id_academic_period
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

    //Nama Periode
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //batas
    if(!empty($_POST['batas'])){
        $batas=$_POST['batas'];
    }else{
        $batas="10";
    }

    //page
    if(!empty($_POST['page'])){
        $page=$_POST['page'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page="1";
        $posisi = 0;
    }

    //id_fee_component
    if(!empty($_POST['id_fee_component'])){
        $id_fee_component=$_POST['id_fee_component'];
    }else{
        $id_fee_component="";
    }

    //id_student
    if(!empty($_POST['id_student'])){
        $id_student=$_POST['id_student'];
    }else{
        $id_student="";
    }
  
    //ShortBy
    if(!empty($_POST['ShortBy'])){
        $ShortBy=$_POST['ShortBy'];
    }else{
        $ShortBy="DESC";
    }

    //OrderBy
    if(!empty($_POST['OrderBy'])){
        $OrderBy=$_POST['OrderBy'];
    }else{
        $OrderBy="id_student ";
    }
    
    //Hitung Jumlah Data
    if(empty($id_fee_component)){
        if(empty($id_student)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
        }
    }else{
        if(empty($id_student)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student' AND id_fee_component='$id_fee_component'"));
        }
    }

    //Mengatur Halaman
    $JmlHalaman = ceil($jml_data/$batas); 

    //Jika Data Kosong
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Tida Ada Data Yang Ditampilkan</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Atur Nomoe Dan Posisi
    $no = 1+$posisi;

    //Routing Query Berdasarkan Kondisi
    if(empty($id_fee_component)){
        if(empty($id_student)){
            $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }else{
            $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }
    }else{
        if(empty($id_student)){
            $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }else{
            $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student' AND id_fee_component='$id_fee_component' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
        }
    }

    //Looping Tampiilkan Data
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_student      = $data['id_fee_by_student'];
        $id_organization_class  = $data['id_organization_class'];
        $id_student             = $data['id_student'];
        $id_fee_component       = $data['id_fee_component'];
        $fee_nominal            = $data['fee_nominal'];
        $fee_discount           = $data['fee_discount'];
        $jumlah_tagihan         = $fee_nominal - $fee_discount;

        //Nama Siswa
        $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

        //Nama Komponen
        $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

        //Pembayaran
        $payment_nominal = GetDetailData($Conn, 'payment', 'id_fee_by_student', $id_fee_by_student, 'payment_nominal');
        if(empty($payment_nominal)){
            $payment_nominal=0;
        }

        //Format Rupiah
        $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
        $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
        $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
        $payment_nominal_format  = "Rp " . number_format($payment_nominal,0,',','.');
               
        echo '
            <tr>
                <td>
                    <input type="checkbox" name="id_fee_by_student[]" class="form-check-input" value="'.$id_fee_by_student .'">
                </td>
                <td><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
                <td><small>'.$fee_discount_format.'</small></td>
                <td><small>'.$jumlah_tagihan_format.'</small></td>
                <td><small>'.$payment_nominal_format.'</small></td>
                
            </tr>
        ';
        $no++;
    }
?>
<script>
    //Creat Javascript Variabel
    var data_count  =<?php echo $jml_data; ?>;
    var page_count  =<?php echo $JmlHalaman; ?>;
    var curent_page =<?php echo $page; ?>;

    //Atribut untuk title
    var class_name ="<?php echo $class_name; ?>";
    var academic_period ="<?php echo $academic_period; ?>";

    //Put into title_tagihan_siswa
    $('#title_tagihan_siswa').html('<h3>DAFTAR TAGIHAN SISWA</h3><b>KELAS '+class_name+' / PERIODE '+academic_period+'</b>');

    //Put Into page_info_tagihan_siswa
    $('#page_info_tagihan_siswa').html(''+curent_page+' / '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button_tagihan_siswa').prop('disabled', true);
    }else{
        $('#prev_button_tagihan_siswa').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button_tagihan_siswa').prop('disabled', true);
    }else{
        $('#next_button_tagihan_siswa').prop('disabled', false);
    }

    //Periksa Checklist
    var total = $('input[name="id_fee_by_student[]"]').length;
    var checked = $('input[name="id_fee_by_student[]"]:checked').length;

    if (total === checked) {
        $('input[name="check_all"]').prop('checked', true);
    } else {
        $('input[name="check_all"]').prop('checked', false);
    }
</script>