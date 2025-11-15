<div class="modal fade" id="ModalPilihPeriodeAkademik" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Periode Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <small>Pilih Periode Akademik Berikut Ini :</small>
                        <?php
                            //Menampilkan Tahun Akademik
                            $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period  ORDER BY academic_period_start ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_academic_period = $data['id_academic_period'];
                                $academic_period= $data['academic_period'];
                                echo '
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="id_academic_period" id="id_academic_period'.$id_academic_period.'" value="'.$id_academic_period.'" checked="">
                                        <label class="form-check-label" for="id_academic_period'.$id_academic_period.'">
                                            <small>Periode '.$academic_period.'</small>
                                        </label>
                                    </div>
                                ';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-rounded" id="TombolTampilkan">
                    <i class="bi bi-check"></i> Tampilkan
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTambah" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambah" autocomplete="off">
                <input type="hidden" name="id_academic_period" id="id_academic_period_tambah" value="">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="class_level">
                                <small>Level Kelas</small>
                            </label>
                            <input type="text" class="form-control" name="class_level" id="class_level" list="ListLevel" required>
                            <small>
                                <small class="text text-muted">
                                    Example : Kelas 1, Kelas2, Kelas 3
                                </small>
                            </small>
                            <datalist id="ListLevel">
                                <!-- List Level Akan Muncul Disini -->
                            </datalist>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="class_name">
                                <small>Nama Kelas</small>
                            </label>
                            <input type="text" class="form-control" name="class_name" id="class_name" required>
                            <small>
                                <small class="text text-muted">
                                    Example : 3A, 3B, 3C
                                </small>
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiTambah">
                            <!-- Notifikasi Proses Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded" id="TombolSimpan">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalExportKelas" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="index.php" method="GET" terget="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-download"></i> Export Kelas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormExportKelas">
                            <!-- Form Export Kelas -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalCopy" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesCopy">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-copy"></i> Copy Dari Periode Lain
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormCopy">
                            <!-- Form Copy -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiCopy">
                            <!-- Notifikasi Copy -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetail" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="TampilkanDetailKelas">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Detail Kelas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormDetail">
                            <!-- Form Detail Kelas Fitur -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Daftar Siswa Berdasarkan Tagihan -->
<div class="modal fade" id="ModalSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-list-check"></i> Daftar Siswa (Berdasarkan Data Tagihan)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12" id="title_data_siswa">
                        <!-- Title Data Siswa -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped border-1 border-top">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Nama Siswa</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>L/P</b></th>
                                        <th><b>Kelas (Aktual)</b></th>
                                        <th><b>Status</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswa">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <small>Tidak Ada Siswa Yang Terdaftar</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Daftar Siswa Berdasarkan Data Siswa Aktual -->
<div class="modal fade" id="ModalSiswaAktual" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-list-check"></i> Daftar Siswa (Berdasarkan Kelas Aktual)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12" id="title_siswa_aktual">
                        <!-- Menampilkan Title Siswa Aktual -->
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped border-1 border-top">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Nama Siswa</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>L/P</b></th>
                                        <th><b>Status</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswaAktual">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <small>Loading...</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Komponen Biaya Pendidikan -->
