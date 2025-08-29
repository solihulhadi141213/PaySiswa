<?php
    $strtotime1=strtotime($SessionDatetimeDaftar);
    $strtotime2=strtotime($SessionDatetimeUpdate);
    $SessionWaktuDaftarDatetime=date('d/m/Y H:i T',$strtotime1);
    $SessionWaktuUpdateDatetime=date('d/m/Y H:i T',$strtotime2);
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-person-circle"></i> Profil Saya</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active"> Profil Saya</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row mb-3">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Berikut ini adalah halaman profil yang digunakan untuk mengelola informasi akses anda.';
                echo '      Pada halaman ini anda bisa melakukan perubahan data akses (Nama, Email, Password dan Foto Profile).';
                echo '      Pada bagian kolom izin akses menunjukan informasi fitur apa saja yang bisa anda gunakan pada aplikasi ini. ';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Informasi Pengguna
                    </b>
                </div>
                <div class="card-body">
                    <div class="row mb-3 border-1 border-bottom">
                        <div class="col col-md-12 text-center mb-4">
                            <img src="assets/img/User/<?php echo "$SessionGambar"; ?>" alt="" width="70%" class="rounded-circle">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6">
                            Nama Lengkap
                        </div>
                        <div class="col col-md-6">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionNama"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6">Kontak</div>
                        <div class="col col-md-6">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionKontakAkses"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6">Email</div>
                        <div class="col col-md-6">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionEmailAkses"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6">Level Akses</div>
                        <div class="col col-md-6">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionLevelAkses"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6">Waktu Daftar</div>
                        <div class="col col-md-6">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionWaktuDaftarDatetime"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-6 mb-3">Update</div>
                        <div class="col col-md-6 mb-3">
                            <small class="credit">
                                <code class="text text-grayish"><?php echo "$SessionWaktuUpdateDatetime"; ?></code>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="javascript:void(0);" class="text-dark"  data-bs-toggle="modal" data-bs-target="#ModalUbahIdentitasProfil">
                                        <i class="bi bi-pencil me-1 text-primary"></i> 
                                        <small class="credit">Ubah Identitias</small>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="javascript:void(0);" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalUbahFotoProfil">
                                        <i class="bi bi-image me-1 text-primary"></i> 
                                        <small class="credit">Ubah Foto Profil</small>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="javascript:void(0);" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalUbahPasswordProfil">
                                        <i class="bi bi-key me-1 text-primary"></i> 
                                        <small class="credit">Ubah Password</small>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b class="card-title">
                        <i class="bi bi-list-check"></i> Izin Akses
                    </b>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="table table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <td align="center" colspan="4"><b>PENGATURAN IJIN AKSES FITUR</b></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><b>No</b></td>
                                            <td colspan="2" align="center"><b>Kategori/Fitur</b></td>
                                            <td align="center"><b>Check</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            //Tampilkan Kategori Ijin Akses
                                            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses_fitur"));
                                            if(empty($jml_data)){
                                                echo '<tr colspan="4">';
                                                echo '  <td class="text-center text-danger">Belum ada data fitur aplikasi, silahkan tambahkan fitur aplikasi terlebih dulu</td>';
                                                echo '</tr>';
                                            }else{
                                                $no_kategori=1;
                                                $QryKategoriFitur = mysqli_query($Conn, "SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                                                while ($DataKategori = mysqli_fetch_array($QryKategoriFitur)) {
                                                    $kategori= $DataKategori['kategori'];
                                                    echo '<tr>';
                                                    echo '  <td align="center"><b>'.$no_kategori.'</b></td>';
                                                    echo '  <td align="left" colspan="2"><label for="IdKategoriEdit'.$no_kategori.'"><b>'.$kategori.'</b></label></td>';
                                                    echo '  <td align="center">';
                                                    echo '      ';
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    $no_fitur=1;
                                                    $QryFitur = mysqli_query($Conn, "SELECT * FROM akses_fitur WHERE kategori='$kategori' ORDER BY nama ASC");
                                                    while ($DataFitur = mysqli_fetch_array($QryFitur)) {
                                                        $id_akses_fitur= $DataFitur['id_akses_fitur'];
                                                        $nama= $DataFitur['nama'];
                                                        $keterangan= $DataFitur['keterangan'];
                                                        $kode= $DataFitur['kode'];
                                                        echo '<tr>';
                                                        echo '  <td align="center"></td>';
                                                        echo '  <td align="center"><label for="IdFiturEdit'.$id_akses_fitur.'">'.$no_kategori.'.'.$no_fitur.'</label></td>';
                                                        echo '  <td align="left"><label for="IdFitur'.$id_akses_fitur.'">'.$nama.'</label><br><code class="text text-grayish">'.$keterangan.'</code></td>';
                                                        echo '  <td align="center">';
                                                        //Validasi Apakah Bersangkutan Punya Akses Ini
                                                        $Validasi=IjinAksesSaya($Conn,$SessionIdAkses,$kode);
                                                        if($Validasi=="Ada"){
                                                            echo '<i class="bi bi-check-circle"></i>';
                                                        }else{
                                                            echo '<i class="bi bi-x"></i>';
                                                        }
                                                        
                                                        echo '  </td>';
                                                        echo '</tr>';
                                                        $no_fitur++;
                                                    }
                                                    $no_kategori++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
