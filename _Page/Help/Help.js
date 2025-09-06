//Fungsi Untuk Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/TabelHelp.php',
        data 	    :  ProsesFilter,
        success     : function(data){
            $('#MenampilkanTabelHelp').html(data);
        }
    });
}
//Fungsi Untuk Menampilkan Data Bantuan
function filterAndLoadTableBantuan() {
    var ProsesFilterBantuan = $('#ProsesFilterBantuan').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/HelpList.php',
        data 	    :  ProsesFilterBantuan,
        success     : function(data){
            $('#MenampilkanListHelp').html(data);
        }
    });
}
//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();
    filterAndLoadTableBantuan();

    //Ketika Modal Kategori Muncul
    $('#ModalListKategori').on('show.bs.modal', function (e) {
        $('#FormListKategoriBantuan').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Help/FormListKategoriBantuan.php',
            success     : function(data){
                $('#FormListKategoriBantuan').html(data);
            }
        });
    });
    
    //Modal Edit Kategori
    $('#ModalEditKategori').on('show.bs.modal', function (e) {
        var kategori = $(e.relatedTarget).data('id');
        $('#FormEditKategori').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Help/FormEditKategori.php',
            data        : {kategori: kategori},
            success     : function(data){
                $('#FormEditKategori').html(data);
            }
        });
    });

    //Proses Edit Kategori
    $('#ProsesEditKategori').on('submit', function(e) {
        e.preventDefault();
        // Mengubah teks tombol menjadi 'Loading..' dan menonaktifkan tombol
        $('#NotifikasiEditKategori').html('Loading..').prop('disabled', true);
        // Membuat objek FormData
        var formData = new FormData(this);
        // Mengirim data melalui AJAX
        $.ajax({
            url             : '_Page/Help/ProsesEditKategori.php',
            type            : 'POST',
            data            : formData,
            contentType     : false,
            processData     : false,
            dataType        : 'json',
            success: function(response) {
                if (response.success) {
                    // Jika sukses, tutup modal dan kembalikan tombol ke semula
                    $('#NotifikasiEditKategori').html('');
                    $('#ModalEditKategori').modal('hide');
                    $('#ModalListKategori').modal('show');
                    filterAndLoadTable();
                } else {
                    // Jika gagal, tampilkan notifikasi error
                    $('#NotifikasiEditKategori').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                // Jika terjadi error pada request
                $('#NotifikasiEditKategori').html('<div class="alert alert-danger">Terjadi kesalahan saat mengirim data.</div>');
            }
        });
    });
    //Modal Hapus Kategori Bantuan
    $('#ModalHapusKategori').on('show.bs.modal', function (e) {
        var kategori = $(e.relatedTarget).data('id');
        $('#FormHapusKategori').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Help/FormHapusKategori.php',
            data        : {kategori: kategori},
            success     : function(data){
                $('#FormHapusKategori').html(data);
                $('#NotifikasiHapusKategori').html("");
            }
        });
    });
    //Proses Hapus Kategori
    $('#ProsesHapusKategori').on('submit', function(e) {
        e.preventDefault();
        // Mengubah teks tombol menjadi 'Loading..' dan menonaktifkan tombol
        $('#NotifikasiHapusKategori').html('Loading..').prop('disabled', true);
        // Membuat objek FormData
        var formData = new FormData(this);
        // Mengirim data melalui AJAX
        $.ajax({
            url             : '_Page/Help/ProsesHapusKategori.php',
            type            : 'POST',
            data            : formData,
            contentType     : false,
            processData     : false,
            dataType        : 'json',
            success: function(response) {
                if (response.success) {
                    // Jika sukses, tutup modal dan kembalikan tombol ke semula
                    $('#FormHapusKategori').html('');
                    $('#NotifikasiHapusKategori').html('');
                    $('#ModalHapusKategori').modal('hide');
                    $('#ModalListKategori').modal('show');
                    filterAndLoadTable();
                } else {
                    // Jika gagal, tampilkan notifikasi error
                    $('#NotifikasiHapusKategori').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                // Jika terjadi error pada request
                $('#NotifikasiHapusKategori').html('<div class="alert alert-danger">Terjadi kesalahan saat mengirim data.</div>');
            }
        });
    });
});
//Ketika KeywordBy Diubah
$('#keyword_by').change(function(){
    var keyword_by = $('#keyword_by').val();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormFilter.php',
        data 	    :  {keyword_by: keyword_by},
        success     : function(data){
            $('#FormFilter').html(data);
        }
    });
});
//Ketika Filter Di Submit
$('#ProsesFilter').submit(function(){
    //Halaman Kembali Default
    $('#PutPage').val('1');
    filterAndLoadTable();
    //Tutup Modal
    $('#ModalFilter').modal('hide');
});
tinymce.init({
    selector: '#deskripsi',
    plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template paste help'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullscreen charmap | ' +
        'forecolor backcolor emoticons | help',
    menu: {
        favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'assets/css/tinymce.css',
    images_upload_url: '_Page/PostAcceptor/PostAcceptor.php',
    images_upload_credentials: true,
    images_reuse_filename: true,
    image_title: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
        };

        input.click();
    },
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    height: "480"
});

