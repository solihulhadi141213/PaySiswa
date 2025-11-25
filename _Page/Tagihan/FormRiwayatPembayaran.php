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
    //Tangkap id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Tagihan Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel 'id_fee_by_student'
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    // BUKA DETAIL TAGIHAN
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');
    $id_fee_component       = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $fee_nominal            = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_nominal');
    $fee_discount           = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_discount');
    $tagihan_netto          = $fee_nominal-$fee_discount;

    # Format Rupiah
    $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
    $tagihan_netto_format   = "Rp " . number_format($tagihan_netto,0,',','.');

    // BUKA DETAIL SISWA
    $QrySiswa = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
    $QrySiswa->bind_param("i", $id_student);
    if (!$QrySiswa->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultSiswa = $QrySiswa->get_result();
    $DataSiswa = $ResultSiswa->fetch_assoc();
    $QrySiswa->close();

    # Jika ID Siswa Tidak Valid
    if(empty($DataSiswa['id_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Siswa Pada Tabel Taguhan Tidak Valid (Tidak Ditemukan Pada Database)</small>
            </div>
        ';
        exit;
    }

    # Buat Variabel
    $student_nis            = $DataSiswa['student_nis'] ?? '-';
    $student_name           = $DataSiswa['student_name'];
    $student_gender         = $DataSiswa['student_gender'];
    $student_status         = $DataSiswa['student_status'];

    # Routing Status Siswa
    if($student_status=="Terdaftar"){
        $label_status='<span class="badge badge-success">Terdaftar</span>';
    }else{
        if($student_status=="Lulus"){
            $label_status='<span class="badge badge-warning">Lulus</span>';
        }else{
            $label_status='<span class="badge badge-danger">Keluar</span>';
        }
    }

    # Routing Gender Siswa
    if($student_gender=="Male"){
        $gender = 'Laki-laki';
    }else{
        if($student_gender=="Female"){
            $gender = 'Perempuan';
        }else{
            $gender = '-';
        }
    }

    // BUKA DETAIL KELAS DAN PERIODE AKADEMIK
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    // BUKA KOMPONEN BIAYA
    $component_name     = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month      = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year       = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
    $tarif_biaya        = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');

    # Nama Bulan
    $nama_bulan         = getNamaBulan($periode_month);

    # Format Rupiah
    $tarif_biaya_format="Rp " . number_format($tarif_biaya,0,',','.');

    // HITUNG JUMLAH PEMBAYARAN
    $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
    $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];

    # Hitung Sisa/Tunggakan
    $sisa_tunggakan             = $tagihan_netto - $jumlah_pembayaran;

    #Format Rupiah
    $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
    $sisa_tunggakan_format      = "Rp " . number_format($sisa_tunggakan,0,',','.');

    //MENYATAKAN STATUS TAGIHAN
    if($jumlah_pembayaran>=$tagihan_netto){
        $status_tagihan = "Lunas";
    }else{
        $status_tagihan = "Belum Lunas";
    }

    //Tampilkan Data
    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-12">
                        <small>
                            <b># Identitas Siswa</b>
                        </small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Nama Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$student_name.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$student_nis.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Jenis Kelamin</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$gender.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">'.$label_status.'</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-12">
                        <small>
                            <b># Periode Akademik</b>
                        </small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$academic_period.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Level/Jenjang</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$class_level.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$class_name.'</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-12">
                        <small>
                            <b># Komponen Biaya</b>
                        </small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Komponen Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$component_name.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kategori</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$component_category.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Bulan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$nama_bulan.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Tahun</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$periode_year.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Tarif Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$tarif_biaya_format.'</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-12">
                        <small>
                            <b># Informasi Tagihan</b>
                        </small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Tagihan (Bruto)</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$fee_nominal_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Diskon/Potongan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$fee_discount_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Tagihan (Netto)</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$tagihan_netto_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Pembayaran</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$jumlah_pembayaran_format.'</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Sisa/Tunggakan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="text text-grayish">'.$sisa_tunggakan_format.'</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-end mb-2">
                <button type="button" class="btn btn-md btn-success modal_tambah_pembayaran" data-id="'.$id_fee_by_student.'">
                    <i class="bi bi-plus"></i> Bayar
                </button>
            </div>
        </div>
    ';
    //Menampilkan Komponen Biaya/Tagihan
    echo '<div class="row mb-2">';
    echo '  <div class="col-12">';
    echo '      <div class="table table-responsive border-1 border-top">';
    echo '          <table class="table table-hover table-striped ">';
    echo '              
                        <thead>
                            <tr>
                                <td><b>No</b></td>
                                <td><b>Tanggal</b></td>
                                <td><b>Waktu/Jam</b></td>
                                <td><b>Metode</b></td>
                                <td align="right"><b>Nominal</b></td>
                                <td align="right"><b>Opsi</b></td>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
                            $no=1;
                            $JumlahPembayaran = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
                            if(empty($JumlahPembayaran)){
                                echo '
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <small>Tidak Ada Riwayat Pembayaran</small>
                                        </td>
                                    </tr>
                                ';
                            }else{
                                $subtotal_pembayaran = 0;
                                $query = mysqli_query($Conn, "SELECT*FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component' ORDER BY id_payment ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_payment = $data['id_payment'];
                                    $id_organization_class= $data['id_organization_class'];
                                    $id_fee_component = $data['id_fee_component'];
                                    $payment_datetime= $data['payment_datetime'];
                                    $payment_nominal= $data['payment_nominal'];
                                    $payment_method= $data['payment_method'];

                                    //Akumulasi Pembayaran
                                    $subtotal_pembayaran = $subtotal_pembayaran + $payment_nominal;

                                    //Format Rupiah
                                    $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

                                    //Format tanggal bayar
                                    $tanggal_bayar=date('d F Y',strtotime($payment_datetime));
                                    $jam_bayar=date('H:i T',strtotime($payment_datetime));
                                    echo '
                                        <tr>
                                            <td><small>'.$no.'</small></td>
                                            <td><small>'.$tanggal_bayar.'</small></td>
                                            <td><small>'.$jam_bayar.'</small></td>
                                            <td><small>'.$payment_method.'</small></td>
                                            <td align="right"><small>'.$payment_nominal_format.'</small></td>
                                            <td align="right">
                                                <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                    <li class="dropdown-header text-start">
                                                        <h6>Option</h6>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item modal_detail_pembayaran" data-id="'.$id_payment .'" title="Detail Pembayaran">
                                                            <i class="bi bi-info-circle"></i> Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item modal_hapus_pembayaran" data-id="'.$id_payment .'" title="Hapus Pembayaran">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    ';
                                    $no++;
                                }

                                //Format Subtotal Pembayaran
                                $subtotal_pembayaran_format = "Rp " . number_format($subtotal_pembayaran,0,',','.');

                                //Tampilkan Baris Tabel Akhir
                                echo '
                                    <tr>
                                        <td colspan="4" align="right"><b><small>JUMLAH PEMBAYARAN</small></b></td>
                                        <td align="right"><b><small>'.$subtotal_pembayaran_format.'</small></b></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"><b><small>STATUS TAGIHAN</small></b></td>
                                        <td align="right"><small>'.$status_tagihan.'</small></td>
                                        <td></td>
                                    </tr>
                                ';
                            }
    echo '';
    echo '              </tbody>';
    echo '          </div>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
?>