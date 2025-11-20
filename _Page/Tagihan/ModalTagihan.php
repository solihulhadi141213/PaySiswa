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
            <form action="javascript:void(0);" id="ProsesFilterTagihan">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-filter-circle"></i> Ubah Periode Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
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
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="IdPeriodeAkademik">
                                <small>Kelas/Rombel</small>
                            </label>
                            <select name="id_organization_class" id="SelectOrganizationClass" class="form-control">
                                <option value=""># Pilih Kelas/Rombel</option>
                            </select>
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
                        <i class="bi bi-info-circle"></i> Daftar Tagihan Siswa
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
<div class="modal fade" id="ModalBayar" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesBayar">
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
<div class="modal fade" id="ModalRiwayatPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
                <button type="button" class="btn btn-outline-info btn-rounded kembali_ke_modal_tagihan" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

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
                <button type="button" class="btn btn-outline-info btn-rounded kembali_ke_riwayat_pembayaran" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id1="" data-id2="">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

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
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check-circle"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_riwayat_pembayaran" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaran" data-id1="" data-id2="">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDetailPembayaran2" tabindex="-1">
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
                    <div class="col-md-12" id="FormDetailPembayaran2">
                        <!-- Form Detail Pembayaran -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info btn-rounded kembali_ke_riwayat_pembayaran_siswa" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaranSiswa" data-id="">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalHapusPembayaran2" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusPembayaran2">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12" id="FormHapusPembayaran2">
                            <!-- Form Detail Pembayaran -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiHapusPembayaran2">
                            <!-- Notifikasi Hapus Pembayaran -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check-circle"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_riwayat_pembayaran_siswa" data-bs-toggle="modal" data-bs-target="#ModalRiwayatPembayaranSiswa" data-id="">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_tagihan" data-bs-toggle="modal" data-bs-target="#ModalTagihanSiswa" data-id="">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