//Kondisi pada saat halaman edit dibuka
var description=$("#GetHelDescription").html();
tinymce.init({
    selector: '#deskripsi_edit',
    setup: function (editor) {
        editor.on('init', function (e) {
            editor.setContent(description);
        });
    },
    plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template paste help'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen charmap | ' +
    'forecolor backcolor emoticons | help',
    menu: {
        favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'assets/css/tinymce.css',
    images_upload_url: '_Page/PostAcceptor/PostAcceptor.php',
    images_upload_credentials: true,
    images_reuse_filename: true,
    image_title: true,
    /* enable automatic uploads of images represented by blob or data URIs*/
    automatic_uploads: true,
    /*
        URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
        images_upload_url: 'postAcceptor.php',
        here we add custom filepicker only to Image dialog
    */
    file_picker_types: 'image',
    /* and here's our custom image picker*/
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        /*
        Note: In modern browsers input[type="file"] is functional without
        even adding it to the DOM, but that might not be the case in some older
        or quirky browsers like IE, so you might want to add it to the DOM
        just in case, and visually hide it. And do not forget do remove it
        once you do not need it anymore.
        */

        input.onchange = function () {
        var file = this.files[0];

        var reader = new FileReader();
        reader.onload = function () {
            /*
            Note: Now we need to register the blob in TinyMCEs image blob
            registry. In the next release this part hopefully won't be
            necessary, as we are looking to handle it internally.
            */
            var id = 'blobid' + (new Date()).getTime();
            var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
            var base64 = reader.result.split(',')[1];
            var blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);

            /* call the callback and populate the Title field with the file name */
            cb(blobInfo.blobUri(), { title: file.name });
        };
        reader.readAsDataURL(file);
        };

        input.click();
    },
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    height : "480"
});
//Proses Simpan Help Documentation
$('#ClickSimpanHelp').click(function(){
    $('#NotifikasiTambahHelp').html('Loading..');
    var judul=$('#judul').val();
    var kategori=$('#kategori').val();
    var status=$('#status').val();
    var deskripsi = tinymce.get("deskripsi").getContent();
    $.ajax({
        type    : 'POST',
        url     : "_Page/Help/ProsesTambahHelp.php",
        data    : {judul: judul, kategori: kategori, status: status, deskripsi: deskripsi},
        success: function(data) {
            $('#NotifikasiTambahHelp').html(data);
            var NotifikasiTambahHelpBerhasil=$('#NotifikasiTambahHelpBerhasil').html();
            if(NotifikasiTambahHelpBerhasil=="Success"){
                window.location.href = "index.php?Page=Help&Sub=HelpData";
            }
        }
    });
});
//Modal Detail Buku Merah
$('#ModalDetail').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormDetail').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormDetail.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormDetail').html(data);
        }
    });
});
//Proses Simpan Help Documentation editor
$('#ClickSimpanEditHelp').click(function(){
    $('#NotifikasiEditHelp').html('Loading..');
    var id_help=$('#id_help').val();
    var judul=$('#judul').val();
    var kategori=$('#kategori').val();
    var status=$('#status').val();
    var deskripsi = tinymce.get("deskripsi_edit").getContent();
    $.ajax({
        type    : 'POST',
        url     : "_Page/Help/ProsesEditHelp.php",
        data    : {id_help: id_help, judul: judul, kategori: kategori, status: status, deskripsi: deskripsi},
        success: function(data) {
            $('#NotifikasiEditHelp').html(data);
            var NotifikasiEditHelpBerhasil=$('#NotifikasiEditHelpBerhasil').html();
            if(NotifikasiEditHelpBerhasil=="Success"){
                window.location.href = "index.php?Page=Help&Sub=HelpData";
            }
        }
    });
});
//Hapus Edit
$('#ModalEdit').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormEdit').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormEdit.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormEdit').html(data);
        }
    });
});
//Hapus Help
$('#ModalHapus').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormHapus').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormHapus.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormHapus').html(data);
            $('#NotifikasiHapus').html("");
        }
    });
});
$('#ProsesHapus').submit(function(){
    var ProsesHapus = $('#ProsesHapus').serialize();
    $('#NotifikasiHapus').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/ProsesHapus.php',
        data 	    :  ProsesHapus,
        success     : function(data){
            $('#NotifikasiHapus').html(data);
            var NotifikasiHapusBerhasil=$('#NotifikasiHapusBerhasil').html();
            if(NotifikasiHapusBerhasil=="Success"){
                $('#NotifikasiHapus').html("");
                filterAndLoadTable();
                $('#ModalHapus').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Konten Bantuan Berhasil!',
                    'success'
                )
            }
        }
    });
});
//Modal Config Help Access
$('#ModalAksesHelp').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormAksesHelp').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormAksesHelp.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormAksesHelp').html(data);
            $('#ProsesSimpanAksesHelp').submit(function(){
                var ProsesSimpanAksesHelp = $('#ProsesSimpanAksesHelp').serialize();
                $('#NotifikasiSimpanAksesHelp').html('Loading...');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Help/ProsesSimpanAksesHelp.php',
                    data 	    :  ProsesSimpanAksesHelp,
                    success     : function(data){
                        $('#NotifikasiSimpanAksesHelp').html(data);
                        var NotifikasiSimpanAksesHelpBerhasil=$('#NotifikasiSimpanAksesHelpBerhasil').html();
                        if(NotifikasiSimpanAksesHelpBerhasil=="Success"){
                            window.location.href = "index.php?Page=Help&Sub=HelpData";
                        }
                    }
                });
            });
        }
    });
});
//Detail Help
$('#ModalDetailHelp').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormDetailHelp').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormDetailHelp.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormDetailHelp').html(data);
        }
    });
});
//Modal preview
$('#ModalPreview').on('show.bs.modal', function (e) {
    var id_help = $(e.relatedTarget).data('id');
    $('#FormPreview').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Help/FormPreview.php',
        data        : {id_help: id_help},
        success     : function(data){
            $('#FormPreview').html(data);
        }
    });
});