<div class="modal fade" id="ModalListKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list-check"></i> Komponen Biaya Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12" id="TitleListKomponenBiaya">
                        <!-- Title List Komponen Biaya Disini -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Biaya</b></th>
                                        <th><b>Kategori</b></th>
                                        <th><b>Bulan</b></th>
                                        <th><b>Tahun</b></th>
                                        <th><b>Nominal</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelKomponenBiaya">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <small>Tidak Ada Komponen Biaya Yang Ditampilkan</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footer_modal_list_komponen_biaya">
                <!-- <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalKomponenBiaya">
                    Atur Komponen <i class="bi bi-chevron-right"></i> 
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Untuk Mengelola Komponen Biaya (Insert-Delete) -->
<div class="modal fade" id="ModalKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list-check"></i> Atur Komponen Biaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" id="ProsesFilterKomponenBiaya">
                    <input type="hidden" name="page_komponen" id="page_komponen" value="1">
                    <input type="hidden" name="id_organization_class" id="put_id_organization_class" value="">
                    <input type="hidden" name="id_academic_period" id="put_id_academic_period" value="">
                </form>
                <div class="row mb-3">
                    <div class="col-12" id="title_komponen_biaya">
                        <!-- Title Komponen Biaya Pendidikan Ditampilkan Disini -->
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Nama Biaya</b></th>
                                        <th><b>Kategori</b></th>
                                        <th><b>Bulan</b></th>
                                        <th><b>Tahun</b></th>
                                        <th><b>Nominal</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelTambahKomponenBiaya">
                                    <!-- Tabel Komponen Biaya Ditampilkan Disini -->
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <small>Loading...</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded kembali_ke_list_komponen">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rekapitulasi Tagiihan Siswa -->
<div class="modal fade" id="ModalRekapTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExportRekapTagihanSiswa.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-table"></i> Rekapitulasi Tagihan - Pembayaran
                    </h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12" id="title_rekapitulasi_tagihan_siswa">
                            <!-- Title Rekapitulasi Tagihan Siswa Disini -->
                        </div>
                    </div>
                    <div class="row mb-3 border-1 border-top">
                        <div class="col-md-12 text-end mt-3 button_tambah_tagihan_per_siswa">
                            <!-- Menampilkan 'button_tambah_tagihan_per_siswa' disini -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table table-responsive">
                                <table class="table table-striped table-hover border-1 border-top">
                                    <thead>
                                        <tr>
                                            <td><b>No</b></td>
                                            <td><b>Siswa</b></td>
                                            <td><b>NIS</b></td>
                                            <td align="right"><b>Nominal</b></td>
                                            <td align="right"><b>Diskon</b></td>
                                            <td align="right"><b>Tagihan</b></td>
                                            <td align="right"><b>Pembayaran</b></td>
                                            <td align="right"><b>Sisa/Tunggakan</b></td>
                                            <td align="left"><b>Opsi</b></td>
                                        </tr>
                                    </thead>
                                    <tbody id="TabelRekapTagihanSiswa">
                                        <!-- <tr>
                                            <td colspan="7" class="text-center">
                                                <small class="text text-grayish">Loading...</small>
                                            </td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary btn-rounded">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Tagihan Per Siswa -->
<div class="modal fade" id="ModalTambahTagihanPerSiswa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahTagihanPerSiswa">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Tagihan Per Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormTambahTagihanPerSiswa">
                            <!-- Form Tambah Tagihan Siswa Akan Muncul Disini -->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiTambahTagihanPerSiswa">
                            <!-- Notifikasi Tambah Tagihan Per Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded tombol_kembali_ke_rekapitulasi_tagihan_1">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Tagihan Per Siswa -->
<div class="modal fade" id="ModalHapusTagihanPerSiswa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusTagihanPerSiswa">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Tagihan Per Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusTagihanPerSiswa">
                            <!-- Form Hapus Tagihan Per Siswa -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiHapusTagihanPerSiswa">
                            <!-- Notifikasi Hapus Tagihan Per Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check-circle"></i> Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded tombol_kembali_ke_rekapitulasi_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Rincian Tagihan Siswa -->
