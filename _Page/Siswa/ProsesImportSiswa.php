<?php
    require '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Csv;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    
    // Time Now Tmp
    $now = date('Y-m-d H:i:s');
    
    // Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang.</small>
                </td>
            </tr>
        ';
        exit;
    }
    
    // Validasi File
    if(empty($_FILES['data_siswa']['name'])) {
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">Silahkan pilih file untuk di upload</small>
                </td>
            </tr>
        ';
        exit;
    }
    
    $nama_file = $_FILES['data_siswa']['name'];
    $file_mimes = array(
        'application/octet-stream', 
        'application/vnd.ms-excel', 
        'application/x-csv', 
        'text/x-csv', 
        'text/csv', 
        'application/csv', 
        'application/excel', 
        'application/vnd.msexcel', 
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.oasis.opendocument.spreadsheet'
    );
    
    if(isset($_FILES['data_siswa']['name']) && in_array($_FILES['data_siswa']['type'], $file_mimes)) {
        $arr_file = explode('.', $_FILES['data_siswa']['name']);
        $extension = end($arr_file);
        
        if('csv' == $extension) {
            $reader = new Csv();
        } else {
            $reader = new Xlsx();
        }
        
        // Mengatasi deprecated function dengan menonaktifkan entity loader secara kondisional
        if (PHP_VERSION_ID < 80000) {
            $entityLoaderDisabled = libxml_disable_entity_loader(true);
        }
        
        try {
            $spreadsheet = $reader->load($_FILES['data_siswa']['tmp_name']);
            
            // Mengembalikan entity loader ke keadaan semula untuk PHP < 8.0
            if (PHP_VERSION_ID < 80000) {
                libxml_disable_entity_loader($entityLoaderDisabled);
            }
            
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $JumlahBaris = count($sheetData);
            $JumlahValidator = $JumlahBaris - 1;
            
            if(empty($JumlahValidator)) {
                echo '
                    <tr>
                        <td colspan="5" class="text-center">
                            <small class="text-danger">Tidak ada data pada file excel yang anda upload</small>
                        </td>
                    </tr>
                ';
                exit;
            }
            
            $JumlahKodeValid = 0;
            for($i = 1; $i < $JumlahBaris; $i++) {
                // Validasi baris kosong
                if(empty($sheetData[$i][0]) && empty($sheetData[$i][1]) && empty($sheetData[$i][2])) {
                    continue; // Lewati baris kosong
                }
                
                if(empty($sheetData[$i][0])) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td colspan="4" class="text-center">
                                <small class="text-danger">NIS tidak boleh kosong</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                if(empty($sheetData[$i][1])) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$sheetData[$i][0].'</td>
                            <td colspan="3" class="text-center">
                                <small class="text-danger">Nama tidak boleh kosong</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                if(empty($sheetData[$i][2])) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$sheetData[$i][0].'</td>
                            <td>'.$sheetData[$i][1].'</td>
                            <td colspan="2" class="text-center">
                                <small class="text-danger">Gender tidak boleh kosong</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                if(empty($sheetData[$i][4])) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$sheetData[$i][0].'</td>
                            <td>'.$sheetData[$i][1].'</td>
                            <td>'.$sheetData[$i][2].'</td>
                            <td class="text-center">
                                <small class="text-danger">Tanggal Daftar tidak boleh kosong</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                if(empty($sheetData[$i][5])) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$sheetData[$i][0].'</td>
                            <td>'.$sheetData[$i][1].'</td>
                            <td>'.$sheetData[$i][2].'</td>
                            <td class="text-center">
                                <small class="text-danger">Status siswa tidak boleh kosong</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                $student_nis = mysqli_real_escape_string($Conn, $sheetData[$i][0]);
                $student_name = mysqli_real_escape_string($Conn, $sheetData[$i][1]);
                $student_gender = mysqli_real_escape_string($Conn, $sheetData[$i][2]);
                
                if(empty($sheetData[$i][3])) {
                    $id_organization_class = "";
                } else {
                    $id_organization_class = mysqli_real_escape_string($Conn, $sheetData[$i][3]);
                }
                
                $student_registered = mysqli_real_escape_string($Conn, $sheetData[$i][4]);
                $student_status = mysqli_real_escape_string($Conn, $sheetData[$i][5]);
                
                // Validasi Duplikat NIS
                $ValidasiDuplikatNis = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM student WHERE student_nis='$student_nis'"));
                if(!empty($ValidasiDuplikatNis)) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-danger">NIS Sudah Terdaftar</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                // Validasi Gender
                if($student_gender !== 'Male' && $student_gender !== 'Female') {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-danger">Gender hanya boleh Female dan Male</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                // Format Tanggal - Perbaikan error pada kode asli
                $student_registered_timestamp = strtotime($student_registered);
                if($student_registered_timestamp === false) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-danger">Format tanggal tidak valid</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                $student_registered_formatted = date('Y-m-d', $student_registered_timestamp);
                
                // Validasi id_organization_class
                $ValidasiKelas = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_organization_class='$id_organization_class'"));
                if(empty($ValidasiKelas)) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-danger">ID Kelas Tidak Ditemukan</small>
                            </td>
                        </tr>
                    ';
                    continue;
                }
                
                // Simpan Data
                $EntryAnggota = "INSERT INTO student (
                    id_organization_class,
                    student_nis,
                    student_name,
                    student_gender,
                    student_registered,
                    student_status
                ) VALUES (
                    '$id_organization_class',
                    '$student_nis',
                    '$student_name',
                    '$student_gender',
                    '$student_registered_formatted',
                    '$student_status'
                )";
                
                $InputAnggota = mysqli_query($Conn, $EntryAnggota);
                if($InputAnggota) {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-success">Success</small>
                            </td>
                        </tr>
                    ';
                    $JumlahKodeValid++;
                } else {
                    echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>'.$student_nis.'</td>
                            <td>'.$student_name.'</td>
                            <td>'.$student_gender.'</td>
                            <td class="text-center">
                                <small class="text-danger">Terjadi kesalahan pada saat proses input: '.mysqli_error($Conn).'</small>
                            </td>
                        </tr>
                    ';
                }
            }
            
            // Tampilkan ringkasan
            echo '
                <tr>
                    <td colspan="5" class="text-center">
                        <small class="text-info">Proses selesai. '.$JumlahKodeValid.' dari '.$JumlahValidator.' data berhasil diimpor.</small>
                    </td>
                </tr>
            ';
            
        } catch (Exception $e) {
            // Mengembalikan entity loader ke keadaan semula untuk PHP < 8.0 jika terjadi error
            if (PHP_VERSION_ID < 80000) {
                libxml_disable_entity_loader($entityLoaderDisabled);
            }
            
            echo '
                <tr>
                    <td colspan="5" class="text-center">
                        <small class="text-danger">Error membaca file: '.$e->getMessage().'</small>
                    </td>
                </tr>
            ';
        }
        
    } else {
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">File tidak valid. Silahkan upload file Excel atau CSV.</small>
                </td>
            </tr>
        ';
    }
?>