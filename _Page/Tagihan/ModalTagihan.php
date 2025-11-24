<!-- Modal Export Tagihan -->
 <div class="modal fade" id="ModalExportTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="_Page/Tagihan/ProsesExportTagihan.php" method="POST" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-download"></i> Export Tagihan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormExportTagihan">
                            <!-- Form Export Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" disabled class="btn btn-primary btn-rounded" id="ButtonExportTagihan">
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

<!-- Modal Filter Tagihan -->
<div class="modal fade" id="ModalFilterTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilterTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-filter-circle"></i> Ubah Periode Akademik Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="IdPeriodeAkademik">
                                <small>Periode Akademik</small>
                            </label>
                            <select name="id_academic_period" id="IdPeriodeAkademik" class="form-control">
                                <?php
                                    //Menampilkan periode akademik
                                    $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period, academic_period_start FROM academic_period ORDER BY academic_period_start ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_academic_period = $data['id_academic_period'];
                                        $academic_period= $data['academic_period'];
                                        $academic_period_start= $data['academic_period_start'];
                                        echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <div class="col-md-12 mb-3">
                            <small>
                                Silahkan pilih kelas / rombel berikut ini.
                            </small>
                        </div>
                        <div class="col-md-12 mb-3" style="overflow-y:auto; height:300px; border: 1px solid #999;">
                            <div class="table table-responsive">
                                <table class="table table-hover bg-secondary">
                                    <thead>
                                        <tr>
                                            <td><small><b><i class="bi bi-check-circle"></i></b></small></td>
                                            <td><small><b>Level/Jenjang</b></small></td>
                                            <td><small><b>Rombel</b></small></td>
                                            <td><small><b>Siswa</b></small></td>
                                        </tr>
                                    </thead>
                                    <tbody id="TabelKelas">
                                        <tr>
                                            <td colspan="4">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Siswa -->
<div class="modal fade" id="ModalPilihSiswa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-check-circle"></i> Pilih Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6"></div>
                    <div class="col-md-12 mb-3">
                        <form action="javascript:void(0);" id="FilterSiswa">
                            <input type="hidden" name="page" id="page_siswa" value="1">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Nama/NIS">
                                <button type="submit" class="btn btn-md btn-secondary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12" style="overflow-y:auto; height:400px;">
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <td><small><b>No</b></small></td>
                                        <td><small><b>NIS</b></small></td>
                                        <td><small><b>Nama Siswa</b></small></td>
                                        <td><small><b>Opsi</b></small></td>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswa">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <small>Loading...</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <small id="page_info_siswa">Page 0 Of 0</small>
                    </div>
                    <div class="col-6 text-end">
                       <button type="button" class="btn btn-md btn-outline-info btn-floating" id="prev_button_siswa">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-md btn-outline-info btn-floating" id="next_button_siswa">
                            <i class="bi bi-chevron-right"></i>
                        </button>
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

<!-- Modal Tambah Tagihan -->
<div class="modal fade" id="ModalTambahTagihan" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Tagihan Parsial
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormTambahTagihan">
                            <!-- Form Tambah Tagihan -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiTambahTagihan">
                            <!-- Notifikasi Tambah Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded modal_pilih_siswa">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDetailSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
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
                        <div class="col-md-12" id="FormDetailSiswa">
                            <!-- Form Detail Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-three-dots"></i> Selengkapnya
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTagihanSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="_Page/Siswa/ProsesExportTagihanSiswa.php" method="GET" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-list-check"></i> Daftar Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormTagihanSiswa">
                            <!-- Form Tagihan Siswa -->
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

<div class="modal fade" id="ModalRiwayatPembayaranSiswa" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-clock-history"></i> Riwayat Pembayaran Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FromRiwayatPembayaranSiswa">
                        <!-- Form Riwayat Pembayaran Siswa -->
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

<!-- Modal Ubah Tagihan Siswa -->
<div class="modal fade" id="ModalUbahTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesUbahTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-pencil"></i> Ubah Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormUbahTagihan">
                            <!-- Form Ubah Tagihan Siswa -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiUbahTagihan">
                            <!-- Notifikasi Ubah Tagihan Siswa -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Hapus tagihan Siswa -->
<div class="modal fade" id="ModalHapusTagihan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Tagihan Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormHapusTagihan">
                            <!-- Form Hapus Tagihan -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiHapusTagihan">
                            <!-- Notifikasi Hapus Tagihan -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check-circle"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_tagihan">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Riwayat Pembayaran -->
<div class="modal fade" id="ModalRiwayatPembayaran" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormRiwayatPembayaran">
                        <!-- Form Riwayat Pembayaran -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-rounded">
                    <i class="bi bi-download"></i> Export
                </button>
                <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_tagihan">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pembayaran (ModalBayar) -->
<div class="modal fade" id="ModalBayar" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahPembayaran">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Tambah Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12" id="FormBayar">
                            <!-- Form Tambah Pembayaran -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="NotifikasiBayar">
                            <!-- Notifikasi Tambah Pembayaran -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded" id="button_tambah_pembayaran">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_riwayat_pembayaran">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="ModalDetailPembayaran" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-info"></i> Detail Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormDetailPembayaran">
                        <!-- Form Detail Pembayaran -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-rounded">
                    <i class="bi bi-download"></i> Export
                </button>
                <button type="button" class="btn btn-secondary btn-rounded kembali_ke_riwayat_pembayaran">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Pembayaran -->
<div class="modal fade" id="ModalHapusPembayaran" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusPembayaran">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormHapusPembayaran">
                            <!-- Form Detail Pembayaran -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiHapusPembayaran">
                            <!-- Notifikasi Hapus Pembayaran -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check-circle"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_riwayat_pembayaran">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
