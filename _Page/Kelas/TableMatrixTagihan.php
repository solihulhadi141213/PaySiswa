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
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);
    $id_organization_class = (int)$id_organization_class;

    //Buka Data access
    $Qry = $Conn->prepare("SELECT * FROM organization_class WHERE id_organization_class = ?");
    $Qry->bind_param("i", $id_organization_class);
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

    //Buat Variabel
    $id_organization_class  =$Data['id_organization_class'];
    $id_academic_period  =$Data['id_academic_period'];
    $class_level            =$Data['class_level'];
    $class_name             =$Data['class_name'];

    //Hitung Jumlah Siswa
    $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class  FROM  student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'"));

    // Hitung Komponen Biaya SPP
    $sql_spp = "
        SELECT COUNT(*) AS jumlah_baris 
        FROM fee_by_class fbc 
        INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
        WHERE fbc.id_organization_class = $id_organization_class
        AND fc.component_category = 'SPP'
    ";
    $result_spp = mysqli_query($Conn, $sql_spp);
    $row_spp = mysqli_fetch_assoc($result_spp);
    $jumlah_baris_spp = (int)$row_spp['jumlah_baris'];

    // Hitung Komponen Biaya Non-SPP
    $sql_non_spp = "
        SELECT COUNT(*) AS jumlah_baris 
        FROM fee_by_class fbc 
        INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
        WHERE fbc.id_organization_class = $id_organization_class
        AND fc.component_category = 'Non-SPP'
    ";
    $result_non_spp = mysqli_query($Conn, $sql_non_spp);
    $row_non_spp = mysqli_fetch_assoc($result_non_spp);
    $jumlah_baris_non_spp = (int)$row_non_spp['jumlah_baris'];

    //Buka Periode Akademik
    $academic_period=GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Tampilkan Data
    echo '
        <div class="row mb-2">
            <div class="col-12 text-center">
                <b>DAFTAR BIAYA PENDIDIKAN SISWA '.$class_level.' ('.$class_name.')</b><br>
                PERIODE AKADEMIK '.$academic_period.'
            </div>
        </div>
    ';
?>

