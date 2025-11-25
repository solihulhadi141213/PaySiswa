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

    //Tangkap 'id_student'
    if(empty($_POST['id_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID sISWA Tidak Boleh Kosong!
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
    $id_student=validateAndSanitizeInput($_POST['id_student']);
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);

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

        //Form Hide
        echo '
            <input type="hidden" name="id_student" value="'.$id_student.'">
            <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        ';
        //Tampilkan Data
        echo '
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
        //Menampilkan Komponen Biaya/Tagihan
        echo '<div class="row mb-2">';
        echo '  <div class="col-12">';
        echo '      <div class="table table-responsive border-1 border-top">';
        echo '          <table class="table table-hover table-striped ">';
        echo '              
                            <thead>
                                <tr>
                                    <th><b>No</b></th>
                                    <th><b>Biaya Pendidikan</b></th>
                                    <th><b>Kategori</b></th>
                                    <th><b>Bulan</b></th>
                                    <th><b>Tahun</b></th>
                                    <th><b>Tagihan</b></th>
                                    <th><b>Diskon</b></th>
                                    <th><b>Bayar</b></th>
                                    <th><b>Sisa</b></th>
                                    <th><b>Opsi</b></th>
                                </tr>
                            </thead>
        ';
        echo '              <tbody>';
                                $no=1;
                                $JumlahKomponen = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
                                if(empty($JumlahKomponen)){
                                    echo '
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <small>Tidak Ada Data Komponen Tagihan</small>
                                            </td>
                                        </tr>
                                    ';
                                }else{
                                    $jumlah_fee_nominal=0;
                                    $jumlah_fee_discount=0;
                                    $jumlah_pembayaran_masuk=0;
                                    $jumlah_sisa_pembayaran=0;
                                    $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' ORDER BY id_fee_by_student ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_fee_by_student = $data['id_fee_by_student'];
                                        $id_organization_class= $data['id_organization_class'];
                                        $id_fee_component = $data['id_fee_component'];
                                        $fee_nominal= $data['fee_nominal'];
                                        $fee_discount= $data['fee_discount'];
                                        $jumlah_tagihan=$fee_nominal-$fee_discount;
                                        
                                        //Buka Detail Komponen
                                        $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
                                        $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                                        $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

                                        //Nama Bulan
                                        $nama_bulan=getNamaBulan($periode_month);

                                        //Format Rupiah
                                        $fee_nominal_format     = "Rp" . number_format($fee_nominal,0,',','.');
                                        $fee_discount_format    = "Rp" . number_format($fee_discount,0,',','.');

                                        //Hitung Pembayaran Yang Sudah Masuk
                                        $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
                                        $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

                                        //Format Rupiah
                                        $JumlahPembayaranMasukFormat="" . number_format($JumlahPembayaranMasuk,0,',','.');

                                        //Hitung sisa pembayaran
                                        $sisa_pembayaran=$jumlah_tagihan-$JumlahPembayaranMasuk;

                                        //Format Rupiah
                                        $sisa_pembayaran_format="" . number_format($sisa_pembayaran,0,',','.');

                                        //Routing Tombol Berdasarkan Sisa Pembayaran
                                        if($sisa_pembayaran>0){
                                            $tomol_bayar='<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id_fee_component="'.$id_fee_component .'" data-id_student="'.$id_student .'">Bayar</button>';
                                        }else{
                                            $tomol_bayar='<button type="button" disabled class="btn btn-sm btn-success">Lunas</button>';
                                        }

                                        //Hitung Total
                                        $jumlah_fee_nominal=$jumlah_fee_nominal+$fee_nominal;
                                        $jumlah_fee_discount=$jumlah_fee_discount+$fee_discount;
                                        $jumlah_pembayaran_masuk=$jumlah_pembayaran_masuk+$JumlahPembayaranMasuk;
                                        $jumlah_sisa_pembayaran=$jumlah_sisa_pembayaran+$sisa_pembayaran;
                                        echo '
                                            <tr>
                                                <td><small>'.$no.'</small></td>
                                                <td><small>'.$component_name.'</small></td>
                                                <td><small>'.$component_category.'</small></td>
                                                <td><small>'.$nama_bulan.'</small></td>
                                                <td><small>'.$periode_year.'</small></td>
                                                <td><small>'.$fee_nominal_format.'</small></td>
                                                <td><small>'.$fee_discount_format.'</small></td>
                                                <td><small>'.$JumlahPembayaranMasukFormat.'</small></td>
                                                <td><small>'.$sisa_pembayaran_format.'</small></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                        <li class="dropdown-header text-start">
                                                            <h6>Option</h6>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="dropdown-item modal_riwayat_pembayaran" data-id="'.$id_fee_by_student .'" title="Riwayat Pembayaran">
                                                                <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="dropdown-item modal_ubah_tagihan" data-id="'.$id_fee_by_student .'" title="Ubah Tagihan">
                                                                <i class="bi bi-pencil"></i> Edit Tagihan
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="dropdown-item modal_hapus_tagihan_siswa" data-id="'.$id_fee_by_student .'" title="Hapus Tagihan">
                                                                <i class="bi bi-trash"></i> Hapus Tagihan
                                                            </a>
                                                        </li>
                                                         
                                                    </ul>
                                                </td>
                                            </tr>
                                        ';
                                        $no++;
                                    }
                                    $jumlah_fee_nominal="" . number_format($jumlah_fee_nominal,0,',','.');
                                    $jumlah_fee_discount="" . number_format($jumlah_fee_discount,0,',','.');
                                    $jumlah_pembayaran_masuk="" . number_format($jumlah_pembayaran_masuk,0,',','.');
                                    $jumlah_sisa_pembayaran="" . number_format($jumlah_sisa_pembayaran,0,',','.');
                                    echo '
                                        <tr>
                                            <td></td>
                                            <td><b>Jumlah</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b><small>'.$jumlah_fee_nominal.'</small></b></td>
                                            <td><b><small>'.$jumlah_fee_discount.'</small></b></td>
                                            <td><b><small>'.$jumlah_pembayaran_masuk.'</small></b></td>
                                            <td><b><small>'.$jumlah_sisa_pembayaran.'</small></b></td>
                                            <td></td>
                                        </tr>
                                    ';
                                }
        echo '';
        echo '              </tbody>';
        echo '          </table>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }
?>