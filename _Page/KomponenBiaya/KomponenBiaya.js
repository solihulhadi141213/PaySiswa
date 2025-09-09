//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var id_academic_period = $('#id_academic_period').val();

    // Efek transisi: fadeOut dulu
    $('#TabelKomponenBiaya').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/KomponenBiaya/TabelKomponenBiaya.php',
            data    : {id_academic_period: id_academic_period},
            success : function(data) {
                $('#TabelKomponenBiaya').html(data);

                // Setelah ganti konten → fadeIn lagi
                $('#TabelKomponenBiaya').fadeIn(200);
            }
        });
    });
}
//Fungsi Data List Kategori
function ShowDataListKategori(ElementIdName) {
    $.ajax({
        type    : 'POST',
        url     : '_Page/KomponenBiaya/ListKategori.php',
        success : function(data) {
            $(ElementIdName).html(data);
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

    //Format Uang Pertama kali
    initializeMoneyInputs();

    //Tampilkan Data Pertama kali
    filterAndLoadTable();

    //Ketika id_academic_period Diubah
    $('#id_academic_period').change(function(){
        filterAndLoadTable();
    });

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambah').on('show.bs.modal', function (e) {
        //Tangkap id_academic_period
        var id_academic_period = $('#id_academic_period').val();

        //tempelkan id_academic_period ke id_academic_period_tambah
        $('#id_academic_period_tambah').val(id_academic_period);

        //Kosongkan Notifikasi
        $('#NotifikasiTambah').html('');

        //Apabila id_academic_period kosong beri tahu
        if(id_academic_period==""){
            $('#NotifikasiTambah').html('<div class="alert alert-danger"><small>Periode Akademik Belum Dipilih!</small></div>');

            //Disable tombol
            $('#TombolSimpan').prop('disabled', true);
        }else{
            $('#NotifikasiTambah').html('');

            //Enable tombol
            $('#TombolSimpan').prop('disabled', false);
        }
    });

    //Proses Tambah Kelas
    $('#ProsesTambah').submit(function(){
        $('#NotifikasiTambah').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambah')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesTambah.php',
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
                        'Tambah KomponenBiaya Berhasil!',
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
        var id_fee_component = $(e.relatedTarget).data('id');
        $('#FormDetail').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormDetail.php',
            data        : {id_fee_component: id_fee_component},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
    });

    //Modal Edit
    $('#ModalEdit').on('show.bs.modal', function (e) {
        var id_fee_component = $(e.relatedTarget).data('id');
        $('#FormEdit').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormEdit.php',
            data        : {id_fee_component: id_fee_component},
            success     : function(data){
                $('#FormEdit').html(data);
                $('#NotifikasiEdit').html('');
                initializeMoneyInputs();
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
            url 	    : '_Page/KomponenBiaya/ProsesEdit.php',
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
                        'Ubah KomponenBiaya Berhasil!',
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
        var id_fee_component = $(e.relatedTarget).data('id');
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormHapus.php',
            data        : {id_fee_component: id_fee_component},
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
            url 	    : '_Page/KomponenBiaya/ProsesHapus.php',
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
                        'Hapus KomponenBiaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Ketika Modal Copy Muncul
    $('#ModalCopy').on('show.bs.modal', function (e) {
        //Tangkap id_academic_period
        var id_academic_period = $('#id_academic_period').val();

        //tempelkan id_academic_period ke id_academic_period_tambah
        $('#periode_tujuan').val(id_academic_period);

        //Kosongkan Notifikasi
        $('#NotifikasiTambah').html('');

        //Apabila id_academic_period kosong beri tahu
        if(id_academic_period==""){
            $('#NotifikasiCopy').html('<div class="alert alert-danger"><small>Periode Akademik Belum Dipilih!</small></div>');

            //Disable tombol
            $('#TombolCopy').prop('disabled', true);
        }else{
            $('#NotifikasiCopy').html('');

            //Enable tombol
            $('#TombolCopy').prop('disabled', false);
        }
    });

    //Proses Copy
    $('#ProsesCopy').submit(function(){
        $('#NotifikasiCopy').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesCopy')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesCopy.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiCopy').html(data);
                var NotifikasiCopyBerhasil=$('#NotifikasiCopyBerhasil').html();
                if(NotifikasiCopyBerhasil=="Success"){
                    $('#NotifikasiCopy').html('');

                    //Tutup Modal
                    $('#ModalCopy').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Copy Komponen Biaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });
    
});