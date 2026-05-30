<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            //Koneksi
            session_start();
            include "_Config/Connection.php";
            include "_Config/GlobalFunction.php";
            include "_Config/SettingGeneral.php";
            include "_Partial/Head.php";
        ?>
    </head>
    <body>
        <main class="landing_background">
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <img src="assets/img/payment.jpg" alt="<?php echo $app_title;?>" width="100%">
                                </div>
                            </div>
                            <?php
                                //Jika Tidak Ada ID
                                if(empty($_GET['id'])){
                                    echo '
                                        <div class="alert alert-danger">
                                            <small>ID Pembayaran Tidak Boleh Kosong!</small>
                                        </div>
                                    ';
                                }else{

                                    //Buat Variabel Dan Sanitasi
                                    $id_payment_request = validateAndSanitizeInput($_GET['id']);

                                    //Buka Data Dengan Prepared Statment
                                    //Buka Data payment
                                    $Qry = $Conn->prepare("SELECT * FROM payment_request WHERE id_payment_request = ?");
                                    $Qry->bind_param("i", $id_payment_request);
                                    if (!$Qry->execute()) {
                                        $error=$Conn->error;
                                        echo '
                                            <div class="alert alert-danger">
                                                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                                            </div>
                                        ';
                                    }else{
                                        $Result = $Qry->get_result();
                                        $Data = $Result->fetch_assoc();
                                        $Qry->close();

                                        //Buat Variabel
                                        $kode_transaksi        = $Data['kode_transaksi'];
                                        $id_payment            = $Data['id_payment'];
                                        $id_fee_by_student     = $Data['id_fee_by_student'];
                                        $id_student            = $Data['id_student'];
                                        $id_organization_class = $Data['id_organization_class'];
                                        $id_fee_component      = $Data['id_fee_component'];
                                        $request_datetime      = $Data['request_datetime'];
                                        $request_expired       = $Data['request_expired'];
                                        $status                = $Data['status'];

                                        // Potong Kode Transaksi
                                        $kode_transaksi_format = substr($kode_transaksi, 0, 15) . '...';

                                        // Buka Identitas Siswa
                                        $student_nis  = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
                                        $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

                                        //Buka Detail Komponen
                                        $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
                                        $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                                        $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

                                        //Nama Bulan
                                        $nama_bulan         = getNamaBulan($periode_month);

                                        // Tampilkan Data
                                        echo '
                                            <div class="card mb-3 p-3">
                                                <div class="card-header text-center">
                                                    <b class="card-title">Transaksi Pembayaran</b>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>Kode Transaksi</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$kode_transaksi_format.'</small></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>NIS</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>Nama Siswa</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>Komponen Biaya</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>Kategori</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$component_category.'</small></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4"><small>Periode</small></div>
                                                        <div class="col-1"><small>:</small></div>
                                                        <div class="col-7"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="button" class="btn btn-md btn-success btn-block">
                                                        Bayar
                                                    </button>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            ?>
                            
                            <div class="credits text-center">
                                <small>
                                    <div class="copyright text-white">
                                        &copy; Copyright <strong><span><?php echo "$app_title"; ?></span></strong>. All Rights Reserved <?php echo "$app_year"; ?>
                                    </div>
                                    <div class="credits text-white">
                                        Designed by <span class="text text-decoration-underline"><?php echo "$app_author"; ?></span>
                                    </div>
                                </small>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </main>
        <?php
            include "_Partial/FooterJs.php";
        ?>
    </body>
</html>