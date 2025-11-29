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
    //Tangkap id_organization_class
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

    //Buat variabel
    $id_student=validateAndSanitizeInput($_POST['id_student']);

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
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Jika ID Siswa Tidak valid
    if(empty($Data['id_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Siswa Tidak Valid!</small>
            </div>
        ';
        exit;
    }

    //Hitung Jumlah Tagihan
    $JumlahTagihan = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student'"));

    //Jika Tidak Ada Tagihan
    if(empty($JumlahTagihan)){
        echo '
            <div class="alert alert-danger">
                <small>Siswa Yang Anda Pilih Tidak Memiliki Tagihan</small>
            </div>
        ';
        exit;
    }
    
    // Form Untuk Menyimpan Parameter Menampilkan Tagihan Siswa
    echo '<form action="javascript:void(0);" id="FilterTagihanSiswa">';
    echo '  <div class="row mb-2">';
    echo '
                <div class="col-12">
                    <small>
                        <b><label for="pilih_periode_kelas"># Pilih Periode Akademik</label></b>
                    </small>
                </div>
    ';
    echo '      <div class="col-12">';
    echo '              <input type="hidden" name="id_student" value="'.$id_student.'">';
    echo '              <select name="id_organization_class" id="pilih_periode_kelas" class="form-control">';
    
                        //Hitung Periode Pendidikan
                        $jml_data_periode_akademik = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student'"));

                        //Jika Tidak Ada
                        if(empty($jml_data_periode_akademik)){
                            echo '      <option>Tidak Ada Periode Akdemik Yang Terdaftar</option>';
                        }else{
                            $query_fee_by_student = mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student' ORDER BY id_organization_class DESC");
                            while ($data_fee_by_student = mysqli_fetch_array($query_fee_by_student)) {
                                $id_organization_class = $data_fee_by_student['id_organization_class'];

                                //Buka id_academic_period dari tabel organization_class 
                                $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

                                //Buka academic_period dari tabel academic_period melalui id_academic_period
                                $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
                                echo '      <option value="'.$id_organization_class.'">'.$academic_period.'</option>';
                            }
                        }
    echo '              </select>';
    echo '      </div>';
    echo '  </div>';
    echo '</form>';

    //Menampilkan Title Tagihan
    echo '
        <div class="row mb-2">
            <div class="col-12" id="title_tagihan">
            </div>
        </div>
    ';

    //Menampilkan Tabel Tagihan Siswa
    echo '
        <div class="row mb-2 mt-3">
            <div class="col-12">
                <div class="table table-responsive border-1 border-top">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <td><small><b>No</b></small></td>
                                <td><small><b>Komponen Biaya</b></small></td>
                                <td><small><b>Kategori</b></small></td>
                                <td><small><b>Bulan</b></small></td>
                                <td><small><b>Tahun</b></small></td>
                                <td align="right"><small><b>Biaya/Tarif</b></small></td>
                                <td align="right"><small><b>Diskon</b></small></td>
                                <td align="right"><small><b>Tagihan</b></small></td>
                                <td align="right"><small><b>Bayar</b></small></td>
                                <td align="right"><small><b>Sisa</b></small></td>
                                <td><small><b>Opsi</b></small></td>
                            </tr>
                        </thead>
                        <tbody id="TabelTagihan">
                            <tr>
                                <td colspan="11" align="center">
                                    <small>Loading...</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    ';
?>