<div class="modal fade" id="ModalRincianTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExporterRincianTagihan.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-list-check"></i> Rincian Tagihan & Pembayaran Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-5"><small>Nama Siswa</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_student_name">-</small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><small>NIS</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_student_nis">-</small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><small>K.B.P</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_jumlah_kbp">-</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-5"><small>Periode Pendidikan</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_academic_period">-</small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><small>Jenjang/Level</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_class_level">-</small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><small>Kelas/Rombel</small></div>
                                <div class="col-1"><small>:</small></div>
                                <div class="col-6"><small class="text text-grayish" id="put_class_name">-</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 border-1 border-top">
                        <div class="col-12 text-end mt-3" id="put_button_tambah_rincian_tagihan_siswa">
                            <!-- Menampilkan Tombol Tambah Rincian Tagihan Siswa -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="table table-responsive">
                                <table class="table table-hover table-striped border-top border-1">
                                    <thead>
                                        <tr>
                                            <th><b>No</b></th>
                                            <th><b>K.B.P</b></th>
                                            <th><b>Nominal</b></th>
                                            <th><b>Diskon</b></th>
                                            <th><b>Tagihan</b></th>
                                            <th><b>Pembayaran</b></th>
                                            <th><b>Sisa/Tagihan</b></th>
                                            <th><b>Opsi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="TabelRincianTagihanSiswa">
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <small>Loading...</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary btn-rounded">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded tombol_kembali_ke_rekapitulasi_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Rincian Tagihan Siswa-->
<div class="modal fade" id="ModalTambahRincianTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahRincianTagihanSiswa">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Rincian Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormTambahRincianTagihanSiswa">
                            <!-- Forrm Tambah Rincian Tagihan Siswa -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiTambahRincianTagihanSiswa">
                            <!-- Notifikasi Tambah Rincian Tagihan Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_rincian_tagihan_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Rincian Tagihan Siswa-->
<div class="modal fade" id="ModalEditRincianTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
           <form action="javascript:void(0);" id="ProsesEditRincianTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-pencil"></i> Edit Rincian Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormEditRincianTagihan">
                            <!-- Form Edit Rincian Tagihan Siswa -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifiikasiEditRincianTagihan">
                            <!-- Notifikasi Edit Rincian Tagihan Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_rincian_tagihan_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
           </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Rincian Tagihan Siswa-->
<div class="modal fade" id="ModalHapusRincianTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
           <form action="javascript:void(0);" id="ProsesHapusRincianTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Rincian Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusRincianTagihan">
                            <!-- Form Hapus Rincian Tagihan Siswa -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiHapusRincianTagihan">
                            <!-- Notifikasi Hapus Rincian Tagihan Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_rincian_tagihan_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
           </form>
        </div>
    </div>
</div>

<!-- Modal Detail Tagihan Siswa -->
<div class="modal fade" id="ModalDetailTagihan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExporterDetailTagihan.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-receipt"></i> Detail Tagihan & Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="FormDetailTagihan">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            Loading...
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary btn-rounded">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_rincian_tagihan_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Pembayaran -->
<div class="modal fade" id="ModalTambahPembayaran" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahPembayaran">
                <input type="hidden" name="id_fee_by_student" id="put_id_fee_by_student_for_payment" value="">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Bayar Tagihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormTambahPembayaran">
                            <!-- Form Tambah Pembayaran -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="NotifikasiTambahPembayaran">
                            <!-- Notifikasi Edit Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_tambah_pembayaran">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_detail_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Hapus Pembayaran -->
<div class="modal fade" id="ModalHapusPembayaran" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusPembayaran">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormHapusPembayaran">
                            <!-- Form Hapus Pembayaran -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="NotifikasiHapusPembayaran">
                            <!-- Notifikasi Hapus Pembayaran Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_detail_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Tagihan Siswa (Aktual) -->
<div class="modal fade" id="ModalMatrixTagihan" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header nav_background">
                <h5 class="modal-title text-light">
                    <i class="bi bi-table"></i> Daftar Tagihan Siswa (Aktual)
                </h5>
                <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="TableMatrixTagihan">
                        <!-- Table Matrix Tagihan -->
                    </div>
                </div>
            </div>
            <div class="modal-footer nav_background">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tagihan Siswa (Multi) -->
<div class="modal fade" id="ModalTambahTagihanMulti" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahTagihanMulti" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Tagihan Siswa (Multiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormTambahTagihanMulti">
                            <!-- Form Tambah Tagihan -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiTambahTagihanMulti">
                            <!-- Notifikasi Tambah Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Tagihan Satu-Persatu Pada 'ModalMatrixTagihan' -->
<div class="modal fade" id="ModalTambahTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahTagihan" autocomplete="off">
                <input type="hidden" id="get_id_organization_class_for_back" value="">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormTambahTagihan">
                            <!-- Form Tambah Tagihan -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiTambahTagihan">
                            <!-- Notifikasi Tambah Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Hapus Tagihan Pada 'ModalMatrixTagihan' -->
