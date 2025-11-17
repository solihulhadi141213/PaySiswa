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

<!-- Copy Komponen Biaya -->
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
                    <button type="submit" class="btn btn-primary btn-rounded" id="TombolCopy">
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

<!-- //Download Export Komponen Biaya -->
<div class="modal fade" id="ModalExport" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-download"></i> Export Komponen Biaya
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12" id="FormExport">
                        <!-- Form Export Kelas -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambah Komponen Biaya -->
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
                                <small>Nominal Biaya <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
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
                    <button type="submit" class="btn btn-primary btn-rounded" id="TombolSimpan">
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

<!-- Modal Edit Kategori Multiple -->
 <div class="modal fade" id="ModalEditKategoriMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditKategoriMultiple">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-tag"></i> Edit Kategori (Multiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormEditKategoriMultiple">
                            <!-- Form Edit Kategori Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiEditKategoriMultiple">
                            <!-- Notifikasi Edit Kategori -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_edit_kategori_multiple">
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

<!-- Modal Edit Tahun Multiple -->
 <div class="modal fade" id="ModalEditTahunMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditTahunMultiple">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-calendar"></i> Edit Tahun (Multiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormEditTahunMultiple">
                            <!-- Form Edit Tahun Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiEditTahunMultiple">
                            <!-- Notifikasi Edit Tahun -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_edit_tahun_multiple">
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

<!-- Modal Edit Tarif Multiple -->
 <div class="modal fade" id="ModalEditTarifMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditTarifMultiple">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-calendar"></i> Edit Tarif (Multiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormEditTarifMultiple">
                            <!-- Form Edit Tarif Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiEditTarifMultiple">
                            <!-- Notifikasi Edit Tarif -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_edit_tarif_multiple">
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

<!-- Modal Hapus Multiple -->
 <div class="modal fade" id="ModalHapusMultiple" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusMultiple">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus (Multiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusMultiple">
                            <!-- Form Hapus Disini -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiHapusMultiple">
                            <!-- Notifikasi Hapus -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_hapus_multiple">
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

<!-- Modal Edit Kategori Parsial -->
 <div class="modal fade" id="ModalEditParsial" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditParsial">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="title_modal_edit_parsial">
                        <i class="bi bi-pencil-square"></i> Edit Komponen (Parsial)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormEditParsial">
                            <!-- Form Edit Kategori Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiEditParsial">
                            <!-- Notifikasi Edit Kategori -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_edit_parsial">
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

<!-- Modal Rekap Tagihan -->
 <div class="modal fade" id="ModalRekapTagihan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExportKomponenBiayaKelas.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-table"></i> Rekapitulasi Tagihan Kelas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="title_rekap_tagihan">
                            <!-- Form Edit Kategori Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="table table-responsive border-1 border-top">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <td><small><b>No</b></small></td>
                                            <td colspan="2"><small><b>Jenjang/Kelas</b></small></td>
                                            <td class="text-end"><small><b>Nominal</b></small></td>
                                            <td class="text-end"><small><b>Diskon</b></small></td>
                                            <td class="text-end"><small><b>Tagihan</b></small></td>
                                            <td class="text-end"><small><b>Pembayaran</b></small></td>
                                            <td class="text-end"><small><b>Sisa/Tunggakan</b></small></td>
                                            <td class="text-end"><small><b>Opsi</b></small></td>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel_rekap_tagihan">
                                        <tr>
                                            <td colspan="9" class="text-center"><small>Loading..</small></td>
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
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tagihan Siswa -->
 <div class="modal fade" id="ModalTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExportKomponenBiayaSiswa.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-table"></i> Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="title_tagihan_siswa">
                            <!-- Form Edit Kategori Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="table table-responsive border-1 border-top">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <td align="center"><small><b>No</b></small></td>
                                            <td><small><b>Nama Siswa</b></small></td>
                                            <td><small><b>NIS</b></small></td>
                                            <td class="text-end"><small><b>Nominal</b></small></td>
                                            <td class="text-end"><small><b>Diskon</b></small></td>
                                            <td class="text-end"><small><b>Tagihan</b></small></td>
                                            <td class="text-end"><small><b>Pembayaran</b></small></td>
                                            <td class="text-end"><small><b>Sisa/Tunggakan</b></small></td>
                                            <td class="text-end"><small><b>Opsi</b></small></td>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel_tagihan_siswa">
                                        <tr>
                                            <td colspan="9" class="text-center"><small>Loading..</small></td>
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
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_rekap_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Tagihan -->
 <div class="modal fade" id="ModalDetailTagihan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Exporter/ExporterDetailTagihan.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-receipt"></i> Detail Tagihan Dan Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormDetailTagihanSiswa">
                            <!-- Form Detail Tagihan Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary btn-rounded">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_tagihan_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


