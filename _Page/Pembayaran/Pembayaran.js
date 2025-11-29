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

                // 🔁 Re-inisialisasi tooltip setelah data dimuat
                $('[data-bs-toggle="tooltip"]').tooltip();

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

            /* Re-inisialisasi tooltip setelah data dimuat */
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

// Fungsi Menampilkan Tagihan Siswa
function ShowTagihan() {
    
    //Menangkap Data Dari Form 'FilterTagihanSiswa'
    var FilterTagihanSiswa = $('#FilterTagihanSiswa').serialize();

    //Loading Table
    $('#TabelTagihan').html('<tr><td colspan="11" align="center"><small>Loading...</small></td></tr>');

    //Tampilkan Dengan Ajax
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/TabelTagihan.php',
        data        : FilterTagihanSiswa,
        success     : function(data){
            $('#TabelTagihan').html(data);

            /* Re-inisialisasi tooltip setelah data dimuat */
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

//Fungsi Menampilkan Modal Komponen Biaya
function ShowKomponenBiaya(id_student) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/FormKomponenBiaya.php',
        data        : {id_student: id_student},
        success     : function(data){
            $('#FormKomponenBiaya').html(data);
            
            /* Re-inisialisasi tooltip setelah data dimuat */
            $('[data-bs-toggle="tooltip"]').tooltip();

            //Tampilkan Tabel Tagihan
            ShowTagihan();
        }
    });
}

//Fungsi Menampilkan 'FormBayar'
function ShowFormBayar(id_fee_by_student) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/FormBayar.php',
        data        : {id_fee_by_student: id_fee_by_student},
        success     : function(data){
            $('#FormBayar').html(data);
            initializeMoneyInputs();
        }
    });
}

// Fungsi untuk memproses input pada elemen dengan class form-money
function processInput(event) {
    let input = event.target;
    let originalValue = input.value;

    // Hilangkan titik dari nilai asli untuk penghitungan
    let rawValue = originalValue.replace(/\./g, "");

    // Format nilai input
    let formattedValue = formatMoney(rawValue);

    // Update nilai input dengan nilai yang telah diformat
    input.value = formattedValue;
}

// Fungsi untuk memformat angka menjadi format ribuan
function formatMoney(value) {
    if (!value) return ""; // Jika kosong, kembalikan string kosong
    // Hilangkan karakter selain angka
    value = value.toString().replace(/[^0-9]/g, "");
    // Tambahkan pemisah ribuan (titik)
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Fungsi untuk menginisialisasi elemen form-money
function initializeMoneyInputs() {
    const moneyInputs = document.querySelectorAll(".form-money");
    moneyInputs.forEach(function (input) {
        // Format nilai awal jika sudah ada
        input.value = formatMoney(input.value);

        // Pastikan input diformat dengan benar
        input.removeEventListener("input", processInput); // Menghapus event listener sebelumnya
        input.addEventListener("input", processInput);
    });
}

//Fungsi Untuk Menampilkan Detail Pembayaran
function ShowDetailPembayaran(id_payment) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/FormDetailPembayaran.php',
        data        : {id_payment: id_payment},
        success     : function(data){
            $('#FormDetailPembayaran').html(data);
        }
    });
}