<div class="modal fade" id="ModalHapusTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusTagihan" autocomplete="off">
                <input type="hidden" id="get_id_organization_class_for_back2" value="">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusTagihan">
                            <!-- Form Hapus Tagihan -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifiikasiHapusTagihan">
                            <!-- Notifikasi Hapus Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEdit" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEdit">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormEdit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="NotifikasiEdit">
                            <!-- Notifikasi Edit Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapus" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapus" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormsHapus">
                            <!-- Form Hapus Fitur -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasisHapus">
                            <!-- Notifikasi Hapus Fitur -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tidak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="ModalTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="btn btn-md btn-info btn-floating" id="show_filter_form_tagihan_siswa" title="Filter Tagihan Siswa">
                    <i class="bi bi-chevron-down"></i>
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-3 border-bottom" id="filter_form_tagihan_siswa" style="display:none;">
                <form action="javascript:void(0);" id="ProsesPencarianTagihanSiswa">
                    <input type="hidden" name="page" id="put_page_for_tagihan_siswa" value="1">
                    <input type="hidden" name="id_organization_class" id="put_id_organization_class_for_tagihan_siswa">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <label for="limit_tagihan_siswa"><small>Limit/Batas</small></label>
                            <select name="batas" id="limit_tagihan_siswa" class="form-control">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="list_fee_component">
                                <small>Komponen Biaya</small>
                            </label>
                            <select name="id_fee_component" id="list_fee_component" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="list_siswa">
                                <small>Nama Siswa</small>
                            </label>
                            <select name="id_student" id="list_siswa" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <br>
                            <button type="submit" class="btn btn-md btn-primary btn-block">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-12 text-center mb-3" id="title_tagihan_siswa"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="javascript:void(0);" id="ProsesTabelTagihanSiswa">
                            <div class="table table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="check_all" class="form-check-input" value="check_all"></th>
                                            <th><b>No</b></th>
                                            <th><b>Siswa</b></th>
                                            <th><b>Komponen</b></th>
                                            <th><b>Nominal</b></th>
                                            <th><b>Potongan</b></th>
                                            <th><b>Jumlah</b></th>
                                            <th><b>Pembayaran</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="TabelTagihanSiswa">
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <small>No Data</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-3 border-top" id="konfirmasi_proses_multiple" style="display:none;">
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-3">
                        <button type="button" class="btn btn-md btn-outline-primary btn-rounded"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i> Option
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditTagihanSiswaMultiple">
                                    <i class="bi bi-pencil"></i> Ubah Tagihan
                                </a>
                                 <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihanSiswaMultiple">
                                    <i class="bi bi-x-circle"></i> Hapus Tagihan
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-9 text-end">
                        <button type="button" class="btn btn-md btn-primary btn-floating" id="prev_button_tagihan_siswa">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" disabled class="btn btn-md btn-primary btn-rounded" id="page_info_tagihan_siswa">
                            0 / 0
                        </button>
                        <button type="button" class="btn btn-md btn-primary btn-floating" id="next_button_tagihan_siswa">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-primary">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditTagihanSiswaMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditTagihanSiswaMultiple">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-pencil"></i> Edit Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormEditTagihanSiswaMultiple">
                            <!-- Form Hapus Tagihan -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiEditTagihanSiswaMultiple">
                            <!-- Notifikasi Hapus Tagihan Multi -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" disabled class="btn btn-danger btn-rounded" id="konfirmasi_edit_tagihan_siswa_multiple">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded Kembali_ke_tagihan_multi">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapusTagihanSiswaMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-tarsh"></i> Hapus Tagihan Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormHapusTagihanSiswaMultiple">
                        <!-- Form Hapus Tagihan -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="NotifikasiHapusTagihanSiswaMultiple">
                        <!-- Notifikasi Hapus Tagihan Multi -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" disabled class="btn btn-danger btn-rounded" id="konfirmasi_hapus_tagihan_siswa_multiple">
                    <i class="bi bi-trash"></i> Hapus
                </button>
                <button type="button" class="btn btn-secondary btn-rounded Kembali_ke_tagihan_multi">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>