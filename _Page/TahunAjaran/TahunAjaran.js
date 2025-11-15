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
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
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
    $('#ModalKomponenBiaya').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#TabelKomponenBiaya').html('<tr><td colspan="6" class="text-center">Loadiing...</td></tr>');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelKomponenBiaya.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#TabelKomponenBiaya').html(data);
            }
        });
    });

    //Menampilkan Modal Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalTagihanSiswa"]', function () {

        //Menangkap 'id_academic_period'
        var id_academic_period = $(this).data('id');

        //Menampilkan Data Tabel Dengan Fungsi 'ShowTabelTagihanSiswa()'
        ShowTabelTagihanSiswa(id_academic_period);
    });

    //Tombol Kembali Ke Tagihan Siswa
    $(document).on('click', '#button_kembali_ke_tagihan_siswa', function () {

        //Menangkap 'id_academic_period'
        var id_academic_period = $(this).data('id');

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

    //Modal Riwayat Pembayaran
    $('#ModalRiwayatPembayaran').on('show.bs.modal', function (e) {
        var id_academic_period = $(e.relatedTarget).data('id');
        $('#TabelRiwayatPembayaran').html('<tr><td colspan="8" class="text-center">Loadiing...</td></tr>');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TahunAjaran/TabelRiwayatPembayaran.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#TabelRiwayatPembayaran').html(data);
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




