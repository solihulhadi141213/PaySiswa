function ShowConnectionSetting() {
    $('#ConnectionSetting').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingPayment/ConnectionSetting.php',
        success     : function(data){
            $('#ConnectionSetting').html(data);
        }
    });
}

function ShowProfileSetting() {
    $('#TabelProfileSetting').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingPayment/TabelProfileSetting.php',
        success     : function(data){
            $('#TabelProfileSetting').html(data);
        }
    });
}
function generateUUID() {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    var uuidFormat = [8, 4, 4, 4, 12]; // Pola UUID

    var uuid = '';
    for (var i = 0; i < uuidFormat.length; i++) {
        if (i > 0) uuid += '-'; // Tambahkan tanda '-' di antara bagian UUID
        for (var j = 0; j < uuidFormat[i]; j++) {
            uuid += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
    }
    return uuid;
}
function generateCustomCode() {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    var uniqueCode = 'PRCB-'; // Awalan 'PRCB-'

    // Generate 31 karakter acak
    for (var i = 0; i < 31; i++) {
        uniqueCode += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return uniqueCode;
}

// Fungsi untuk memformat angka dengan tanda titik setiap ribuan
function formatRupiah(angka) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(), // Hapus karakter selain angka dan koma
        split = number_string.split(','), 
        sisa = split[0].length % 3, 
        rupiah = split[0].substr(0, sisa), 
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
    // Tambahkan titik jika ada ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah;
}

$(document).ready(function() {
    //Menampilkan Setting Koneksi
    ShowConnectionSetting();

    //Menampilkan Tabel Profil Setting
    ShowProfileSetting();

    //Modal Setting Koneksi
    $('#ModalSettingKoneksi').on('show.bs.modal', function (e) {
        $('#FormSettingKoneksi').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormSettingKoneksi.php',
            success     : function(data){
                $('#FormSettingKoneksi').html(data);

                //Kosongkan Notifikasi
                $('#NotifikasiSettingKoneksi').html('');

            }
        });
    });

    //Simpan Setting Koneksi
    $('#ProsesSettingKoneksi').on('submit', function(e) {
        // Mencegah form dari submit secara default
        e.preventDefault(); 

        // Mengambil data dari form
        var formData = new FormData(this);

        // Loading
        $('#NotifikasiSettingKoneksi').html('Loading...');

        // Mengirimkan data melalui AJAX
        $.ajax({
            url         : '_Page/SettingPayment/ProsesSettingKoneksi.php',
            method      : 'POST',
            data        : formData,
            contentType : false,
            processData : false,
            success: function(response) {
                $('#NotifikasiSettingKoneksi').html(response);
                var NotifikasiSettingKoneksiBerhasil=$('#NotifikasiSettingKoneksiBerhasil').html();
                if(NotifikasiSettingKoneksiBerhasil=="Success"){

                    //Jika berhasil tutup modal
                    $('#ModalSettingKoneksi').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Pengaturan Koneksi Payment Gateway Berhasil Disimpan!',
                        'success'
                    );

                    //Tampilkan ulang status koneksi
                    ShowConnectionSetting();
                }
            }
        });
    });

    //Modal Test Koneksi
    $('#ModalTestKoneksi').on('show.bs.modal', function (e) {
        $('#FormTestKoneksi').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormTestKoneksi.php',
            success     : function(data){
                $('#FormTestKoneksi').html(data);
            }
        });
    });

    //Modal Tambah Profil Pengaturan
    $('#ModalTambahProfilPaymentGateway').on('show.bs.modal', function (e) {
        $('#FormTambahProfilPaymentGateway').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormTambahProfilPaymentGateway.php',
            success     : function(data){
                $('#FormTambahProfilPaymentGateway').html(data);

                //Kosongkan Notifikasi
                $('#NotifikasiTambahProfilPaymentGateway').html('');

            }
        });
    });

    //Proses Tambah Profil Payment Gateway
    $('#ProsesTambahProfilPaymentGateway').on('submit', function(e) {
        e.preventDefault();
        
        // Mengambil data dari form
        var formData = new FormData(this);
        
        // Tombol diubah menjadi "Loading..." saat proses
        $('#NotifikasiTambahProfilPaymentGateway').html('Loading...');
        
        // Mengirimkan data melalui AJAX
        $.ajax({
            url         : '_Page/SettingPayment/ProsesTambahProfilPaymentGateway.php',
            method      : 'POST',
            data        : formData,
            contentType : false,
            processData : false,
            success: function(response) {
                $('#NotifikasiTambahProfilPaymentGateway').html(response);
                var NotifikasiTambahProfilPaymentGatewayBerhasil=$('#NotifikasiTambahProfilPaymentGatewayBerhasil').html();
                if (NotifikasiTambahProfilPaymentGatewayBerhasil=='Success') {
                    //Jika Berhasil Tutup Modal
                    $('#ModalTambahProfilPaymentGateway').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Profil Pengaturan Berhasil Ditambahkan!',
                        'success'
                    );

                    //Tampilkan ulang status koneksi
                    ShowProfileSetting();
                }
            }
        });
    });

    //Modal Detail Profil Payment Gateway
    $('#ModalDetailProfilPaymentGateway').on('show.bs.modal', function (e) {
        var id_setting_payment = $(e.relatedTarget).data('id');
        $('#FormDetailProfil').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormDetailProfil.php',
            data        : {id_setting_payment: id_setting_payment},
            success     : function(data){
                $('#FormDetailProfil').html(data);
            }
        });
    });

    //Modal Edit Profil Payment Gateway
    $('#ModalEditProfilPaymentGateway').on('show.bs.modal', function (e) {
        var id_setting_payment = $(e.relatedTarget).data('id');

        //Loading Form
        $('#FormEditProfilPaymentGateway').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditProfilPaymentGateway').html('');

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormEditProfilPaymentGateway.php',
            data        : {id_setting_payment: id_setting_payment},
            success     : function(data){
                $('#FormEditProfilPaymentGateway').html(data);
            }
        });
    });

    //Proses Edit Profil Payment Gateway
    $('#ProsesEditProfilPaymentGateway').on('submit', function(e) {
        e.preventDefault();
        
        // Mengambil data dari form
        var formData = new FormData(this);
        
        // Tombol diubah menjadi "Loading..." saat proses
        $('#NotifikasiEditProfilPaymentGateway').html('Loading...');
        
        // Mengirimkan data melalui AJAX
        $.ajax({
            url         : '_Page/SettingPayment/ProsesEditProfilPaymentGateway.php',
            method      : 'POST',
            data        : formData,
            contentType : false,
            processData : false,
            success: function(response) {
                $('#NotifikasiEditProfilPaymentGateway').html(response);
                var NotifikasiEditProfilPaymentGatewayBerhasil=$('#NotifikasiEditProfilPaymentGatewayBerhasil').html();
                if (NotifikasiEditProfilPaymentGatewayBerhasil=='Success') {
                    //Jika Berhasil Tutup Modal
                    $('#ModalEditProfilPaymentGateway').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Profil Pengaturan Berhasil Disimpan!',
                        'success'
                    );

                    //Tampilkan ulang status koneksi
                    ShowProfileSetting();
                }
            }
        });
    });

    //Modal Hapus Profil Payment Gateway
    $('#ModalHapusProfilPaymentGateway').on('show.bs.modal', function (e) {
        var id_setting_payment = $(e.relatedTarget).data('id');

        //Loading Form
        $('#FormHapusProfilPaymentGateway').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiHapusProfilPaymentGateway').html('');

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormHapusProfilPaymentGateway.php',
            data        : {id_setting_payment: id_setting_payment},
            success     : function(data){
                $('#FormHapusProfilPaymentGateway').html(data);
            }
        });
    });

    //Proses Hapus Profil Payment Gateway
    $('#ProsesHapusProfilPaymentGateway').on('submit', function(e) {
        e.preventDefault();
        
        // Mengambil data dari form
        var formData = new FormData(this);
        
        // Tombol diubah menjadi "Loading..." saat proses
        $('#NotifikasiHapusProfilPaymentGateway').html('Loading...');
        
        // Mengirimkan data melalui AJAX
        $.ajax({
            url         : '_Page/SettingPayment/ProsesHapusProfilPaymentGateway.php',
            method      : 'POST',
            data        : formData,
            contentType : false,
            processData : false,
            success: function(response) {
                $('#NotifikasiHapusProfilPaymentGateway').html(response);
                var NotifikasiHapusProfilPaymentGatewayBerhasil=$('#NotifikasiHapusProfilPaymentGatewayBerhasil').html();
                if (NotifikasiHapusProfilPaymentGatewayBerhasil=='Success') {
                    //Jika Berhasil Tutup Modal
                    $('#ModalHapusProfilPaymentGateway').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Profil Pengaturan Berhasil Dihapus!',
                        'success'
                    );

                    //Tampilkan ulang status koneksi
                    ShowProfileSetting();
                }
            }
        });
    });

    //Modal Creat Snap Token
    $('#ModalCreatSnapToken').on('show.bs.modal', function (e) {
        //Loading Form
        $('#FormCreatSnapToken').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiCreatSnapToken').html('');

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingPayment/FormCreatSnapToken.php',
            success     : function(data){
                $('#FormCreatSnapToken').html(data);
            }
        });
    });

    // Tombol Generate Kode Transaksi dengan Event Delegation
    $(document).on('click', '#GenerateKodeTransaksi', function() {
        var kode_transaksi = generateCustomCode(); 
        $('#kode_transaksi').val(kode_transaksi);
    });

    // Tombol Generate Order ID dengan Event Delegation
    $(document).on('click', '#GenerateOrderId', function() {
        var uniqueCode = generateUUID();
        $('#order_id').val(uniqueCode);
    });

    // Gross Amount -> hanya angka & auto format rupiah dengan Event Delegation
    $(document).on('input', '#gross_amount', function() {
        var input = $(this).val();
        $(this).val(formatRupiah(input));
    });

    $(document).on('keypress', '#gross_amount', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });

    // Phone -> hanya angka
    $(document).on('keypress', '#phone', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });
    

});


