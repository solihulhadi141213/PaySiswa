//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    var $tabel = $('#TabelSiswa');

    // Tambahkan efek visual loading (opacity menurun)
    $tabel.css({
        'opacity': '0.5',
        'pointer-events': 'none',
        'transition': 'opacity 0.3s ease'
    });

    $.ajax({
        type: 'POST',
        url: '_Page/Siswa/TabelSiswa.php',
        data: ProsesFilter,
        success: function(data) {
            // Ganti isi tabel tanpa mengganti elemen induk
            $tabel.html(data);

            // Reset checkbox utama
            $('input[name="check_all"]').prop('checked', false);

            // Kembalikan efek normal
            $tabel.css({
                'opacity': '1',
                'pointer-events': 'auto'
            });
            
            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        error: function() {
            $tabel.html('<div class="alert alert-danger m-2">Gagal memuat data. Silakan coba lagi.</div>');
            $tabel.css({
                'opacity': '1',
                'pointer-events': 'auto'
            });
        }
    });
}

//Rekapitulasi Tagihan Siswa
function ShowRekapitulasiTagihan(id_student) {
    //Tampilkan 'FormRekapitulasiTagihanSiswa.php' dengan ajax
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Siswa/FormRekapitulasiTagihanSiswa.php',
        data        : {id_student: id_student},
        success     : function(data){
            $('#FormRekapitulasiTagihanSiswa').html(data);
        }
    });
}

//Tabel Tagihan Siswa
function ShowTagihanSiswa() {
    var FormFilterTagihanSiswa = $('#FormFilterTagihanSiswa').serialize();

    // Efek transisi: fadeOut dulu
    $('#TabelTagihanSiswa').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/Siswa/TabelTagihanSiswa.php',
            data    : FormFilterTagihanSiswa,
            success : function(data) {
                $('#TabelTagihanSiswa').html(data);

                // Setelah ganti konten → fadeIn lagi
                $('#TabelTagihanSiswa').fadeIn(200);
            }
        });
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

// Fungsi untuk menampilkan rincian tagihan siswa
function ShowRincianTagihan(id_organization_class, id_student) {
    //Menampilkan Rincian Tagihan Dengan AJAX
    $.ajax({
        type    : 'POST',
        url     : '_Page/Siswa/TabelRincianTagihanSiswa.php',
        data    : {id_organization_class: id_organization_class, id_student: id_student},
        success : function(data) {
            $('#TabelRincianTagihanSiswa').html(data);
        }
    });
}

