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
                <td colspan="6" class="text-center">
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

        //Menghitung Jumlah Data
        if(empty($keyword)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student "));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student  FROM student WHERE student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%'"));
        }
        
        //Mengatur Jumlah Halaman
        $JmlHalaman = ceil($jml_data/$batas); 
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="6" class="text-center">
                        <small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if(empty($keyword)){
                $query = mysqli_query($Conn, "SELECT*FROM student  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
            }else{
                $query = mysqli_query($Conn, "SELECT*FROM student  WHERE student_name like '%$keyword%' OR student_nis like '%$keyword%' OR id_organization_class like '%$keyword%' OR student_gender like '%$keyword%' OR student_registered like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
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

                //Format Tanggal Daftar
                $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

                //Status
                if($student_status=="Terdaftar"){
                    $label_status="";
                }else{
                    if($student_status=="Lulus"){
                        $label_status='bg bg-success-subtle';
                    }else{
                        $label_status='bg bg-danger-subtle';
                    }
                }

                echo '
                    <tr>
                        <td><small>'.$no.'</small></td>
                        <td><small>'.$student_nis.'</small></td>
                        <td><small>'.$student_name.'</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btn-floating modal_tambah_tagihan" data-id="'.$id_student.'">
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