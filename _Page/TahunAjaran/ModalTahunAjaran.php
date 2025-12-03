<!-- Filter Data -->
<div class="modal fade" id="ModalExportTahunAjaran" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="_Page/Exporter/ExportTahunAjaran.php" method="GET" target="_blank">
                <input type="hidden" name="page" id="page" value="1">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-download"></i> Cetak/Export Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <div class="alert alert-info">
                                <small>
                                    <b><i class="bi bi-info-circle"></i> Penting</b><br>
                                    Semakin besar data, maka akan membutuhkan waktu yang lebih lama untuk menampilkan output data ini.

                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-download"></i> Cetak / Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Data -->
<div class="modal fade" id="ModalFilter" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilter">
                <input type="hidden" name="page" id="page" value="1">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-funnel"></i> Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="batas">
                                <small>Limit</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="batas" id="batas" class="form-control">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="OrderBy">
                                <small>Dasar Urutan</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="academic_period">Tahun Akademik</option>
                                <option value="academic_period_start">Tanggal Mulai</option>
                                <option value="academic_period_end">Tanggal Berakhir</option>
                                <option value="academic_period_status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="ShortBy">
                                <small>Tipe Urutan</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="ASC">A To Z</option>
                                <option selected value="DESC">Z To A</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="KeywordBy">
                                <small>Dasar Pencarian</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="KeywordBy" id="KeywordBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="academic_period">Tahun Akademik</option>
                                <option value="academic_period_start">Tanggal Mulai</option>
                                <option value="academic_period_end">Tanggal Berakhir</option>
                                <option value="academic_period_status">Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="keyword">
                                <small>Kata Kunci</small>
                            </label>
                        </div>
                        <div class="col-8" id="FormFilter">
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambah Periode Pendidikan -->
<div class="modal fade" id="ModalTambah" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambah" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="academic_period">
                                <small>Tahun Akademik</small>
                            </label>
                            <input type="text" class="form-control" name="academic_period" id="academic_period" plceholder="Contoh: Periode 2025/2026" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="academic_period_start">
                                <small>Tanggal Mulai</small>
                            </label>
                            <input type="date" class="form-control" name="academic_period_start" id="academic_period_start" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="academic_period_end">
                                <small>Tanggal Berakhir</small>
                            </label>
                            <input type="date" class="form-control" name="academic_period_end" id="academic_period_end" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="NotifikasiTambah">
                            <!-- Notifikasi Proses Akan Muncul Disini -->
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

<!-- Modal Detail Periode Pendidikan -->
<div class="modal fade" id="ModalDetail" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-info-circle"></i> Detail Tahun Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormDetail">
                        <!-- Form Detail Tahun Akademik -->
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

<div class="modal fade" id="ModalDaftarKelas" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Daftar Kelas (Rombongan Belajar)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12 text-center" id="title_daftar_kelas">
                        <!-- Menampilkan Titla Daftar Kelas -->
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive border-top border-1">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Jenjang / Level</b></th>
                                        <th><b>Kelas / Rombel</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelDaftarKelas">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <small>No Data</small>
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
<div class="modal fade" id="ModalSiswaPerKelas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Daftar Siswa / Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <b>DAFTAR SISWA PER KELAS</b><br>
                        <span id="title_siswa_per_kelas"></span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-hover border-top">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-left" valign="middle"><b>No</b></th>
                                        <th rowspan="2" class="text-left" valign="middle"><b>Jenjang</b></th>
                                        <th rowspan="2" class="text-left" valign="middle"><b>Kelas</b></th>
                                        <th colspan="3" class="text-center" valign="middle"><b>Jumlah Siswa</b></th>
                                        <th rowspan="2" class="text-center" valign="middle"><b>Opsi</b></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" valign="middle"><b>L</b></th>
                                        <th class="text-center" valign="middle"><b>P</b></th>
                                        <th class="text-center" valign="middle"><b>&#8721;</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswaPerKelas">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <small>No Data</small>
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

<!-- Modal Daftar Siswa  -->
<div class="modal fade" id="ModalDaftarSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Daftar Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <b id="title_daftar_Siswa_1"></b><br>
                        <b id="title_daftar_Siswa_2" class="text text-decoration-underline"></b><br>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive border-top">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Nama</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>Gender</b></th>
                                        <th><b>Tgl.Daftar</b></th>
                                        <th><b>Status</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelDaftarSiswa">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <small>No Data</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded button_kembali_ke_daftar_siswa_perkelas">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Komponen Biaya -->
