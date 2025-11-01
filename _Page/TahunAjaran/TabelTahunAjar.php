<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel pertama kali
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
            $OrderBy="id_academic_period";
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
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period  "));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period WHERE academic_period like '%$keyword%' OR academic_period_end like '%$keyword%' OR academic_period_start like '%$keyword%' OR academic_period_status like '%$keyword%'"));
            }
        }else{
            if(empty($keyword)){
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period "));
            }else{
                $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period  WHERE $keyword_by like '%$keyword%'"));
            }
        }
        
        //Mengatur Halaman
        $JmlHalaman = ceil($jml_data/$batas); 
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="11" class="text-center">
                        <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            $no = 1+$posisi;
            //KONDISI PENGATURAN MASING FILTER
            if(empty($keyword_by)){
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM academic_period  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM academic_period  WHERE academic_period like '%$keyword%' OR academic_period_end like '%$keyword%' OR academic_period_start like '%$keyword%' OR academic_period_status like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }else{
                if(empty($keyword)){
                    $query = mysqli_query($Conn, "SELECT*FROM academic_period  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }else{
                    $query = mysqli_query($Conn, "SELECT*FROM academic_period  WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                }
            }
            while ($data = mysqli_fetch_array($query)) {
                $id_academic_period = $data['id_academic_period'];
                $academic_period= $data['academic_period'];
                $academic_period_start= $data['academic_period_start'];
                $academic_period_end= $data['academic_period_end'];
                $academic_period_status= $data['academic_period_status'];
                if($academic_period_status==true){
                    $label_status='<span class="badge badge-success"><i class="bi bi-check-circle"></i> Unlock</span>';
                    $tombol_lanjutan='<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKunci" data-id="'.$id_academic_period .'"><i class="bi bi-lock"></i> Kunci</a>';
                }else{
                    $label_status='<span class="badge badge-danger"><i class="bi bi-lock"></i> Locked</span>';
                    $tombol_lanjutan='<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKunci" data-id="'.$id_academic_period .'"><i class="bi bi-check-circle"></i> Aktifkan</a>';
                }

                //Menghitung Jumlah Kelas
                $jumlah_kelas=mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));
                if(empty($jumlah_kelas)){
                    $label_jumlah_kelas = '
                        <a href="javascript:void(0);" class="badge badge-secondary" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="'.$id_academic_period .'">
                            '.$jumlah_kelas.' Kelas
                        </a>
                    ';
                }else{
                    $label_jumlah_kelas = '
                        <a href="javascript:void(0);" class="badge badge-info" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="'.$id_academic_period .'">
                            '.$jumlah_kelas.' Kelas
                        </a>
                    ';
                }

                //Menghiutng Biaya Pendidikan
                $jumlah_fee_component=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));

                //Menghitung Jumlah Tagihan
                //1. Looping Data Kelas Berdasarkan Periode
                $total_tagihan=0;
                $total_pembayaran=0;
                $total_siswa=0;
                $QryKelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'");
                while ($DataKelas = mysqli_fetch_array($QryKelas)) {
                    $id_organization_class = $DataKelas['id_organization_class'];
                    
                    //2. Hitung Jumlah Tagihan Berdasarkan id_organization_class
                    $SumTagihan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                    $jumlah_tagihan = $SumTagihan['total_tagihan'];
                    $total_tagihan=$total_tagihan+$jumlah_tagihan;

                    //2. Hitung Jumlah Pembayaran
                    $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS total_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
                    $jumlah_pembayaran = $SumPembayaran['total_pembayaran'];
                    $total_pembayaran=$total_pembayaran+$jumlah_pembayaran;

                    //Hitung jumlah siswa yang terdaftar pada periode tersebut melalui tabel  fee_by_student  
                    $jumlah_siswa = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                    $total_siswa = $total_siswa + $jumlah_siswa;
                }
                if(empty($total_siswa)){
                    $label_total_siswa = '
                        <a href="javascript:void(0);" class="badge badge-secondary" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="'.$id_academic_period .'">
                           '.$total_siswa.' Orang
                        </a>
                    ';
                }else{
                    $label_total_siswa = '
                        <a href="javascript:void(0);" class="badge badge-info" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="'.$id_academic_period .'">
                           '.$total_siswa.' Orang
                        </a>
                    ';
                }

                //Ubah total_tagihan Dalam Format Rupiah
                $total_tagihan=round($total_tagihan);
                $total_tagihan_format="Rp " . number_format($total_tagihan,0,',','.');

                //Ubah total_pembayaran Dalam Format Rupiah
                $total_pembayaran=round($total_pembayaran);
                $total_pembayaran_format="Rp " . number_format($total_pembayaran,0,',','.');

                
                //Tampilkan Data
                echo '
                    <tr>
                        <td><small>'.$no.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="text text-primary text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_academic_period .'">
                                <small>'.$academic_period.'</small>
                            </a>
                        </td>
                        <td><small>'.date('d/m/Y', strtotime($academic_period_start)).'</small></td>
                        <td><small>'.date('d/m/Y', strtotime($academic_period_end)).'</small></td>
                        <td>'.$label_jumlah_kelas.'</td>
                        <td>'.$label_total_siswa.'</td>
                        <td>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya" data-id="'.$id_academic_period .'">
                                <span class="badge badge-info">'.$jumlah_fee_component.'</span>
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="'.$id_academic_period .'"> 
                                <small>'.$total_tagihan_format.'</small>
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id="'.$id_academic_period .'"> 
                                <small>'.$total_pembayaran_format.'</small>
                            </a>
                        </td>
                        <td><small>'.$label_status.'</small></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </li>
                                <li>
                                    '. $tombol_lanjutan.'
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-building"></i> Kelas / Rombel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDaftarSiswa" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-people"></i> Daftar Siswa
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-list-nested"></i> Komponen Biaya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="'.$id_academic_period .'">
                                        <i class="bi bi-cash-stack"></i> Tagihan Siswa
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id="'.$id_academic_period .'">
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