//Proses Simpan Setting Payment
$('#ProsesSettingPayment').on('submit', function(e) {
    e.preventDefault(); // Mencegah form dari submit secara default
    // Mengambil data dari form
    var formData = new FormData(this);
    // Tombol diubah menjadi "Loading..." saat proses
    var $submitButton = $('#NotifikasiSimpanSettingPayment');
    $submitButton.html('Loading...').prop('disabled', true);
    // Mengirimkan data melalui AJAX
    $.ajax({
        url: '_Page/SettingPayment/ProsesSettingPayment.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Jika proses berhasil, reload halaman
                window.location.reload();
            } else {
                // Tampilkan notifikasi error jika gagal
                Swal.fire(
                    'Gagal!',
                    response.message,
                    'error'
                );
                // Kembalikan tombol ke keadaan semula
                $submitButton.html('<i class="bi bi-save"></i> Simpan Pengaturan').prop('disabled', false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Tampilkan pesan jika terjadi kesalahan pada server
            Swal.fire(
                'Gagal!',
                'Terjadi kesalahan pada server, coba lagi nanti. (' + textStatus + ': ' + errorThrown + ')',
                'error'
            );
            // Kembalikan tombol ke keadaan semula
            $submitButton.html('<i class="bi bi-save"></i> Simpan Pengaturan').prop('disabled', false);
        },
        complete: function() {
            // Kembalikan tombol ke keadaan semula
            $submitButton.html('<i class="bi bi-save"></i> Simpan Pengaturan').prop('disabled', false);
        }
    });
});

