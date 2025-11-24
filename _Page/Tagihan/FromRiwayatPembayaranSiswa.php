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
        echo '      <div class="table table-responsive">';
        echo '          <table class="table table-hover table-striped ">';
        echo '              
                            <thead>
                                <tr>
                                    <th><b>No</b></th>
                                    <th><b>Tgl.Bayar</b></th>
                                    <th><b>Uraian</b></th>
                                    <th><b>Nominal</b></th>
                                    <th><b>Metode</b></th>
                                    <th><b>Option</b></th>
                                </tr>
                            </thead>
        ';
        echo '              <tbody>';
                                $no=1;
                                $JumlahPayment = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE id_student='$id_student'"));
                                if(empty($JumlahPayment)){
                                    echo '
                                        <tr>
                                            <td colspan="5" class="text-center">
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
                                        
                                        //Format Rupiah
                                        $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

                                        //Buka Detail Komponen
                                        $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

                                        echo '
                                            <tr>
                                                <td><small>'.$no.'</small></td>
                                                <td><small>'.date('d/m/Y', strtotime($payment_datetime)).'</small></td>
                                                <td><small>'.$component_name.' | '.$component_category.'</small></td>
                                                <td><small>'.$payment_nominal_format.'</small></td>
                                                <td><small>'.$payment_method.'</small></td>
                                                <td>
                                                   <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                                        <li class="dropdown-header text-start">
                                                            <h6>Option</h6>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailPembayaran2" data-id="'.$id_payment .'">
                                                                <i class="bi bi-info-circle"></i> Detail Pembayaran
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusPembayaran2" data-id="'.$id_payment .'">
                                                                <i class="bi bi-trash"></i> Hapus Pembayaran
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        ';
                                        $no++;
                                    }
                                }
        echo '';
        echo '              </tbody>';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }
?>