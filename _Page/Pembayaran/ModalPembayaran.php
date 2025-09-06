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
                                <option value="id_fee_component">Komponen Biaya</option>
                                <option value="payment_datetime">Tanggal Pembayaran</option>
                                <option value="payment_method">Metode</option>
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
                                <option value="id_fee_component">Komponen Biaya</option>
                                <option value="payment_datetime">Tanggal Pembayaran</option>
                                <option value="payment_method">Metode</option>
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
<div class="modal fade" id="ModalSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dak"><i class="bi bi-people"></i> Pilih Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" id="ProsesFilterSiswa">
                    <input type="hidden" name="page_siswa" id="page_siswa" value="1">
                    <div class="row mb-3">
                        <div class="col-md-2 mb-2">
                            <label for="batas_siswa">
                                <small>Batas</small>
                            </label>
                            <select name="batas_siswa" id="batas_siswa" class="form-control">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="keyword_by_siswa">
                                <small>Dasar Pencarian</small>
                            </label>
                            <select name="keyword_by_siswa" id="keyword_by_siswa" class="form-control">
                                <option value="">Cari Dari</option>
                                <option value="student_name">Nama</option>
                                <option value="student_nis">NIS</option>
                                <option value="id_organization_class">Kelas</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-2">
                            <label for="keyword_siswa">
                                <small>Kata Kunci</small>
                            </label>
                            <div id="FormFilterSiswa">
                                <input type="text" class="form-control" name="keyword_siswa" id="keyword_siswa" placeholder="Kata Kunci">
                            </div>
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="semua_siswa" name="semua_siswa" value="true">
                                <label class="form-check-label" for="semua_siswa">
                                    <small>Tampilkan siswa yang sudah lulus/keluar</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small><br></small>
                            <button type="submit" class="btn btn-md btn-primary btn-round btn-block">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>Nama</b></th>
                                        <th><b>Kelas</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswa">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <small class="text-danger">Tidak Ada Data Siswa Yang Ditampilkan</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <small id="page_info_siswa">
                            Page 1 Of 100
                        </small>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button_siswa">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button_siswa">
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

<div class="modal fade" id="ModalKomponenBiaya" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-check-circle"></i> Pilih Komponen Biaya
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormKomponenBiaya">
                        <!-- Form Komponen Biaya -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalSiswa">
                    <i class="bi bi-chevron-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