<div class="row border-top border-1">
    <div class="col-12">
        <div class="table table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th rowspan="2" valign="middle"><b>No</b></th>
                        <th rowspan="2" valign="middle"><b>Siswa</b></th>
                        <th class="text-center" valign="middle" colspan="<?php echo $jumlah_baris_spp; ?>"><b>SPP</b></th>
                        <th class="text-center" valign="middle" colspan="<?php echo $jumlah_baris_non_spp; ?>"><b>Non-SPP</b></th>
                        <th class="text-center" valign="middle" rowspan="2"><b>Jumlah</b></th>
                    </tr>
                    <tr>
                        <?php
                            // Looping Daftar Biaya Pendidikan SPP
                            $qry_list_spp = mysqli_query($Conn, "
                                SELECT fbc.id_fee_component, fc.periode_month, fc.periode_year
                                FROM fee_by_class fbc 
                                INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
                                WHERE fbc.id_organization_class = $id_organization_class
                                AND fc.component_category = 'SPP'
                            ");

                            while ($data_list_spp = mysqli_fetch_assoc($qry_list_spp)) {
                                $id_fee_component = $data_list_spp['id_fee_component'];
                                $periode_month    = $data_list_spp['periode_month'];
                                $periode_year     = $data_list_spp['periode_year'];
                                //Definisikan Mont periode sebagai bulan
                                $nama_bulan=getNamaBulanSingkat($periode_month);
                                echo '
                                    <td class="text-center">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihanMulti" data-id1="'.$id_academic_period.'" data-id2="'.$id_organization_class.'" data-id3="'.$id_fee_component.'">
                                            <small>'.$nama_bulan.'</small><br>
                                            <small>('.$periode_year.')</small><br>
                                            <small><i class="bi bi-arrow-down-circle"></i></small>
                                        </a>
                                    </td>
                                ';
                            }
                            // Looping Daftar Biaya Pendidikan Non SPP
                            $qry_list_non_spp = mysqli_query($Conn, "
                                SELECT fbc.id_fee_component, fc.component_name
                                FROM fee_by_class fbc 
                                INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
                                WHERE fbc.id_organization_class = $id_organization_class
                                AND fc.component_category = 'Non-SPP'
                            ");

                            while ($data_list_non_spp = mysqli_fetch_assoc($qry_list_non_spp)) {
                                $id_fee_component = $data_list_non_spp['id_fee_component'];
                                $component_name    = $data_list_non_spp['component_name'];
                                 echo '
                                    <td class="text-center">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihanMulti" data-id1="'.$id_academic_period.'" data-id2="'.$id_organization_class.'" data-id3="'.$id_fee_component.'">
                                            <small>'.$component_name.'</small><br>
                                            <small>('.$nama_bulan.' '.$periode_year.')</small><br>
                                            <small><i class="bi bi-arrow-down-circle"></i></small>
                                        </a>
                                    </td>
                                ';
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jumlah_colspan=3+$jumlah_baris_spp+$jumlah_baris_non_spp;
                        if(empty($jumlah_siswa)){
                            echo '
                                <tr>
                                    <td colspan="'.$jumlah_colspan.'" align="center">
                                        <spall class="text-danger">Tidak Ada Data Siswa Yang Ditampilkan</spall>
                                    </td>
                                </tr>
                            ';
                            exit;
                        }

                        //Tampilkan Daftar Siswa
                        $no=1;
                        $qry_siswa = mysqli_query($Conn, "SELECT * FROM student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar' ORDER BY student_name ASC");
                        while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
                            $id_student = $data_siswa['id_student'];
                            $id_organization_class= $data_siswa['id_organization_class'];
                            $student_name= $data_siswa['student_name'];
                            $student_nis= $data_siswa['student_nis'];

                            echo '<tr>';
                            echo '  <td><small>'.$no.'</small></td>';
                            echo '  <td><small>'.$student_name.'<br><small class="text text-grayish">NIS:'.$student_nis.'</small></small></td>';
                            
                            $jumlah_tagihan_siswa=0;
                            // Looping Daftar Biaya Pendidikan SPP
                            $qry_list_spp = mysqli_query($Conn, "
                                SELECT fbc.id_fee_component, fc.periode_month, fc.periode_year
                                FROM fee_by_class fbc 
                                INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
                                WHERE fbc.id_organization_class = $id_organization_class
                                AND fc.component_category = 'SPP'
                            ");
                            while ($data_list_spp = mysqli_fetch_assoc($qry_list_spp)) {
                                $id_fee_component = $data_list_spp['id_fee_component'];
                                $periode_month    = $data_list_spp['periode_month'];
                                $periode_year     = $data_list_spp['periode_year'];
                                //Buka Data fee_by_student berdasarkan id_student dan id_fee_component dan id_organization_class
                                $id_fee_by_student=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'id_fee_by_student');
                                $fee_nominal=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'fee_nominal');
                                $fee_discount=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'fee_discount');
                                $jumlah_tagihan=$fee_nominal-$fee_discount;
                                $jumlah_tagihan_siswa=$jumlah_tagihan_siswa+$jumlah_tagihan;
                                $jumlah_tagihan_format="" . number_format($jumlah_tagihan,0,',','.');

                                if(empty($id_fee_by_student)){
                                    echo '
                                        <td class="text-center">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <i class="bi bi-plus"></i>
                                            </a>
                                        </td>
                                    ';
                                }else{
                                    echo '
                                        <td class="text-center">
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <small class="text text-grayish">'.$jumlah_tagihan_format.'</small>
                                            </a><br>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <small class="text-danger">x Hapus</small>
                                            </a>
                                        </td>
                                    ';
                                }
                            }

                            // Looping Daftar Biaya Pendidikan Non SPP
                            $qry_list_non_spp = mysqli_query($Conn, "
                                SELECT fbc.id_fee_component, fc.component_name
                                FROM fee_by_class fbc 
                                INNER JOIN fee_component fc ON fbc.id_fee_component = fc.id_fee_component 
                                WHERE fbc.id_organization_class = $id_organization_class
                                AND fc.component_category = 'Non-SPP'
                            ");
                            while ($data_list_non_spp = mysqli_fetch_assoc($qry_list_non_spp)) {
                                $id_fee_component = $data_list_non_spp['id_fee_component'];
                                //Buka Data fee_by_student berdasarkan id_student dan id_fee_component dan id_organization_class
                                $id_fee_by_student=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'id_fee_by_student');
                                $fee_nominal=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'fee_nominal');
                                $fee_discount=ShowFeeByStudent($id_student,$id_fee_component,$id_organization_class,'fee_discount');
                                $jumlah_tagihan=$fee_nominal-$fee_discount;
                                $jumlah_tagihan_siswa=$jumlah_tagihan_siswa+$jumlah_tagihan;
                                $jumlah_tagihan_format="" . number_format($jumlah_tagihan,0,',','.');

                                if(empty($id_fee_by_student)){
                                     echo '
                                        <td class="text-center">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <i class="bi bi-plus"></i>
                                            </a>
                                        </td>
                                    ';
                                }else{
                                    echo '
                                        <td class="text-center">
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <small class="text text-grayish">'.$jumlah_tagihan_format.'</small>
                                            </a><br>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihan" data-id1="'.$id_organization_class .'" data-id2="'.$id_student .'" data-id3="'.$id_fee_component .'">
                                                <small class="text-danger">x Hapus</small>
                                            </a>
                                        </td>
                                    ';
                                }
                            }
                            $jumlah_tagihan_siswa_format="" . number_format($jumlah_tagihan_siswa,0,',','.');
                            echo '
                                <td class="text-center">
                                    <small class="text text-dark">'.$jumlah_tagihan_siswa_format.'</small>
                                </td>
                            ';
                            echo '</tr>';
                            $no++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>