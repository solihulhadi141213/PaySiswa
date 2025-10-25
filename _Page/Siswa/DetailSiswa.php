<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-people"></i> Siswa</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?Page=Siswa">Siswa</a></li>
            <li class="breadcrumb-item active">Detail Siswa</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <?php
        //Validasi id
        if(empty($_GET['id'])){
            echo '
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <small>
                                ID Siswa tidak boleh kosong! Sistem tidak bisa menampilkan data dan informasi siswa tanpa parameter ID tersebut
                            </small>
                        </div>
                    </div>
                </div>
            ';
        }else{
            $id_student =validateAndSanitizeInput($_GET['id']);

            //Buka Data Dari Tabel 'student'
            $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
            $Qry->bind_param("i", $id_student);
            if (!$Qry->execute()) {
                $error=$Conn->error;
                echo '
                    <div class="alert alert-danger">
                        <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                    </div>
                ';
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                            </div>
                        </div>
                    </div>
                ';
            }else{
                $Result = $Qry->get_result();
                $Data = $Result->fetch_assoc();
                $Qry->close();

                //Buat Variabel
                $id_organization_class  =$Data['id_organization_class'];
                $student_nis            =$Data['student_nis'] ?? '-';
                $student_nisn           =$Data['student_nisn'] ?? '-';
                $student_name           =$Data['student_name'];
                $student_gender         =$Data['student_gender'];
                $student_parent         =$Data['student_parent'];
                $student_registered     =$Data['student_registered'];
                $student_status         =$Data['student_status'];

                //Parent
                $parent_arry=json_decode($student_parent, true);
                if(empty($parent_arry['nama'])){
                    $parent_nama="-";
                }else{
                    $parent_nama=$parent_arry['nama'];
                }
                if(empty($parent_arry['kontak'])){
                    $parent_kontak="-";
                }else{
                    $parent_kontak=$parent_arry['kontak'];
                }

                //Tempat lahir
                if(empty($place_of_birth)){
                    $place_of_birth ="-";
                }else{
                    $place_of_birth =$Data['place_of_birth'];
                }

                //Tanggal Lahir
                if($Data['date_of_birth']=="0000-00-00"){
                    $date_of_birth="-";
                }else{
                    $date_of_birth =date('d F Y', strtotime($Data['date_of_birth']));
                }

                //Kontak
                if(empty($Data['student_contact'])){
                    $student_contact ="-";
                }else{
                    $student_contact =$Data['student_contact'];
                }

                //Kontak
                if(empty($Data['student_email'])){
                    $student_email ="-";
                }else{
                    $student_email =$Data['student_email'];
                }

                //student_address
                if(empty($Data['student_address'])){
                    $student_address ="-";
                }else{
                    $student_address =$Data['student_address'];
                }

                //student_foto
                if(empty($Data['student_foto'])){
                    $student_foto ="No-Image.png";
                }else{
                    $student_foto =$Data['student_foto'];
                }

                //Format Tanggal Daftar
                $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

                //Status
                if($student_status=="Terdaftar"){
                    $label_status='<span class="badge badge-success">Terdaftar</span>';
                }else{
                    if($student_status=="Lulus"){
                        $label_status='<span class="badge badge-warning">Lulus</span>';
                    }else{
                        $label_status='<span class="badge badge-danger">Keluar</span>';
                    }
                }

                //Tampilkan informasi alert
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman detail siswa. 
                                    Halaman ini berfungsi menampilkan semua informasi transaksi tagihan dan pembayaran siswa.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </small>
                            </div>
                        </div>
                    </div>
                ';
                //Tampilkan informasi siswa
                echo '
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-8">
                                            <b class="card-title"><i class="bi bi-info-circle"></i> Informasi Siswa</b>
                                        </div>
                                        <div class="col-4 text-end">
                                            <a href="index.php?Page=Siswa" class="btn btn-md btn-dark btn-floating">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Nama</small></div>
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
                                                <div class="col-4"><small>NISN</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$student_nisn.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Gender</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$student_gender.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Tempat Lahir</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$place_of_birth.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Tanggal Lahir</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$date_of_birth.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Kontak</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$student_contact.'</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Email</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$student_email.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Alamat</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$student_address.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Nama Orang Tua</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$parent_nama.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Kontak Orang Tua</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$parent_kontak.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Tgl.Daftar</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    <small class="text text-grayish">'.$tanggal_daftar.'</small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4"><small>Status</small></div>
                                                <div class="col-1"><small>:</small></div>
                                                <div class="col-7">
                                                    '.$label_status.'
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';

                //Tampilkan tagihan siswa
                echo '
                    <form action="javascript:void(0);" id="FormFilterTagihanSiswa">
                        <input type="hidden" name="id_student" id="put_id_studentu_for_tagihan_siswa" value="'.$id_student.'">
                        <input type="hidden" name="page" id="page_tagihan_siswa" value="1">
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-8">
                                            <b class="card-title"><i class="bi bi-coin"></i> Biaya Pendidikan Siswa</b>
                                        </div>
                                        <div class="col-4 text-end">
                                            <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalExportTagihanSiswa" data-id="'.$id_student.'">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th><b>No</b></th>
                                                    <th><b>Kelas</b></th>
                                                    <th><b>Periode Akademik</b></th>
                                                    <th><b>Komponen</b></th>
                                                    <th><b>Bulan</b></th>
                                                    <th><b>Tahun</b></th>
                                                    <th><b>Nominal</b></th>
                                                    <th><b>Diskon</b></th>
                                                    <th><b>Jumlah</b></th>
                                                    <th><b>Pembayaran</b></th>
                                                </tr>
                                            </thead>
                                            <tbody id="TabelTagihanSiswa">
                                                <tr>
                                                    <td class="text-center" colspan="10">
                                                        <small>No Data</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <small id="data_count_tagihan_siiswa"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        }
    ?>
    
</section>