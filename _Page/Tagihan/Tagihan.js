//Fungsi Menampilkan Select Option Class
function SelectOrganizationClass(id_academic_period, callback){
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/TabelKelas.php',
        data        : {id_academic_period: id_academic_period},
        success     : function(data){
            $('#TabelKelas').html(data);

            if (typeof callback === "function") {
                callback(); // <-- panggil setelah AJAX selesai
            }
        }
    });
}

//Fungsi Menampilkan Data Tagihan
function FilterTagihan() {
    var ProsesFilterTagihan = $('#ProsesFilterTagihan').serialize();

    // Efek transisi: fadeOut dulu
    $('#TabelTagihan').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/Tagihan/TabelTagihan.php',
            data    : ProsesFilterTagihan,
            success : function(data) {
                $('#TabelTagihan').html(data);

                // 🔁 Re-inisialisasi tooltip setelah data dimuat
                $('[data-bs-toggle="tooltip"]').tooltip();

                // Setelah ganti konten → fadeIn lagi
                $('#TabelTagihan').fadeIn(200);
            }
        });
    });
}

//Fungsi Menampilkan Data Siswa
function FilterSiswa() {
    var FilterSiswa = $('#FilterSiswa').serialize();

    // Efek transisi: fadeOut dulu
    $('#TabelSiswa').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/Tagihan/TabelSiswa.php',
            data    : FilterSiswa,
            success : function(data) {
                $('#TabelSiswa').html(data);

                // 🔁 Re-inisialisasi tooltip setelah data dimuat
                $('[data-bs-toggle="tooltip"]').tooltip();

                // Setelah ganti konten → fadeIn lagi
                $('#TabelSiswa').fadeIn(200);
            }
        });
    });
}

//Fungsi Show Form Tagihan Siswa
function ShowTagihanSiswa(id_student,id_organization_class) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormTagihanSiswa.php',
        data        : {id_student: id_student, id_organization_class: id_organization_class},
        success     : function(data){
            $('#FormTagihanSiswa').html(data);
        }
    });
}

//Fungsi 'ShowFormBayar'
function ShowFormBayar(id_fee_by_student) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormBayar.php',
        data        : {id_fee_by_student: id_fee_by_student},
        success     : function(data){
            $('#FormBayar').html(data);
            
            //Format 'form-money'
            initializeMoneyInputs();
        }
    });
}

//Fungsi Show Riwayat Pembayaran SIswa
function ShowRiwayatPembayaranSiswa(id_student,id_organization_class) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FromRiwayatPembayaranSiswa.php',
        data 	    :  {id_student: id_student, id_organization_class: id_organization_class},
        success     : function(data){
            $('#FromRiwayatPembayaranSiswa').html(data);
        }
    });
}

