<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'QbQ4qF57AzCEp5qG0KG');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small class="mobile-text">';
                    echo '      Berikut ini adalah halaman form untuk menambahkan data bantuan pengguna.';
                    echo '      Tulis informasi secara lengkap dengan judul yang mewakili keseluruhan isi konten.';
                    echo '      Gunakan juga kalimat yang sering dicari sehingga pengguna dapat menemukan informasi yang sesuai';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title">
                                    <i class="bi bi-clipboard-plus"></i> Buat Konten Bantuan
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-info-circle"></i>
                                    </span>
                                    <input type="text" name="judul" id="judul" class="form-control"  placeholder="Judul Bantuan">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <input type="text" name="kategori" id="kategori" list="ListCategori" class="form-control" autocomplete="off" placeholder="Kategori Bantuan">
                                    <datalist id="ListCategori">
                                        <?php
                                            //Tampilkan list categori help
                                            $QryKategori = mysqli_query($Conn, "SELECT DISTINCT kategori FROM help ORDER BY kategori ASC");
                                            while ($DataKategori = mysqli_fetch_array($QryKategori)) {
                                                $kategori_list=$DataKategori['kategori'];
                                                echo '<option value="'.$kategori_list.'">';
                                            }
                                        ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-0-circle"></i>
                                    </span>
                                    <select name="status" id="status" class="form-control">>
                                        <option value="">Status</option>
                                        <option value="Publish">Publish</option>
                                        <option value="Draft">Draft</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12" id="NotifikasiTambahHelp"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-md btn-rounded btn-primary btn-block" id="ClickSimpanHelp">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="index.php?Page=Help&Sub=HelpData" class="btn btn-md btn-dark btn-rounded btn-block">
                                    <i class="bi bi-chevron-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>