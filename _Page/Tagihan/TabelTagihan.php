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
                <td colspan="11" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }
    if(empty($_POST['id_academic_period'])){
         echo '
            <tr>
                <td colspan="11" class="text-center">
                    <small class="text-danger">Silahkan pilih <b>Periode Tahun Akademik</b> terlebih dulu untuk menampilkan data tagihan siswa</small>
                </td>
            </tr>
        ';
        exit;
    }
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="11" class="text-center">
                    <small class="text-danger">Silahkan pilih <b>group kelas</b> terlebih dulu untuk menampilkan data tagihan siswa</small>
                </td>
            </tr>
        ';
        exit;
    }
    //kelompok_status_siswa
    if(!empty($_POST['kelompok_status_siswa'])){
        $status_siswa=$_POST['kelompok_status_siswa'];
    }else{
        $status_siswa="";
    }

    //Buat variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);

    //Hitung jumlah
    if(empty($_POST['kelompok_status_siswa'])){
        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class'"));
    }else{
        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE student_status='$status_siswa' AND id_organization_class='$id_organization_class'"));
    }
    
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="11" class="text-center">
                    <small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small>
                </td>
            </tr>
        ';
    }else{
        $no = 1;
        //KONDISI PENGATURAN MASING FILTER
        if(empty($_POST['kelompok_status_siswa'])){
            $query = mysqli_query($Conn, "SELECT*FROM student WHERE id_organization_class='$id_organization_class' ORDER BY student_name ASC");
        }else{
            $query = mysqli_query($Conn, "SELECT*FROM student WHERE student_status='$status_siswa' AND id_organization_class='$id_organization_class' ORDER BY student_name ASC");
        }
        while ($data = mysqli_fetch_array($query)) {
            $id_student = $data['id_student'];
            $id_organization_class= $data['id_organization_class'];
            $student_name= $data['student_name'];
            $student_gender= $data['student_gender'];
            $student_registered= $data['student_registered'];
            $student_status= $data['student_status'];

            //NIS
            if(empty($data['student_nis'])){
                $student_nis='-';
            }else{
                $student_nis=$data['student_nis'];
            }

            //Routing Gender
            if($student_gender=="Male"){
                $gender_label='<i class="bi bi-gender-male"></i> Male';
            }else{
                $gender_label='<i class="bi bi-gender-female"></i> Female';
            }

            //Buka Kelas
            if(empty($data['id_organization_class'])){
                $label_kelas='-';
                $id_academic_period="";
                $academic_period="-";
            }else{
                $id_academic_period=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                $label_kelas="$level-$kelas";

                $academic_period=GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
            }
            

            //Format Tanggal Daftar
            $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

            //Status
            if($student_status=="Terdaftar"){
                $label_status='<span class="badge badge-success">Terdaftar</span>';
            }else{
                if($student_status=="Lulus"){
                    $label_status='<span class="badge badge-warning">Lulus</span>';
                }else{
                    $label_status='<span class="badge badge-danger">Keluar</span>';
                }
            }

            //Buka Data Tagihan Siswa
            $jumlah_tagihan=0;
            $query_tagihan = mysqli_query($Conn, "SELECT fee_nominal, fee_discount FROM  fee_by_student WHERE id_student='$id_student'");
            while ($data_tagihan = mysqli_fetch_array($query_tagihan)) {
                $fee_nominal = $data_tagihan['fee_nominal'];
                $fee_discount = $data_tagihan['fee_discount'];

                //Hitung Subtotal
                $subtotal = $fee_nominal-$fee_discount;

                //Totalkan
                $jumlah_tagihan=$jumlah_tagihan+$subtotal;
            }
            
            //Format Uang Tagihan
            $jumlah_tagihan_format="" . number_format($jumlah_tagihan,0,',','.');

            //Buka Data Pembayaran Siswa
            $jumlah_payment=0;
            $query_payment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_student='$id_student'");
            while ($data_payment = mysqli_fetch_array($query_payment)) {
                if(empty($data_payment['payment_nominal'])){
                    $payment_nominal =0;
                }else{
                    $payment_nominal = $data_payment['payment_nominal'];
                }
                

                //Totalkan
                $jumlah_payment=$jumlah_payment+$payment_nominal;
            }
            
            //Format Uang Tagihan
            $jumlah_payment_format="" . number_format($jumlah_payment,0,',','.');

            //Menghitung Sisa Pembayaran
            $sisa=$jumlah_tagihan-$jumlah_payment;
            $sisa_format="" . number_format($sisa,0,',','.');

            //Tampilkan Data
            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td>
                        <a href="javascript:void(0);" class="text text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetailSiswa" data-id="'.$id_student .'">
                            <small>'.$student_name.'</small>
                        </a>
                    </td>
                    <td><small>'.$student_nis.'</small></td>
                    <td><small>'.$label_kelas.'</small></td>
                    <td><small>'.$academic_period.'</small></td>
                    <td><small>'.$tanggal_daftar.'</small></td>
                    <td><small>'.$label_status.'</small></td>
                    <td><small>'.$jumlah_tagihan_format.'</small></td>
                    <td><small>'.$jumlah_payment_format.'</small></td>
                    <td><small>'.$sisa_format.'</small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="'.$id_student .'">
                                    <i class="bi bi-list-check"></i> List Tagihan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaranSiswa" data-id="'.$id_student .'">
                                    <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }
    }
?>
<script>
    //Creat Javascript Variabel
    var jml_data="<?php echo $jml_data; ?>";
    
    //Put Into Pagging Element
    $('#page_info').html('Jumlah Data : '+jml_data+' Siswa');
    
</script>