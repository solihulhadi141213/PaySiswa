<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $JmlHalaman=0;
    $page=0;
    $jml_data = 0;
    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="11" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>
                $("#title_table").html("");
                $("#page_info").html("Jumlah Data : '.$jml_data.'");
                $("#put_id_organization_class").val("");
            </script>
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
            <script>
                $("#title_table").html("");
                $("#page_info").html("Jumlah Data : '.$jml_data.'");
                $("#put_id_organization_class").val("");
            </script>
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
            <script>
                $("#title_table").html("");
                $("#page_info").html("Jumlah Data : '.$jml_data.'");
                $("#put_id_organization_class").val("");
            </script>
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

    //Buka Detail Periode Akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Buat title tabel
    $table_title = '
        <div class="row mb-2">
            <div class="col-12 text-center"><b class="text text-decoration-underline">DAFTAR TAGIHAN SISWA</b></div>
        </div>
        <div class="row mb-2 border-1 border-bottom">
            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <a href="javascript:void(0);" class="text text-primary modal_filter_tagihan" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ubah Filter Periode Akademik Dan Mode Tampilan Data">
                            <small class="text text-primary">'.$academic_period.' <i class="bi bi-arrow-up-right-square"></i></small>
                        </a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Mulai</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period_start.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Selesai</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period_end.'</small></div>
                </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="col-5"><small>Level/Jenjang</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-md btn-outline-secondary modal_metriks_tagihan">
                    <i class="bi bi-table"></i> Metriks Tagihan
                </button>
            </div>
        </div>
    ';

    //Hitung jumlah
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student  WHERE id_organization_class='$id_organization_class'"));
    
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="11" class="text-center">
                    <small class="text-danger">Tidak Ada Data Tagihan Yang Ditampilkan!</small>
                </td>
            </tr>
            <script>
                $("#title_table").html(' . json_encode($table_title) . ');
                $("#page_info").html("Jumlah Data : '.$jml_data.'");
                $("#put_id_organization_class").val("'.$id_organization_class.'");
            </script>
        ';
    }else{
        //Inisialisasi Nomor
        $no = 1;
        //MEMBUAT QUERY UNTUK MENAMPILKAN DATA DAN MENGURUTKAN BERDASARKAN NAMA
        $query = mysqli_query($Conn, "
            SELECT DISTINCT f.id_student 
            FROM fee_by_student AS f
            JOIN student AS s ON f.id_student = s.id_student
            WHERE f.id_organization_class = '$id_organization_class'
            ORDER BY s.student_name ASC
        ");
        while ($data = mysqli_fetch_array($query)) {
            $id_student = $data['id_student'];

            //Buka data dari tabel 'student'
            $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
            $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');


            //Menghitung Biaya Pendidikan, Diskon, Tagihan Siswa, Pembayaran dan Sisa Tunggakan
            ## Hitung Jumlah Biaya Pendidikan
            $SumBiayaPendidikan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS biaya_pendidikan FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
            $jumlah_biaya_pendidikan        = $SumBiayaPendidikan['biaya_pendidikan'];
            $jumlah_biaya_pendidikan_format = "" . number_format($jumlah_biaya_pendidikan,0,',','.');
            if(empty($jumlah_biaya_pendidikan)){
                $label_jumlah_biaya_pendidikan = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_jumlah_biaya_pendidikan = '<span class="text text-dark">'.$jumlah_biaya_pendidikan_format.'</span>';
            }
            
            ## Hitung Jumlah Diskon
            $SumDiskon              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
            $jumlah_diskon          = $SumDiskon['total_diskon'];
            $jumlah_diskon          = round($jumlah_diskon);
            $jumlah_diskon_format   = "Rp " . number_format($jumlah_diskon,0,',','.');
            if(empty($jumlah_diskon)){
                $label_jumlah_diskon = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_jumlah_diskon = '<span class="text text-dark">'.$jumlah_diskon_format.'</span>';
            }

            ## Hitung Jumlah Tagihan
            $SumTagihan              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
            $jumlah_tagihan          = $SumTagihan['total_tagihan'];
            $jumlah_tagihan_format   = "Rp " . number_format($jumlah_tagihan,0,',','.');
            if(empty($jumlah_tagihan)){
                $label_jumlah_tagihan = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_jumlah_tagihan = '<span class="text text-dark">'.$jumlah_tagihan_format.'</span>';
            }

            ## Hitung Jumlah Pembayaran
            $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
            if(empty($jumlah_pembayaran)){
                $label_jumlah_pembayaran = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_jumlah_pembayaran = '<span class="text text-success">'.$jumlah_pembayaran_format.'</span>';
            }

            ## Menghitung Sisa Tagihan
            $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
            $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');
            if(empty($sisa_tagihan)){
                $label_sisa_tagihan = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_sisa_tagihan = '<span class="text text-dark">'.$sisa_tagihan_format.'</span>';
            }

            //Tampilkan Data
            echo '
                <tr>
                    <td align="center"><small>'.$no.'</small></td>
                    <td>
                        <a href="javascript:void(0);" class="underscore_doted modal_detail_siswa" data-id="'.$id_student .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click Untuk Melihat Detail Siswa">
                            <small>'.$student_name.'</small>
                        </a>
                    </td>
                    <td><small>'.$student_nis.'</small></td>
                    <td align="right"><small class="">'.$label_jumlah_biaya_pendidikan.'</small></td>
                    <td align="right"><small class="">'.$label_jumlah_diskon.'</small></td>
                    <td align="right"><small class="">'.$label_jumlah_tagihan.'</small></td>
                    <td align="right">
                        <a href="javascript:void(0);" class="underscore_doted modal_riwayat_pembayaran_siswa" data-id_student="'.$id_student .'" data-id_organization_class="'.$id_organization_class.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click Untuk Melihat Riwayat Pembayaran Siswa">
                            <small class="">'.$label_jumlah_pembayaran.'</small>
                        </a>
                    </td>
                    <td align="right">
                        <a href="javascript:void(0);" class="underscore_doted modal_tagihan_siswa" data-id_student="'.$id_student .'" data-id_organization_class="'.$id_organization_class.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click Untuk Melihat List Tagihan Siswa">
                            <small class="">'.$label_sisa_tagihan.'</small>
                        </a>
                    </td>
                    <td align="right">
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-item modal_tagihan_siswa"  data-id_student="'.$id_student .'" data-id_organization_class="'.$id_organization_class.'">
                                    <i class="bi bi-list-check"></i> List Tagihan
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-item modal_riwayat_pembayaran_siswa" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaranSiswa" data-id_student="'.$id_student .'" data-id_organization_class="'.$id_organization_class.'">
                                    <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }

        echo '
            <script>
                $("#title_table").html(' . json_encode($table_title) . ');
                $("#page_info").html("Jumlah Data : '.$jml_data.'");
                $("#put_id_organization_class").val("'.$id_organization_class.'");
            </script>
        ';
    }
?>