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
                    <button type="submit" class="btn btn-success btn-rounded">
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Daftar Kelas (Rombongan Belajar)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-12 text-center">
                        <b id="title_daftar_kelas" class="text text-decoration-underline">DAFTAR KELAS PERIODE</b>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Kelas</b></th>
                                        <th><b>Siswa</b></th>
                                        <th><b>Komponen Biaya</b></th>
                                        <th><b>Tagihan Siswa</b></th>
                                        <th><b>Pembayaran</b></th>
                                        <th><b>Sisa Tunggakan</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelDaftarKelas">
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
                        <span>DAFTAR SISWA</span><br>
                        <b id="title_daftar_Siswa_per_kelas_2" class="text text-decoration-underline"></b> / <b class="text text-decoration-underline" id="title_daftar_Siswa_per_kelas_1"></b> 
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover border-top">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center" valign="middle"><b>No</b></th>
                                        <th rowspan="2" class="text-center" valign="middle"><b>Kelas</b></th>
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
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
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
                <button type="button" class="btn btn-secondary btn-rounded" id="button_kembali" data-bs-toggle="modal" data-bs-target="#ModalSiswaPerKelas" data-id="">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Komponen Biaya Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <b>DAFTAR KOMPONEN BIAYA</b><br>
                        <b id="title_komponen_biaya" class="text text-decoration-underline"></b>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Biaya Pendidikan</b></th>
                                        <th><b>Kategori</b></th>
                                        <th><b>Bulan</b></th>
                                        <th><b>Tahun</b></th>
                                        <th><b>Nominal</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelKomponenBiaya">
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
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Tagihan & Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <b>DAFTAR TAGIHAN & PEMBAYARAN</b><br>
                        <b id="title_tagihan_biaya_pendidikan" class="text text-decoration-underline"></b>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover border-top">
                                <thead>
                                    <tr>
                                        <td align="center"><b>No</b></th>
                                        <td align="left"><b>Kelas</b></th>
                                        <td align="right">
                                            <b>
                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Komponen Biaya Pendidikan">KBP</a>
                                            </b>
                                        </th>
                                        <td align="right"><b>Tagihan</b></th>
                                        <td align="right"><b>Diskon</b></th>
                                        <td align="right"><b>Pembayaran</b></th>
                                        <td align="right"><b>Sisa</b></th>
                                        <td align="center"><b>opsi</b></th>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" id="ExportTagihanSiswaPdf" target="_blank">
                            <i class="bi bi-file-pdf"></i> Export To PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" id="ExportTagihanSiswaHtml" target="_blank">
                            <i class="bi bi-filetype-html"></i> Export To HTML
                        </a>
                    </li>
                </ul>
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
                    <div class="col-12 text-center">
                        <b>DAFTAR TAGIHAN BIAYA PENDIDIKAN</b><br>
                        <b id="title_riincian_tagihan_biaya_pendidikan" class="text text-decoration-underline"></b>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12" id="TabelRincianTagihanSiswa">
                        <!-- Menampilkan Rincian Tagihan SIswa  -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" id="ExportRincianTagihanSiswaPdf" target="_blank">
                            <i class="bi bi-file-pdf"></i> Export To PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" id="ExportRincianTagihanSiswaHtml" target="_blank">
                            <i class="bi bi-filetype-html"></i> Export To HTML
                        </a>
                    </li>
                </ul>
                <button type="button" class="btn btn-secondary btn-rounded" id="button_kembali_ke_tagihan_siswa" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalRiwayatPembayaran" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list"></i> Riwayat Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <b>RIWAYAT PEMBAYARAN</b><br>
                        <b id="title_riwayat_pembayaran" class="text text-decoration-underline"></b>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover border-top">
                                <thead>
                                    <tr>
                                        <td align="center"><b>No</b></th>
                                        <td align="left"><b>Siswa</b></th>
                                        <td align="left"><b>NIS</b></th>
                                        <td align="left"><b>Kelas</b></th>
                                        <td align="left"><b>Komponen</b></th>
                                        <td align="right"><b>Tgl.Bayar</b></th>
                                        <td align="right"><b>Nominal</b></th>
                                        <td align="center"><b>Metode</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelRiwayatPembayaran">
                                    <tr>
                                        <td colspan="8" class="text-center">
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


