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
                                <small>Limit/Batas</small>
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
                                <option value="student_name">Nama</option>
                                <option value="student_nis">NIS</option>
                                <option value="id_organization_class">Kelas</option>
                                <option value="student_gender">Gender</option>
                                <option value="student_registered">Tgl.Daftar</option>
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
                            <select name="keyword_by" id="KeywordBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="student_name">Nama</option>
                                <option value="student_nis">NIS</option>
                                <option value="id_organization_class">Kelas</option>
                                <option value="student_gender">Gender</option>
                                <option value="student_registered">Tgl.Daftar</option>
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
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="kelompok_status_siswa">
                                <small>Status Siswa</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="kelompok_status_siswa" id="kelompok_status_siswa" class="form-control">
                                <option value="">Semua</option>
                                <option selected value="Terdaftar">Terdaftar</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Filter
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambah">
                <div class="modal-header">
                    <h5 class="modal-title text-dak"><i class="bi bi-plus"></i> Tambah Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_nis">
                                <small>NIS <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="student_nis" id="student_nis" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_nisn">
                                <small>NISN</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="student_nisn" id="student_nisn" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_name">
                                <small>Nama <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="student_name" id="student_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_gender">
                                <small>Gender <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="student_gender" id="student_gender" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="Male">Laki-laki</option>
                                <option value="Female">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="place_of_birth">
                                <small>Tempat Lahir</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="date_of_birth">
                                <small>Tanggal Lahir</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_contact">
                                <small>No.Kontak</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="student_contact" id="student_contact" class="form-control" placeholder="+62">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_email">
                                <small>Email</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="email" name="student_email" id="student_email" class="form-control" placeholder="alamat_email@domain.com">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_address">
                                <small>Alamat</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <textarea name="student_address" id="student_address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="nama_ortu">
                                <small>Nama Orang Tua/Wali</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="nama_ortu" id="nama_ortu" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="kontak_ortu">
                                <small>Kontak Orang Tua/Wali</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="kontak_ortu" id="kontak_ortu" class="form-control" placeholder="+62">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_foto">
                                <small>Foto</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="file" name="student_foto" id="student_foto" class="form-control">
                            <small>
                                <small class="text text-grayish">Maximum 2 Mb (File Type: PNG, JPG, GIF)</small>
                            </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_registered">
                                <small>Tanggal Masuk <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="date" name="student_registered" id="student_registered" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="student_status">
                                <small>Status <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select name="student_status" id="student_status" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="Terdaftar">Terdaftar</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12" id="NotifikasiTambah">
                            <!-- Notifikasi Tambah Akses Akan Muncul Disini -->
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
            <form action="index.php" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Detail Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormDetail">
                            <!-- Form Detail Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-rounded">
                        <i class="bi bi-three-dots"></i> Lihat Selengkapnya
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEdit">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormEdit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="NotifikasiEdit">
                            <!-- Notifikasi Edit Siswa Akan Muncul Disini -->
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
            <form action="javascript:void(0);" id="ProsesHapus">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapus">
                            <!-- Form Hapus Disini -->
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

<div class="modal fade" id="ModalUpdateStatus" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesUpdateStatus">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-tags"></i> Update Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormUpdateStatus">
                            <!-- Form Form Status Disini -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiUpdateStatus">
                            <!-- Notifikasi Update Status -->
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

<div class="modal fade" id="ModalUpdateKelas" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesUpdateKelas">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-building"></i> Update Kelas Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormUpdateKelas">
                            <!-- Form Update Kelas Disini -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiUpdateKelas">
                            <!-- Notifikasi Update Kelas -->
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

<div class="modal fade" id="ModalImport" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-upload"></i> Import Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <b>Petunjuk Penggunaan</b><br>
                            <small>
                                <ol>
                                    <li>Unduh <strong>Template</strong> yang telah disediakan agar format kolom sesuai dengan sistem.</li>
                                    <li>Jangan mengubah <em>urutan</em> atau <em>nama kolom</em> pada template. Perubahan menyebabkan kegagalan proses import.</li>
                                    <li>Isi data pada file Excel sesuai ketentuan kolom di bawah ini (contoh format diberikan).</li>
                                    <li>NIS harus unik — jika NIS duplikat, baris akan dianggap error.</li>
                                    <li>Lihat halaman kelas untuk mengetahui ID kelas.</li>
                                    <li>Format tanggal harus <code>YYYY-MM-DD</code>. Contoh: <code>2025-09-05</code>.</li>
                                    <li>Gunakan hanya nilai <code>Male</code> atau <code>Female</code> pada kolom Jenis Kelamin.</li>
                                    <li>Status siswa terdiri dari <code>Terdaftar, Lulus dan Keluar</code></li>
                                    <li>Simpan file dan unggah melalui tombol <strong>Pilih File Excel</strong>, lalu klik <strong>Mulai Import</strong>.</li>
                                    <li>Sistem akan melakukan validasi otomatis dan menampilkan hasil (baris valid / baris error).</li>
                                </ol>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <form id="ProsesImportSiswa" action="javascript:void(0);">
                            <div class="input-group">
                                <input type="file" name="data_siswa" class="form-control" accept=".xlsx,.xls">
                                <a href="_Page/Siswa/template-siswa.xlsx" class="btn btn-md btn-info" role="button" aria-label="Unduh Template Excel">
                                    <i class="bi bi-download"></i> Template
                                </a>
                                <button type="submit" class="btn btn-md btn-primary">
                                    <i class="bi bi-upload"></i> Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>Baris</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>Nama</b></th>
                                        <th><b>Gender</b></th>
                                        <th><b>Keterangan</b></th>
                                    </tr>
                                </thead>
                                <tbody id="NotifikasiImport">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <small class="text-danger">Belum Ada Proses Import</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" disabled class="btn btn-warning btn-rounded" id="ResetFormImport">
                    <i class="bi bi-arrow-repeat"></i> Reset Form
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalExportTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="_Page/Siswa/ProsesExportTagihanSiswa.php" target="_blank" method="GET">
                <input type="hidden" name="id" id="put_id_siswa_export_tagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-download"></i> Export Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            Pilih Format Data :
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_file" id="tipe_file_pdf" value="PDF" checked>
                                <label class="form-check-label" for="tipe_file_pdf">
                                    <small>PDF</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe_file" id="tipe_file_html" value="HTML">
                                <label class="form-check-label" for="tipe_file_html">
                                    <small>HTML</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
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