<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Session Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Pilih Tahun Akademik Terlebih Dulu</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period=$_POST['id_academic_period'];

    //Buka Nama Periode
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    //Jika Tidak Ada Data Kelas
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Tidak ada data <b>Kelas</b> pada periode akademik tersebut</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }

    //Tampilkan Data Level
    $no_level=1;
    $jumlah_level=0;
    $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($data_level = mysqli_fetch_array($query_level)) {
        $class_level = $data_level['class_level'];

        //Hitung Jumlah Level
        $jumlah_level=$jumlah_level+1;
        echo '
            <tr>
                <td align="left" class="bg bg-body-secondary"><b>'.$no_level.'</b></td>
                <td colspan="9" class="bg bg-body-secondary"><b>'.$class_level.'</b></td>
                <td class="bg bg-body-secondary">
                    <button type="button" class="btn btn-sm btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambah" data-id="'.$class_level.'" title="Tambah Kelas">
                        <i class="bi bi-plus"></i>
                    </button>
                </td>
            </tr>
        ';
        //Menampilkan List Kelas
        $no_kelas=1;
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class = $data_kelas['id_organization_class'];
            $class_name = $data_kelas['class_name'];

            //Hitung Jumlah Siswa Pada Tabel 'fee_by_student'
            $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

            //Hitung Jumlah Siswa Pada Tabel 'student'
            $jumlah_siswa_aktual=mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM student WHERE id_organization_class='$id_organization_class'"));

            //Routing $jumlah_siswa
            if(empty($jumlah_siswa)){
                $label_jumlah_siswa='
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswa" data-id="'.$id_organization_class .'">
                        <small class="badge badge-danger" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Siswa Berdasarkan Tagihan">'.$jumlah_siswa.' Org</small>
                    </a>
                ';
            }else{
                $label_jumlah_siswa='
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswa" data-id="'.$id_organization_class .'">
                        <small class="badge badge-info" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Siswa Berdasarkan Tagihan">'.$jumlah_siswa.' Org</small>
                    </a>
                ';
            }
            //Routing $jumlah_siswa_aktual
            if(empty($jumlah_siswa_aktual)){
                $label_jumlah_siswa_aktual='
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswaAktual" data-id="'.$id_organization_class .'">
                        <small class="badge badge-danger" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Siswa Aktual">'.$jumlah_siswa_aktual.' Org</small>
                    </a>
                ';
            }else{
                $label_jumlah_siswa_aktual='
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswaAktual" data-id="'.$id_organization_class .'">
                        <small class="badge badge-info" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Siswa Aktual">'.$jumlah_siswa_aktual.' Org</small>
                    </a>
                ';
            }

            //Hitung Komponen Biaya
            $jumlah_komponen=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));

            //Routing $jumlah_komponen
            if(empty($jumlah_komponen)){
                $label_jumlah_komponen='<a href="javascript:void(0);" class="list_komponen_biaya" data-id="'.$id_organization_class .'"><small class="text text-grayish">'.$jumlah_komponen.' Record</small></a>';
            }else{
                $label_jumlah_komponen='<a href="javascript:void(0);" class="list_komponen_biaya" data-id="'.$id_organization_class .'"><small>'.$jumlah_komponen.' Record</small></a>';
            }

            //Hitung Jumlah Nomiinal Tagihan
            $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
            $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');
            if(empty($jumlah_nominal_tagihan)){
                $label_nominal_tagihan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Tagihan Sebelum Diskon (Potongn)">
                        <a href="javascript:void(0);" class="text-grayish underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_nominal_tagihan_format.'
                        </a>
                    </small>
                ';
            }else{
                $label_nominal_tagihan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Tagihan Sebelum Diskon (Potongn)">
                        <a href="javascript:void(0);" class="text-dark underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_nominal_tagihan_format.'
                        </a>
                    </small>
                ';
            }

            //Hitung Jumlah Diskon
            $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_diskon = $SumDiskon['jumlah_diskon'];
            $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');
            if(empty($jumlah_diskon)){
                $labal_jumlah_diskon='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Potongan Biaya Pendidikan">
                        <a href="javascript:void(0);" class="text-grayish underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_diskon_format.'
                        </a>
                    </small>
                ';
            }else{
                $labal_jumlah_diskon='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Potongan Biaya Pendidikan">
                        <a href="javascript:void(0);" class="text-dark underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_diskon_format.'
                        </a>
                    </small>
                ';
            }

            //Hitung Jumlah Tagihan
            $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_tagihan = $SumTagihan['total_tagihan'];
            $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
            $jumlah_tagihan_format2 = "" . number_format($jumlah_tagihan,0,',','.');
            if(empty($jumlah_tagihan)){
                $label_jumlah_tagihan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Tagihan Setelah Diskon (Potongn)">
                        <a href="javascript:void(0);" class="text-grayish underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_tagihan_format.'
                        </a>
                    </small>
                ';
            }else{
                $label_jumlah_tagihan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Tagihan Setelah Diskon (Potongn)">
                        <a href="javascript:void(0);" class="text-dark underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_tagihan_format.'
                        </a>
                    </small>
                ';
            }

            //Hitung Jumlah Pembayaran
            $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class'"));
            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
            if(empty($jumlah_pembayaran)){
                $label_jumlah_pembayaran='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Pembayaran Siswa">
                        <a href="javascript:void(0);" class="text-grayish underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_pembayaran_format.'
                        </a>
                    </small>
                ';
            }else{
                $label_jumlah_pembayaran='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Pembayaran Siswa">
                        <a href="javascript:void(0);" class="text-dark underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$jumlah_pembayaran_format.'
                        </a>
                    </small>
                ';
            }

            //Sisa Tagihan
            $sisa_tagihan=$jumlah_tagihan-$jumlah_pembayaran;
            $sisa_tagihan_format = "Rp " . number_format($sisa_tagihan,0,',','.');
            if(empty($sisa_tagihan)){
                $label_sisa_tunggakan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Pembayaran Siswa">
                        <a href="javascript:void(0);" class="text-success underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$sisa_tagihan_format.'
                        </a>
                    </small>
                ';
            }else{
                $label_sisa_tunggakan='
                    <small data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Jumlah Pembayaran Siswa">
                        <a href="javascript:void(0);" class="text-danger underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                            '.$sisa_tagihan_format.'
                        </a>
                    </small>
                ';
            }

            //Tampilkan Data
            echo '
            <tr>
                <td align="left"></td>
                <td>
                    <small class="text text-grayish">
                        '.$no_level.'.'.$no_kelas.'
                    </small>
                </td>
                <td>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_organization_class .'">
                        <small class="text text-primary text-decoration-underline">
                            '.$class_level.' ('.$class_name.')
                        </small>
                    </a>
                </td>
                <td>'.$label_jumlah_siswa.' / '.$label_jumlah_siswa_aktual.'</td>
                <td>'.$label_jumlah_komponen.'</td>
                <td>'.$label_nominal_tagihan.'</td>
                <td>'.$labal_jumlah_diskon.'</td>
                <td>'.$label_jumlah_tagihan.'</td>
                <td>'.$label_jumlah_pembayaran.'</td>
                <td>'.$label_sisa_tunggakan.'</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow bg-body-secondary" style="">
                        <li class="dropdown-header text-center">
                            <h6>Option</h6>
                        </li>
                        <li><hr class="dropdown-divider border-1 border-bottom"></li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_organization_class .'">
                                <i class="bi bi-info-circle"></i> Detail Kelas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_organization_class .'">
                                <i class="bi bi-pencil"></i> Edit Kelas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_organization_class .'">
                                <i class="bi bi-x"></i> Hapus Kelas
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-1 border-bottom"></li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalSiswa" data-id="'.$id_organization_class .'">
                                <i class="bi bi-list"></i> Siswa
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" class="list_komponen_biaya" data-id="'.$id_organization_class .'">
                                <i class="bi bi-tag"></i> K.B.P
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-1 border-bottom"></li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalMatrixTagihan" data-id="'.$id_organization_class .'">
                                <i class="bi bi-plus-square-dotted"></i> Buat Tagihan (Aktual)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item show_modal_tagihan_siswa" href="javascript:void(0)" data-id="'.$id_organization_class .'">
                                <i class="bi bi-receipt"></i> Semua Daftar Tagihan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRekapTagihanSiswa" data-id="'.$id_organization_class .'">
                                <i class="bi bi-grid-3x2"></i> Rekapitulasi Tagihan & Pembayaran
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        }

        $no_level++;
    }
    echo '<script>$("#id_academic_period_terpilih").html("'.$academic_period.'"); $("#id_academic_period").val("'.$academic_period.'");</script>';
?>

<script>
    //Creat Javascript Variabel
    var jml_data=<?php echo $jml_data; ?>;
    var jumlah_level=<?php echo $jumlah_level; ?>;
    
    //Put Into Pagging Element
    $('#put_jumlah_data').html(' '+jumlah_level+' / '+jml_data+'');

    
    
</script>