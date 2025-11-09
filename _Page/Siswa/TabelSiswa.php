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
                <td colspan="10" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
    }else{
        //kelompok_status_siswa
        if(!empty($_POST['kelompok_status_siswa'])){
            $status_siswa=$_POST['kelompok_status_siswa'];
        }else{
            $status_siswa="";
        }
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
            $OrderBy="id_student ";
        }
        //Atur Page
        if(!empty($_POST['page'])){
            $page=$_POST['page'];
            $posisi = ( $page - 1 ) * $batas;
        }else{
            $page="1";
            $posisi = 0;
        }
        if(empty($_POST['kelompok_status_siswa'])){
            if(empty($keyword_by)){
                if(empty($keyword)){
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student "));
                }else{
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student WHERE student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%'"));
                }
            }else{
                if(empty($keyword)){
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student "));
                }else{
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student  WHERE $keyword_by like '%$keyword%'"));
                }
            }
        }else{
            if(empty($keyword_by)){
                if(empty($keyword)){
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student WHERE student_status='$status_siswa'"));
                }else{
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student WHERE (student_status='$status_siswa') AND (student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%')"));
                }
            }else{
                if(empty($keyword)){
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student WHERE student_status='$status_siswa'"));
                }else{
                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student  WHERE (student_status='$status_siswa') AND ($keyword_by like '%$keyword%')"));
                }
            }
        }
        
        //Mengatur Halaman
        $JmlHalaman = ceil($jml_data/$batas); 
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="10" class="text-center">
                        <small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if(empty($_POST['kelompok_status_siswa'])){
                if(empty($keyword_by)){
                    if(empty($keyword)){
                        $query = mysqli_query($Conn, "SELECT*FROM student  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }else{
                        $query = mysqli_query($Conn, "SELECT*FROM student  WHERE student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }
                }else{
                    if(empty($keyword)){
                        $query = mysqli_query($Conn, "SELECT*FROM student  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }else{
                        $query = mysqli_query($Conn, "SELECT*FROM student  WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }
                }
            }else{
                if(empty($keyword_by)){
                    if(empty($keyword)){
                        $query = mysqli_query($Conn, "SELECT*FROM student WHERE student_status='$status_siswa' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }else{
                        $query = mysqli_query($Conn, "SELECT*FROM student  WHERE (student_status='$status_siswa') AND (student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%') ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }
                }else{
                    if(empty($keyword)){
                        $query = mysqli_query($Conn, "SELECT*FROM student WHERE student_status='$status_siswa' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }else{
                        $query = mysqli_query($Conn, "SELECT*FROM student WHERE (student_status='$status_siswa') AND ($keyword_by like '%$keyword%') ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                    }
                }
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
                    $gender_label='<span class="badge badge-success"><i class="bi bi-gender-male"></i> Male</span>';
                }else{
                    if($student_gender=="Female"){
                        $gender_label='<span class="badge badge-danger"><i class="bi bi-gender-female"></i> Female</span>';
                    }else{
                        $gender_label='<span class="badge badge-dark"><i class="bi bi-x-circle"></i> NONE</span>';
                    }
                }

                //Buka Kelas
                if(empty($data['id_organization_class'])){
                    $label_kelas='-';
                    $id_academic_period='';
                    $academic_period='-';
                }else{
                    $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                    $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                    $id_academic_period=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                    $label_kelas="$level-$kelas";

                    //Periode Akademik
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
                echo '
                    <tr>
                        <td>
                            <input type="checkbox" name="id_student[]" class="form-check-input" value="'.$id_student .'">
                        </td>
                        <td><small>'.$no.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="text text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_student .'">
                                <small>'.$student_name.'</small>
                            </a>
                        </td>
                        <td><small>'.$student_nis.'</small></td>
                        <td><small>'.$label_kelas.'</small></td>
                        <td><small>'.$academic_period.'</small></td>
                        <td><small>'.$gender_label.'</small></td>
                        <td><small>'.$tanggal_daftar.'</small></td>
                        <td><small>'.$label_status.'</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_student .'">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_student .'">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_student .'">
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