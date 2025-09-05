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
                    Tidak Ada Data Yang Dipilih
                </small>
            </div>
        ';
        exit;
    }
    if(empty(count($_POST['id_student']))){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Data Yang Dipilih
                </small>
            </div>
        ';
        exit;
    }

    echo '<div class="row mb-3">';
    echo '  <div class="col-md-12">';
    echo '      <div class="table table-responsive">';
    echo '          <table class="table table-hover table-striped">';
    echo '              
                        <thead>
                            <tr>
                                <th><b>NIS</b></th>
                                <th><b>Nama</b></th>
                                <th><b>Kelas</b></th>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
                        $no=1;
                        foreach ($_POST['id_student'] as $id_student) {
                            //Buka Data Siswa
                            $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
                            $Qry->bind_param("i", $id_student);
                            if (!$Qry->execute()) {
                                $error=$Conn->error;
                                echo '
                                    <tr>
                                        <td colspan="4">
                                            <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                                        </td>
                                    </tr>
                                ';
                            }else{
                                $Result = $Qry->get_result();
                                $Data = $Result->fetch_assoc();
                                $Qry->close();

                                //Buat Variabel
                                $student_nis            =$Data['student_nis'] ?? '-';
                                $student_name           =$Data['student_name'];
                                $student_gender         =$Data['student_gender'];
                                $student_parent         =$Data['student_parent'];
                                $student_registered     =$Data['student_registered'];
                                $student_status         =$Data['student_status'];
                                
                                //Buka Kelas
                                if(empty($Data['id_organization_class'])){
                                    $label_kelas='-';
                                }else{
                                    $id_organization_class  =$Data['id_organization_class'];
                                    $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                                    $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                                    $label_kelas="$level-$kelas";
                                }

                                //Tampilkan Pada Baris Tabel
                                echo '
                                    <tr>
                                        <td>
                                            <small>'.$student_nis.'</small>
                                            <input type="hidden" name="id_student[]" value="'.$id_student .'">
                                        </td>
                                        <td><small>'.$student_name.'</small></td>
                                        <td><small>'.$label_kelas.'</small></td>
                                    </tr>
                                ';
                            }
                            $no++;
                        }
    echo '              </tbody>';
    echo '          </table>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
    echo '
        <div class="row">
            <div class="col-12">
                <label for="status_siswa_update">
                    <small>Status Siswa</small>
                </label>
                <select name="status_siswa" id="status_siswa_update" class="form-control" required>
                    <option value="">Pilih</option>
                    <option value="Terdaftar">Terdaftar</option>
                    <option value="Lulus">Lulus</option>
                    <option value="Keluar">Keluar</option>
                </select>
            </div>
        </div>
    ';

?>