//Proses Generate Snap Token
$('#GenerateSnapToken').on('click', function(e) {
    e.preventDefault();
    
    // Pastikan elemen form diambil dengan benar
    var formData = new FormData($('#ProsesGenerateSnapButton')[0]); 
    var $GenerateSnapToken = $('#GenerateSnapToken');
    
    // Ubah text dan disable tombol saat proses berjalan
    $GenerateSnapToken.html('<code class="text text-success">Loading...</code>').prop('disabled', true);
    
    // Mengirimkan data melalui AJAX
    $.ajax({
        url: '_Page/SettingPayment/GenerateSnapToken.php',
        method: 'POST',
        data: formData,
        contentType: false, // Biarkan browser menentukan tipe konten
        processData: false, // Jangan memproses data sebagai string query
        dataType: 'json',   // Harapkan respons JSON
        success: function(response) {
            if (response.status === 'success') {
                // Jika berhasil, masukkan snap token ke input
                var snap_token = response.token;
                $('#snap_token').val(snap_token);
            } else {
                // Tampilkan notifikasi error jika gagal
                Swal.fire(
                    'Gagal!',
                    response.message,
                    'error'
                );
            }
        },
        error: function(xhr, status, error) {
            // Tampilkan pesan jika terjadi kesalahan pada server
            Swal.fire(
                'Gagal!',
                'Terjadi kesalahan pada server, coba lagi nanti.',
                'error'
            );
            console.error("Error details:", status, error);
            $GenerateSnapToken.html('<code class="text text-success">Generate</code>').prop('disabled', false);
        },
        complete: function() {
            // Kembalikan tombol ke keadaan semula
            $GenerateSnapToken.html('<code class="text text-success">Generate</code>').prop('disabled', false);
        }
    });
});

//Generate Snap Button
$('#ProsesGenerateSnapButton').submit(function(){
    $('#NotifikasiGenerateSnapButton').html("Loading..");
    $('#ModalSnapButton').modal('show');
    var form = $('#ProsesGenerateSnapButton')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingPayment/GenerateSnapButton.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiGenerateSnapButton').html(data);
        }
    });
});
//Ketika KeywordBy Diubah
$('#KeywordBy').change(function(){
    var KeywordBy = $('#KeywordBy').val();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingPayment/FormFilter.php',
        data        : {KeywordBy: KeywordBy},
        success     : function(data){
            $('#FormFilter').html(data);
        }
    });
});
//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/SettingPayment/TabelPaymentLog.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelPaymentLog').html(data);
        }
    });
}
//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();
});
//Filter Data
$('#ProsesFilter').submit(function(){
    $('#page').val("1");
    filterAndLoadTable();
    $('#ModalFilter').modal('hide');
});
//Reload Data
$('#ReloadPaymentLog').click(function(){
    $('#page').val("1");
    filterAndLoadTable();
});
//Modal Detail Order Transaksi
$('#ModalDetailOrderTransaksi').on('show.bs.modal', function (e) {
    var id_order_transaksi = $(e.relatedTarget).data('id');
    $('#FormDetailOrderTransaksi').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingPayment/FormDetailOrderTransaksi.php',
        data        : {id_order_transaksi: id_order_transaksi},
        success     : function(data){
            $('#FormDetailOrderTransaksi').html(data);
        }
    });
});