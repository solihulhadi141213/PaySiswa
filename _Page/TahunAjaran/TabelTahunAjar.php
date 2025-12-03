<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    // Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr>
                <td colspan="12" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }

    // Inisiasi Variabel dan Sanitasi Input
    $keyword_by = !empty($_POST['keyword_by']) ? $_POST['keyword_by'] : "";
    $keyword    = !empty($_POST['keyword']) ? mysqli_real_escape_string($Conn, $_POST['keyword']) : ""; // Sanitasi keyword
    $batas      = !empty($_POST['batas']) ? (int)$_POST['batas'] : 10;
    $ShortBy    = !empty($_POST['ShortBy']) ? $_POST['ShortBy'] : "DESC";
    $OrderBy    = !empty($_POST['OrderBy']) ? $_POST['OrderBy'] : "id_academic_period";
    $page       = !empty($_POST['page']) ? (int)$_POST['page'] : 1;
    $posisi     = ($page - 1) * $batas;

    // Persiapan Klausa WHERE
    $where_clause = "";
    if (!empty($keyword)) {
        if (!empty($keyword_by)) {
            // Menggunakan prepared statement untuk keamanan yang lebih baik
            // Namun, karena keyword_by adalah nama kolom, ini sedikit rumit dengan mysqli
            // Untuk mysqli biasa, kita harus memvalidasi nama kolom.
            $allowed_columns = ['academic_period', 'academic_period_start', 'academic_period_end', 'academic_period_status'];
            if (in_array($keyword_by, $allowed_columns)) {
                 $where_clause = " WHERE $keyword_by LIKE '%$keyword%'";
            }
        } else {
            // Pencarian di beberapa kolom
            $where_clause = " WHERE academic_period LIKE '%$keyword%' OR academic_period_end LIKE '%$keyword%' OR academic_period_start LIKE '%$keyword%' OR academic_period_status LIKE '%$keyword%'";
        }
    }

    // Menghitung Jumlah Data Total
    $query_jml_data = "SELECT COUNT(id_academic_period) AS jml_data FROM academic_period $where_clause";
    $result_jml_data = mysqli_query($Conn, $query_jml_data);
    $data_jml_data = mysqli_fetch_assoc($result_jml_data);
    $jml_data = (int)$data_jml_data['jml_data'];

    // Mengatur Halaman
    $JmlHalaman = ceil($jml_data / $batas);

    if (empty($jml_data)) {
        echo '
            <tr>
                <td colspan="12" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                </td>
            </tr>
        ';
    } else {
        $no = 1 + $posisi;

        // Query Data Periode Akademik
        $query = "SELECT * FROM academic_period $where_clause ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas";
        $result_period = mysqli_query($Conn, $query);

        while ($data_period = mysqli_fetch_assoc($result_period)) {
            $id_academic_period     = $data_period['id_academic_period'];
            $academic_period        = $data_period['academic_period'];
            $academic_period_start  = $data_period['academic_period_start'];
            $academic_period_end    = $data_period['academic_period_end'];
            $academic_period_status = $data_period['academic_period_status'];

            // --- Bagian Perhitungan Total Menggunakan Query Lebih Efisien ---
            // *PERHATIAN*: Menggunakan subquery untuk menggabungkan perhitungan dari organization_class, fee_by_student, dan payment.
            // Ini akan menggantikan banyak loop dan query di dalam loop PHP, menjadikannya lebih efisien.
            
            // Query untuk mendapatkan total biaya, diskon, tagihan, pembayaran, dan jumlah siswa dalam satu langkah
            $query_total = "
                SELECT
                    SUM(t1.fee_nominal) AS total_biaya,
                    SUM(t1.fee_discount) AS total_diskon,
                    SUM(t1.fee_nominal - t1.fee_discount) AS total_tagihan,
                    SUM(t2.payment_nominal) AS total_pembayaran,
                    COUNT(DISTINCT t1.id_student) AS total_siswa
                FROM fee_by_student t1
                LEFT JOIN payment t2 ON t1.id_organization_class = t2.id_organization_class
                WHERE t1.id_organization_class IN (
                    SELECT id_organization_class
                    FROM organization_class
                    WHERE id_academic_period = '$id_academic_period'
                )
            ";
            
            // *CATATAN OPTIMALISASI*: Query di atas mungkin menghasilkan total pembayaran yang tidak akurat karena JOIN.
            // Solusi yang lebih akurat adalah menghitung total tagihan dan total pembayaran secara terpisah berdasarkan ID Kelas dalam periode ini,
            // lalu menjumlahkannya di PHP.

            $total_biaya = 0;
            $total_diskon = 0;
            $total_tagihan = 0;
            $total_pembayaran = 0;
            $total_siswa = 0;
            $jumlah_kelas = 0;
            $QryKelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'");
            $jumlah_kelas = mysqli_num_rows($QryKelas);

            while ($DataKelas = mysqli_fetch_assoc($QryKelas)) {
                $id_organization_class = $DataKelas['id_organization_class'];

                // Total Biaya, Diskon, dan Tagihan untuk Kelas ini
                $SumFee = mysqli_fetch_assoc(mysqli_query($Conn, "SELECT SUM(fee_nominal) AS total_biaya, SUM(fee_discount) AS total_diskon, SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                $total_biaya += (float)$SumFee['total_biaya'];
                $total_diskon += (float)$SumFee['total_diskon'];
                $total_tagihan += (float)$SumFee['total_tagihan'];

                // Total Pembayaran untuk Kelas ini
                $SumPayment = mysqli_fetch_assoc(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS total_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
                $total_pembayaran += (float)$SumPayment['total_pembayaran'];

                // Hitung jumlah siswa unik per kelas dan tambahkan ke total
                $jumlah_siswa_kelas = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                $total_siswa += $jumlah_siswa_kelas;
            }
            
            $total_sisa = $total_tagihan - $total_pembayaran;

            // --- Routing dan Formatting Data ---

            // Routing 'label_status' dan 'tombol_lanjutan'
            if ($academic_period_status == 1) { // Menggunakan integer 1/0 lebih baik untuk status boolean
                $label_status = '<span class="badge badge-success"><i class="bi bi-check-circle"></i> Unlock</span>';
                $tombol_lanjutan = '<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKunci" data-id="' . $id_academic_period . '"><i class="bi bi-lock"></i> Kunci</a>';
            } else {
                $label_status = '<span class="badge badge-danger"><i class="bi bi-lock"></i> Locked</span>';
                $tombol_lanjutan = '<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKunci" data-id="' . $id_academic_period . '"><i class="bi bi-check-circle"></i> Aktifkan</a>';
            }

            // Jumlah Kelas
            if (empty($jumlah_kelas)) {
                $label_jumlah_kelas = '<a href="javascript:void(0);" class="text text-grayish" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="' . $id_academic_period . '">'.$jumlah_kelas.' Kelas</a>';
            } else {
                $label_jumlah_kelas = '<a href="javascript:void(0);" class="text text-dark" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="' . $id_academic_period . '">'.$jumlah_kelas.' Kelas</a>';
            }

            // Menghitung Komponen Biaya (K.B.P)
            $jumlah_fee_component = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));
            if (empty($jumlah_fee_component)) {
                $label_komponen = '<span class="text text-grayish">'.$jumlah_fee_component.' Komponen</span>';
            } else {
                $label_komponen = '<span class="text text-dark">'.$jumlah_fee_component.' Komponen</span>';
            }

            // Routing 'label_total_siswa'
            if (empty($total_siswa)) {
                $label_total_siswa = '<a href="javascript:void(0);" class="text text-grayish" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="' . $id_academic_period . '">'.$total_siswa.' Orang</a>';
            } else {
                $label_total_siswa = '<a href="javascript:void(0);" class="text text-dark" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="' . $id_academic_period . '">'.$total_siswa.' Orang</a>';
            }

            // Format Mata Uang
            $total_biaya_format         = "Rp " . number_format($total_biaya, 0, ',', '.');
            $total_diskon_format        = "Rp " . number_format($total_diskon, 0, ',', '.');
            $total_tagihan_format       = "Rp " . number_format($total_tagihan, 0, ',', '.');
            $total_pembayaran_format    = "Rp " . number_format($total_pembayaran, 0, ',', '.');
            $total_sisa_format          = "Rp " . number_format($total_sisa, 0, ',', '.');

            // Routing 'LabelSisaTagihan'
            if (empty($total_sisa)) {
                $LabelSisaTagihan = '<span class="text text-success">'.$total_sisa_format.'</span>';
            } else {
                $LabelSisaTagihan = '<span class="text text-danger">'.$total_sisa_format.'</span>';
            }

            // Tampilkan Data
            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td>
                        <a href="javascript:void(0);" class="text text-primary modal_detail" data-id="'.$id_academic_period .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat Detail Periode Akademik">
                            <small class="underscore_doted">'.$academic_period.'</small>
                        </a>
                    </td>
                    <td><small class="underscore_doted">'.$label_jumlah_kelas.'</small></td>
                    <td><small class="underscore_doted">'.$label_total_siswa.'</small></td>
                    <td>
                        <a href="javascript:void(0);" class="modal_komponen_biaya" data-id="'.$id_academic_period .'">
                            <small class="underscore_doted">'.$label_komponen.'</small>
                        </a>
                    </td>
                    <td><small>'.$total_biaya_format.'</small></td>
                    <td><small>'.$total_diskon_format.'</small></td>
                    <td><small>'.$total_tagihan_format.'</small></td>
                    <td><small>'.$total_pembayaran_format.'</small></td>
                    <td>
                        <a href="javascript:void(0);" class="modal_tagihan_siswa" data-id="'.$id_academic_period .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat Rincian Tagihan, Pembayaran dan Sisa Tunggakan">
                            <small class="underscore_doted">'.$LabelSisaTagihan.'</small>
                        </a>
                    </td>
                    <td><small>'.$label_status.'</small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start"><h6>Option</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item modal_detail" href="javascript:void(0)" data-id="'.$id_academic_period .'"><i class="bi bi-info-circle"></i> Detail</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_academic_period .'"><i class="bi bi-pencil"></i> Edit</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_academic_period .'"><i class="bi bi-trash"></i> Hapus</a></li>
                            <li>'. $tombol_lanjutan.'</li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDaftarKelas" data-id="'.$id_academic_period .'"><i class="bi bi-building"></i> Kelas / Rombel</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="'.$id_academic_period .'"><i class="bi bi-people"></i> Daftar Siswa</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya" data-id="'.$id_academic_period .'"><i class="bi bi-list-nested"></i> Komponen Biaya</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id="'.$id_academic_period .'"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</a></li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }
    }
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