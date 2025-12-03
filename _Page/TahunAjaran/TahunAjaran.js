//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/TahunAjaran/TabelTahunAjar.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelTahunAjaran').html(data);

            // Re-inisialisasi Tooltip Bootstrap 5
            $('[data-bs-toggle="tooltip"]').tooltip();
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

//Fungsi Menampilkan Baris Tabel Siswa Per Kelas
function ShowTabelsiswaPerkelas(id_academic_period){
    $('#TabelSiswaPerKelas').html('<tr><td colspan="6" class="text-center">Loadiing...</td></tr>');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/TahunAjaran/TabelSiswaPerKelas.php',
        data        : {id_academic_period: id_academic_period},
        success     : function(data){
            $('#TabelSiswaPerKelas').html(data);
        }
    });
}

//Fungsi menampilkan Tabel Tagihan Siswa
function ShowTabelTagihanSiswa(id_academic_period) {
    $('#TabelTagihanSiswa').html('<tr><td colspan="5" class="text-center">Loadiing...</td></tr>');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/TahunAjaran/TabelTagihanSiswa.php',
        data        : {id_academic_period: id_academic_period},
        success     : function(data){
            $('#TabelTagihanSiswa').html(data);
        }
    });
}



// LOAD HALAMAN
$(document).ready(function() {

    /* Menampilkan Data Tabel Pertama Kali */
    filterAndLoadTable();
    
    /* Menampilkan Modal Filter Dengan Event Click 'modal_filter' */
    $(document).on('click', '.modal_filter', function(){
        //Tampilkan Modal
        $('#ModalFilter').modal('show');
    });

    //Ketika Filter Data 'ProsesFilter' Di Submit
    $('#ProsesFilter').submit(function(){
        $('#page').val("1");
        filterAndLoadTable();
        $('#ModalFilter').modal('hide');
    });

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

    /* Menampilkan Modal 'ModalExportTahunAjaran' Dengan Event Click 'modal_export_periode_akademik' */
    $(document).on('click', '.modal_export_periode_akademik', function(){
        //Tampilkan Modal 'ModalExportTahunAjaran'
        $('#ModalExportTahunAjaran').modal('show');
    });

    /* Menampilkan Modal 'ModalTambah' Dengan Event Click 'modal_tambah_periode_akademik' */
    $(document).on('click', '.modal_tambah_periode_akademik', function(){
        //Tampilkan Modal 'ModalTambah'
        $('#ModalTambah').modal('show');
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

    //Menampilkan Modal 'ModalDetail' dengan event click 'modal_detail'
    $(document).on('click', '.modal_detail', function(){

        //Tangkap 'id_academic_period'
        var id_academic_period = $(this).data('id');

        //Tampilkan Modal 'ModalDetail'
        $('#ModalDetail').modal('show');

        //Form Loading
        $('#FormDetail').html("Loading...");

        //Tampilkan Form Loading Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/FormDetail.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
    });

    //Modal Daftar Kelas
    $('#ModalDaftarKelas').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#TabelDaftarKelas').html('<tr><td colspan="11" class="text-center"><small class="text-danger">Loading...</small></td></tr>');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelDaftarKelas.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#TabelDaftarKelas').html(data);
            }
        });
    });

    //Modal Daftar Siswa Per Kelas Dengan Delegation
    $(document).on('click', '[data-bs-target="#ModalSiswaPerKelas"]', function () {
        var id_academic_period = $(this).data('id');
        ShowTabelsiswaPerkelas(id_academic_period);
    });
    

    //Modal Daftar Siswa
    $('#ModalDaftarSiswa').on('show.bs.modal', function (e) {

        //Tangkap id_academic_period
        var id_academic_period = $(e.relatedTarget).data('id1');
        var id_organization_class = $(e.relatedTarget).data('id2');

        //Loading
        $('#TabelDaftarSiswa').html('<tr><td colspan="6" class="text-center"><small>Loading...</small></td></tr>');

        //Tampiilkan Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelDaftarSiswa.php',
            data 	    :  {id_academic_period: id_academic_period, id_organization_class: id_organization_class},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#TabelDaftarSiswa').html(data);
            }
        });
    });

    //Event Tombol 'button_kembali_ke_daftar_siswa_perkelas'
    $(document).on('click', '.button_kembali_ke_daftar_siswa_perkelas', function(){
        //Sembunyikan Modal
        $('#ModalDaftarSiswa').modal('hide');

        //Tampilkan Modal
        $('#ModalSiswaPerKelas').modal('show');
    });

    //Modal Komponen Biaya 
    $(document).on('click', '.modal_komponen_biaya', function () {

        //Tangkap 'id_academic_period'
        var id_academic_period = $(this).data('id');

        //Tampilkan Modal
        $('#ModalKomponenBiaya').modal('show');

        //Loading Tabel
        $('#TabelKomponenBiaya').html('<tr><td colspan="6" class="text-center">Loadiing...</td></tr>');

        //Tampilkan Data 'TabelKomponenBiaya' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelKomponenBiaya.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){

                //Tampilkan data
                $('#TabelKomponenBiaya').html(data);
                
                // Re-inisialisasi Tooltip Bootstrap 5
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    });

    //Tombol Kembali Ke Komponen Biaya 'kembali_ke_komponen_biaya'
    $(document).on('click', '.kembali_ke_komponen_biaya', function () {
        
        //Tampilkan Modal 'ModalKomponenBiaya'
        $('#ModalKomponenBiaya').modal('show');

        //Tutup Modal 'ModalRincianKomponenBiaya'
        $('#ModalRincianKomponenBiaya').modal('hide');
        
    });


    //Modal Komponen Biaya 
    $(document).on('click', '.modal_rincian_komponen_biaya', function () {

        //Tangkap 'id_fee_component'
        var id_fee_component = $(this).data('id');

        //Tampilkan Modal
        $('#ModalRincianKomponenBiaya').modal('show');

        //Loading Tabel
        $('#TabelRincianKomponenBiaya').html('<tr><td colspan="11" class="text-center">Loadiing...</td></tr>');

        //Tampilkan Data 'TabelKomponenBiaya' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelRincianKomponenBiaya.php',
            data        : {id_fee_component: id_fee_component},
            success     : function(data){

                //Tampilkan data
                $('#TabelRincianKomponenBiaya').html(data);
                
                // Re-inisialisasi Tooltip Bootstrap 5
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    });

    //Menampilkan Modal Tagihan Siswa
    $(document).on('click', '.modal_tagihan_siswa', function () {

        //Menangkap 'id_academic_period'
        var id_academic_period = $(this).data('id');

        //Tampilkan Modal
         $('#ModalTagihanSiswa').modal('show');

        //Menampilkan Data Tabel Dengan Fungsi 'ShowTabelTagihanSiswa()'
        ShowTabelTagihanSiswa(id_academic_period);
    });

    //Tombol 'kembali_ke_tagihan_siswa'
    $(document).on('click', '.kembali_ke_tagihan_siswa', function () {

        //Tutup Modal
        $('#ModalRincianTagihanSiswa').modal('hide');

        //Munculkan Modal
        $('#ModalTagihanSiswa').modal('show');

    });

    //Modal Rincian Tagihan Siswa
    $('#ModalRincianTagihanSiswa').on('show.bs.modal', function (e) {
        var id_academic_period      = $(e.relatedTarget).data('id1');
        var id_organization_class   = $(e.relatedTarget).data('id2');
        $('#TabelRincianTagihanSiswa').html('Loadiing...');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelRincianTagihanSiswa.php',
            data        : {id_academic_period: id_academic_period, id_organization_class: id_organization_class},
            success     : function(data){
                $('#TabelRincianTagihanSiswa').html(data);
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




