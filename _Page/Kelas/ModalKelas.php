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
<div class="modal fade" id="ModalMatrixTagihan" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header nav_background">
                <h5 class="modal-title text-light">
                    <i class="bi bi-table"></i> Matrix Biaya Pendidikan Siswa
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
<div class="modal fade" id="ModalListKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list-check"></i> Komponen Biaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="TitleListKomponenBiaya">
                        <!-- Title List Komponen Biaya Disini -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table table-responsive">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark"><i class="bi bi-list-check"></i> Atribut Komponen Biaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" id="ProsesFilterKomponenBiaya">
                    <input type="hidden" name="page_komponen" id="page_komponen" value="1">
                    <input type="hidden" name="id_organization_class" id="put_id_organization_class" value="">
                    <input type="hidden" name="id_academic_period" id="put_id_academic_period" value="">
                </form>
                <div class="row mb-3">
                    <div class="col-12 text-center" id="title_komponen_biaya"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="table table-responsive">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-list-check"></i> Daftar Siswa Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Nama Siswa</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>L/P</b></th>
                                        <th><b>Kelas</b></th>
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
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik" data-bs-toggle="modal" data-bs-target="#ModalMatrixTagihan" data-id="">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik" data-bs-toggle="modal" data-bs-target="#ModalMatrixTagihan" data-id="">
                        <i class="bi bi-chevron-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTambahTagihanMulti" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahTagihanMulti" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Tagihan Siswa
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
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-rounded kembali_ke_modal_metrik" data-bs-toggle="modal" data-bs-target="#ModalMatrixTagihan" data-id="">
                        <i class="bi bi-chevron-left"></i> Kembali
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