//Fungsi untuk menampilkan detail tagihan dan pembayaran
function ShowDetailTagihan(id_fee_by_student) {
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Siswa/FormDetailTagihan.php',
        data        : {id_fee_by_student: id_fee_by_student},
        success     : function(data){
            $('#FormDetailTagihan').html(data);
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
            url 	    : '_Page/Siswa/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    // Check/uncheck semua siswa
    $('input[name="check_all"]').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('#TabelSiswa input[name="id_student[]"]').prop('checked', isChecked);
    });

    // Jika semua siswa di-check manual, otomatis check_all ikut tercentang
    $(document).on('change', '#TabelSiswa input[name="id_student[]"]', function() {
        let total = $('#TabelSiswa input[name="id_student[]"]').length;
        let checked = $('#TabelSiswa input[name="id_student[]"]:checked').length;
        $('input[name="check_all"]').prop('checked', total === checked);
    });

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambah').on('show.bs.modal', function (e) {
        $('#NotifikasiTambah').html('');
    });

    //Proses Tambah Kelas
    $('#ProsesTambah').submit(function(){
        $('#NotifikasiTambah').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambah')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesTambah.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambah').html(data);
                var NotifikasiTambahBerhasil=$('#NotifikasiTambahBerhasil').html();
                if(NotifikasiTambahBerhasil=="Success"){
                   //Tutup Modal
                    $('#ModalTambah').modal('hide');

                    //Menampilkan Data
                    filterAndLoadTable();
                    Swal.fire(
                        'Success!',
                        'Tambah Siswa Berhasil!',
                        'success'
                    );
                    //Reset Form
                    $("#ProsesTambah")[0].reset();
                }
            }
        });
    });

    //Modal Detail
    $('#ModalDetail').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#FormDetail').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormDetail.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
    });

    //Modal Edit
    $('#ModalEdit').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#FormEdit').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormEdit.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormEdit').html(data);
                $('#NotifikasiEdit').html('');
            }
        });
    });

    //Proses Edit
    $('#ProsesEdit').submit(function(){
        $('#NotifikasiEdit').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesEdit')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesEdit.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();
                if(NotifikasiEditBerhasil=="Success"){
                    $('#NotifikasiEdit').html('');
                    $('#ModalEdit').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Ubah Siswa Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Hapus
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormHapus.php',
            data        : {id_student: id_student},
            success     : function(data){
                $('#FormHapus').html(data);
                $('#NotifikasiHapus').html('');
            }
        });
    });

    //Proses Hapus
    $('#ProsesHapus').submit(function(){
        $('#NotifikasiHapus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapus')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesHapus.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapus').html(data);
                var NotifikasisHapusBerhasil=$('#NotifikasisHapusBerhasil').html();
                if(NotifikasisHapusBerhasil=="Success"){
                    $('#NotifikasisHapus').html('');

                    //Tutup Modal
                    $('#ModalHapus').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Hapus Siswa Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Update Status Multiple
    $('#ModalUpdateStatus').on('show.bs.modal', function (e) {
        $('#FormUpdateStatus').html('<div class="row"><div class="col-md-12 text-center"><div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div></div></div>');
        var ProsesMultipleSiswa = $('#ProsesMultipleSiswa').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormUpdateStatus.php',
            data 	    :  ProsesMultipleSiswa,
            success     : function(data){
                $('#FormUpdateStatus').html(data);
                $('#NotifikasiUpdateStatus').html('');
            }
        });
    });

    //Proses Update Status
    $('#ProsesUpdateStatus').submit(function(){
        $('#NotifikasiUpdateStatus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesUpdateStatus')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesUpdateStatus.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateStatus').html(data);
                var NotifikasiUpdateStatusBerhasil=$('#NotifikasiUpdateStatusBerhasil').html();
                if(NotifikasiUpdateStatusBerhasil=="Success"){
                    $('#NotifikasiUpdateStatus').html('');

                    //Tutup Modal
                    $('#ModalUpdateStatus').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Update Status Siswa Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Update Kelas Multiple
    $('#ModalUpdateKelas').on('show.bs.modal', function (e) {
        $('#FormUpdateKelas').html('<div class="row"><div class="col-md-12 text-center"><div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div></div></div>');
        var ProsesMultipleSiswa = $('#ProsesMultipleSiswa').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormUpdateKelas.php',
            data 	    :  ProsesMultipleSiswa,
            success     : function(data){
                $('#FormUpdateKelas').html(data);
                $('#NotifikasiUpdateKelas').html('');
            }
        });
    });

    //Proses Update Kelas
    $('#ProsesUpdateKelas').submit(function(){
        $('#NotifikasiUpdateKelas').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesUpdateKelas')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesUpdateKelas.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateKelas').html(data);
                var NotifikasiUpdateKelasBerhasil=$('#NotifikasiUpdateKelasBerhasil').html();
                if(NotifikasiUpdateKelasBerhasil=="Success"){
                    $('#NotifikasiUpdateKelas').html('');

                    //Tutup Modal
                    $('#ModalUpdateKelas').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Update Kelas Siswa Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Jika Reset Form Import
    $('#ResetFormImport').submit(function(){

        //Reset Form Import
        $("#ProsesImportSiswa")[0].reset();

        //Kosongkan Table
        $('#NotifikasiImport').html('<tr><td colspan="4" class="text-center"><small class="text-danger">Belum Ada Proses Import</small></td></tr>');

        //Disable Button
        $('#ResetFormImport').prop('disabled', true);
    });

    //Proses Import Siswa
    $('#ProsesImportSiswa').submit(function(){
        var form = $('#ProsesImportSiswa')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesImportSiswa.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiImport').html(data);
            }
        });
    });

    //DETAIL SISWA
    // Cek apakah form dengan ID 'FormFilterTagihanSiswa' ada di halaman
    if ($("#FormFilterTagihanSiswa").length) {
        
        // Jika ada, panggil fungsi
        ShowTagihanSiswa();
    }

    //Modal Rincian Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalRincianTagihanSiswa"]', function () {
        //Tangkap Data Pada Tombol
        var id_organization_class = $(this).data('id_organization_class');
        var id_student = $(this).data('id_student');

        //Loading Row Table
        $('#TabelRincianTagihanSiswa').html('<tr><td colspan="8" class="text-center"><small>Loading...</small></td></tr>');

        //Menampilkan Rincian Tagihan Dengan AJAX melalui fungsi 'ShowRincianTagihan'
        ShowRincianTagihan(id_organization_class, id_student);
        
    });

    //Click 'modal_edit_tagihan'
    $(document).on('click', '.kembali_ke_rincian_tagihan', function () {
        //Sembunyikan Modal 'ModalDetailTagihan'
        $('#ModalTambahTagihanSiswa').modal('hide');

        //Sembunyikan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('hide');

        //Sembunyikan Modal 'ModalEditTagihan'
        $('#ModalEditTagihan').modal('hide');

        //Sembunyikan Modal 'ModalHapusTagihan'
        $('#ModalHapusTagihan').modal('hide');

        //Buka/Tampilkan Modal 'ModalEditTagihan'
        $('#ModalRincianTagihanSiswa').modal('show');
    });

    //Modal Tambah Rincian Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalTambahTagihanSiswa"]', function () {
        //Tangkap Data Pada Tombol
        var id_organization_class = $(this).data('id_organization_class');
        var id_student = $(this).data('id_student');

        //Loading Form
        $('#FormTambahTagihanSiswa').html('Loading...');

        //Menampilkan Form Tambah Tagihan Siswa Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormTambahTagihanSiswa.php',
            data        : {id_organization_class: id_organization_class, id_student: id_student},
            success     : function(data){
                $('#FormTambahTagihanSiswa').html(data);

                //Format Form untuk class 'form-money'
                initializeMoneyInputs();
            }
        });
    });

    

    //Proses Tambah Tagihan Siswa
    $('#ProsesTambahTagihanSiswa').submit(function(){

        //Tangkap Data Dari Form
        var ProsesTambahTagihanSiswa = $('#ProsesTambahTagihanSiswa').serialize();

        //Loading Notifikasi
        $('#NotifikasiTambahTagihanSiswa').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesTambahRincianTagihanSiswa.php',
            data 	    :  ProsesTambahTagihanSiswa,
            success     : function(data){
                $('#NotifikasiTambahTagihanSiswa').html(data);

                //Tangkap Notifikasi
                var NotifikasiTambahRincianTagihanSiswaBerhadil = $('#NotifikasiTambahRincianTagihanSiswaBerhadil').html();
                
                //tangkap 'id_organization_class' dan id_student
                var id_organization_class   = $('#id_organization_class_for_back').val();
                var id_student              = $('#id_student_for_back').val();
                
                if(NotifikasiTambahRincianTagihanSiswaBerhadil=="Success"){
                    $('#NotifikasiTambahTagihanSiswa').html('');

                    //Tutup Modal
                    $('#ModalTambahTagihanSiswa').modal('hide');

                    //Menampilkan Data
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Loading Row Table
                    $('#TabelRincianTagihanSiswa').html('<tr><td colspan="8" class="text-center"><small>Loading...</small></td></tr>');

                    //Menampilkan Rincian Tagihan Dengan AJAX
                    ShowRincianTagihan(id_organization_class, id_student);

                    //Loadd Ulang Tagihan Siswa
                    ShowTagihanSiswa();

                    //Load Ulang Rekapitulasi Tagihan Siswa
                    ShowRekapitulasiTagihan(id_student);

                    //reset form
                    $('#ProsesTambahTagihanSiswa')[0].reset();
                }
            }
        });
    });
    

    //Click 'modal_edit_tagihan'
    $(document).on('click', '.modal_edit_tagihan', function () {
        //Tangkap Data Pada Tombol
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal 'ModalEditTagihan'
        $('#ModalEditTagihan').modal('show');

        //Tutup Modal 'ModalEditTagihan'
        $('#ModalRincianTagihanSiswa').modal('hide');

        //Kosongkan Notifikasi
        $('#NotifikasiEditTagihan').html('');

        //Menampilkan Loading
        $('#FormEditTagihan').html('Loading...');

        //Menampilkan Form Edit Tagihan Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormEditTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormEditTagihan').html(data);
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Tagihan Siswa
    $('#ProsesEditTagihan').submit(function(){

        //Tangkap Data Dari Form
        var ProsesEditTagihan = $('#ProsesEditTagihan').serialize();

        //Loading Notifikasi
        $('#NotifikasiEditTagihan').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesEditTagihan.php',
            data 	    :  ProsesEditTagihan,
            dataType    : 'json',
            success     : function(response){
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;

                //Jika Berhasil
                if(status=="success"){

                    //Tutup 'ModalEditTagihan'
                    $('#ModalEditTagihan').modal('hide');

                    //Buka 'ModalRincianTagihanSiswa'
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Load Ulang Rincian Tagihan
                    ShowRincianTagihan(id_organization_class, id_student);

                    //Load Ulang Rekapitulasi Tagihan Siswa
                    ShowRekapitulasiTagihan(id_student);

                    //Menampilkan Tagihan Siswa Pada halaman detail siswa
                    ShowTagihanSiswa();

                }else{

                    //Jika Gagal, Tampilkan 'message'
                    $('#NotifikasiEditTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }

            }
        });
    });

    //Click 'modal_hapus_tagihan'
    $(document).on('click', '.modal_hapus_tagihan', function () {
        //Tangkap Data Pada Tombol
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal 'ModalEditTagihan'
        $('#ModalHapusTagihan').modal('show');

        //Tutup Modal 'ModalEditTagihan'
        $('#ModalRincianTagihanSiswa').modal('hide');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusTagihan').html('');

        //Menampilkan Loading
        $('#FormHapusTagihan').html('Loading...');

        //Menampilkan Form Edit Tagihan Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormHapusTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormHapusTagihan').html(data);
            }
        });
    });

    //Proses Hapus Tagihan Siswa
    $('#ProsesHapusTagihan').submit(function(){

        //Tangkap Data Dari Form
        var ProsesHapusTagihan = $('#ProsesHapusTagihan').serialize();

        //Loading Notifikasi
        $('#NotifikasiHapusTagihan').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesHapusTagihan.php',
            data 	    :  ProsesHapusTagihan,
            dataType    : 'json',
            success     : function(response){
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;

                //Jika Berhasil
                if(status=="success"){

                    //Tutup 'ModalHapusTagihan'
                    $('#ModalHapusTagihan').modal('hide');

                    //Buka 'ModalRincianTagihanSiswa'
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Load Ulang Rincian Tagihan
                    ShowRincianTagihan(id_organization_class, id_student);

                    //Load Ulang Rekapitulasi Tagihan Siswa
                    ShowRekapitulasiTagihan(id_student);

                    //Menampilkan Tagihan Siswa Pada halaman detail siswa
                    ShowTagihanSiswa();

                }else{

                    //Jika Gagal, Tampilkan 'message'
                    $('#NotifikasiHapusTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }

            }
        });
    });

    //Menampilkan Detail Tagihan
    $(document).on('click', '[data-bs-target="#ModalDetailTagihan"]', function () {
        //Tangkap Data Pada Tombol
        var id_fee_by_student = $(this).data('id');
        
        //Loading Pada Form
        $('#FormDetailTagihan').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');

        //Tampilkan 'FormDetailTagihan.php' dengan ajax melalui fungsi ShowDetailTagihan
        ShowDetailTagihan(id_fee_by_student);
    });

    //Click 'kembali_ke_detail_tagihan'
    $(document).on('click', '.kembali_ke_detail_tagihan', function () {
        //Sembunyikan Modal 'ModalTambahPembayaran'
        $('#ModalTambahPembayaran').modal('hide');

        //Sembunyikan Modal 'ModalHapusPembayaran'
        $('#ModalHapusPembayaran').modal('hide');

        //Buka/Tampilkan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('show');
    });

    //Menampilkan Modal Tambah Pembayaran
    $(document).on('click', '.modal_tambah_pembayaran', function () {
        //Tangkap Data Pada Tombol
        var id_fee_by_student = $(this).data('id');

        //Tampilkan Modal 'ModalTambahPembayaran'
        $('#ModalTambahPembayaran').modal('show');

        //Sembunyikan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('hide');

        //Loading Pada Form
        $('#FormTambahPembayaran').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahPembayaran').html('');

        //Menampilkan Form Tambah Pembayaran
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormTambahPembayaran.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormTambahPembayaran').html(data);

                //Format 'form-money'
                initializeMoneyInputs();
            }
        });

    });

    //Proses Tambah Pembayaran
    $('#ProsesTambahPembayaran').submit(function(){

        //Tangkap Data Dari Form
        var ProsesTambahPembayaran = $('#ProsesTambahPembayaran').serialize();

        //Loading Notifikasi
        $('#NotifikasiTambahPembayaran').html("Loading...");

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

                    //Tutup 'ModalTambahPembayaran'
                    $('#ModalTambahPembayaran').modal('hide');

                    //Buka 'ModalDetailTagihan'
                    $('#ModalDetailTagihan').modal('show');

                    //Load Ulang Detail Pembayaran Tagihan
                    ShowDetailTagihan(id_fee_by_student);

                    //Load Ulang Rincian Tagihan
                    ShowRincianTagihan(id_organization_class, id_student);

                    //Load Ulang Rekapitulasi Tagihan Siswa
                    ShowRekapitulasiTagihan(id_student);

                    //Menampilkan Tagihan Siswa Pada halaman detail siswa
                    ShowTagihanSiswa();

                }else{

                    //Jika Gagal, Tampilkan 'message'
                    $('#NotifikasiTambahPembayaran').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }

            }
        });
    });

    //Menampilkan Modal Hapus Pembayaran
    $(document).on('click', '.modal_hapus_pembayaran', function () {
        //Tangkap Data Pada Tombol
        var id_payment = $(this).data('id_payment');
        var id_fee_by_student = $(this).data('id_fee_by_student');

        //Tampilkan Modal 'ModalTambahPembayaran'
        $('#ModalHapusPembayaran').modal('show');

        //Sembunyikan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('hide');

        //Loading Pada Form
        $('#FormHapusPembayaran').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusPembayaran').html('');

        //Menampilkan Form Tambah Pembayaran
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/FormHapusPembayaran.php',
            data        : {id_payment: id_payment, id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormHapusPembayaran').html(data);
            }
        });

    });

    //Proses Hapus Pembayaran
    $('#ProsesHapusPembayaran').submit(function(){

        //Tangkap Data Dari Form
        var ProsesHapusPembayaran = $('#ProsesHapusPembayaran').serialize();

        //Loading Notifikasi
        $('#NotifikasiHapusPembayaran').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Siswa/ProsesHapusPembayaran.php',
            data 	    :  ProsesHapusPembayaran,
            dataType    : 'json',
            success     : function(response){
                var status                  = response.status;
                var message                 = response.message;
                var id_fee_by_student       = response.id_fee_by_student;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;

                //Jika Berhasil
                if(status=="success"){

                    //Tutup 'ModalHapusPembayaran'
                    $('#ModalHapusPembayaran').modal('hide');

                    //Buka 'ModalDetailTagihan'
                    $('#ModalDetailTagihan').modal('show');

                    //Load Ulang Detail Pembayaran Tagihan
                    ShowDetailTagihan(id_fee_by_student);

                    //Load Ulang Rincian Tagihan
                    ShowRincianTagihan(id_organization_class, id_student);

                    //Load Ulang Rekapitulasi Tagihan Siswa
                    ShowRekapitulasiTagihan(id_student);

                    //Menampilkan Tagihan Siswa Pada halaman detail siswa
                    ShowTagihanSiswa();

                }else{

                    //Jika Gagal, Tampilkan 'message'
                    $('#NotifikasiHapusPembayaran').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }

            }
        });
    });


    //Menampilkan Rekapitulasi Tagihan
    $(document).on('click', '.modal_rekapitulasi_tagihan_siswa', function () {
        //Tangkap Data Pada Tombol
        var id_student = $(this).data('id');

        //Tampilkan Modal
        $('#ModalRekapitulasiTagihanSiswa').modal('show');
        
        //Loading Pada Form
        $('#FormRekapitulasiTagihanSiswa').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');

        //Menampilkan RRekapitulasi dengan fungsi 'ShowRekapitulasiTagihan
        ShowRekapitulasiTagihan(id_student);
    });

    
});