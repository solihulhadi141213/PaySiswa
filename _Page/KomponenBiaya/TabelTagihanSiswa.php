<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>
                $("#title_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //Validasi 'id_fee_component'
    if(empty($_POST['id_fee_component'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">ID Komponen Biaya Pendidikan Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#title_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //Validasi 'id_organization_class'
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#title_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //Buat Variabel Dan Sanitasi
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka Data fee_component
    $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
    $Qry->bind_param("i", $id_fee_component);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                </td>
            </tr>
            <script>$("#title_tagihan_siswa").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Jika Data Tidak Ditemukan
    if(empty($Data['id_fee_component'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">ID Komponen Biaya Pendidikan Tidak Valid</small>
                </td>
            </tr>
            <script>$("#title_tagihan_siswa").html("");</script>
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period     = $Data['id_academic_period'];
    $periode_month          = $Data['periode_month'];
    $periode_year           = $Data['periode_year'];
    $component_name         = $Data['component_name'];
    $component_category     = $Data['component_category'];
    
    //Nama Bulan 
    $nama_bulan=getNamaBulan($periode_month);

    //Buka Informasi Periode Akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    //Detail Informasi Kelas
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buat title
    $tite_tagihan_siswa = '
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <div class="row">
            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="col-5"><small>Komponen Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="col-5"><small>Kategori</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_category.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Bulan, Tahun</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="col-5"><small>Jenjang/Level</small></div>
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
    ';
    //Inisiasi Akumulasi
    $total_nominal      = 0;
    $total_diskon       = 0;
    $total_tagihan      = 0;
    $total_pembayaran   = 0;
    $total_sisa         = 0;
    //Menampilkan 'fee_by_student' secara distinct
    $no = 1;
    $qry_fee_by_student = mysqli_query($Conn, "SELECT id_fee_by_student, id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' ORDER BY id_student ASC");
    while ($data_fee_by_student = mysqli_fetch_array($qry_fee_by_student)) {
        $id_fee_by_student  = $data_fee_by_student['id_fee_by_student'];
        $id_student         = $data_fee_by_student['id_student'];

        //Buka Data Siswa
        $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
        $student_name    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

        //menghitung jumlah tagihan
        $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
        $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
        $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');
        if(empty($jumlah_nominal_tagihan)){
            $label_nominal = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_nominal = '<span class="text text-dark">'.$jumlah_nominal_tagihan_format.'</span>';
        }

        //Hitung Jumlah Diskon
        $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
        $jumlah_diskon = $SumDiskon['jumlah_diskon'];
        $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');
        if(empty($jumlah_diskon)){
            $label_diskon = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_diskon = '<span class="text text-dark">'.$jumlah_diskon_format.'</span>';
        }

        //Hitung Jumlah Tagihan
        $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
        $jumlah_tagihan = $SumTagihan['total_tagihan'];
        $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
        if(empty($jumlah_tagihan)){
            $label_tagihan = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_tagihan = '<span class="text text-dark">'.$jumlah_tagihan_format.'</span>';
        }

        //Hitung Jumlah Pembayaran
        $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
        $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        if(empty($jumlah_pembayaran)){
            $label_pembayaran = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_pembayaran = '<span class="text text-dark">'.$jumlah_pembayaran_format.'</span>';
        }

        //Menghitung Sisa Tagihan
        $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
        $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');
        if(empty($sisa_tagihan)){
            $label_sisa = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_sisa = '<span class="text text-dark">'.$sisa_tagihan_format.'</span>';
        }

        //Akumulasi
        $total_nominal      = $total_nominal + $jumlah_nominal_tagihan;
        $total_diskon       = $total_diskon + $jumlah_diskon;
        $total_tagihan      = $total_tagihan + $jumlah_tagihan;
        $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
        $total_sisa         = $total_sisa + $sisa_tagihan;
        //Menampilkan Tabel
        echo '
            <tr>
                <td align="center"><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$student_nis.'</small></td>
                <td align="right"><small>'.$label_nominal.'</small></td>
                <td align="right"><small>'.$label_diskon.'</small></td>
                <td align="right"><small>'.$label_tagihan.'</small></td>
                <td align="right"><small>'.$label_pembayaran.'</small></td>
                <td align="right"><small>'.$label_sisa.'</small></td>
                <td align="right">
                    <button type="button" class="btn btn-sm btn-secondary btn-floating modal_detail_tagihan_siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat Detail Tagihan Dan Riwayat Pembayaran" data-id="'.$id_fee_by_student.'">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </td>
            </tr>
        ';
        $no++;
    }

    //Format Akumulasi
    $total_nominal_format       = "Rp " . number_format($total_nominal,0,',','.');
    $total_diskon_format        = "Rp " . number_format($total_diskon,0,',','.');
    $total_tagihan_format       = "Rp " . number_format($total_tagihan,0,',','.');
    $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');
    $total_sisa_format          = "Rp " . number_format($total_sisa,0,',','.');
    echo '
        <tr>
            <td><small></small></td>
            <td colspan="2"><small><b>JUMLAH/TOTAL</b></small></td>
            <td align="right"><small><b>'.$total_nominal_format.'</b></small></td>
            <td align="right"><small><b>'.$total_diskon_format.'</b></small></td>
            <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
            <td align="right"><small><b>'.$total_pembayaran_format.'</b></small></td>
            <td align="right"><small><b>'.$total_sisa_format.'</b></small></td>
            <td></td>
        </tr>
    ';
    echo '
        <script>
            $("#title_tagihan_siswa").html(' . json_encode($tite_tagihan_siswa) . ');
        </script>
    ';
?>