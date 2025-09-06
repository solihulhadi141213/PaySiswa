//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();

    // Efek transisi: fadeOut dulu
    $('#TabelPembayaran').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/Pembayaran/TabelPembayaran.php',
            data    : ProsesFilter,
            success : function(data) {
                $('#TabelPembayaran').html(data);

                //Uncheck checkbox utama
                $('input[name="check_all"]').prop('checked', false);

                // Setelah ganti konten → fadeIn lagi
                $('#TabelPembayaran').fadeIn(200);
            }
        });
    });
}

//Fungsi Menampilkan Data Siswa
function filterAndLoadTableSiswa() {
    var ProsesFilterSiswa = $('#ProsesFilterSiswa').serialize();

     $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/TabelSiswa.php',
        data        : ProsesFilterSiswa,
        success     : function(data){
            $('#TabelSiswa').html(data);
        }
    });
}


//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();

    //Menampilkan Data siswa
    filterAndLoadTableSiswa();

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

    //Pagging Siswa
    $(document).on('click', '#next_button_siswa', function() {
        var page_now = parseInt($('#page_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page_siswa').val(next_page);
        filterAndLoadTableSiswa(0);
    });
    $(document).on('click', '#prev_button_siswa', function() {
        var page_now = parseInt($('#page_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page_siswa').val(next_page);
        filterAndLoadTableSiswa(0);
    });

    //Filter Data
    $('#ProsesFilter').submit(function(){
        $('#page').val("1");
        filterAndLoadTable();
        $('#ModalFilter').modal('hide');
    });

    //Filter Data Siswa
    $('#ProsesFilterSiswa').submit(function(){
        $('#page_siswa').val("1");
        filterAndLoadTableSiswa();
    });

    //Ketika KeywordBy Diubah
    $('#KeywordBy').change(function(){
        var KeywordBy = $('#KeywordBy').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Ketika keyword_by_siswa Diubah
    $('#keyword_by_siswa').change(function(){
        var keyword_by_siswa = $('#keyword_by_siswa').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormFilterSiswa.php',
            data        : {keyword_by_siswa: keyword_by_siswa},
            success     : function(data){
                $('#FormFilterSiswa').html(data);
            }
        });
    });

    //Modal Komponen Biaya
    $('#ModalKomponenBiaya').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#FormKomponenBiaya').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormKomponenBiaya.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormKomponenBiaya').html(data);
            }
        });
    });


    
});