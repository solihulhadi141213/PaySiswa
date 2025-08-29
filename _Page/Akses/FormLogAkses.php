<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <code>Sesi Login Sudah Berakhir, Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        //Tangkap id_akses
        if(empty($_POST['id_akses'])){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center">';
            echo '      <code>ID Akses Tidak Boleh Kosong</code>';
            echo '  </div>';
            echo '</div>';
        }else{
            $id_akses=$_POST['id_akses'];
            //Bersihkan Variabel
            $id_akses=validateAndSanitizeInput($id_akses);
            //Buka data askes
            $nama_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'nama_akses');
            $kontak_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'kontak_akses');
            $email_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'email_akses');
            $image_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'image_akses');
            $akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'akses');
            $datetime_daftar=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_daftar');
            $datetime_update=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_update');
            //Jumlah
            $JumlahAktivitas =mysqli_num_rows(mysqli_query($Conn, "SELECT id_akses FROM log WHERE id_akses='$id_akses'"));
            $JumlahRole =mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM akses_ijin WHERE id_akses='$id_akses'"));
            //Format Tanggal
            $strtotime1=strtotime($datetime_daftar);
            $strtotime2=strtotime($datetime_update);
            //Menampilkan Tanggal
            $DateDaftar=date('d/m/Y H:i:s T', $strtotime1);
            $DateUpdate=date('d/m/Y H:i:s T', $strtotime2);
            if(!empty($image_akses)){
                $image_akses=$image_akses;
            }else{
                $image_akses="No-Image.png";
            }
?>
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <b>Informasi Akses</b>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row mb-3">
                        <div class="col col-md-4">Nama Lengkap</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $nama_akses; ?></code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">Nomor Kontak</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $kontak_akses; ?></code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">Alamat Email</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $email_akses; ?></code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">Level Akses</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $akses; ?></code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">Tanggal Data</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $DateDaftar; ?></code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">Tanggal Update</div>
                        <div class="col col-md-8">
                            <code class="text text-grayish"><?php echo $DateUpdate; ?></code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <b>Rekapitulasi Log Akses</b>
                </div>
                <div class="col-md-12">
                    <div class="table table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td align="center"><b>No</b></td>
                                    <td align="center"><b>Kategori Log</b></td>
                                    <td align="center"><b>Jumlah Aktivitas</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(empty($JumlahAktivitas)){
                                        echo '<tr>';
                                        echo '  <td class="text-center text-danger" colspan="3">Akun Akses Ini Belum Memiliki Record Aktivitas</td>';
                                        echo '</tr>';
                                    }
                                    $no=1;
                                    $query = mysqli_query($Conn, "SELECT DISTINCT kategori_log FROM log WHERE id_akses='$id_akses' ORDER BY id_log DESC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $kategori_log= $data['kategori_log'];
                                        //Menghitung Jumlah LOG
                                        $JumlahLog = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM log WHERE id_akses='$id_akses' AND kategori_log='$kategori_log'"));
                                        echo '<tr>';
                                        echo '  <td class="text-center">'.$no.'</td>';
                                        echo '  <td class="text-left">'.$kategori_log.'</td>';
                                        echo '  <td class="text-center">'.$JumlahLog.' Record</td>';
                                        echo '</tr>';
                                        $no++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <a href="index.php?Page=Akses&Sub=LogAkses&id=<?php echo $id_akses; ?>" class="btn btn-md btn-outline-primary">
                        Lihat Selengkapnya
                    </a>
                </div>
            </div> -->
<?php 
        } 
    } 
?>