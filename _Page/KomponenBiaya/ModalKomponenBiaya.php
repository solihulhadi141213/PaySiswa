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
                                <option value="component_name">Nama Komponen</option>
                                <option value="component_category">Kategori</option>
                                <option value="periode_start">Awal Tempo</option>
                                <option value="periode_end">Akhir Tempo</option>
                                <option value="fee_nominal">Nominal</option>
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
                                <option value="component_name">Nama Komponen</option>
                                <option value="component_category">Kategori</option>
                                <option value="periode_start">Awal Tempo</option>
                                <option value="periode_end">Akhir Tempo</option>
                                <option value="fee_nominal">Nominal</option>
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
            <form action="javascript:void(0);" id="ProsesTambah" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dak"><i class="bi bi-plus"></i> Tambah Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="component_category">
                                <small>Kategori <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="component_category" id="component_category" list="component_category_list" class="form-control">
                            <small class="text text-grayish">Contoh : SPP Kelas 4-6 (2024/2025)</small>
                            <datalist id="component_category_list">
                                <!-- Menampilkan Kategori Komponen -->
                            </datalist>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="component_name">
                                <small>Nama Komponen <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="component_name" id="component_name" class="form-control" required>
                            <small class="text text-grayish">Contoh : SPP Januari</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="periode_start">
                                <small>Periode Awal <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="date" name="periode_start" id="periode_start" class="form-control" required>
                            <small class="text text-grayish">Waktu pembayaran berlaku</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="periode_end">
                                <small>Periode Akhir <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="date" name="periode_end" id="periode_end" class="form-control" required>
                            <small class="text text-grayish">Waktu pembayaran berakhir</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="fee_nominal">
                                <small>Nominal Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="fee_nominal" id="fee_nominal" class="form-control form-money">
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
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-info-circle"></i> Detail Komponen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormDetail">
                        <!-- Form Detail Komponen -->
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEdit" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit Komponen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="FormEdit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="NotifikasiEdit">
                            <!-- Notifikasi Edit Komponen Akan Muncul Disini -->
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
                        <i class="bi bi-trash"></i> Hapus Komponen
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
