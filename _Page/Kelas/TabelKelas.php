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
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period=$_POST['id_academic_period'];
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
                <td align="left"><b>'.$no_level.'</b></td>
                <td colspan="7"><b>'.$class_level.'</b></td>
                <td>
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

            //Hitung Jumlah Siswa
            $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class  FROM  student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'"));

            //Hitung Komponen Biaya
            $jumlah_komponen=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));

            //Routing $jumlah_komponen
            if(empty($jumlah_komponen)){
                $label_jumlah_komponen='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalListKomponenBiaya" data-id="'.$id_organization_class .'"><small class="text text-grayish">'.$jumlah_komponen.' Record</small></a>';
            }else{
                $label_jumlah_komponen='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalListKomponenBiaya" data-id="'.$id_organization_class .'"><small>'.$jumlah_komponen.' Record</small></a>';
            }

            //Routing $jumlah_siswa
            if(empty($jumlah_siswa)){
                $label_jumlah_siswa='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswa" data-id="'.$id_organization_class .'"><small class="text-grayish">'.$jumlah_siswa.' Orang</small></a>';
            }else{
                $label_jumlah_siswa='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalSiswa" data-id="'.$id_organization_class .'"><small>'.$jumlah_siswa.' Orang</small></a>';
            }

            //Hitung Jumlah Tagihan
            $SumTagihan = mysqli_fetch_array(mysqli_query(
                $Conn,
                "SELECT SUM(fee_nominal - fee_discount) AS total_tagihan 
                FROM fee_by_student 
                WHERE id_organization_class='$id_organization_class'"
            ));
            $jumlah_tagihan = $SumTagihan['total_tagihan'];
            $jumlah_tagihan_format = "Rp " . number_format($jumlah_tagihan,0,',','.');
            if(empty($jumlah_tagihan)){
                $label_jumlah_tagihan='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTagihan" data-id="'.$id_organization_class .'"><small class="text-grayish">'.$jumlah_tagihan_format.'</small></a>';
            }else{
                $label_jumlah_tagihan='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTagihan" data-id="'.$id_organization_class .'"><small>'.$jumlah_tagihan_format.'</small></a>';
            }

            //Hitung Jumlah Pembayaran
            $SumPembayaran = mysqli_fetch_array(mysqli_query(
                $Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class'"
            ));
            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
            $jumlah_pembayaran_format = "Rp " . number_format($jumlah_pembayaran,0,',','.');
            if(empty($jumlah_pembayaran)){
                $label_jumlah_pembayaran='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id="'.$id_organization_class .'"><small class="text-grayish">'.$jumlah_pembayaran_format.'</small></a>';
            }else{
                $label_jumlah_pembayaran='<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id="'.$id_organization_class .'"><small>'.$jumlah_pembayaran_format.'</small></a>';
            }

            //Sisa Tagihan
            $sisa_tagihan=$jumlah_tagihan-$jumlah_pembayaran;
            $sisa_tagihan_format = "Rp " . number_format($sisa_tagihan,0,',','.');

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
                <td>'.$label_jumlah_siswa.'</td>
                <td>'.$label_jumlah_komponen.'</td>
                <td>'.$label_jumlah_tagihan.'</td>
                <td>'.$label_jumlah_pembayaran.'</td>
                <td>'.$sisa_tagihan_format.'</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_organization_class .'">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_organization_class .'">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_organization_class .'">
                                <i class="bi bi-x"></i> Hapus
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya" data-id="'.$id_organization_class .'">
                                <i class="bi bi-list"></i> Biaya Pendidikan
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        }

        $no_level++;
    }
?>

<script>
    //Creat Javascript Variabel
    var jml_data=<?php echo $jml_data; ?>;
    var jumlah_level=<?php echo $jumlah_level; ?>;
    
    //Put Into Pagging Element
    $('#put_jumlah_data').html(' '+jumlah_level+' / '+jml_data+'');
    
</script>