//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();

    //Menampilkan Modal 'modal_siswa'
    $(document).on('click', '.modal_siswa', function() {

        //Tampilkan Modal 'ModalSiswa'
        $('#ModalSiswa').modal('show');

        //Loading Tabel
        $('#TabelSiswa').html('<tr><td colspan="5" align="center">Loading...</td></tr>');

        //Tampilkan Dengan Fungsi 
        filterAndLoadTableSiswa();
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

    //Select Option Untuk Kelas
    $(document).on('change', '#select_periode_akademik_for_class', function() {

        //Menangkap 'id_fee_by_student'
        var id_academic_period = $(this).val();

        //Menampilkan 'select_organization_class' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/select_organization_class.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#select_organization_class').html(data);
            }
        });
    });

    //Select Option Untuk Komponen Biaya
    $(document).on('change', '#select_periode_akademik_for_komponen', function() {

        //Menangkap 'id_fee_by_student'
        var id_academic_period = $(this).val();

        //Menampilkan 'select_komponen' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/select_komponen.php',
            data        : {id_academic_period: id_academic_period},
            success     : function(data){
                $('#select_komponen').html(data);
            }
        });
    });

    //Filter Data Siswa
    $('#ProsesFilterSiswa').submit(function(){
        $('#page_siswa').val("1");
        filterAndLoadTableSiswa();
    });

    //Ketika keyword_by Diubah
    $('#KeywordBy').change(function(){
        var keyword_by = $('#KeywordBy').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormFilter.php',
            data        : {keyword_by: keyword_by},
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
    $(document).on('click', '.modal_komponen_biaya', function() {

        //tangkap 'id_student'
        var id_student = $(this).data('id');

        //Tampilkan Modal 'ModalKomponenBiaya'
        $('#ModalKomponenBiaya').modal("show");

        //Tutup Modal Siswa
        $('#ModalSiswa').modal('hide');

        //Loading Form
        $('#FormKomponenBiaya').html("Loading...");

        //Tampilkan 'FormKomponenBiaya' dengan AJAX melalui fungsi 'ShowKomponenBiaya(id_student)'
        ShowKomponenBiaya(id_student);
    });

    //Ketika 'pilih_periode_kelas' Diubah
    $(document).on('change', '#pilih_periode_kelas', function() {
        ShowTagihan();
    });

    //Ketika 'FilterTagihanSiswa' Submit
    $(document).on('submit', '#FilterTagihanSiswa', function() {
        ShowTagihan();
    });

    //Menampilkan Modal 'modal_bayar'
    $(document).on('click', '.modal_bayar', function() {

        //Tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal
        $('#ModalBayar').modal('show');

        //Sembunyikan Modal 'ModalKomponenBiaya'
        $('#ModalKomponenBiaya').modal('hide');

        //Loading Form 'FormBayar'
        $('#FormBayar').html("Loading...");

        //Kosongkan Notifikasi 'NotifikasiBayar'
        $('#NotifikasiBayar').html('');

        //Tampilkan 'FormBayar' dengan AJAX melaluiu Fungsi 'ShowFormBayar(id_fee_by_student)'
        ShowFormBayar(id_fee_by_student);

    });

    //Proses Bayar
    $('#ProsesBayar').submit(function(){
               
        //Loading
        $('#NotifikasiBayar').html('Loading...');

        //Get Data Form
        var ProsesBayar = $('#ProsesBayar').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/ProsesBayar.php',
            data 	    :  ProsesBayar,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){

                //Tangkap Response
                var status      = response.status;
                var message     = response.message;
                var id_payment  = response.id_payment;
                
                //Apabila Berhasil
                if(status=="success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalBayar').modal('hide');

                    // Menampilkan 'ModalKomponenBiaya'
                    $('#ModalKomponenBiaya').modal('show');

                    //Reload Tabel Tagihan
                    filterAndLoadTable();

                    //Tampilkan Tabel Tagihan melalui Fungsi 'ShowTagihan()'
                    ShowTagihan();
                }else{
                    $('#NotifikasiBayar').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Detail Tagihan
    $(document).on('click', '.modal_detail_tagihan', function() {

        //Menangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Munculkan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('show');

        //Tutup Modal 'ModalKomponenBiaya'
        $('#ModalKomponenBiaya').modal('hide');

        //Loading Form
        $('#FormDetailTagihan').html("Loading...");

        //Menampilkan 'FormDetailTagihan' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormDetailTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormDetailTagihan').html(data);
            }
        });
    });

    //Modal Edit Tagihan
    $(document).on('click', '.modal_edit_tagihan', function() {
        
        // Tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Munculkan Modal 'ModalEditTagihan'
        $('#ModalEditTagihan').modal('show');

        //Tutup Modal 'ModalKomponenBiaya'
        $('#ModalKomponenBiaya').modal('hide');

        //Loading Form
        $('#FormEditTagihan').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditTagihan').html('');

        //Tampilkan 'FormEditTagihan' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormEditTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormEditTagihan').html(data);

                //Input Format uang 'form-money'
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Tagihan
    $('#ProsesEditTagihan').submit(function(){
               
        //Loading 'NotifikasiEditTagihan'
        $('#NotifikasiEditTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data 'ProsesEditTagihan'
        var ProsesEditTagihan = $('#ProsesEditTagihan').serialize();

        //Simpan Data Dengan 'Ajax' dan response dengan 'JSON'
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/ProsesEditTagihan.php',
            data 	    :  ProsesEditTagihan,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){

                //Tangkap Response
                var status = response.status;
                var message = response.messge;
                var id_student = response.id_student;

                if(status=="success"){
                    //Jika Berhasil Maka
                    
                    /* Reload Tabel Pembayaran */
                    filterAndLoadTable();

                    /* reload 'ShowTagihan()' */
                    ShowTagihan();

                    /* Tutup ModalEditTagihan */
                    $('#ModalEditTagihan').modal('hide');

                    /* Tampilkan 'ModalKomponenBiaya' */
                    $('#ModalKomponenBiaya').modal('show');

                }else{
                    /* Jika Gagal, Tampilkan Pesan Kesalahan Pada 'NotifikasiEditTagihan' */
                    $('#NotifikasiEditTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Tagihan
    $('#ModalHapusTagihan').on('show.bs.modal', function (e) {
        var id_fee_by_student = $(e.relatedTarget).data('id');
        $('#FormHapusTagihan').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormHapusTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormHapusTagihan').html(data);
                $('#NotifikasiHapusTagihan').html('');
            }
        });
    });

    //Proses Hapus Tagihan
    $('#ProsesHapusTagihan').submit(function(){
               
        //Loading
        $('#NotifikasiHapusTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesHapusTagihan = $('#ProsesHapusTagihan').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/ProsesHapusTagihan.php',
            data 	    :  ProsesHapusTagihan,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){

                //Tangkap Response
                var status = response.status;
                var message = response.messge;

                if(status=="success"){
                    //Jika Berhasil Maka
                    
                    /* Reload Tabel Pembayaran */
                    filterAndLoadTable();

                    /* reload 'ShowTagihan()' */
                    ShowTagihan();

                    /* Tutup ModalHapusTagihan */
                    $('#ModalHapusTagihan').modal('hide');

                    /* Tampilkan 'ModalKomponenBiaya' */
                    $('#ModalKomponenBiaya').modal('show');

                }else{
                    /* Jika Gagal, Tampilkan Pesan Kesalahan Pada 'NotifikasiHapusTagihan' */
                    $('#NotifikasiHapusTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });
    
    //Modal Detail Pembayaran
    $(document).on('click', '.modal_detail_pembayaran', function() {
        //Tangkap id_payment
        var id_payment = $(this).data('id');

        //Tampilkan Modal 'ModalDetailPembayaran'
        $('#ModalDetailPembayaran').modal('show');

        //Loading 'FormDetailPembayaran'
        $('#FormDetailPembayaran').html("Loading...");

        //Tampilkan Dengan Fungsi 
        ShowDetailPembayaran(id_payment);
    });

    //Modal Edit
    $('#ModalEdit').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $('#FormEdit').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormEditPembayaran.php',
            data        : {id_payment: id_payment},
            success     : function(data){
                $('#FormEdit').html(data);
                $('#NotifikasiEdit').html('');
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Pembayaran
    $('#ProsesEdit').submit(function(){
               
        //Loading
        $('#NotifikasiEdit').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesEdit = $('#ProsesEdit').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/ProsesEdit.php',
            data 	    :  ProsesEdit,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);

                //Tangkap Notifikasi
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();

                //Jika Berhasil
                if(NotifikasiEditBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalEdit').modal('hide');

                    //Reload Tabel Tagihan
                    filterAndLoadTable();

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Update Pembayaran Berhasil!',
                        'success'
                    );
                }
            }
        });
    });

    //Modal Hapus
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormHapus.php',
            data        : {id_payment: id_payment},
            success     : function(data){
                $('#FormHapus').html(data);
                $('#NotifikasiHapus').html('');
            }
        });
    });

    //Proses Hapus Pembayaran
    $('#ProsesHapus').submit(function(){
               
        //Loading
        $('#NotifikasiHapus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesHapus = $('#ProsesHapus').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/ProsesHapus.php',
            data 	    :  ProsesHapus,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapus').html(data);

                //Tangkap Notifikasi
                var NotifikasiHapusBerhasil=$('#NotifikasiHapusBerhasil').html();

                //Jika Berhasil
                if(NotifikasiHapusBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalHapus').modal('hide');

                    //Reload Tabel Tagihan
                    filterAndLoadTable();

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Hapus Pembayaran Berhasil!',
                        'success'
                    );
                }
            }
        });
    });

    // Menampilkan Modal 'modal_detail_siswa'
    $(document).on('click', '.modal_detail_siswa', function() {
        
        //Tangkap 'id_student'
        var id_student = $(this).data('id');

        //Tampilkan Modal 'ModalDetailSiswa'
        $('#ModalDetailSiswa').modal('show');

        //Loading 'FormDetailSiswa'
        $('#FormDetailSiswa').html("Loading...");

        //Tampilkan 'FormDetailSiswa' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormDetail.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormDetailSiswa').html(data);
            }
        });
    });

    // Menampilkan Modal 'modal_detail_kelas'
    $(document).on('click', '.modal_detail_kelas', function() {
        
        //Tangkap 'id_organization_class'
        var id_organization_class = $(this).data('id');

        //Tampilkan Modal 'ModalDetailKelas'
        $('#ModalDetailKelas').modal('show');

        //Loading 'FormDetailPeriodeAkademik'
        $('#FormDetailKelas').html("Loading...");

        //Tampilkan 'FormDetailPeriodeAkademik' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormDetail.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormDetailKelas').html(data);
            }
        });
    });

    // Menampilkan Modal 'modal_detail_komponen_biaya'
    $(document).on('click', '.modal_detail_komponen_biaya', function() {
        
        //Tangkap 'id_fee_component'
        var id_fee_component = $(this).data('id');

        //Tampilkan Modal 'ModalDetailKomponenBiaya'
        $('#ModalDetailKomponenBiaya').modal('show');

        //Loading 'FormDetailKomponenBiaya'
        $('#FormDetailKomponenBiaya').html("Loading...");

        //Tampilkan 'FormDetailKomponenBiaya' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormDetailKomponenBiaya.php',
            data        : {id_fee_component: id_fee_component},
            success     : function(data){
                $('#FormDetailKomponenBiaya').html(data);
            }
        });
    });
    
});