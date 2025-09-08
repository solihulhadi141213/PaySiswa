//Fungsi Menampilkan Data
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

                // Setelah ganti konten → fadeIn lagi
                $('#TabelTagihan').fadeIn(200);
            }
        });
    });
}

//Fungsi Show Form Tagihan Siswa
function ShowTagihanSiswa(id_student) {
    $('#FormTagihanSiswa').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Tagihan/FormTagihanSiswa.php',
        data        : {id_student: id_student},
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

    //Menampilkan Data Pertama Kali
    FilterTagihan();

    //Ketika Filter Di submit
    $('#ProsesFilterTagihan').submit(function(){
        FilterTagihan();
    });

    //Jika keyword_by diubah
     $('#keyword_by').change(function(){
        var keyword_by = $('#keyword_by').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Tagihan/form_filter.php',
            data        : {keyword_by: keyword_by},
            success     : function(data){
                $('#form_filter').html(data);
            }
        });
    });

    //Pagging
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


    //Modal Detail Siswa
    $('#ModalDetailSiswa').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#FormDetailSiswa').html("Loading...");
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
    $('#ModalTagihanSiswa').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        ShowTagihanSiswa(id_student);
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

});