//Fungsi Show Riwayat Pembayaran
function ShowRiwayatPembayaran(id_fee_by_student) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormRiwayatPembayaran.php',
        data        : {id_fee_by_student: id_fee_by_student},
        success     : function(data){
            $('#FormRiwayatPembayaran').html(data);
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

//Menampilkan Data Pertama Kali
$(document).ready(function() {

    //Tangkap 'IdPeriodeAkademik'
    var id_academic_period = $('#IdPeriodeAkademik').val();

    //Menampilkan Select Option Class Pertama Kali
    SelectOrganizationClass(id_academic_period, function() {
        //Menampilkan Data Pertama Kali
        FilterTagihan();
    });

    //Ketika Filter Di submit
    $('#ProsesFilterTagihan').submit(function(){

        //Reload data dengan fungsi 'FilterTagihan'
        FilterTagihan();

        //Tutup Modal Filter
        $('#ModalFilterTagihan').modal('hide');
    });

    //Jika IdPeriodeAkademik diubah
     $('#IdPeriodeAkademik').change(function(){
        var id_academic_period = $('#IdPeriodeAkademik').val();
        SelectOrganizationClass(id_academic_period);
    });

    //Menampilkan Modal Export
    $(document).on('click', '.modal_export_tagihan', function() {
        //Tampilkan Modal
        $('#ModalExportTagihan').modal('show');

        //Tangkap Data Dari Form Tagihan
        var ProsesFilterTagihan = $('#ProsesFilterTagihan').serialize();

        //Loading Form
        $('#FormExportTagihan').html('Loading...');

        // Kirim Ke Form Export Dengan AJAX
        $.ajax({
            type    : 'POST',
            url     : '_Page/Tagihan/FormExportTagihan.php',
            data    : ProsesFilterTagihan,
            success : function(data) {
                $('#FormExportTagihan').html(data);
            }
        });
    });

    //Menampilkan Modal Filter
    $(document).on('click', '.modal_filter_tagihan', function() {
        //Tampilkan Modal
        $('#ModalFilterTagihan').modal('show');
    });

    //Menampilkan Modal Pilih Siswa
    $(document).on('click', '.modal_pilih_siswa', function() {
        //Tampilkan Modal
        $('#ModalPilihSiswa').modal('show');

        //Tutup Modal 'ModalTambahTagihan'
        $('#ModalTambahTagihan').modal('hide');

        //Load Tabel
        FilterSiswa();
    });

    //Ketika 'FilterSiswa' Di submit
    $('#FilterSiswa').submit(function(){

        //Kembali ke halaman 1
        $('#page_siswa').val('1');

        //Reload data dengan fungsi 'FilterSiswa'
        FilterSiswa();
    });

    //Pagging Tagihan
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        FilterTagihan(0);
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        FilterTagihan(0);
    });

    //Pagging Tabel Siswa
    $(document).on('click', '#next_button_siswa', function() {
        var page_now = parseInt($('#page_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page_siswa').val(next_page);
        FilterSiswa();
    });
    $(document).on('click', '#prev_button_siswa', function() {
        var page_now = parseInt($('#page_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page_siswa').val(next_page);
        FilterSiswa();
    });

    //Menampilkan Modal 'ModalTambahTagihan'
    $(document).on('click', '.modal_tambah_tagihan', function() {
        //Tangkap 'id_student'
        var id_student = $(this).data('id');
        var id_organization_class = $('#put_id_organization_class').val();

        //Tampilkan Modal 'ModalTambahTagihan'
        $('#ModalTambahTagihan').modal('show');

        //Tutup modal 'ModalPilihSiswa'
        $('#ModalPilihSiswa').modal('hide');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahTagihan').html('');

        //Loading Form
        $('#FormTambahTagihan').html('Loading...');

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormTambahTagihan.php',
            data        : {id_student: id_student, id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormTambahTagihan').html(data);

                //Format Uang
                initializeMoneyInputs();
            }
        });

    });

    // Event delegation untuk checkbox komponen biaya
    $(document).on('change', "input[type='checkbox'][name='id_fee_component[]']", function () {
        var row = $(this).closest('tr');

        // Gunakan wildcard selector
        var nominalInput = row.find("input[name^='fee_nominal']");
        var discountInput = row.find("input[name^='fee_discount']");

        // Ambil value default (hardcoded dari attr value)
        var defaultNominal = nominalInput.attr('value') || '';
        var defaultDiscount = discountInput.attr('value') || '';

        if ($(this).is(':checked')) {
            nominalInput.prop('disabled', false);
            discountInput.prop('disabled', false);
        } else {
            nominalInput.prop('disabled', true).val(defaultNominal);
            discountInput.prop('disabled', true).val(defaultDiscount);
        }

        initializeMoneyInputs();
    });

    // Checkbox 'Pilih Semua'
    $(document).on('change', "input[name='pilih_semua_komponen']", function () {
        var status = $(this).is(':checked');

        $("input[name='id_fee_component[]']").prop('checked', status);
        $("input[name='id_fee_component[]']").trigger('change');
    });

    // Proses Tambah Tagihan Parsial
    $('#ProsesTambahTagihan').on('submit', function(e){
        e.preventDefault(); // cegah reload form

        //Loading Indicator
        $('#NotifikasiTambahTagihan').html(`
            <div class="spinner-border text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        `);

        //Get Form Data
        var formData = $('#ProsesTambahTagihan').serialize();

        $.ajax({
            type      : 'POST',
            url       : '_Page/Tagihan/ProsesTambahTagihan.php',
            data      : formData,
            dataType  : 'json',
            timeout   : 15000, // 15 detik (opsional)

            success : function(response){
                console.log("Response sukses:", response); // debugging ke console

                var status  = response.status;
                var message = response.message;

                if(status == "success"){
                    
                    $('#ModalTambahTagihan').modal('hide');
                    FilterTagihan();

                    Swal.fire(
                        'Success!',
                        'Tambah Tagihan Berhasil!',
                        'success'
                    );

                }else{
                    $('#NotifikasiTambahTagihan').html(`
                        <div class="alert alert-danger"><small>${message}</small></div>
                    `);
                }
            },

            error : function(xhr, status, error){
                console.error("XHR:", xhr);
                console.error("Status:", status);
                console.error("Error:", error);

                // Tampilkan error detail dari server jika ada
                let errorMessage = `
                    <div class="alert alert-danger">
                        <b>Terjadi kesalahan AJAX!</b><br>
                        Status: ${status}<br>
                        Error: ${error}<br>
                `;

                // Tambahkan response dari server (misalnya error PHP)
                if(xhr.responseText){
                    errorMessage += `
                        <br><b>Response Server:</b><br>
                        <pre style="white-space:pre-wrap; font-size:11px;">${xhr.responseText}</pre>
                    `;
                }

                errorMessage += `</div>`;

                $('#NotifikasiTambahTagihan').html(errorMessage);
            }
        });
    });



    //Modal Detail Siswa
    $(document).on('click', '.modal_detail_siswa', function() {

        //Tangkap 'id_student'
        var id_student = $(this).data('id');

        //Show Modal
        $('#ModalDetailSiswa').modal('show');

        //Loading Modal
        $('#FormDetailSiswa').html("Loading...");

        //Tampilkan Detail Siswa Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormDetail.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormDetailSiswa').html(data);
            }
        });
    });

    //Modal Tagihan Siswa
    $(document).on('click', '.modal_tagihan_siswa', function() {

        //Tangkap 'id_student' dan
        var id_student              = $(this).data('id_student');
        var id_organization_class   = $(this).data('id_organization_class');

        //Tampilkan Modal
        $('#ModalTagihanSiswa').modal('show');

        //Tampilkan Loading
        $('#FormTagihanSiswa').html("Loading...");

        //Tampilkan Data Dengan AJX melalui fungsi 'ShowTagihanSiswa'
        ShowTagihanSiswa(id_student,id_organization_class);
    });


    //Modal Riwayat Pembayaran Siswa
    $(document).on('click', '.modal_riwayat_pembayaran_siswa', function() {

        //Tangkap 'id_student' dan 'id_organization_class'
        var id_student              = $(this).data('id_student');
        var id_organization_class   = $(this).data('id_organization_class');

        //Munculkan Modal 'ModalRiwayatPembayaranSiswa'
        $('#ModalRiwayatPembayaranSiswa').modal('show');

        //Loading Form
        $('#FromRiwayatPembayaranSiswa').html('Loading...');

        //Tampilkan 'FromRiwayatPembayaranSiswa' dengan AJAX melalui fungsi 'ShowRiwayatPembayaranSiswa'
        ShowRiwayatPembayaranSiswa(id_student,id_organization_class);
    });

    //Modal Ubah Tagihan (click 'modal_ubah_tagihan')
    $(document).on('click', '.modal_ubah_tagihan', function() {

        //tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Tutup Modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('hide');

        //Tampilkan modal 'ModalUbahTagihan'
        $('#ModalUbahTagihan').modal('show');

        //Kosongkan Notifikasi
        $('#NotifikasiUbahTagihan').html('');

        //Loading Form
        $('#FormUbahTagihan').html('Loading...');

        //Tampilkan 'FormUbahTagihan' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormUbahTagihan.php',
            data 	    :  {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormUbahTagihan').html(data);
                
                //Format Uang
                initializeMoneyInputs();
            }
        });
    });

    //Ketika Click 'kembali_ke_modal_tagihan'
    $(document).on('click', '.kembali_ke_modal_tagihan', function() {

       //Tampilkan Modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('show');

        //Tutup modal 'ModalUbahTagihan'
        $('#ModalUbahTagihan').modal('hide');

        //Tutup modal 'ModalUbahTagihan'
        $('#ModalHapusTagihan').modal('hide');

        //Tutup modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('hide');

        //Tutup modal 'ModalRequestPayment'
        $('#ModalRequestPayment').modal('hide');
    });

    //Proses Ubah Tagihan
    $('#ProsesUbahTagihan').submit(function(){
        //Tangkap Data Dari Form
        var ProsesUbahTagihan = $('#ProsesUbahTagihan').serialize();   
        
        //Loading 'NotifikasiUbahTagihan'
        $('#NotifikasiUbahTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesUbahTagihan.php',
            data 	    :  ProsesUbahTagihan,
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap status, message, id_student dan id_organization_class
                var status                  = response.status;
                var message                 = response.message;
                var id_student              = response.id_student;
                var id_organization_class   = response.id_organization_class;

                //Jika Proses Berhasil
                if(status=="success"){
                    //Tutup Modal 'ModalUbahTagihan'
                    $('#ModalUbahTagihan').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');

                    //Reload Tagihan Siswa
                    ShowTagihanSiswa(id_student,id_organization_class);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }else{
                    //Jika Proses Gagal, Tampilkan pada Notifikasi
                    $('#NotifikasiUbahTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Tagihan (click 'modal_ubah_tagihan')
    $(document).on('click', '.modal_hapus_tagihan_siswa', function() {

        //tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Tutup Modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('hide');

        //Tampilkan modal 'ModalHapusTagihan'
        $('#ModalHapusTagihan').modal('show');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusTagihan').html('');

        //Loading Form
        $('#FormHapusTagihan').html('Loading...');

        //Tampilkan 'FormHapusTagihan' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormHapusTagihan.php',
            data 	    :  {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormHapusTagihan').html(data);
                
                //Format Uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Hapus Tagihan
    $('#ProsesHapusTagihan').submit(function(){
        //Tangkap Data Dari Form
        var ProsesHapusTagihan = $('#ProsesHapusTagihan').serialize();   
        
        //Loading 'NotifikasiHapusTagihan'
        $('#NotifikasiHapusTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesHapusTagihan.php',
            data 	    :  ProsesHapusTagihan,
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap status, message, id_student dan id_organization_class
                var status                  = response.status;
                var message                 = response.message;
                var id_student              = response.id_student;
                var id_organization_class   = response.id_organization_class;

                //Jika Proses Berhasil
                if(status=="success"){
                    //Tutup Modal 'ModalHapusTagihan'
                    $('#ModalHapusTagihan').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');

                    //Reload Tagihan Siswa
                    ShowTagihanSiswa(id_student,id_organization_class);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }else{
                    //Jika Proses Gagal, Tampilkan pada Notifikasi
                    $('#NotifikasiHapusTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Ketika click 'modal_riwayat_pembayaran'
    $(document).on('click', '.modal_riwayat_pembayaran', function() {

        //Tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('show');

        //Tutup modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('hide');

        //Loading Form
        $('#FormRiwayatPembayaran').html('Loading...');

        //Tampilkan Riwayat Pembayaran Dengan AJAX melalui fungsi 'ShowRiwayatPembayaran'
        ShowRiwayatPembayaran(id_fee_by_student);
    });

    //Ketika Click 'kembali_ke_riwayat_pembayaran'
    $(document).on('click', '.kembali_ke_riwayat_pembayaran', function() {

        //Tampilkan Modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('show');

        //Tutup modal 'ModalBayar'
        $('#ModalBayar').modal('hide');

        //Tutup Modal 'ModalDetailPembayaran'
        $('#ModalDetailPembayaran').modal('hide');

        // Tutupm Modal 'ModalHapusPembayaran'
        $('#ModalHapusPembayaran').modal('hide');
    });

    //Ketika Click 'modal_tambah_pembayaran'
    $(document).on('click', '.modal_tambah_pembayaran', function() {

        //Tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal 'ModalBayar'
        $('#ModalBayar').modal('show');

        //Tutup modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('hide');

        //Loading Form 'FormBayar'
        $('#FormBayar').html('Loading...');

        //Kosongkan Notifikasi
        $('#NotifikasiBayar').html('');

        //Tampilkan 'FormBayar' Dengan AJAX melalui Fungsi 'ShowFormBayar'
        ShowFormBayar(id_fee_by_student);

    });

    //Proses Tambah Pembayaran
    $('#ProsesTambahPembayaran').submit(function(){

        //Tangkap Data Dari Form
        var ProsesTambahPembayaran = $('#ProsesTambahPembayaran').serialize();

        //Loading Notifikasi
        $('#NotifikasiBayar').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesTambahPembayaran.php',
            data 	    :  ProsesTambahPembayaran,
            dataType    : 'json',
            success     : function(response){
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_fee_by_student       = response.id_fee_by_student;
                var id_student              = response.id_student;

                //Jika Berhasil
                if(status=="success"){

                    //Tutup 'ModalBayar'
                    $('#ModalBayar').modal('hide');

                    //Buka 'ModalRiwayatPembayaran'
                    $('#ModalRiwayatPembayaran').modal('show');

                    //Load Ulang 'ShowRiwayatPembayaran'
                    ShowRiwayatPembayaran(id_fee_by_student);

                    //Reload Tagihan Siswa
                    ShowTagihanSiswa(id_student,id_organization_class);

                    //Reload Tabel Tagihan
                    FilterTagihan();

                }else{

                    //Jika Gagal, Tampilkan 'message'
                    $('#NotifikasiBayar').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }

            }
        });
    });

    //Modal Detail Pembayaran
    $(document).on('click', '.modal_detail_pembayaran', function() {

        //Tangkap 'id_payment'
        var id_payment = $(this).data('id');

        //Tampilkan Modal 'ModalDetailPembayaran'
        $('#ModalDetailPembayaran').modal('show');

        //Tutup Modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('hide');

        //Loading Form
        $('#FormDetailPembayaran').html('Loading...');

        //Tampilkan Form Detail Pembayaran Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormDetailPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormDetailPembayaran').html(data);
            }
        });
    });

    // Ketika Click 'modal_hapus_pembayaran'
    $(document).on('click', '.modal_hapus_pembayaran', function() {

        //Tangkap 'id_payment'
        var id_payment = $(this).data('id');

        //Tampilkan Modal 'ModalHapusPembayaran'
        $('#ModalHapusPembayaran').modal('show');

        //Tutup Modal 'ModalRiwayatPembayaran'
        $('#ModalRiwayatPembayaran').modal('hide');

        //Loading Form
        $('#FormHapusPembayaran').html('Loading...');

        // Kosongkan Notifikasi
        $('#NotifikasiHapusPembayaran').html('');

        //Tampilkan 'FormHapusPembayaran' Pembayaran Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormHapusPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormHapusPembayaran').html(data);
            }
        });
    });

    //Proses Hapus Pembayaran
    $('#ProsesHapusPembayaran').submit(function(){
               
        //Loading 'NotifikasiHapusPembayaran'
        $('#NotifikasiHapusPembayaran').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesHapusPembayaran = $('#ProsesHapusPembayaran').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesHapusPembayaran.php',
            data 	    :  ProsesHapusPembayaran,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){

                //Menangkap Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_fee_by_student       = response.id_fee_by_student;
                var id_student              = response.id_student;

                // Jika Berhasil
                if(status=="success"){

                    //Tutup Modal 'ModalHapusPembayaran'
                    $('#ModalHapusPembayaran').modal('hide');

                    //Buka 'ModalRiwayatPembayaran'
                    $('#ModalRiwayatPembayaran').modal('show');

                    //Load Ulang 'ShowRiwayatPembayaran'
                    ShowRiwayatPembayaran(id_fee_by_student);

                    //Reload Tagihan Siswa
                    ShowTagihanSiswa(id_student,id_organization_class);

                    //Reload Tabel Tagihan
                    FilterTagihan();

                }else{
                    // Jika Gagal
                    $('#NotifikasiHapusPembayaran').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Request Payment
    $(document).on('click', '.request_payment', function() {

        //Tangkap 'id_fee_by_student'
        var id_fee_by_student   = $(this).data('id');

        //Tampilkan Modal
        $('#ModalRequestPayment').modal('show');

        //tutup 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('hide');

        //Tampilkan Loading
        $('#FormRequestPayment').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiRequestPayment').html('');

        //Buka Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormRequestPayment.php',
            data 	    :  {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormRequestPayment').html(data);
            }
        });
    });

    //Submit 'ProsesRequestPayment'
    $('#ProsesRequestPayment').submit(function(){
               
        //Loading 'NotifikasiHapusPembayaran'
        $('#NotifikasiRequestPayment').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesRequestPayment = $('#ProsesRequestPayment').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesRequestPayment.php',
            data 	    :  ProsesRequestPayment,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){

                //Menangkap Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;

                // Jika Berhasil
                if(status=="success"){

                    //Tutup Modal 'ModalRequestPayment'
                    $('#ModalRequestPayment').modal('hide');

                    //Buka 'ModalRiwayatPembayaran'
                    $('#ModalTagihanSiswa').modal('show');

                    //Reload Tagihan Siswa
                    ShowTagihanSiswa(id_student,id_organization_class);

                    //Reload Tabel Tagihan
                    FilterTagihan();

                }else{
                    // Jika Gagal
                    $('#NotifikasiRequestPayment').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });
});
