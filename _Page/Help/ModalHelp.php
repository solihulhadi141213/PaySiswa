<div class="modal fade" id="ModalFilter" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilter">
                <input type="hidden" name="page" id="PutPage">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-funnel"></i> Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col col-md-4">
                            <label for="batas">Batas</label>
                        </div>
                        <div class="col col-md-8">
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
                        <div class="col col-md-4">
                            <label for="OrderBy">Mode Urutan</label>
                        </div>
                        <div class="col col-md-8">
                            <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="judul">Judul Bantuan</option>
                                <option value="kategori">Kategori</option>
                                <option value="deskripsi">Deskripsi</option>
                                <option value="status">Status</option>
                                <option value="datetime_creat">Tanggal Posting</option>
                                <option value="datetime_update">Tanggal Update</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">
                            <label for="ShortBy">Tipe Urutan</label>
                        </div>
                        <div class="col col-md-8">
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="DESC">Z To A</option>
                                <option value="ASC">A To Z</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">
                            <label for="keyword_by">Pencarian</label>
                        </div>
                        <div class="col col-md-8">
                            <select name="keyword_by" id="keyword_by" class="form-control">
                                <option value="">Pilih</option>
                                <option value="judul">Judul Bantuan</option>
                                <option value="kategori">Kategori</option>
                                <option value="deskripsi">Deskripsi</option>
                                <option value="status">Status</option>
                                <option value="datetime_creat">Tanggal Posting</option>
                                <option value="datetime_update">Tanggal Update</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-md-4">
                            <label for="keyword">Kata Kunci</label>
                        </div>
                        <div class="col col-md-8" id="FormFilter">
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Filter
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalListKategori" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-plus"></i> Kategori Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border-0">
                <div class="row">
                    <div class="col-12" id="FormListKategoriBantuan">
                        <!-- List Kategori Bantuan Akan Muncul Disini -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditKategori" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditKategori">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-pencil"></i> Ubah Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0">
                    <div class="row mb-2">
                        <div class="col-12" id="FormEditKategori">
                            <!-- List Kategori Bantuan Akan Muncul Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12" id="NotifikasiEditKategori">
                            <!-- Notifikasi Edit Kategori Bantuan Akan Muncul Disini -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalListKategori">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapusKategori" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusKategori">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Kategori Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3" id="FormHapusKategori"></div>
                        <div class="col-md-12 mb-3" id="NotifikasiHapusKategori"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalListKategori">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTambahBantuan" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header border-1">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-plus"></i> Tambah Konten Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3 text-center">
                        Untuk menambahkan konten bantuan, anda akan diarahkan pada halaman form tambah bantuan.
                    </div>
                    <div class="col-md-12 mb-3 text-center">
                        <small class="credit text-info">
                            Apakah anda ingin melanjutkan?
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?Page=Help&Sub=TambahHelp" class="btn btn-primary btn-rounded">
                    Lanjutkan <i class="bi bi-chevron-right"></i>
                </a>
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-1">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-info-circle"></i> Detail Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="FormDetail">
                <!-- Form Edit Ditampilkan Di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-1">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-pencil"></i> Ubah Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="FormEdit">
                <!-- Form Edit Ditampilkan Di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapus" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapus">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3" id="FormHapus"></div>
                        <div class="col-md-12 mb-3" id="NotifikasiHapus"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalAksesHelp" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesSimpanAksesHelp">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-light"><i class="bi bi-person"></i> Pengaturan Akses Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><b>No</b></th>
                                            <th><b>Akses</b></th>
                                            <th><b>Opsi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="FormAksesHelp">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiSimpanAksesHelp">
                            <span class="text-primary">Pastikan Data Yang Anda input Sudah Benar</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-info">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> No
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    //Ketika kondisi edit
    if(empty($_GET['Sub'])){
        $Sub="";
    }else{
        $Sub=$_GET['Sub'];
    }
    if($Sub=="EditHelp"){
        if(!empty($_GET['id'])){
            $id_help=$_GET['id'];
            //Buka data dokumentasi_api
            $QryHelp = mysqli_query($Conn,"SELECT * FROM help WHERE id_help='$id_help'")or die(mysqli_error($Conn));
            $DataHelp = mysqli_fetch_array($QryHelp);
            $deskripsi= $DataHelp['deskripsi'];
        }else{
            $deskripsi="";
        }
?>
    <div class="modal fade" id="ModalEditApi" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="GetHelDescription"> 
                            <?php echo html_entity_decode($deskripsi); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="modal fade" id="ModalDetailHelp" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-light"><i class="bi bi-info"></i> Detail Bantuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormDetailHelp">

                    </div>
                </div>
            </div>
            <div class="modal-footer bg-info">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalPreview" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormPreview">

                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>