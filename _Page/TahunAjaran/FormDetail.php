<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Periode Akademik Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Data payment
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    if(empty($Data['id_academic_period'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Tidak Valid!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $academic_period        = $Data['academic_period'];
    $academic_period_start  = $Data['academic_period_start'];
    $academic_period_end    = $Data['academic_period_end'];
    $academic_period_status = $Data['academic_period_status'];

    //Format datetime
    $academic_period_start  = date('d F Y', strtotime($academic_period_start));
    $academic_period_end    = date('d F Y', strtotime($academic_period_end));

    //Routing Status
    if($academic_period_status==true){
        $label_status='<span class="badge badge-success"><i class="bi bi-check-circle"></i> Active</span>';
    }else{
        $label_status='<span class="badge badge-danger"><i class="bi bi-lock"></i> Locked</span>';
    }
    //Tampilkan Data
    echo '
        <div class="row mb-2">
            <div class="col-4"><small>Tahun Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$academic_period.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Periode Awal</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$academic_period_start.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Periode Akhir</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$academic_period_end.'</small>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-4"><small>Status</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$label_status.'</small>
            </div>
        </div>
    ';

    //Jumlah Kelas
    $jumlah_kelas=mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    //Menghiutng K.B.P
    $jumlah_fee_component=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));
    
    // Menghitung Jumlah Biaya, Diskon, Tagihan, Pembayaran dan Sisa/tunggakan
    
    /* 1. Inisiasi Variabel biaya, diskon, tagihan, pembayaran, sisa dan siswa */
    $total_biaya        = 0;
    $total_diskon       = 0;
    $total_tagihan      = 0;
    $total_pembayaran   = 0;
    $total_sisa         = 0;
    $total_siswa        = 0;
    
    /* 2. Looping Data Kelas Berdasarkan Periode */
    $QryKelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'");
    while ($DataKelas = mysqli_fetch_array($QryKelas)) {
        $id_organization_class = $DataKelas['id_organization_class'];
        
        /* 2. Hitung Jumlah 'biaya' Berdasarkan 'id_organization_class' */
        $SumBiaya       = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(fee_nominal) AS total_biaya FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $jumlah_biaya   = $SumBiaya['total_biaya'];
        $total_biaya    = $total_biaya+$jumlah_biaya;

        /* 3. Hitung Jumlah 'diskon' Berdasarkan 'id_organization_class' */
        $SumDiskon      = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $jumlah_diskon  = $SumDiskon['total_diskon'];
        $total_diskon   = $total_diskon+$jumlah_diskon;

        /* 4. Hitung Jumlah Tagihan Berdasarkan id_organization_class */
        $SumTagihan     = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $jumlah_tagihan = $SumTagihan['total_tagihan'];
        $total_tagihan  = $total_tagihan+$jumlah_tagihan;

        /* 5. Hitung Jumlah Pembayaran */
        $SumPembayaran      = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS total_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
        $jumlah_pembayaran  = $SumPembayaran['total_pembayaran'];
        $total_pembayaran   = $total_pembayaran+$jumlah_pembayaran;

        /* 6. Hitung Jumlah Sisa */
        $jumlah_sisa    = $jumlah_tagihan - $jumlah_pembayaran;
        $total_sisa     = $total_sisa + $jumlah_sisa;

        //Hitung jumlah siswa yang terdaftar pada periode tersebut melalui tabel  fee_by_student  
        $jumlah_siswa = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $total_siswa = $total_siswa + $jumlah_siswa;
    }

    //Format 'total_biaya'
    $total_biaya        = round($total_biaya);
    $total_biaya_format = "Rp " . number_format($total_biaya,0,',','.');

    //Format 'total_diskon'
    $total_diskon           = round($total_diskon);
    $total_diskon_format    = "Rp " . number_format($total_diskon,0,',','.');

    //Format 'total_tagihan'
    $total_tagihan          = round($total_tagihan);
    $total_tagihan_format   = "Rp " . number_format($total_tagihan,0,',','.');

    //Format 'total_pembayaran'
    $total_pembayaran           = round($total_pembayaran);
    $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');

    //Format 'total_sisa'
    $total_sisa           = round($total_sisa);
    $total_sisa_format    = "Rp " . number_format($total_sisa,0,',','.');

    //Tampilkan Data
    echo '
        <div class="row mb-2 border-top border-1">
            <div class="col-12"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kelas / Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$jumlah_kelas.' Kelas</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Jumlah Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_siswa.' Orang</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Komponen Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$jumlah_fee_component.' Komponen</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Biaya Pendidikan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_biaya_format.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Diskon/Potongan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_diskon_format.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_tagihan_format.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Pembayaran</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_pembayaran_format.'</small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Sisa/Tunggakan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <small class="text text-grayish">'.$total_sisa_format.'</small>
            </div>
        </div>
    ';

?>