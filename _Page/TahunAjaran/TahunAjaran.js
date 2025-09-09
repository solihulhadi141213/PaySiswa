//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/TahunAjaran/TabelTahunAjar.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelTahunAjaran').html(data);
        }
    });
}

//Fungsi Menampilkan Data List Kategori
function ShowDataListKategori() {
    $.ajax({
        type: 'POST',
        url: '_Page/TahunAjaran/ListKategori.php',
        success: function(data) {
            $('#ListKategori').html(data);
        }
    });
}

//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();

    //Pagging
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        filterAndLoadTable(0);
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        filterAndLoadTable(0);
    });

    //Filter Data
    $('#ProsesFilter').submit(function(){
        $('#page').val("1");
        filterAndLoadTable();
        $('#ModalFilter').modal('hide');
    });

    //Ketika KeywordBy Diubah
    $('#KeywordBy').change(function(){
        var KeywordBy = $('#KeywordBy').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Proses Tambah
    $('#ProsesTambah').submit(function(){
        $('#NotifikasiTambah').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambah = $('#ProsesTambah').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/ProsesTambah.php',
            data 	    :  ProsesTambah,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambah').html(data);
                var NotifikasiTambahBerhasil=$('#NotifikasiTambahBerhasil').html();
                if(NotifikasiTambahBerhasil=="Success"){
                    //Kosongkan Notifikasi
                    $('#NotifikasiTambah').html('');
                    $('#page').val("1");
                    $("#ProsesFilter")[0].reset();
                    $("#ProsesTambah")[0].reset();
                    $('#ModalTambah').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambahh Tahun Akademik Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Detail 
    $('#ModalDetail').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#FormDetail').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormDetail.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
    });

    //Ketika Modal Edit 
    $('#ModalEdit').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#FormEdit').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormEdit.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#FormEdit').html(data);
                $('#NotifikasiEdit').html('');
            }
        });
    });

    //Proses Edit Fitur
    $('#ProsesEdit').submit(function(){
        $('#NotifikasiEdit').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEdit = $('#ProsesEdit').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/ProsesEdit.php',
            data 	    :  ProsesEdit,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();

                if(NotifikasiEditBerhasil=="Success"){

                    $('#ModalEdit').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Edit Tahun Akademik Berhasil!',
                        'success'
                    );

                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Ketika Modal Hapus Muncul
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormHapus.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#FormHapus').html(data);
                $('#NotifikasiHapus').html('');
            }
        });
    });
    
    //Proses Hapus
    $('#ProsesHapus').submit(function(){
        $('#NotifikasiHapus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesHapus = $('#ProsesHapus').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/ProsesHapus.php',
            data 	    :  ProsesHapus,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapus').html(data);
                var NotifikasiHapusBerhasil=$('#NotifikasiHapusBerhasil').html();
                if(NotifikasiHapusBerhasil=="Success"){
                    $("#ProsesHapus")[0].reset();
                    $('#ModalHapus').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Periode Akademik Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Ketika Modal Update Kunci
    $('#ModalKunci').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#FormKunci').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormKunci.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#FormKunci').html(data);
                $('#NotifikasiKunci').html('');
            }
        });
    });
    
    //Proses Kunci
    $('#ProsesKunci').submit(function(){
        $('#NotifikasiKunci').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesKunci = $('#ProsesKunci').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/ProsesKunci.php',
            data 	    :  ProsesKunci,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiKunci').html(data);
                var NotifikasiKunciBerhasil=$('#NotifikasiKunciBerhasil').html();
                if(NotifikasiKunciBerhasil=="Success"){
                    $("#ProsesKunci")[0].reset();
                    $('#ModalKunci').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Kunci Periode Akademik Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });
    
});




