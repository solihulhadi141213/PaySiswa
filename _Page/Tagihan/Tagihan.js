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

//Fungsi Show Bayar
function ShowFormBayar(id_fee_component,id_student) {
    $('#FormBayar').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormBayar.php',
        data        : {id_fee_component: id_fee_component, id_student: id_student},
        success     : function(data){
            $('#FormBayar').html(data);
            //Format Zero Padding
            initializeMoneyInputs();
        }
    });
}

//Fungsi Show Riwayat Pembayaran
function ShowRiwayatPembayaran(id_fee_component,id_student) {
    $('#FormRiwayatPembayaran').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormRiwayatPembayaran.php',
        data        : {id_fee_component: id_fee_component, id_student: id_student},
        success     : function(data){
            $('#FormRiwayatPembayaran').html(data);
        }
    });
}

//Fungsi Show Riwayat Pembayaran SIswa
function ShowRiwayatPembayaranSiswa(id_student) {
    $('#FromRiwayatPembayaranSiswa').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FromRiwayatPembayaranSiswa.php',
        data 	    :  {id_student: id_student},
        success     : function(data){
            $('#FromRiwayatPembayaranSiswa').html(data);
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

    //Modal Tambah Pembayaran
    $('#ModalBayar').on('show.bs.modal', function (e) {
        var id_fee_component = $(e.relatedTarget).data('id1');
        var id_student = $(e.relatedTarget).data('id2');
        ShowFormBayar(id_fee_component,id_student);
    });

    //Modal Riwayat Pembayaran Siswa
    $('#ModalRiwayatPembayaranSiswa').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        ShowRiwayatPembayaranSiswa(id_student);
    });

    //Proses Bayar
    $('#ProsesBayar').submit(function(){
               
        //Loading
        $('#NotifikasiBayar').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesBayar = $('#ProsesBayar').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesBayar.php',
            data 	    :  ProsesBayar,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiBayar').html(data);

                //Tangkap id_siswa
                var id_student=$('#get_id_student').val();

                //Tangkap Notifikasi
                var NotifikasiBayarBerhasil=$('#NotifikasiBayarBerhasil').html();

                //Jika Berhasil
                if(NotifikasiBayarBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalBayar').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');
                    ShowTagihanSiswa(id_student);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }
            }
        });
    });

    //Modal Riwayat Pembayaran
    $('#ModalRiwayatPembayaran').on('show.bs.modal', function (e) {
        var id_fee_component = $(e.relatedTarget).data('id1');
        var id_student = $(e.relatedTarget).data('id2');
        ShowRiwayatPembayaran(id_fee_component,id_student);
    });

    //Modal Detail Pembayaran
    $('#ModalDetailPembayaran').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormDetailPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormDetailPembayaran').html(data);
            }
        });
    });

    //Modal Hapus Pembayaran
    $('#ModalHapusPembayaran').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormHapusPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormHapusPembayaran').html(data);
                $('#NotifikasiHapusPembayaran').html('');
            }
        });
    });

    //Modal Detail Pembayaran2
    $('#ModalDetailPembayaran2').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormDetailPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormDetailPembayaran2').html(data);
            }
        });
    });

    //Modal Hapus Pembayaran2
    $('#ModalHapusPembayaran2').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormHapusPembayaran.php',
            data 	    :  {id_payment: id_payment},
            success     : function(data){
                $('#FormHapusPembayaran2').html(data);
                $('#NotifikasiHapusPembayaran2').html('');
            }
        });
    });

    //Proses Hapus Pembayaran
    $('#ProsesHapusPembayaran').submit(function(){
               
        //Loading
        $('#NotifikasiHapusPembayaran').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesHapusPembayaran = $('#ProsesHapusPembayaran').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesHapusPembayaran.php',
            data 	    :  ProsesHapusPembayaran,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusPembayaran').html(data);

                //Tangkap id_siswa
                var id_fee_component=$('#get_id_fee_component2').val();
                var id_student=$('#get_id_student2').val();

                //Tangkap Notifikasi
                var NotifikasiHapusPembayaranBerhasil=$('#NotifikasiHapusPembayaranBerhasil').html();

                //Jika Berhasil
                if(NotifikasiHapusPembayaranBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalHapusPembayaran').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalRiwayatPembayaran').modal('show');
                    ShowRiwayatPembayaran(id_fee_component,id_student);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }
            }
        });
    });

    //Proses Hapus Pembayaran2
    $('#ProsesHapusPembayaran2').submit(function(){
               
        //Loading
        $('#NotifikasiHapusPembayaran2').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesHapusPembayaran2 = $('#ProsesHapusPembayaran2').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesHapusPembayaran.php',
            data 	    :  ProsesHapusPembayaran2,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusPembayaran2').html(data);

                //Tangkap id_siswa
                var id_student=$('#get_id_student2').val();

                //Tangkap Notifikasi
                var NotifikasiHapusPembayaranBerhasil=$('#NotifikasiHapusPembayaranBerhasil').html();

                //Jika Berhasil
                if(NotifikasiHapusPembayaranBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalHapusPembayaran2').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalRiwayatPembayaranSiswa').modal('show');
                    ShowRiwayatPembayaranSiswa(id_student);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }
            }
        });
    });

    //Modal Ubah Tagihan
    $('#ModalUbahTagihan').on('show.bs.modal', function (e) {
        var id_fee_by_student = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/FormUbahTagihan.php',
            data 	    :  {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormUbahTagihan').html(data);
                $('#NotifikasiUbahTagihan').html('');
                initializeMoneyInputs();
            }
        });
    });

    //Proses Ubah Tagihan
    $('#ProsesUbahTagihan').submit(function(){
               
        //Loading
        $('#NotifikasiUbahTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesUbahTagihan = $('#ProsesUbahTagihan').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesUbahTagihan.php',
            data 	    :  ProsesUbahTagihan,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUbahTagihan').html(data);

                //Tangkap id_siswa
                var id_student=$('#get_id_student3').val();

                //Tangkap Notifikasi
                var NotifikasiUbahTagihanBerhasil=$('#NotifikasiUbahTagihanBerhasil').html();

                //Jika Berhasil
                if(NotifikasiUbahTagihanBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalUbahTagihan').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');
                    ShowTagihanSiswa(id_student);

                    //Reload Tabel Tagihan
                    FilterTagihan();
                }
            }
        });
    });

    //Modal Export Tagihan
    $('#ModalExportTagihan').on('show.bs.modal', function (e) {

        //Tangkap Data Dari Form Tagihan
        var ProsesFilterTagihan = $('#ProsesFilterTagihan').serialize();

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

});
