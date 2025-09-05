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
// Ketika tombol GenerateKodeTransaksi di klik
$('#GenerateKodeTransaksi').on('click', function() {
    var kode_transaksi = generateCustomCode(); // Generate kode dengan format 'PRCB-[unik 31 karakter]'
    $('#kode_transaksi').val(kode_transaksi); // Mengisi input dengan kode yang dihasilkan
});
// Ketika tombol GenerateOrderId di klik
$('#GenerateOrderId').on('click', function() {
    var uniqueCode = generateUUID(); // Generate kode unik 36 karakter
    $('#order_id').val(uniqueCode); // Mengisi input order_id dengan kode unik
});
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
// Event untuk mencegah karakter selain angka
$('#gross_amount').on('input', function(e) {
    var input = $(this).val();
    $(this).val(formatRupiah(input)); // Format nilai input menjadi format rupiah
});

// Mencegah karakter selain angka yang diinput
$('#gross_amount').on('keypress', function(e) {
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) { // Hanya izinkan angka (0-9)
        e.preventDefault();
    }
});
// Mencegah karakter selain angka yang diinput
$('#phone').on('keypress', function(e) {
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) { // Hanya izinkan angka (0-9)
        e.preventDefault();
    }
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