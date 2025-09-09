<div class="modal fade" id="ModalTambah" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambah">
                <input type="hidden" name="id_academic_period" id="id_academic_period_tambah">
                <div class="modal-header">
                    <h5 class="modal-title text-dak"><i class="bi bi-plus"></i> Tambah Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="component_name">
                                <small>Biaya Pendidikan <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                            <input type="text" name="component_name" id="component_name" class="form-control" required>
                            <small class="text text-grayish">Contoh : SPP Januari</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="component_category">
                                <small>Kategori <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                            <select class="form-control" name="component_category" id="component_category">
                                <option value="">Pilih</option>
                                <option value="SPP">SPP</option>
                                <option value="Non-SPP">Non-SPP</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="periode_month">
                                <small>Periode Bulan <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                            <select class="form-control" name="periode_month" id="periode_month">
                                <option value="">Pilih</option>
                                <?php
                                    $nama_bulan = [
                                        1 => "Januari",
                                        2 => "Februari",
                                        3 => "Maret",
                                        4 => "April",
                                        5 => "Mei",
                                        6 => "Juni",
                                        7 => "Juli",
                                        8 => "Agustus",
                                        9 => "September",
                                        10 => "Oktober",
                                        11 => "November",
                                        12 => "Desember"
                                    ];
                                    foreach ($nama_bulan as $key => $val) {
                                        echo '<option value="'.$key.'">'.$val.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="periode_year">
                                <small>Periode Tahun <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                            <input type="number" min="1" name="periode_year" id="periode_year" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="periode_start">
                                <small>Tempo Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="date" name="periode_start" id="periode_start" class="form-control" required>
                            <small class="text text-grayish">Awal</small>
                        </div>
                        <div class="col-6">
                            <input type="date" name="periode_end" id="periode_end" class="form-control" required>
                            <small class="text text-grayish">Akhir</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="fee_nominal">
                                <small>Nominal Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                            </label>
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
    <div class="modal-dialog modal-md">
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

<div class="modal fade" id="ModalCopy" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesCopy">
                <input type="hidden" name="periode_tujuan" id="periode_tujuan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Copy Biaya Pendidikan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="periode_asal">
                                <small>Perode Akademik (Sumber)</small>
                            </label>
                            <select name="periode_asal" id="periode_asal" class="form-control">
                                <option value="">Pilih</option>
                                <?php
                                    //Menampilkan Tahun Akademik
                                    $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period  ORDER BY academic_period_start DESC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_academic_period = $data['id_academic_period'];
                                        $academic_period= $data['academic_period'];
                                        echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiCopy">
                            <!-- Notifikasi Copy -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded" id="TombolCopy">
                        <i class="bi bi-copy"></i> Copy
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

