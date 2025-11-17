//Fungsi Menampilkan Data
function filterAndLoadTable() {

    //uncheck checkbox
    $('input[name="check_all"]').prop('checked', false);

    //Menangkap 'id_academic_period'
    var id_academic_period = $('[name="id_academic_period"]:checked').val();

    //Tampilkan 'TabelKomponenBiaya' dengan AJAX
    $.ajax({
        type    : 'POST',
        url     : '_Page/KomponenBiaya/TabelKomponenBiaya.php',
        data    : {id_academic_period: id_academic_period},
        success : function(data) {
            $('#TabelKomponenBiaya').html(data);
            
            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
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

    //Loading Table
    $('#TabelKomponenBiaya').html('<tr><td class="text-center" colspan="10"><small>Loading...</small></td></tr>');

    //Inisiasi Data Komponen Biya Pertama kali
    filterAndLoadTable();

    // Check/uncheck semua siswa
    $('input[name="check_all"]').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('#TabelKomponenBiaya input[name="id_fee_component[]"]').prop('checked', isChecked);
    });

    //Ketika 'TombolTampilkan' Di Click
    $('#TombolTampilkan').click(function(){

        //Loading Table 
        $('#TabelKomponenBiaya').html('<tr><td class="text-center" colspan="10"><small>Loading...</small></td></tr>');

        //Panggil Fungsi
        filterAndLoadTable();

        //Tutup Modal
        $('#ModalPilihPeriodeAkademik').modal('hide');

    });

    //Format Uang Pertama kali
    initializeMoneyInputs();

    //Ketika id_academic_period Diubah
    $('#id_academic_period').change(function(){
        filterAndLoadTable();
    });

    //Ketika Modal Copy Muncul
    $('.button_copy_komponen_biaya').on('click', function() {
        //Tangkap 'id_academic_period'
        var id_academic_period = $('[name="id_academic_period"]:checked').val();

        //Menampilkan modal 'ModalCopy'
        $('#ModalCopy').modal('show');

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

    $('.button_export_komponen_biaya').on('click', function() {
        //Tangkap 'id_academic_period'
        var id_academic_period = $('[name="id_academic_period"]:checked').val();

        //Menampilkan modal 'ModalExport'
        $('#ModalExport').modal('show');
        
        //Loading 'FormExport'
        $('#FormExport').html('Loading...');

        //Buka Form Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormExport.php',
            data 	    :  {id_academic_period: id_academic_period},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormExport').html(data);
            }
        });
    });

    //Ketika Modal Tambah Fitur Muncul
    $('.button_tambah_komponen').on('click', function() {

        //Tangkap id_academic_period
        var id_academic_period = $('[name="id_academic_period"]:checked').val();

        //Tampilkan Modal
        $('#ModalTambah').modal('show');

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
    $(document).on('click', '.detail_komponen_biaya_pendidikan', function(){

        //Tangkap Data
        var id_fee_component = $(this).data('id');

        //Tampilkan Modal
        $('#ModalDetail').modal('show');

        //Loading Form
        $('#FormDetail').html("Loading...");

        //Tampilkan Form Detail Dengan AJAX
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

    //Modal Edit Kategori Multiple
    $('#ModalEditKategoriMultiple').on('show.bs.modal', function (e) {

        //Tangkap Data Dari Form
        var ProsesMultipleKomponenBiaya = $('#ProsesMultipleKomponenBiaya').serialize();

        //Loading Form
        $('#FormEditKategoriMultiple').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditKategoriMultiple').html("");

        //Disable Button
        $("#button_edit_kategori_multiple").prop("disabled", true);

        //Buka Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormEditKategoriMultiple.php',
            data        : ProsesMultipleKomponenBiaya,
            success     : function(data){
                $('#FormEditKategoriMultiple').html(data);
            }
        });
    });

    //Proses Edit Kategori Multiple
    $('#ProsesEditKategoriMultiple').submit(function(){

        //Loading Notifikasi
        $('#NotifikasiEditKategoriMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data
        var form = $('#ProsesEditKategoriMultiple')[0];
        var data = new FormData(form);

        //Hapus Multiple Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesEditKategoriMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status  = response.status;
                var message = response.message;
                
                //ika status 'success'
                if(status=="success"){

                    //Kosongkan Notifikasi
                    $('#NotifikasiEditKategoriMultiple').html('');

                    //Tutup Modal
                    $('#ModalEditKategoriMultiple').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Edit Kategori Komponen Biaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }else{
                    $('#NotifikasiHapusMultiple').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Edit Tahun Multiple
    $('#ModalEditTahunMultiple').on('show.bs.modal', function (e) {

        //Tangkap Data Dari Form
        var ProsesMultipleKomponenBiaya = $('#ProsesMultipleKomponenBiaya').serialize();

        //Loading Form
        $('#FormEditTahunMultiple').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditTahunMultiple').html("");

        //Disable Button
        $("#button_edit_tahun_multiple").prop("disabled", true);

        //Buka Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormEditTahunMultiple.php',
            data        : ProsesMultipleKomponenBiaya,
            success     : function(data){
                $('#FormEditTahunMultiple').html(data);
            }
        });
    });

    //Proses Ubah Tahun Multiple
    $('#ProsesEditTahunMultiple').submit(function(){

        //Loading Notifikasi
        $('#NotifikasiEditTahunMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data
        var form = $('#ProsesEditTahunMultiple')[0];
        var data = new FormData(form);

        //Hapus Multiple Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesEditTahunMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status = response.status;
                var message = response.message;
                
                //ika status 'success'
                if(status=="success"){

                    //Kosongkan Notifikasi
                    $('#NotifikasiEditTahunMultiple').html('');

                    //Tutup Modal
                    $('#ModalEditTahunMultiple').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Ubah Periode Tahun Komponen Biaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }else{
                    $('#NotifikasiEditTahunMultiple').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Edit Tarif Multiple
    $('#ModalEditTarifMultiple').on('show.bs.modal', function (e) {

        //Tangkap Data Dari Form
        var ProsesMultipleKomponenBiaya = $('#ProsesMultipleKomponenBiaya').serialize();

        //Loading Form
        $('#FormEditTarifMultiple').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditTarifMultiple').html("");

        //Disable Button
        $("#button_edit_tarif_multiple").prop("disabled", true);

        //Buka Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormEditTarifMultiple.php',
            data        : ProsesMultipleKomponenBiaya,
            success     : function(data){
                $('#FormEditTarifMultiple').html(data);

                //Format Uang untuk Form 'form-money'
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Tarif Multiple
    $('#ProsesEditTarifMultiple').submit(function(){

        //Loading Notifikasi
        $('#NotifikasiEditTarifMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data
        var form = $('#ProsesEditTarifMultiple')[0];
        var data = new FormData(form);

        //Hapus Multiple Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesEditTarifMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status = response.status;
                var message = response.message;
                
                //ika status 'success'
                if(status=="success"){

                    //Kosongkan Notifikasi
                    $('#NotifikasiEditTarifMultiple').html('');

                    //Tutup Modal
                    $('#ModalEditTarifMultiple').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Update Tarif Komponen Biaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }else{
                    $('#NotifikasiEditTarifMultiple').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Multiple
    $('#ModalHapusMultiple').on('show.bs.modal', function (e) {

        //Tangkap Data Dari Form
        var ProsesMultipleKomponenBiaya = $('#ProsesMultipleKomponenBiaya').serialize();

        //Loading Form
        $('#FormHapusMultiple').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiHapusMultiple').html("");

        //Disable Button
        $("#button_hapus_multiple").prop("disabled", true);

        //Buka Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormHapusMultiple.php',
            data        : ProsesMultipleKomponenBiaya,
            success     : function(data){
                $('#FormHapusMultiple').html(data);
            }
        });
    });

    //Proses Hapus Multiple
    $('#ProsesHapusMultiple').submit(function(){

        //Loading Notifikasi
        $('#NotifikasiHapusMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data
        var form = $('#ProsesHapusMultiple')[0];
        var data = new FormData(form);

        //Hapus Multiple Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesHapusMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status = response.status;
                var message = response.message;
                
                //ika status 'success'
                if(status=="success"){

                    //Kosongkan Notifikasi
                    $('#NotifikasiHapusMultiple').html('');

                    //Tutup Modal
                    $('#ModalHapusMultiple').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Hapus Komponen Biaya Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }else{
                    $('#NotifikasiHapusMultiple').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Edit Kategori Parsial
    $(document).on('dblclick', '.click_edit_parsial', function(){

        //Tangkap Data
        var id_fee_component    = $(this).data('id');
        var form_name           = $(this).data('form_name');

        //Tampilkan Modal
        $('#ModalEditParsial').modal('show');

        //Loading Form
        $('#FormEditParsial').html("Loading...");

        //Kosongkan Notifikasi
        $('#NotifikasiEditParsial').html("");

        //disable tombol Simpan
        $("#button_edit_parsial").prop("disabled", true);

        //Tampilkan Form Detail Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormEditParsial.php',
            data        : {id_fee_component: id_fee_component, form_name: form_name},
            success     : function(data){
                $('#FormEditParsial').html(data);

                //Format Uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Simpan Edit Parsial
    $('#ProsesEditParsial').submit(function(){

        //Loading Notifikasi
        $('#NotifikasiEditParsial').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Tangkap Data
        var form = $('#ProsesEditParsial')[0];
        var data = new FormData(form);

        //Hapus Multiple Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/ProsesEditParsial.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status = response.status;
                var message = response.message;
                
                //ika status 'success'
                if(status=="success"){

                    //Kosongkan Notifikasi
                    $('#NotifikasiEditParsial').html('');

                    //Tutup Modal
                    $('#ModalEditParsial').modal('hide');

                    //Menampilkan Ulang Data Komponen Biaya
                    filterAndLoadTable();
                }else{

                    //Jika gagal, tampilkan pesan dalam alert
                    $('#NotifikasiEditParsial').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Rekap Tagihan
    $(document).on('click', '.modal_rekap_tagihan', function(){

        //Tangkap Data
        var id_fee_component = $(this).data('id');

        //Tampilkan Modal
        $('#ModalRekapTagihan').modal('show');

        //Loading Form
        $('#tabel_rekap_tagihan').html('<tr><td colspan="8" class="text-center"><small>Loading..</small></td></tr>');

        //Tampilkan Form Detail Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/TabelRekapTagihan.php',
            data        : {id_fee_component: id_fee_component},
            success     : function(data){
                $('#tabel_rekap_tagihan').html(data);
            }
        });
    });

    //Tombol Kembali 'kembali_ke_rekap_tagihan'
    $(document).on('click', '.kembali_ke_rekap_tagihan', function(){

        //Sembunyikan Modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('hide');

        //Tampilkan modal 'ModalRekapTagihan'
        $('#ModalRekapTagihan').modal('show');

    });

    //Modal Rekap Tagihan
    $(document).on('click', '.modal_tagihan_siswa', function(){

        //Tangkap Data
        var id_fee_component        = $(this).data('id_fee_component');
        var id_organization_class   = $(this).data('id_organization_class');

        //Tampilkan Modal 'ModalTagihanSiswa'
        $('#ModalTagihanSiswa').modal('show');

        //tutup modal 'ModalRekapTagihan'
        $('#ModalRekapTagihan').modal('hide');

        //Loading Form
        $('#tabel_tagihan_siswa').html('<tr><td colspan="9" class="text-center"><small>Loading..</small></td></tr>');

        //Tampilkan Form Detail Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/TabelTagihanSiswa.php',
            data        : {id_fee_component: id_fee_component, id_organization_class: id_organization_class},
            success     : function(data){
                $('#tabel_tagihan_siswa').html(data);
            }
        });
    });

    //Tombol Kembali 'kembali_ke_tagihan_siswa'
    $(document).on('click', '.kembali_ke_tagihan_siswa', function(){

        //Sembunyikan Modal 'ModalTagihanSiswa'
        $('#ModalDetailTagihan').modal('hide');

        //Tampilkan modal 'ModalRekapTagihan'
        $('#ModalTagihanSiswa').modal('show');

    });

    //Modal Detail Tagihan
    $(document).on('click', '.modal_detail_tagihan_siswa', function(){

        //Tangkap Data
        var id_fee_by_student   = $(this).data('id');

        //Tampilkan Modal 'ModalTagihanSiswa'
        $('#ModalDetailTagihan').modal('show');

        //tutup modal 'ModalRekapTagihan'
        $('#ModalTagihanSiswa').modal('hide');

        //Loading Form
        $('#FormDetailTagihanSiswa').html('<tr><td colspan="6" class="text-center"><small>Loading..</small></td></tr>');

        //Tampilkan Form Detail Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KomponenBiaya/FormDetailTagihanSiswa.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormDetailTagihanSiswa').html(data);
            }
        });
    });
    
});