<div class="modal fade" id="ModalKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Komponen Biaya Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center" id="title_komponen_biaya">
                        <!-- Menampilkan Title Komponen Biaya -->
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><small><b>No</b></small></th>
                                        <th><small><b>Komponen Biaya</b></small></th>
                                        <th><small><b>Kategori</b></small></th>
                                        <th><small><b>Periode</b></small></th>
                                        <th><small><b>Nominal</b></small></th>
                                        <th><small><b>Biaya Pendidikan</b></small></th>
                                        <th><small><b>Diskon</b></small></th>
                                        <th><small><b>Tagihan</b></small></th>
                                        <th><small><b>Pembayaran</b></small></th>
                                        <th><small><b>Sisa/Tunggakan</b></small></th>
                                        <th><small><b>Opsi</b></small></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelKomponenBiaya">
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <small>No Data</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary btn-rounded" target="_blank" id="export_komponen_biaya">
                    <i class="bi bi-download"></i> Cetak / Export
                </a>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rincian Komponen Biaya -->
<div class="modal fade" id="ModalRincianKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Rincian Komponen Biaya Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12" id="title_rincian_komponen_biaya">
                        <!-- Menampilkan Title Rincian Komponen Biaya -->
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><small><b>No</b></small></th>
                                        <th><small><b>Nama Siswa</b></small></th>
                                        <th><small><b>NIS</b></small></th>
                                        <th><small><b>Jenjang / Level</b></small></th>
                                        <th><small><b>Rombel / Kelas</b></small></th>
                                        <th><small><b>Biaya Pendidikan</b></small></th>
                                        <th><small><b>Diskon</b></small></th>
                                        <th><small><b>Tagihan</b></small></th>
                                        <th><small><b>Pembayaran</b></small></th>
                                        <th><small><b>Sisa / Tunggakan</b></small></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelRincianKomponenBiaya">
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <small>No Data</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary btn-rounded" id="export_rincian_komponen_biaya" target="_blank">
                    <i class="bi bi-download"></i> Cetak / Export
                </a>
                <button type="button" class="btn btn-secondary btn-rounded kembali_ke_komponen_biaya">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tagihan Siswa -->
<div class="modal fade" id="ModalTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Tagihan & Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center" id="title_tagihan_siswa">
                        <!-- Title Tagihan Siswa -->
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover border-top">
                                <thead>
                                    <tr>
                                        <td align="center"><small><b>No</b></small></th>
                                        <td align="left"><small><b>Jenjang/Level</b></small></th>
                                        <td align="left"><small><b>Rombel/Kelas</b></small></th>
                                        <td align="left"><small><b>Siswa</b></small></th>
                                        <td align="left">
                                            <small>
                                                <b data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah Komponen Biaya Pendidikan">
                                                    K.B.P
                                                </b>
                                            </small>
                                        </th>
                                        <td align="right"><small><b>Biaya Pendidikan</b></small></th>
                                        <td align="right"><small><b>Diskon/Potongan</b></small></th>
                                        <td align="right"><small><b>Tagihan</b></small></th>
                                        <td align="right"><small><b>Pembayaran</b></small></th>
                                        <td align="right"><small><b>Sisa/Tunggakan</b></small></th>
                                        <td align="center"><small><b>opsi</b></small></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelTagihanSiswa">
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <small>No Data</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-md btn-primary btn-rounded" id="export_tagihan_siswa" target="_blank">
                    <i class="bi bi-download"></i> Cetak / Export
                </a>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalRincianTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Rincian Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center" id="title_riincian_tagihan_biaya_pendidikan">
                        <!-- Title Rincian Tagihan Biaya Pendidikan Disini -->
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12" id="TabelRincianTagihanSiswa">
                        <!-- Menampilkan Rincian Tagihan SIswa  -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary btn-rounded" id="export_rincian_tagihan_siswa" target="_blank">
                    <i class="bi bi-download"></i> Cetak / Export
                </a>
                <button type="button" class="btn btn-secondary btn-rounded kembali_ke_tagihan_siswa">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ModalEdit" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEdit" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormEdit">
                            <!-- Form Edit -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiEdit">
                            <!-- Notifikasi Edit -->
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
<div class="modal fade" id="ModalHapus" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapus" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Fitur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapus">
                            <!-- Form Hapus -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiHapus">
                            <!-- Notifikasi Hapus -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
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
<div class="modal fade" id="ModalKunci" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesKunci" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-repeat"></i> Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormKunci">
                            <!-- Form Kunci -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiKunci">
                            <!-- Notifikasi Kunci -->
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


