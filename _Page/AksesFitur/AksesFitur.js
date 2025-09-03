//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/AksesFitur/TabelAksesFitur.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelFitur').html(data);
        }
    });
}

//Fungsi Menampilkan Data List Kategori
function ShowDataListKategori() {
    $.ajax({
        type: 'POST',
        url: '_Page/AksesFitur/ListKategori.php',
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
            url 	    : '_Page/AksesFitur/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambahFitur').on('show.bs.modal', function (e) {
        ShowDataListKategori();
    });

    //Proses Tambah Fitur
    $('#ProsesTambahFitur').submit(function(){
        $('#TombolTambahFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambahFitur = $('#ProsesTambahFitur').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/ProsesTambahFitur.php',
            data 	    :  ProsesTambahFitur,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahAksesFitur').html(data);
                var NotifikasiTambahAksesFiturBerhasil=$('#NotifikasiTambahAksesFiturBerhasil').html();
                if(NotifikasiTambahAksesFiturBerhasil=="Success"){
                    $('#TombolTambahFitur').html('<i class="bi bi-save"></i> Simpan');
                    $('#NotifikasiTambahAksesFitur').html('');
                    $('#page').val("1");
                    $("#ProsesFilter")[0].reset();
                    $("#ProsesTambahFitur")[0].reset();
                    $('#ModalTambahFitur').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambahh Fitur Akses Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }else{
                    $('#TombolTambahFitur').html('<i class="bi bi-save"></i> Simpan');
                }
            }
        });
    });

    //Ketika Modal Hapus Fitur Muncul
    $('#ModalHapusFitur').on('show.bs.modal', function (e) {
        var id_access_feature = $(e.relatedTarget).data('id');
        $('#FormHapusFitur').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/FormHapusFitur.php',
            data        : {id_access_feature: id_access_feature},
            success     : function(data){
                $('#FormHapusFitur').html(data);
                $('#NotifikasiHapusFitur').html('');
            }
        });
    });
    //Proses Hapus Fitur
    $('#ProsesHapusFitur').submit(function(){
        $('#NotifikasiHapusFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesHapusFitur = $('#ProsesHapusFitur').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/ProsesHapusFitur.php',
            data 	    :  ProsesHapusFitur,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusFitur').html(data);
                var NotifikasiHapusFiturBerhasil=$('#NotifikasiHapusFiturBerhasil').html();
                if(NotifikasiHapusFiturBerhasil=="Success"){
                    $("#ProsesHapusFitur")[0].reset();
                    $('#ModalHapusFitur').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Fitur Akses Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });
    //Ketika Modal Edit Fitur Muncul
    $('#ModalEditFitur').on('show.bs.modal', function (e) {
        var id_access_feature = $(e.relatedTarget).data('id');
        $('#FormEditFitur').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/FormEditFitur.php',
            data        : {id_access_feature: id_access_feature},
            success     : function(data){
                $('#FormEditFitur').html(data);
                $('#NotifikasiEditFitur').html('Pastikan data yang anda input sudah sesuai');
            }
        });
    });
    //Proses Edit Fitur
    $('#ProsesEditFitur').submit(function(){
        $('#NotifikasiEditFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEditFitur = $('#ProsesEditFitur').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/ProsesEditFitur.php',
            data 	    :  ProsesEditFitur,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditFitur').html(data);
                var NotifikasiEditFiturBerhasil=$('#NotifikasiEditFiturBerhasil').html();
                if(NotifikasiEditFiturBerhasil=="Success"){
                    $('#NotifikasiEditFitur').html('Pastikan data yang anda input sudah benar');
                    $('#ModalEditFitur').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Edit Fitur Akses Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Detail Fitur
    $('#ModalDetailFitur').on('show.bs.modal', function (e) {
        var id_access_feature = $(e.relatedTarget).data('id');
        $('#FormDetailFitur').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/AksesFitur/FormDetailFitur.php',
            data        : {id_access_feature: id_access_feature},
            success     : function(data){
                $('#FormDetailFitur').html(data);
            }
        });
    });
});




