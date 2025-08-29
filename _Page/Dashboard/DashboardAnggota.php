<?php
    //Jumlah Pinjaman
    $SumPinjaman = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah_pinjaman) AS jumlah_pinjaman FROM pinjaman WHERE id_anggota='$SessionIdAkses'"));
    $JumlahPinjaman = $SumPinjaman['jumlah_pinjaman'];
    if(empty($JumlahPinjaman)){
        $JumlahPinjaman=0;
    }
    $JumlahPinjamanFormat = "" . number_format($JumlahPinjaman,0,',','.');
    //Jumlah Angsuran
    $SumAngsuran = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM pinjaman_angsuran WHERE id_anggota='$SessionIdAkses'"));
    $JumlahAngsuran = $SumAngsuran['jumlah'];
    if(empty($JumlahAngsuran)){
        $JumlahAngsuran=0;
    }
    $JumlahAngsuranFormat = "" . number_format($JumlahAngsuran,0,',','.');
    //Simpanan Kotor
    $SumSimpananKotor = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori!='Penarikan' AND id_anggota='$SessionIdAkses'"));
    $JumlahSimpananKotor = $SumSimpananKotor['jumlah'];
    if(empty($JumlahSimpananKotor)){
        $JumlahSimpananKotor=0;
    }
    $JumlahSimpananKotorFormat = "" . number_format($JumlahSimpananKotor,0,',','.');
    //Penarikan Simpanan
    $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori='Penarikan' AND id_anggota='$SessionIdAkses'"));
    $JumlahPenarikan = $SumPenarikan['jumlah'];
    if(empty($JumlahPenarikan)){
        $JumlahPenarikan=0;
    }
    $JumlahPenarikanFormat = "" . number_format($JumlahPenarikan,0,',','.');
    //Jumlah Simpanan Bersih
    $JumlahSimpananBersih=$JumlahSimpananKotor-$JumlahPenarikan;
    $JumlahSimpananBersihFormat = "" . number_format($JumlahSimpananBersih,0,',','.');
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-grid"></i> Dashboard
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Selamat Datang Di Halaman Utama <b>'.$title_page.'</b><br> ';
                echo '      Pada halaman ini anda bisa melihat semua riwayat transaksi anda.';
                echo '      Hubungi pengurus/admin apabila terdapat kesalahan pada pencatatan transaksi untuk dapat segera diperbaiki.';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Simpanan Anggota</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahSimpananBersihFormat.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>
                                <a href="index.php?Page=RiwayatAnggota&Sub=Simpanan">Lihat Selengkapnya</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Penarikan Dana</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPenarikanFormat.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>
                                <a href="index.php?Page=RiwayatAnggota&Sub=Penarikan">Lihat Selengkapnya</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Pinjaman Anggota</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahPinjamanFormat.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>
                                <a href="index.php?Page=RiwayatAnggota&Sub=Pinjaman">Lihat Selengkapnya</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Angsuran Pinjaman</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                        echo '  <span class="text-muted small pt-1 fw-bold">'.$JumlahAngsuranFormat.'</span><br>';
                                        echo '  <span class="text-muted small pt-2 ps-1">Rp/IDR</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small>
                                <a href="index.php?Page=RiwayatAnggota&Sub=Angsuran">Lihat Selengkapnya</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>