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
    
    //Tangkap id_student
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka Data Siswa
    $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
    $Qry->bind_param("i", $id_student);
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
        $student_nis            = $Data['student_nis'] ?? '-';
        $student_name           = $Data['student_name'];
        $student_gender         = $Data['student_gender'];

        //Routing Gender
        if($student_gender=="Male"){
            $gender = "Laki-laki";
        }else{
            if($student_gender=="Female"){
                $gender = "Perempuan";
            }else{
                $gender = "-";
            }
        }

        //Buka Kelas
        $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
        $id_academic_period     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

        //Buka Periode Akademik
        $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_student" value="'.$id_student.'">
            <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
            <div class="row mb-3">
                <div class="col-md-6">
                   <div class="row mb-2">
                        <div class="col-4"><small>Nama Siswa</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$student_name.'</small>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><small>NIS</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$student_nis.'</small>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><small>Jenis Kelamin</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$gender.'</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mb-2">
                        <div class="col-4"><small>Tahun Akademik</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$academic_period.'</small>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><small>Jenjang/Level</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$class_level.'</small>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><small>Kelas/Rombel</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-7">
                            <small class="text text-grayish">'.$class_name.'</small>
                        </div>
                    </div>
                </div>
            </div>
        ';
        //Menampilkan Riwayat Pembayaran Siswa
        echo '<div class="row mb-2">';
        echo '  <div class="col-12">';
        echo '      <div class="table table-responsive border-1 border-top">';
        echo '          <table class="table table-hover table-striped ">';
        echo '              
                            <thead>
                                <tr>
                                    <th><b>No</b></th>
                                    <th><b>Tanggal</b></th>
                                    <th><b>Jam</b></th>
                                    <th><b>Komponen Biaya</b></th>
                                    <th><b>Kategori</b></th>
                                    <th><b>Bulan</b></th>
                                    <th><b>Tahun</b></th>
                                    <th><b>Metode</b></th>
                                    <th><b>Pembayaran</b></th>
                                </tr>
                            </thead>
        ';
        echo '              <tbody>';
                                //Inisialisasi Nomor Urut
                                $no=1;

                                //Inisialisasi Pembayaran
                                $subtotal_pembayaran = 0;

                                //Hitung Jumlah Data
                                $JumlahPayment = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE id_student='$id_student'"));

                                //Jika Tidak Ada Data
                                if(empty($JumlahPayment)){
                                    echo '
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <small>Tidak Ada Data Riwayat Pembayaran</small>
                                            </td>
                                        </tr>
                                    ';
                                }else{
                                    $query = mysqli_query($Conn, "SELECT*FROM payment WHERE id_student='$id_student' ORDER BY id_payment ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_payment = $data['id_payment'];
                                        $id_fee_component= $data['id_fee_component'];
                                        $payment_datetime = $data['payment_datetime'];
                                        $payment_nominal= $data['payment_nominal'];
                                        $payment_method= $data['payment_method'];

                                        //Akumulasi Pembayaran
                                        $subtotal_pembayaran = $subtotal_pembayaran + $payment_nominal;
                                        
                                        //Format Rupiah
                                        $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

                                        //Buka Detail Komponen
                                        $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
                                        $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                                        $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

                                        //Nama Bulan
                                        $bulan              = getNamaBulan($periode_month);

                                        echo '
                                            <tr>
                                                <td><small>'.$no.'</small></td>
                                                <td><small>'.date('d/m/Y', strtotime($payment_datetime)).'</small></td>
                                                <td><small>'.date('H:i T', strtotime($payment_datetime)).'</small></td>
                                                <td><small>'.$component_name.'</small></td>
                                                <td><small>'.$component_category.'</small></td>
                                                <td><small>'.$bulan.'</small></td>
                                                <td><small>'.$periode_year.'</small></td>
                                                <td><small>'.$payment_method.'</small></td>
                                                <td><small>'.$payment_nominal_format.'</small></td>
                                            </tr>
                                        ';
                                        $no++;
                                    }
                                }
                                //Format total pembayaran
                                $subtotal_pembayaran_format="Rp " . number_format($subtotal_pembayaran,0,',','.');
                                echo '
                                    <tr>
                                        <td colspan="8"><small><b>JUMLAH PEMBAYARAN</b></small></td>
                                        <td class="text-right"><small><b>'.$subtotal_pembayaran_format.'</b></small></td>
                                    </tr>
                                ';
        echo '';
        echo '              </tbody>';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }
?>