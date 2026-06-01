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
                <td colspan="12" class="text-center">
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
                    <td colspan="12" class="text-center">
                        <small class="text-danger">Tidak Ada Data Siswa Yang Ditampilkan!</small>
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
                    $gender_label='<span class="badge bg-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Laki-laki (Male)">L</span>';
                }else{
                    if($student_gender=="Female"){
                        $gender_label='<span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Perempuan (Female)">P</span>';
                    }else{
                        $gender_label='<span class="badge bg-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tidak Diketahui">-</span>';
                    }
                }

                //Buka Kelas
                if(empty($data['id_organization_class'])){
                    $label_jenjang='-';
                    $label_kelas='-';
                    $id_academic_period='';
                    $academic_period='-';
                }else{
                    $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                    $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                    $id_academic_period=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                    $label_jenjang="$level";
                    $label_kelas="$kelas";

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

                //Menghitung Sisa Tunggakan
                ## Hitung Jumlah Tagihan
                $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_student='$id_student'"));
                if(!empty($SumTagihan['total_tagihan'])){
                    $jumlah_tagihan = $SumTagihan['total_tagihan'];
                }else{
                    $jumlah_tagihan = 0;
                }
                $jumlah_tagihan_format  = "" . number_format($jumlah_tagihan,0,',','.');

                ## Hitung Jumlah Pembayaran
                $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_student='$id_student'"));
                if(!empty($SumPembayaran['payment_nominal'])){
                    $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
                }else{
                    $jumlah_pembayaran = 0;
                }
                
                $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');

                ## Menghitung Sisa Tagihan
                $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
                $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');
                if(empty($sisa_tagihan)){
                    $label_sisa = '<span class="text text-grayish">Rp 0</span>';
                }else{
                    $label_sisa = '<span class="text text-dark">'.$sisa_tagihan_format.'</span>';
                }
                $tooltip = "
                <div class='text-left' style='margin:0; font-size:13px; align:left;'>
                Tagihan     : $jumlah_tagihan_format
                Pembayaran  : $jumlah_pembayaran_format
                --------------------------------
                Sisa        : $sisa_tagihan_format
                </div>
                ";

                echo '
                    <tr>
                        <td>
                            <input type="checkbox" name="id_student[]" class="form-check-input" value="'.$id_student .'">
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="badge badge-info" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_student .'">
                                '.$no.'
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_student .'">
                                <small>'.$student_name.'</small>
                            </a>
                        </td>
                        <td><small>'.$student_nis.'</small></td>
                        <td><small>'.$label_jenjang.'</small></td>
                        <td><small>'.$label_kelas.'</small></td>
                        <td><small>'.$academic_period.'</small></td>
                        <td>'.$gender_label.'</td>
                        <td><small>'.$tanggal_daftar.'</small></td>
                        <td><small>'.$label_status.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="modal_rekapitulasi_tagihan_siswa" data-id="'.$id_student.'" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="'.$tooltip.'">
                                <small class="underscore_doted">'.$label_sisa.'</small>
                            </a>
                        </td>
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