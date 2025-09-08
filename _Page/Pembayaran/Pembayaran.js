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

//Fungsi Menampilkan Modal Komponen Biaya
function ShowKomponenBiaya(id_student) {
    $('#FormKomponenBiaya').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Pembayaran/FormKomponenBiaya.php',
        data        : {id_student: id_student},
        success     : function(data){
            $('#FormKomponenBiaya').html(data);
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
    $('#ModalKomponenBiaya').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        ShowKomponenBiaya(id_student);
    });

    //Modal Bayar
    $('#ModalBayar').on('show.bs.modal', function (e) {
        var id_fee_by_student = $(e.relatedTarget).data('id');
        $('#FormBayar').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormBayar.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormBayar').html(data);
                $('#NotifikasiBayar').html('');
                initializeMoneyInputs();
            }
        });
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

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalKomponenBiaya').modal('show');
                    ShowKomponenBiaya(id_student);

                    //Reload Tabel Tagihan
                    filterAndLoadTable();

                     //Tutup Modal 'ModalBayar'
                    $('#ModalBayar').modal('hide');
                }
            }
        });
    });

    //Modal Detail Tagihan
    $('#ModalDetailTagihan').on('show.bs.modal', function (e) {
        var id_fee_by_student = $(e.relatedTarget).data('id');
        $('#FormDetailTagihan').html("Loading...");
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
    $('#ModalEditTagihan').on('show.bs.modal', function (e) {
        var id_fee_by_student = $(e.relatedTarget).data('id');
        $('#FormEditTagihan').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormEditTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormEditTagihan').html(data);
                $('#NotifikasiEditTagihan').html('');
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Tagihan
    $('#ProsesEditTagihan').submit(function(){
               
        //Loading
        $('#NotifikasiEditTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Get Data Form
        var ProsesEditTagihan = $('#ProsesEditTagihan').serialize();

        //Simpan Data Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/ProsesUbahTagihan.php',
            data 	    :  ProsesEditTagihan,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditTagihan').html(data);

                //Tangkap id_siswa
                var id_student=$('#get_id_student3').val();

                //Tangkap Notifikasi
                var NotifikasiUbahTagihanBerhasil=$('#NotifikasiUbahTagihanBerhasil').html();

                //Jika Berhasil
                if(NotifikasiUbahTagihanBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalEditTagihan').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalKomponenBiaya').modal('show');
                    ShowKomponenBiaya(id_student);

                    //Reload Tabel Tagihan
                    filterAndLoadTable();
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
            success     : function(data){
                $('#NotifikasiHapusTagihan').html(data);

                //Tangkap id_siswa
                var id_student=$('#get_id_student_hapus_tagihan').val();

                //Tangkap Notifikasi
                var NotifikasiHapusTagihanBerhasil=$('#NotifikasiHapusTagihanBerhasil').html();

                //Jika Berhasil
                if(NotifikasiHapusTagihanBerhasil=="Success"){

                    //Tutup Modal 'ModalBayar'
                    $('#ModalHapusTagihan').modal('hide');

                    //Buka Modal 'ModalTagihanSiswa'
                    $('#ModalKomponenBiaya').modal('show');
                    ShowKomponenBiaya(id_student);

                    //Reload Tabel Tagihan
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Detail Pembayaran
    $('#ModalDetail').on('show.bs.modal', function (e) {
        var id_payment = $(e.relatedTarget).data('id');
        $('#FormDetail').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Pembayaran/FormDetailPembayaran.php',
            data        : {id_payment: id_payment},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
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
    
});