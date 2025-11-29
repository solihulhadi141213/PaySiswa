<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi variabel
    $title_pembayaran = "";
    $JmlHalaman = 0;
    $page = 0;

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="12" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
    }else{
        // TANGKAP PARAMETER FILTER
        $keyword_by = !empty($_POST['keyword_by']) ? mysqli_real_escape_string($Conn, $_POST['keyword_by']) : "";
        $keyword = !empty($_POST['keyword']) ? mysqli_real_escape_string($Conn, $_POST['keyword']) : "";
        $batas = !empty($_POST['batas']) ? intval($_POST['batas']) : 10;
        $ShortBy = !empty($_POST['ShortBy']) ? mysqli_real_escape_string($Conn, $_POST['ShortBy']) : "DESC";
        $OrderBy = !empty($_POST['OrderBy']) ? mysqli_real_escape_string($Conn, $_POST['OrderBy']) : "payment_datetime";
        
        // Validasi dan mapping field untuk ORDER BY
        $allowed_order_fields = [
            'student_name' => 's.student_name',
            'student_nis' => 's.student_nis',
            'id_academic_period' => 'ap.academic_period',
            'id_organization_class' => 'oc.class_name',
            'id_fee_component' => 'fc.component_name',
            'payment_datetime' => 'p.payment_datetime',
            'payment_method' => 'p.payment_method',
            'payment_nominal' => 'p.payment_nominal'
        ];
        
        // Gunakan field yang valid atau default
        $order_field = isset($allowed_order_fields[$OrderBy]) ? $allowed_order_fields[$OrderBy] : 'p.payment_datetime';
        
        // Validasi ShortBy
        $ShortBy = in_array(strtoupper($ShortBy), ['ASC', 'DESC']) ? strtoupper($ShortBy) : 'DESC';

        // PERIODE AKADEMIK (jika ada parameter tambahan)
        $id_academic_period = !empty($_POST['id_academic_period']) ? intval($_POST['id_academic_period']) : "";
        $id_organization_class = !empty($_POST['id_organization_class']) ? intval($_POST['id_organization_class']) : "";
        $id_fee_component = !empty($_POST['id_fee_component']) ? intval($_POST['id_fee_component']) : "";

        // Atur 'posisi' untuk pagination
        $page = !empty($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
        $posisi = ($page - 1) * $batas;

        // Buat '$title_pembayaran'
        $display_keyword_by = empty($keyword_by) ? "Semua Field" : $keyword_by;
        $display_keyword = empty($keyword) ? "-" : $keyword;

        //Display 'ShortBy'
        if($ShortBy=="ASC"){
            $DisplayShortBy = "A to Z";
        }else{
            $DisplayShortBy = "Z to A";
        }

        //Display 'OrderBy'
        if($OrderBy=="student_name"){
            $DisplayOrderBy = "Nama Siswa";
        }else{
            if($OrderBy=="student_nis"){
                $DisplayOrderBy = "NIS Siswa";
            }else{
                if($OrderBy=="id_academic_period"){
                    $DisplayOrderBy = "Periode Akademik";
                }else{
                    if($OrderBy=="id_organization_class"){
                        $DisplayOrderBy = "Kelas/Rombel";
                    }else{
                        if($OrderBy=="id_fee_component"){
                            $DisplayOrderBy = "Komponen Biaya";
                        }else{
                            if($OrderBy=="payment_datetime"){
                                $DisplayOrderBy = "Tanggal Pembayaran";
                            }else{
                                if($OrderBy=="payment_method"){
                                    $DisplayOrderBy = "Metode Pembayaran";
                                }else{
                                    if($OrderBy=="payment_nominal"){
                                        $DisplayOrderBy = "Nominal Pembayaran";
                                    }else{
                                        $DisplayShortBy = "-";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //Display 'keyword_by'
        if($keyword_by=="student_name"){
            $DisplayKeywordBy = "Nama Siswa";
        }else{
            if($keyword_by=="student_nis"){
                $DisplayKeywordBy = "NIS Siswa";
            }else{
                if($keyword_by=="id_academic_period"){
                    $DisplayKeywordBy = "Periode Akademik";
                    if(!empty($id_academic_period)){
                        $display_keyword = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
                    }
                }else{
                    if($keyword_by=="id_organization_class"){
                        $DisplayKeywordBy = "Kelas/Rombel";
                        if(!empty($id_organization_class)){
                            $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                            $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                            $display_keyword    = "$class_name | $class_level";
                        }
                    }else{
                        if($keyword_by=="id_fee_component"){
                            $DisplayKeywordBy = "Komponen Biaya";
                            if(!empty($id_organization_class)){
                                $display_keyword = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                            }
                        }else{
                            if($keyword_by=="payment_datetime"){
                                $DisplayKeywordBy = "Tanggal Pembayaran";
                            }else{
                                if($keyword_by=="payment_method"){
                                    $DisplayKeywordBy = "Metode Pembayaran";
                                }else{
                                    if($keyword_by=="payment_nominal"){
                                        $DisplayKeywordBy = "Nominal Pembayaran";
                                    }else{
                                        $DisplayKeywordBy = "Semua Field";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $title_pembayaran = '
            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-2">
                        <div class="col-5"><small>Batas/Limit</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-6"><small class="text text-grayish">'.$batas.' Baris</small></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><small>Pengurutan</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-6"><small class="text text-grayish">'.$DisplayShortBy.'</small></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><small>Dasar Pengurutan</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-6"><small class="text text-grayish">'.$DisplayOrderBy.'</small></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mb-2">
                        <div class="col-5"><small>Dasar Pencarian</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-6"><small class="text text-grayish">'.$DisplayKeywordBy.'</small></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><small>Kata Kunci</small></div>
                        <div class="col-1"><small>:</small></div>
                        <div class="col-6"><small class="text text-grayish">'.$display_keyword.'</small></div>
                    </div>
                </div>
            </div>
        ';

        // Query dasar dengan JOIN yang lebih lengkap
        $sql_base = "
            FROM payment p
            LEFT JOIN student s ON p.id_student = s.id_student
            LEFT JOIN organization_class oc ON p.id_organization_class = oc.id_organization_class
            LEFT JOIN academic_period ap ON oc.id_academic_period = ap.id_academic_period
            LEFT JOIN fee_component fc ON p.id_fee_component = fc.id_fee_component
        ";

        // Filter pencarian dengan prepared statement style
        $where_conditions = [];
        $params = [];
        
        // Filter berdasarkan keyword
        if(!empty($keyword)){
            if(empty($keyword_by)){
                // Pencarian di semua field
                $where_conditions[] = "(
                    s.student_name LIKE ? OR
                    s.student_nis LIKE ? OR
                    s.student_nisn LIKE ? OR
                    oc.class_name LIKE ? OR
                    oc.class_level LIKE ? OR
                    fc.component_name LIKE ? OR
                    fc.component_category LIKE ? OR
                    p.payment_datetime LIKE ? OR
                    p.payment_method LIKE ? OR
                    ap.academic_period LIKE ?
                )";
                $search_term = "%$keyword%";
                for($i = 0; $i < 10; $i++){
                    $params[] = $search_term;
                }
            }else{
                // Pencarian berdasarkan field tertentu
                $allowed_search_fields = [
                    'student_name' => 's.student_name',
                    'student_nis' => 's.student_nis',
                    'id_academic_period' => 'ap.academic_period',
                    'id_organization_class' => 'oc.class_name',
                    'id_fee_component' => 'fc.component_name',
                    'payment_datetime' => 'p.payment_datetime',
                    'payment_method' => 'p.payment_method'
                ];
                
                if(isset($allowed_search_fields[$keyword_by])){
                    $where_conditions[] = $allowed_search_fields[$keyword_by] . " LIKE ?";
                    $params[] = "%$keyword%";
                }
            }
        }
        
        // Filter tambahan berdasarkan periode akademik, kelas, atau komponen
        if(!empty($id_academic_period)){
            $where_conditions[] = "oc.id_academic_period = ?";
            $params[] = $id_academic_period;
        }
        
        if(!empty($id_organization_class)){
            $where_conditions[] = "p.id_organization_class = ?";
            $params[] = $id_organization_class;
        }
        
        if(!empty($id_fee_component)){
            $where_conditions[] = "p.id_fee_component = ?";
            $params[] = $id_fee_component;
        }
        
        // Gabungkan kondisi WHERE
        $where = "";
        if(!empty($where_conditions)){
            $where = " WHERE " . implode(" AND ", $where_conditions);
        }

        // Hitung total data
        $count_sql = "SELECT COUNT(p.id_payment) as jml " . $sql_base . $where;
        $stmt_count = mysqli_prepare($Conn, $count_sql);
        
        if(!empty($params)){
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt_count, $types, ...$params);
        }
        
        mysqli_stmt_execute($stmt_count);
        $result_count = mysqli_stmt_get_result($stmt_count);
        $h_count = mysqli_fetch_assoc($result_count);
        $jml_data = $h_count['jml'];
        mysqli_stmt_close($stmt_count);

        // Mengatur Halaman
        $JmlHalaman = ceil($jml_data / $batas); 
        
        if(empty($jml_data)){
            echo '
                <tr>
                    <td colspan="12" class="text-center">
                        <small class="text-danger">Tidak Ada Data Pembayaran Yang Ditampilkan!</small>
                    </td>
                </tr>
            ';
        }else{
            // Query data dengan prepared statement
            $data_sql = "
                SELECT 
                    p.*, 
                    s.student_name, 
                    s.student_nis, 
                    s.student_nisn,
                    oc.class_level, 
                    oc.class_name,
                    oc.id_academic_period,
                    ap.academic_period as academic_period_name,
                    fc.component_name, 
                    fc.component_category
                " . $sql_base . $where . "
                ORDER BY $order_field $ShortBy 
                LIMIT $posisi, $batas
            ";
            
            $stmt_data = mysqli_prepare($Conn, $data_sql);
            
            if(!empty($params)){
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt_data, $types, ...$params);
            }
            
            mysqli_stmt_execute($stmt_data);
            $result_data = mysqli_stmt_get_result($stmt_data);
            
            $no = 1 + $posisi;
            while ($data = mysqli_fetch_array($result_data)) {
                $id_payment             = $data['id_payment'];
                $id_organization_class  = $data['id_organization_class'];
                $id_fee_component       = $data['id_fee_component'];
                $id_student             = $data['id_student'];
                $student_nis            = $data['student_nis'];
                $student_nisn           = $data['student_nisn'];
                $student_name           = $data['student_name'];
                $class_level            = $data['class_level'];
                $class_name             = $data['class_name'];
                $label_kelas            = $class_level . "-" . $class_name;
                $component_name         = $data['component_name'];
                $component_category     = $data['component_category'];
                $payment_method         = $data['payment_method'];
                $academic_period_name   = $data['academic_period_name'];

                // Format Payment Datetime
                $payment_datetime_format = date('d/m/Y H:i T', strtotime($data['payment_datetime']));

                // Format Nominal Payment
                $payment_nominal_format = "Rp " . number_format($data['payment_nominal'], 0, ',', '.');

                echo '
                    <tr>
                        <td><small>'.$no.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="modal_detail_siswa" data-id="'.$id_student.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click Untuk Melihat Detail Siswa">
                                <small class="underscore_doted">'.$student_name.'</small>
                            </a>
                        </td>
                        <td><small>'.$student_nis.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="modal_detail_kelas" data-id="'.$id_organization_class.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Detail Periode Akademik">
                                <small class="underscore_doted"><small>'.$academic_period_name.'</small></small>
                            </a>
                        </td>
                        <td><small>'.$class_level.'</small></td>
                        <td><small>'.$class_name.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="modal_detail_komponen_biaya" data-id="'.$id_fee_component.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Detail Komponen Biaya Pendidikan">
                                <small class="underscore_doted">'.$component_name.'</small>
                            </a>
                        </td>
                        <td><small>'.$component_category.'</small></td>
                        <td><small>'.$payment_datetime_format.'</small></td>
                        <td><small>'.$payment_method.'</small></td>
                        <td>
                            <a href="javascript:void(0);" class="modal_detail_pembayaran" data-id="'.$id_payment.'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Detail Pembayaran Siswa">
                                <small class="text-dark underscore_doted">'.$payment_nominal_format.'</small>
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-dark btn-floating" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item modal_detail_pembayaran" data-id="'.$id_payment.'">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_payment.'">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_payment.'">
                                        <i class="bi bi-x"></i> Hapus
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                ';
                $no++;
            }
            
            mysqli_stmt_close($stmt_data);
        }
    }

    // Menempelkan title dan informasi pagination
    echo '
        <script>
            $("#title_pembayaran").html(' . json_encode($title_pembayaran) . ');
            $("#total_data").text("' . $jml_data . '");
            $("#total_halaman").text("' . $JmlHalaman . '");
        </script>
    ';
?>
<script>
    //Creat Javascript Variabel
    var page_count=<?php echo $JmlHalaman; ?>;
    var curent_page=<?php echo $page; ?>;
    
    //Put Into Pagging Element
    $('#page_info').html('Page '+curent_page+' Of '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button').prop('disabled', true);
    }else{
        $('#prev_button').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button').prop('disabled', true);
    }else{
        $('#next_button').prop('disabled', false);
    }
</script>
