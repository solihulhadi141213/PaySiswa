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
                <td colspan="5" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
    }else{
        //semua_siswa
        $status_siswa="Terdaftar";
        if(!empty($_POST['semua_siswa'])){
            $semua_siswa=$_POST['semua_siswa'];
        }else{
            $semua_siswa="";
        }
        
        //Keyword_by
        if(!empty($_POST['keyword_by_siswa'])){
            $keyword_by=$_POST['keyword_by_siswa'];
        }else{
            $keyword_by="";
        }
        
        //keyword
        if(!empty($_POST['keyword_siswa'])){
            $keyword=$_POST['keyword_siswa'];
        }else{
            $keyword="";
        }
        
        //batas
        if(!empty($_POST['batas_siswa'])){
            $batas=$_POST['batas_siswa'];
        }else{
            $batas="10";
        }
        
        //ShortBy
        $ShortBy="DESC";
        
        //OrderBy
        $OrderBy="id_student ";
        
        //Atur Page
        if(!empty($_POST['page_siswa'])){
            $page=$_POST['page_siswa'];
            $posisi = ( $page - 1 ) * $batas;
        }else{
            $page="1";
            $posisi = 0;
        }
        if($semua_siswa!==""){
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
                    <td colspan="5" class="text-center">
                        <small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if($semua_siswa!==""){
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

                //Buka Kelas
                if(empty($data['id_organization_class'])){
                    $label_kelas='-';
                    $id_academic_period='';
                    $academic_period='';
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
                    $label_status='text-dark';
                }else{
                    $label_status='text-grayish';
                }

                if($student_status=="Terdaftar"){
                    $label_status2='<span class="badge badge-success">Terdaftar</span>';
                }else{
                    if($student_status=="Lulus"){
                        $label_status2='<span class="badge badge-warning">Lulus</span>';
                    }else{
                        $label_status2='<span class="badge badge-danger">Keluar</span>';
                    }
                }
                echo '
                    <tr>
                        <td><small class="'.$label_status.'">'.$no.'</small></td>
                        <td><small class="'.$label_status.'">'.$student_name.'</small></td>
                        <td><small class="'.$label_status.'">'.$student_nis.'</small></td>
                        <td><small class="'.$label_status.'">'.$label_kelas.'</small></td>
                        <td><small class="'.$label_status.'">'.$academic_period.'</small></td>
                        <td><small class="'.$label_status.'">'.$label_status2.'</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btn-floating"  data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya" data-id="'.$id_student .'">
                                <i class="bi bi-check"></i>
                            </button>
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
    $('#page_info_siswa').html('Page '+curent_page+' Of '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button_siswa').prop('disabled', true);
    }else{
        $('#prev_button_siswa').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button_siswa').prop('disabled', true);
    }else{
        $('#next_button_siswa').prop('disabled', false);
    }
</script>