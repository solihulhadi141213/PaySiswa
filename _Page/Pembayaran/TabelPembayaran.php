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
        if(!empty($_POST['keyword_by'])){
            $keyword_by=$_POST['keyword_by'];
        }else{
            $keyword_by="";
        }
        //keyword
        if(!empty($_POST['keyword'])){
            $keyword=$_POST['keyword'];
        }else{
            $keyword="";
        }
        //batas
        if(!empty($_POST['batas'])){
            $batas=$_POST['batas'];
        }else{
            $batas="10";
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
            $OrderBy="id_payment";
        }
        //Atur Page
        if(!empty($_POST['page'])){
            $page=$_POST['page'];
            $posisi = ( $page - 1 ) * $batas;
        }else{
            $page="1";
            $posisi = 0;
        }
        if(empty($keyword_by)){
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment  FROM payment "));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment  FROM payment WHERE id_student like '%$keyword%' OR id_organization_class like '%$keyword%' OR id_fee_component like '%$keyword%' OR payment_datetime like '%$keyword%' OR payment_nominal like '%$keyword%' OR payment_method like '%$keyword%'"));
            }
        }else{
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment  FROM payment "));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment  FROM payment  WHERE $keyword_by like '%$keyword%'"));
            }
        }
        
        //Mengatur Halaman
        $JmlHalaman = ceil($jml_data/$batas); 
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="9" class="text-center">
                        <small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if(empty($keyword_by)){
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM payment  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM payment  WHERE id_student like '%$keyword%' OR id_organization_class like '%$keyword%' OR id_fee_component like '%$keyword%' OR payment_datetime like '%$keyword%' OR payment_nominal like '%$keyword%' OR payment_method like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }else{
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM payment  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM payment  WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }
            while ($data = mysqli_fetch_array($query)) {
                $id_payment = $data['id_payment'];
                $id_student = $data['id_student'];
                $id_organization_class= $data['id_organization_class'];
                $id_fee_component= $data['id_fee_component'];
                $payment_datetime= $data['payment_datetime'];
                $payment_nominal= $data['payment_nominal'];
                $payment_method= $data['payment_method'];

                //NIS Siswa
                $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                //Nama Siswa
                $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

                //Nama Komponen Biaya
                $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

                //Buka Kelas
                if(empty($data['id_organization_class'])){
                    $label_kelas='-';
                }else{
                    $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                    $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                    $label_kelas="$level-$kelas";
                }
                
                //Format Tanggal pembayaran
                $payment_datetime_format=date('d/m/Y', strtotime($payment_datetime));

                //Format Rp Nominal
                $payment_nominal_format="Rp" . number_format($payment_nominal,0,',','.');

                echo '
                    <tr>
                        <td><small>'.$no.'</small></td>
                        <td><small>'.$payment_datetime_format.'</small></td>
                        <td><small>'.$student_nis.'</small></td>
                        <td><small>'.$student_name.'</small></td>
                        <td><small>'.$label_kelas.'</small></td>
                        <td><small>'.$component_name.'</small></td>
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