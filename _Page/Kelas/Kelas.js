//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var id_academic_period = $('[name="id_academic_period"]:checked').val();
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/TabelKelas.php',
        data    : {id_academic_period: id_academic_period},
        success: function(data) {
            $('#TabelKelas').html(data);
            
            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

//Fungsi Menampilkan Komponen Biaya
function ShowKomponenBiaya(id_organization_class,id_academic_period) {
    //Tempelkan id_organization_class
    $('#put_id_organization_class').val(id_organization_class);
    $('#put_id_academic_period').val(id_academic_period);

    //Tangkap Data Dari Form
    var ProsesFilterKomponenBiaya = $('#ProsesFilterKomponenBiaya').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/TabelTambahKomponenBiaya.php',
        data    : ProsesFilterKomponenBiaya,
        success: function(data) {
            $('#TabelTambahKomponenBiaya').html(data);
        }
    });
}

//Fungsi Tambah Komponen Biaya
function AddKomponenBiaya(id_fee_component, id_organization_class) {
    $.ajax({
        type: 'POST',
        url: '_Page/Kelas/ProsesTambahKomponenBiaya.php',
        data: {
            id_fee_component: id_fee_component,
            id_organization_class: id_organization_class
        },
        dataType: 'json', // Pastikan response diparse sebagai JSON
        success: function(response) {
            if (response.status === 'success') {
                filterAndLoadTable();

                //TANGKAP id_academic_period
                var id_academic_period=$('[name="id_academic_period"]:checked').val();
                ShowKomponenBiaya(id_organization_class,id_academic_period);
            } else {
                // kalau gagal, tampilkan pesan error
                alert(response.message || 'Terjadi kesalahan!');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("Gagal menghubungi server!");
        }
    });
}

//Fungsi Hapus Komponen Biaya
function HapusKomponenBiaya(id_fee_component, id_organization_class) {
    $.ajax({
        type: 'POST',
        url: '_Page/Kelas/ProsesHapusKomponenBiaya.php',
        data: {
            id_fee_component: id_fee_component,
            id_organization_class: id_organization_class
        },
        dataType: 'json', // Pastikan response diparse sebagai JSON
        success: function(response) {
            if (response.status === 'success') {
                filterAndLoadTable();
                var id_academic_period=$('[name="id_academic_period"]:checked').val();
                ShowKomponenBiaya(id_organization_class,id_academic_period);
            } else {
                // kalau gagal, tampilkan pesan error
                alert(response.message || 'Terjadi kesalahan!');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("Gagal menghubungi server!");
        }
    });
}

//Fungsi Menampilkan Data List Level Kelas
function ShowListLevel() {
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/ListLevel.php',
        success: function(data) {
            $('#ListLevel').html(data);
        }
    });
}

//Fungsi Menampilkan Matrix Tagihan
function ShowMatrixTagihan(id_organization_class) {
    $('#TableMatrixTagihan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kelas/TableMatrixTagihan.php',
        data        : {id_organization_class: id_organization_class},
        success     : function(data){
            $('#TableMatrixTagihan').html(data);

            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
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

//Fungsi Menampilkan Data Tagihan Siswa
function ShowTagihanSiswa() {
    var ProsesPencarianTagihanSiswa=$('#ProsesPencarianTagihanSiswa').serialize();
    $('#TabelTagihanSiswa').html('<tr><td colspan="7" class="text-center"><small>Loading...</small></td></tr>');
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/TabelTagihanSiswa.php',
        data    : ProsesPencarianTagihanSiswa,
        success: function(data) {
            $('#TabelTagihanSiswa').html(data);
        }
    });
}

//Fungsi Menampiilkan Rekapitulasi Tagihan Siswa
function ShowRekapTagihanSiswa(id_organization_class) {
    $('#TabelRekapTagihanSiswa').html('<tr><td colspan="7" class="text-center"><small class="text text-grayish">Loading...</small></td></tr>');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kelas/TabelRekapTagihanSiswa.php',
        data        : {id_organization_class: id_organization_class},
        success     : function(data){
            $('#TabelRekapTagihanSiswa').html(data);

            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

//Fungsi Menampiilkan Rincian Tagihan Siswa
function ShowRincianTagihanSiswa(id_organization_class, id_student) {
    $('#TabelRincianTagihanSiswa').html('<tr><td colspan="7" class="text-center"><small class="text text-grayish">Loading...</small></td></tr>');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kelas/TabelRincianTagihanSiswa.php',
        data        : {id_organization_class: id_organization_class, id_student: id_student},
        success     : function(data){
            $('#TabelRincianTagihanSiswa').html(data);

            // 🔁 Re-inisialisasi tooltip setelah data dimuat
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

//Fungsi Menampiilkan Detail Tagihan Siswa
function ShowDetailTagihan(id_fee_by_student) {
    $('#FormDetailTagihan').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kelas/FormDetailTagihan.php',
        data        : {id_fee_by_student: id_fee_by_student},
        success     : function(data){
            $('#FormDetailTagihan').html(data);
        }
    });
}

//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();

    //Ketika 'TombolTampilkan' Di Click
    $('#TombolTampilkan').click(function(){
        //Panggil Fungsi
        filterAndLoadTable();

        //Tutup Modal
        $('#ModalPilihPeriodeAkademik').modal('hide');

    });

    //Ketika id_academic_period Diubah
    $('#id_academic_period').change(function(){
        filterAndLoadTable();
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
            url 	    : '_Page/Kelas/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Modal Copy
    $('#ModalCopy').on('show.bs.modal', function (e) {

        //Tangkap id_academic_period
        var id_academic_period = $('[name="id_academic_period"]:checked').val();

        //Remove Notifikasi
        $('#NotifikasiCopy').html('');

        //Loading
        $('#FormCopy').html('Loading...');

        //Buka Form Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormCopy.php',
            data 	    :  {id_academic_period: id_academic_period},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormCopy').html(data);
            }
        });

    });

    //Modal Export Kelas
    $('#ModalExportKelas').on('show.bs.modal', function (e) {

        //Tangkap id_academic_period
        var id_academic_period = $('[name="id_academic_period"]:checked').val();

        //Loading
        $('#FormExportKelas').html('Loading...');

        //Buka Form Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormExportKelas.php',
            data 	    :  {id_academic_period: id_academic_period},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormExportKelas').html(data);
            }
        });

    });

    //Proses Copy Periode Pendidikan
    $('#ProsesCopy').submit(function(){
        $('#NotifikasiCopy').html('Loading...');
        var ProsesCopy = $('#ProsesCopy').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesCopy.php',
            data 	    :  ProsesCopy,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiCopy').html(data);
                filterAndLoadTable();
            }
        });
    });

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambah').on('show.bs.modal', function (e) {

        //Tangkap class_level
        var class_level = $(e.relatedTarget).data('id');

        //Tangkap id_academic_period
        var id_academic_period=$('[name="id_academic_period"]:checked').val();

        //Tempelkan Ke form
        $('#class_level').val(class_level);
        $('#id_academic_period_tambah').val(id_academic_period);

        //Tampilkan class_list datalist
        ShowListLevel();

        //Kosongkan notifikasi
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
        $('#TombolTambahFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambah = $('#ProsesTambah').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesTambah.php',
            data 	    :  ProsesTambah,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambah').html(data);
                var NotifikasiTambahBerhasil=$('#NotifikasiTambahBerhasil').html();
                if(NotifikasiTambahBerhasil=="Success"){

                    //Tutup Modal
                    $('#ModalTambah').modal('hide');

                    //Menampilkan Data
                    filterAndLoadTable();

                    //Reset Form
                    $("#ProsesTambah")[0].reset();
                }
            }
        });
    });

    //Modal Detail
    $('#ModalDetail').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#FormDetail').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormDetail.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormDetail').html(data);
            }
        });
    });

    //Modal Siswa
    $('#ModalSiswa').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#TabelSiswa').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/TabelSiswa.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#TabelSiswa').html(data);
            }
        });
    });
    
    //Modal Siswa Aktual
    $('#ModalSiswaAktual').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#TabelSiswaAktual').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/TabelSiswaAktual.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#TabelSiswaAktual').html(data);
            }
        });
    });

    //Modal Edit
    $('#ModalEdit').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#FormEdit').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormEdit.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormEdit').html(data);
                $('#NotifikasiEdit').html('');
                $.ajax({
                    type    : 'POST',
                    url     : '_Page/Kelas/ListLevel.php',
                    success: function(data) {
                        $('#ListLevelEdit').html(data);
                    }
                });
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
            url 	    : '_Page/Kelas/ProsesEdit.php',
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
                        'Ubah Kelas Berhasil!',
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
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#FormsHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormsHapus.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormsHapus').html(data);
                $('#NotifikasisHapus').html('');
            }
        });
    });

    //Proses Hapus
    $('#ProsesHapus').submit(function(){
        $('#NotifikasisHapus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapus')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesHapus.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasisHapus').html(data);
                var NotifikasisHapusBerhasil=$('#NotifikasisHapusBerhasil').html();
                if(NotifikasisHapusBerhasil=="Success"){
                    $('#NotifikasisHapus').html('');

                    //Tutup Modal
                    $('#ModalHapus').modal('hide');

                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Ketika keyword_by_komponen Diubah
    $('#keyword_by_komponen').change(function(){
        var keyword_by_komponen = $('#keyword_by_komponen').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormFilterKomponen.php',
            data        : {keyword_by_komponen: keyword_by_komponen},
            success     : function(data){
                $('#FormFilterKomponen').html(data);
            }
        });
    });

    //Submit ProsesFilterKomponenBiaya
    $('#ProsesFilterKomponenBiaya').submit(function(){
        $('#page_komponen').val("1");
        var id_organization_class=$('#put_id_organization_class').val();
        ShowKomponenBiaya(id_organization_class);
    });

    //Pagging komponen biaya
    $(document).on('click', '#next_button_komponen', function() {
        var id_organization_class=$('#put_id_organization_class').val();
        var page_now = parseInt($('#page_komponen').val(), 10);
        var next_page = page_now + 1;
        $('#page_komponen').val(next_page);
        ShowKomponenBiaya(id_organization_class);
    });
    $(document).on('click', '#prev_button_komponen', function() {
        var id_organization_class=$('#put_id_organization_class').val();
        var page_now = parseInt($('#page_komponen').val(), 10);
        var next_page = page_now - 1;
        $('#page_komponen').val(next_page);
        ShowKomponenBiaya(id_organization_class);
    });

    //Ketika class tambah komponen di click
    $(document).on('click', '.tambah_komponen', function(){
        var $btn = $(this);
        var id_fee_component = $btn.data('id_1');
        var id_organization_class = $btn.data('id_2');

        // Simpan isi tombol asli
        var originalHtml = $btn.html();
        // Ganti dengan indikator loading (titik tiga / spinner)
        $btn.html('<span class="spinner-border spinner-border-sm"></span>');

        //Lanjutkan proses Ajax menggunakan function
        AddKomponenBiaya(id_fee_component, id_organization_class);
    });

    //Ketika class hapus komponen di click
    $(document).on('click', '.hapus_komponen', function(){
        var $btn = $(this);
        var id_fee_component = $btn.data('id_1');
        var id_organization_class = $btn.data('id_2');

        // Simpan isi tombol asli
        var originalHtml = $btn.html();
        // Ganti dengan indikator loading (titik tiga / spinner)
        $btn.html('<span class="spinner-border spinner-border-sm"></span>');

        //Lanjutkan proses Ajax menggunakan function
        HapusKomponenBiaya(id_fee_component, id_organization_class);
    });

    //Modal List Komponen Biaya
    $(document).on('click', '.list_komponen_biaya', function () {

        //Tangkap 'id_organization_class' dari tombol
        var id_organization_class = $(this).data('id');

        //Tampilkan Modal
        $('#ModalListKomponenBiaya').modal('show');

        //Loading Table
        $('#TabelKomponenBiaya').html("Loading...");

        //Tampilkan Komponen Biaya Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/TabelKomponenBiaya.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#TabelKomponenBiaya').html(data);
            }
        });
    });

     //Modal Komponen Biaya (Untuk Mengelola Data Komponen Biaya)
    $('#ModalKomponenBiaya').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        var id_academic_period=$('[name="id_academic_period"]:checked').val();
        ShowKomponenBiaya(id_organization_class,id_academic_period);
    });


    //Ketika click tombol dengan class 'tombol_kembali_ke_rekapitulasi_tagihan_1'
    $('.kembali_ke_list_komponen').on('click', function() {
        //tutup modal 'ModalKomponenBiaya'
       $('#ModalKomponenBiaya').modal('hide');

       //Buka modal 'ModalListKomponenBiaya'
       $('#ModalListKomponenBiaya').modal('show');
    });

    

    //Menampilkan Rekapitulasi Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalRekapTagihanSiswa"]', function () {
        //Tangkap Data Pada Tombol
        var id_organization_class = $(this).data('id');

        //Menempelkan tombol 'ModalTambahTagihanPerSiswa' pada col 'button_tambah_tagihan_per_siswa'
        $('.button_tambah_tagihan_per_siswa').html(`
            <button type="button" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihanPerSiswa" data-id="${id_organization_class}">
                <i class="bi bi-plus-circle-dotted"></i> Tambah
            </button>
        `);

        //Tampilkan data dengan fungsi 'ShowRekapTagihanSiswa'
        ShowRekapTagihanSiswa(id_organization_class);
    });

    //Ketika click tombol dengan class 'tombol_kembali_ke_rekapitulasi_tagihan_1'
    $(document).on('click', '.tombol_kembali_ke_rekapitulasi_tagihan_1', function () {
       
        //tutup modal
       $('#ModalTambahTagihanPerSiswa').modal('hide');

       //Buka modal
       $('#ModalRekapTagihanSiswa').modal('show');
    });

    //Menampilkan Modal Tambah Tagihan Per Siswa
    $(document).on('click', '[data-bs-target="#ModalTambahTagihanPerSiswa"]', function () {
        
        //Tangkap Data Pada Tombol
        var id_organization_class = $(this).data('id');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahTagihanPerSiswa').html('');

        //Loading Form
        $('#FormTambahTagihanPerSiswa').html('<div class="row"><div class="col-12 text-center">Loading...</div></div>');

        //Buka FormTambahTagihanPerSiswa dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahTagihanPerSiswa.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                //Tampilkan Konfirmasi Form Hapus Tagihan Per Siswa
                $('#FormTambahTagihanPerSiswa').html(data);

                //Inisialisasi form dengan class 'form-money'
                initializeMoneyInputs();

                //Menampilkan Form Select Option siswa (select2)
                if (!$('#select_siswa').hasClass("select2-hidden-accessible")) {
                    $('#select_siswa').select2({
                        theme           : 'bootstrap-5',
                        placeholder     : "Cari..",
                        dropdownParent  : $('#ModalTambahTagihanPerSiswa'),
                        ajax: {
                            url         : '_Page/Kelas/SelectSiswa.php',
                            type        : 'POST',
                            dataType    : 'json',
                            delay       : 250,
                            data: function(params) {
                                return { search: params.term };
                            },
                            processResults: function(data) {
                                return { results: data };
                            },
                            cache: true
                        }
                    });
                }

                //Menampilkan select option komponen biaya
                $('#selest_kbp').html('<option value="">Loading..</option>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kelas/SelectOptionKomponen.php',
                    data        : {id_organization_class: id_organization_class},
                    success     : function(data){
                        //Tampilkan Konfirmasi Form Hapus Tagihan Per Siswa
                        $('#selest_kbp').html(data);
                    }
                });

                //Event Pendelegasiian ketika 'selest_kbp' di ubah (change)
                $('#selest_kbp').on('change', function() {
                    // ambil atribut
                    var nominal = $(this).find(':selected').attr('nominal');

                    // tampilkan ke input
                    $('#nominal_tagihan_siswa').val(nominal); 
                    
                    //Inisialisasi form dengan class 'form-money'
                    initializeMoneyInputs();
                });

            }
        });
    });

    
    // tombol_kembali_ke_rekapitulasi_tagihan
    $(document).on('click', '.tombol_kembali_ke_rekapitulasi_tagihan', function () {
        
        // Tutup modal tambah
        $('#ModalTambahTagihanPerSiswa').modal('hide');

        //tutup modal 'ModalHapusTagihanPerSiswa'
        $('#ModalHapusTagihanPerSiswa').modal('hide');

        //tutup modal 'ModalRincianTagihanSiswa'
        $('#ModalRincianTagihanSiswa').modal('hide');

        // Tampilkan modal rekap
        $('#ModalRekapTagihanSiswa').modal('show');
    });

    //Proses Simpan Tagihan Per Siswa
    $('#ProsesTambahTagihanPerSiswa').submit(function(){

        //Tangkap Data Dari Form
        var ProsesTambahTagihanPerSiswa = $('#ProsesTambahTagihanPerSiswa').serialize();

        //tangkap 'put_id_organization_class3' dari form
        var id_organization_class = $('#put_id_organization_class3').val();

        //Loading Notifikasi
        $('#NotifikasiTambahTagihanPerSiswa').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesTambahTagihan.php',
            data 	    :  ProsesTambahTagihanPerSiswa,
            dataType    : 'json',
            success     : function(response){

                //Tangkap Notifikasi
                var status = response.status;
                
                if(status=="success"){
                    var id_organization_class = response.id_organization_class;

                    //kosongkan element 'FormTambahTagihanPerSiswa' dan 'NotifikasiTambahTagihanPerSiswa'
                    $('#FormTambahTagihanPerSiswa').html('');
                    $('#NotifikasiTambahTagihanPerSiswa').html('');

                    //Tutup 'ModalTambahTagihanPerSiswa'
                    $('#ModalTambahTagihanPerSiswa').modal('hide');

                    //Menampilkan 'ModalRekapTagihanSiswa'
                    $('#ModalRekapTagihanSiswa').modal('show');

                    //Tampilkan data dengan fungsi 'ShowRekapTagihanSiswa'
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesTambahTagihanPerSiswa')[0].reset();
                }else{
                    //Jika error tangkap message
                    var message = response.message;
                    $('#NotifikasiTambahTagihanPerSiswa').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Tagihan Per Siswa
    $('#ModalHapusTagihanPerSiswa').on('show.bs.modal', function (e) {
        var id_organization_class   = $(e.relatedTarget).data('id_organization_class');
        var id_student              = $(e.relatedTarget).data('id_student');

        //Loading pada FormHapusTagihanPerSiswa
        $('#FormHapusTagihanPerSiswa').html('<div class="row"><div class="col-12 text-center">Loadiing..</div></div>');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusTagihanPerSiswa').html("");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormHapusTagihanPerSiswa.php',
            data        : {id_organization_class: id_organization_class, id_student: id_student},
            success     : function(data){

                //Tampilkan Konfirmasi Form Hapus Tagihan Per Siswa
                $('#FormHapusTagihanPerSiswa').html(data);
            }
        });
    });

    //Proses Hapus Tagihan Per Siswa
    $('#ProsesHapusTagihanPerSiswa').submit(function(){

        //Tangkap Data Dari Form
        var ProsesHapusTagihanPerSiswa = $('#ProsesHapusTagihanPerSiswa').serialize();

        //Loading Notifikasi
        $('#NotifikasiHapusTagihanPerSiswa').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesHapusTagihanPerSiswa.php',
            data 	    :  ProsesHapusTagihanPerSiswa,
            dataType    : 'json',
            success     : function(response){

                //Tangkap Notifikasi
                var status_proses           = response.status;
                var message_proses          = response.message;
                var id_organization_class   = response.id_organization_class;
                
                //Routing Proses
                if(status_proses=="success"){
                    
                    //Tampilkan Notifikasi
                    $('#NotifikasiHapusTagihanPerSiswa').html('<div class="alert alert-success"><small>Hapus Tagihan Per Siswa Berhasil!</small></div>');

                    //Jika Berhasil Tutup Modal 'ModalHapusTagihanPerSiswa'
                    $('#ModalHapusTagihanPerSiswa').modal('hide');

                    //Tampilkan Modal 'ModalRekapTagihanSiswa'
                    $('#ModalRekapTagihanSiswa').modal('show');

                    //Load data dengan Fungsi 'ShowRekapTagihanSiswa'
                    ShowRekapTagihanSiswa(id_organization_class);
                }else{
                    $('#NotifikasiHapusTagihanPerSiswa').html('<div class="alert alert-danger"><small>'+message_proses+'</small></div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#NotifikasiHapusTagihanPerSiswa').html('<div class="alert alert-danger"><small>Terjadi kesalahan koneksi!</small></div>');
            }
        });
    });

    //Menampilkan Modal Rincian Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalRincianTagihanSiswa"]', function () {
        var id_organization_class   = $(this).data('id_organization_class');
        var id_student              = $(this).data('id_student');
        
        //Tampilkan Data Dengan AJAX melalui fungsi 'ShowRincianTagihanSiswa'
        ShowRincianTagihanSiswa(id_organization_class, id_student);
    });

    //Button Tambah Rincian Tagihan Siswa
    $(document).on('click', '.button_tambah_rincian_tagihan_siswa', function () {

        //Sembunyikan 'ModalRincianTagihanSiswa'
        $('#ModalRincianTagihanSiswa').modal('hide');

        //Munculkan Modal 'ModalTambahRincianTagihanSiswa'
        $('#ModalTambahRincianTagihanSiswa').modal('show');

        //Tangkap 'id_organization_class' dan 'id_student'
        var id_organization_class   = $(this).data('id_organization_class');
        var id_student              = $(this).data('id_student');

        //Kosongkan Notifikasi 'NotifikasiTambahRincianTagihanSiswa'
        $('#NotifikasiTambahRincianTagihanSiswa').html('');
        
        //Loading Form 'FormTambahRincianTagihanSiswa'
        $('#FormTambahRincianTagihanSiswa').html('Loading...');

        //Menampilkan Form 'FormTambahRincianTagihanSiswa' dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahRincianTagihanSiswa.php',
            data        : {id_organization_class: id_organization_class, id_student: id_student},
            success     : function(data){
                
                //Tampilkan Konfirmasi Form Hapus Tagihan Per Siswa
                $('#FormTambahRincianTagihanSiswa').html(data);

                //Inisialisasi form dengan class 'form-money'
                initializeMoneyInputs();
            }
        });
    });

    //Event Pendelegasiian ketika 'selest_kbp2' di ubah (change)
    $(document).on('change', '#selest_kbp2', function () {
        var nominal = $(this).find(':selected').attr('nominal');
        $('#nominal_tagihan_siswa2').val(nominal);

        // Inisialisasi ulang format uang
        initializeMoneyInputs();
    });

    //Ketika Click Tombol dengan class 'kembali_ke_rincian_tagihan_siswa'
    $('.kembali_ke_rincian_tagihan_siswa').on('click', function() {
        //Munculkan Modal 'ModalRincianTagihanSiswa'
        $('#ModalRincianTagihanSiswa').modal('show');

        //Sembunyikan Modal 'ModalTambahRincianTagihanSiswa'
        $('#ModalTambahRincianTagihanSiswa').modal('hide');

        //Sembunyikan Modal 'ModalEditRincianTagihan'
        $('#ModalEditRincianTagihan').modal('hide');

        //Sembunyikan Modal 'ModalHapusRincianTagihan'
        $('#ModalHapusRincianTagihan').modal('hide');

        //Sembunyikan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('hide');
    });

    //Proses Tambah Rincian Tagihan Siswa
    $('#ProsesTambahRincianTagihanSiswa').submit(function(){

        //Tangkap Data Dari Form
        var ProsesTambahRincianTagihanSiswa = $('#ProsesTambahRincianTagihanSiswa').serialize();

        //Loading Notifikasi
        $('#NotifikasiTambahRincianTagihanSiswa').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesTambahRincianTagihanSiswa.php',
            data 	    :  ProsesTambahRincianTagihanSiswa,
            dataType    : 'json',
            success     : function(response){

                //Tangkap 'status', 'message', 'id_organization_class' dan 'id_student'
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;
                

                //Apabila status berhasil
                if(status=="success"){
                    $('#NotifikasiTambahRincianTagihanSiswa').html('');

                    //Tutup Modal
                    $('#ModalTambahRincianTagihanSiswa').modal('hide');

                    //Menampilkan Data
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Tampilkan Data Dengan AJAX melalui fungsi 'ShowRincianTagihanSiswa'
                    ShowRincianTagihanSiswa(id_organization_class, id_student);

                    //Update Rekapitulasi Tagihan Siswa
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesTambahRincianTagihanSiswa')[0].reset();
                }else{
                    $('#NotifikasiTambahRincianTagihanSiswa').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Edit Rincian Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalEditRincianTagihan"]', function () {

        //Tangkap 'id_fee_by_student'
        var id_fee_by_student   = $(this).data('id');

        //Kosongkan Notifikasi
        $('#NotifiikasiEditRincianTagihan').html("");

        //Menempelkan nilai data-id_organization_class pada tombol 'kembali_ke_rincian_tagihan'

        $('#FormEditRincianTagihan').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormEditRincianTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormEditRincianTagihan').html(data);

                //Inisialisasi form dengan class 'form-money'
                initializeMoneyInputs();
            }
        });
    });

    //Proses Edit Rincian Tagihan Siswa
    $('#ProsesEditRincianTagihan').submit(function(){

        //Tangkap Data Dari Form
        var ProsesEditRincianTagihan = $('#ProsesEditRincianTagihan').serialize();

        //tangkap 'put_id_organization_class3' dari form
        var id_organization_class   = $('#put_id_organization_class5').val();
        var id_student              = $('#put_id_student5').val();

        //Loading Notifikasi
        $('#NotifiikasiEditRincianTagihan').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesEditRincianTagihan.php',
            data 	    :  ProsesEditRincianTagihan,
            dataType    : 'json',
            success     : function(response){

                //Tangkap Variabel Dari Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;
                
                //Jika Status success
                if(status=="success"){
                    $('#NotifiikasiEditRincianTagihan').html('');

                    //Tutup Modal
                    $('#ModalEditRincianTagihan').modal('hide');

                    //Menampilkan Data
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Tampilkan Data Dengan AJAX melalui fungsi 'ShowRincianTagihanSiswa'
                    ShowRincianTagihanSiswa(id_organization_class, id_student);

                    //Update Rekapitulasi Tagihan Siswa
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesEditRincianTagihan')[0].reset();
                }else{
                    $('#NotifiikasiEditRincianTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Rincian Tagihan Siswa
    $(document).on('click', '[data-bs-target="#ModalHapusRincianTagihan"]', function () {
        
        //Tangkap 'id_fee_by_student'
        var id_fee_by_student = $(this).data('id');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusRincianTagihan').html("");

        //Loading Form
        $('#FormHapusRincianTagihan').html('<div class="row"><div class="col-md-12 text-center"><small>Loading...</small></div></div>');

        //Buka Form Hapus Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormHapusRincianTagihan.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormHapusRincianTagihan').html(data);
            }
        });
    });

    //Proses Hapus Rincian Tagihan Siswa
    $('#ProsesHapusRincianTagihan').submit(function(){

        //Tangkap Data Dari Form
        var ProsesHapusRincianTagihan = $('#ProsesHapusRincianTagihan').serialize();

        //Loading Notifikasi
        $('#NotifikasiHapusRincianTagihan').html("Loading...");

        //Proses Data Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesHapusRincianTagihan.php',
            data 	    :  ProsesHapusRincianTagihan,
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Variabel Dari Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;
                
                //Apabila Status Success
                if(status=="success"){
                    $('#NotifikasiHapusRincianTagihan').html('');

                    //Tutup Modal
                    $('#ModalHapusRincianTagihan').modal('hide');

                    //Menampilkan Data
                    $('#ModalRincianTagihanSiswa').modal('show');

                    //Tampilkan Data Dengan AJAX melalui fungsi 'ShowRincianTagihanSiswa'
                    ShowRincianTagihanSiswa(id_organization_class, id_student);

                    //Update Rekapitulasi Tagihan Siswa
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesHapusRincianTagihan')[0].reset();
                }else{

                    //Jika Gagal Hapus
                    $('#NotifikasiHapusRincianTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Menampilkan Modal Detail Tagihan Dan Pembayaran
    $(document).on('click', '[data-bs-target="#ModalDetailTagihan"]', function () {
        //Tangkap 'id_fee_by_student' dari tombol
        var id_fee_by_student   = $(this).data('id');

        //Tampilkan data dengan fungsi 'ShowDetailTagihan'
        ShowDetailTagihan(id_fee_by_student);
    });

    //Ketika Click Tombol dengan class 'kembali_ke_detail_tagihan'
    $('.kembali_ke_detail_tagihan').on('click', function() {
        //Munculkan Modal 'ModalDetailTagihan'
        $('#ModalDetailTagihan').modal('show');

        //Sembunyikan Modal 'ModalTambahPembayaran'
        $('#ModalTambahPembayaran').modal('hide');

        //Sembunyikan Modal 'ModalHapusPembayaran'
        $('#ModalHapusPembayaran').modal('hide');

    });

    //Menampilkan Modal Tambah Pembayaran
    $(document).on('click', '[data-bs-target="#ModalTambahPembayaran"]', function () {
        //Tangkap 'id_fee_by_student'
        var id_fee_by_student   = $(this).data('id');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahPembayaran').html('');

        //Loading Form
        $('#FormTambahPembayaran').html('<div class="row"><div class="col-12 text-center">Loading...</div></div>');

        //Buka Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahPembayaran.php',
            data        : {id_fee_by_student: id_fee_by_student},
            success     : function(data){
                $('#FormTambahPembayaran').html(data);

                //Inisialisasi form dengan class 'form-money'
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
            url 	    : '_Page/Kelas/ProsesTambahPembayaran.php',
            data 	    :  ProsesTambahPembayaran,
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Variabel Dari Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;
                var id_fee_by_student       = response.id_fee_by_student;
                
                //Jika Status Success
                if(status=="success"){
                    $('#NotifikasiTambahPembayaran').html('');

                    //Tutup Modal 'ModalTambahPembayaran'
                    $('#ModalTambahPembayaran').modal('hide');

                    //Menampilkan Modal 'ModalDetailTagihan'
                    $('#ModalDetailTagihan').modal('show');

                    //Reload 'ModalDetailTagihan' melalui fungsi 'ShowDetailTagihan'
                    ShowDetailTagihan(id_fee_by_student);

                    //Reload Rincian Tagihan
                    ShowRincianTagihanSiswa(id_organization_class, id_student);

                    //Reload Rekapitulasi Tagihan Siswa
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesTambahPembayaran')[0].reset();
                }
            }
        });
    });

    //Menampilkan Modal Hapus Pembayaran
    $(document).on('click', '[data-bs-target="#ModalHapusPembayaran"]', function () {
        //Tangkap 'id_payment' dan 'id_fee_by_student'
        var id_payment          = $(this).data('id');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusPembayaran').html('');
        
        //Loading 'FormHapusPembayaran'
        $('#FormHapusPembayaran').html('<div class="row"><div class="col-12 text-center">Loading...</div></div>');

        //Buka 'FormHapusPembayaran' Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormHapusPembayaran.php',
            data        : {id_payment: id_payment},
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
            url 	    : '_Page/Kelas/ProsesHapusPembayaran.php',
            data 	    :  ProsesHapusPembayaran,
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Variabel Dari Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;
                var id_student              = response.id_student;
                var id_fee_by_student       = response.id_fee_by_student;
                
                if(status=="success"){
                    $('#NotifikasiHapusPembayaran').html('');

                    //Tutup Modal 'ModalTambahPembayaran'
                    $('#ModalHapusPembayaran').modal('hide');

                    //Menampilkan Modal 'ModalDetailTagihan'
                    $('#ModalDetailTagihan').modal('show');

                    //Reload 'ModalDetailTagihan' melalui fungsi 'ShowDetailTagihan'
                    ShowDetailTagihan(id_fee_by_student);

                    //Reload Rincian Tagihan
                    ShowRincianTagihanSiswa(id_organization_class, id_student);

                    //Reload Rekapitulasi Tagihan Siswa
                    ShowRekapTagihanSiswa(id_organization_class);

                    //Loadd Ulang data kelas
                    filterAndLoadTable();

                    //reset form
                    $('#ProsesHapusPembayaran')[0].reset();
                }else{

                }
            }
        });
    });

    //Modal Menampilkan Matrix Tagihan
    $(document).on('click', '[data-bs-target="#ModalMatrixTagihan"]', function () {

        //Menangkap Data
        var id_organization_class = $(this).data('id');

        //Tampilkan Metrik dengan fungsi 'ShowMatrixTagihan'
        ShowMatrixTagihan(id_organization_class);
    });

    //Ketika Click Tombol dengan class 'kembali_ke_modal_metrik'
    $('.kembali_ke_modal_metrik').on('click', function() {
        //Munculkan Modal 'ModalMatrixTagihan'
        $('#ModalMatrixTagihan').modal('show');

        //Sembunyikan Modal 'ModalTambahTagihanMulti'
        $('#ModalTambahTagihanMulti').modal('hide');

        //Sembunyikan Modal 'ModalTambahTagihan'
        $('#ModalTambahTagihan').modal('hide');

        //Sembunyikan Modal 'ModalHapusTagihan'
        $('#ModalHapusTagihan').modal('hide');

    });

    //Modal Tambah Tagihan Multi
    $(document).on('click', '.tambah_tagihan_multi', function () {

        //Tangkap Data Dari Tombol
        var id_academic_period      = $(this).data('id1');
        var id_organization_class   = $(this).data('id2');
        var id_fee_component        = $(this).data('id3');

        //Sembunyikan Modal 'ModalMatrixTagihan'
        $('#ModalMatrixTagihan').modal('hide');

        //Munculkan Modal
        $('#ModalTambahTagihanMulti').modal('show');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahTagihanMulti').html('');

        //Loading Form
        $('#FormTambahTagihanMulti').html('Loading...');

        //Buka Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahTagihanMulti.php',
            data        : {
                id_academic_period: id_academic_period, 
                id_organization_class: id_organization_class, 
                id_fee_component: id_fee_component
            },
            success     : function(data){
                $('#FormTambahTagihanMulti').html(data);
                
                //Format uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Tambah Tagihan Multi
    $('#ProsesTambahTagihanMulti').submit(function(){

        //Ambil data dari form
        var form = $('#ProsesTambahTagihanMulti')[0];
        var data = new FormData(form);
        
        //Loading notifikasi
        $('#NotifikasiTambahTagihanMulti').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Proses Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesTambahTagihanMulti.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                
                //Tangkap Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;

                //Jika Berhasil
                if(status=="success"){
                    
                    //Kosongkan Notifikasi
                    $('#NotifikasiTambahTagihanMulti').html('');

                    //Tutup Modal 'ModalTambahTagihanMulti'
                    $('#ModalTambahTagihanMulti').modal('hide');

                    //Munculkan Modal 'ModalMatrixTagihan'
                    $('#ModalMatrixTagihan').modal('show');

                    ShowMatrixTagihan(id_organization_class);

                    filterAndLoadTable();
                }else{
                    $('#NotifikasiTambahTagihanMulti').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });


    //Modal Tambah Tagihan (Single Record)
    $(document).on('click', '[data-bs-target="#ModalTambahTagihan"]', function () {

        //Tangkap Data Dari Tombol
        var id_organization_class   = $(this).data('id1');
        var id_student              = $(this).data('id2');
        var id_fee_component        = $(this).data('id3');

        //Kosongkan Notifikasi
        $('#NotifikasiTambahTagihan').html('');

        //Loading Form
        $('#FormTambahTagihan').html('Loading...');

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahTagihan.php',
            data        : {
                id_organization_class: id_organization_class, 
                id_student: id_student, 
                id_fee_component: id_fee_component
            },
            success     : function(data){

                //Tampilkan Element Form
                $('#FormTambahTagihan').html(data);
                
                //Format uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Tambah Tagihan
    $('#ProsesTambahTagihan').submit(function(){
        
        //Loading notifikasi
        $('#NotifikasiTambahTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Ambil data dari form
        var form = $('#ProsesTambahTagihan')[0];
        var data = new FormData(form);

        //Proses Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesTambahTagihan.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                //Tangkap Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;

                //Jika Berhasil
                if(status=="success"){
                    $('#NotifikasiTambahTagihan').html('');

                    //Tutup Modal
                    $('#ModalTambahTagihan').modal('hide');

                    //Menampilkan Data
                    $('#ModalMatrixTagihan').modal('show');

                    ShowMatrixTagihan(id_organization_class);

                    filterAndLoadTable();
                }else{
                    $('#NotifikasiTambahTagihan').html('<div class="alert alert-danger"><small>'+message+'</small></div>');
                }
            }
        });
    });

    //Modal Hapus Tagihan
    $(document).on('click', '[data-bs-target="#ModalHapusTagihan"]', function () {

        //tangkap data dan buat variabel
        var id_organization_class   = $(this).data('id1');
        var id_student              = $(this).data('id2');
        var id_fee_component        = $(this).data('id3');

        //Form Loading
        $('#FormHapusTagihan').html('Loading...');

        //Tampilkan Form Dengan Ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormHapusTagihan.php',
            data        : {
                id_organization_class: id_organization_class, 
                id_student: id_student, 
                id_fee_component: id_fee_component
            },
            success     : function(data){
                $('#FormHapusTagihan').html(data);
                $('#NotifikasiHapusTagihan').html('');
            }
        });
    });

    //Proses Hapus Tagihan
    $('#ProsesHapusTagihan').submit(function(){
        
        //Loading notifikasi
        $('#NotifiikasiHapusTagihan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

        //Ambil data dari form
        var form = $('#ProsesHapusTagihan')[0];
        var data = new FormData(form);

        //Proses Dengan AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesHapusTagihan.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            dataType    : 'json',
            success     : function(response){
                //Tangkap Response
                var status                  = response.status;
                var message                 = response.message;
                var id_organization_class   = response.id_organization_class;

                //Jika Berhasil
                if(status=="success"){
                    $('#NotifiikasiHapusTagihan').html('');

                    //Tutup Modal
                    $('#ModalHapusTagihan').modal('hide');

                    //Menampilkan Data
                    $('#ModalMatrixTagihan').modal('show');

                    ShowMatrixTagihan(id_organization_class);

                    filterAndLoadTable();
                }
            }
        });
    });

    
    

    //Modal Tagihan Siswa
    $(document).on('click', '.show_modal_tagihan_siswa', function(){
        //tampilkan modal
        $('#ModalTagihanSiswa').modal('show');

        //Tangkap id_organization_class
        var id_organization_class   = $(this).data('id');

        //Loading
        $('#put_id_organization_class_for_tagihan_siswa').val(id_organization_class);

        //Tampilkan ke form id_fee_component_tagihan_siswa Dengan ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/list_fee_component.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#list_fee_component').html(data);
            }
        });


        //Tampilkan ke form list_siswa Dengan ajax
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/list_siswa.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#list_siswa').html(data);
            }
        });

        //Tampilkan Data
        ShowTagihanSiswa();
    });

    // Awal: sembunyikan form filter
    $('#filter_form_tagihan_siswa').hide();

    // Event klik tombol
    $('#show_filter_form_tagihan_siswa').on('click', function() {
        var filterForm = $('#filter_form_tagihan_siswa');
        var icon = $(this).find('i');

        // Toggle tampil/sembunyi dengan animasi
        filterForm.slideToggle(300);

        // Ganti ikon panah
        if (icon.hasClass('bi-chevron-down')) {
            icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
        } else {
            icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
        }
    });

    //Submit ProsesPencarianTagihanSiswa
    $('#ProsesPencarianTagihanSiswa').submit(function(){
        $('#put_page_for_tagihan_siswa').val("1");
        ShowTagihanSiswa();
    });

    // Check/uncheck semua tagihan siswa
    $('input[name="check_all"]').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('#TabelTagihanSiswa input[name="id_fee_by_student[]"]').prop('checked', isChecked);
    });

    //Pagging tagihan siswa
    $(document).on('click', '#next_button_tagihan_siswa', function() {
        var page_now = parseInt($('#put_page_for_tagihan_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#put_page_for_tagihan_siswa').val(next_page);
        ShowTagihanSiswa(0);
    });
    $(document).on('click', '#prev_button_tagihan_siswa', function() {
        var page_now = parseInt($('#put_page_for_tagihan_siswa').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#put_page_for_tagihan_siswa').val(next_page);
        ShowTagihanSiswa(0);
    });

    //Modal 'ModalEditTagihanSiswaMultiple'
    $('#ModalEditTagihanSiswaMultiple').on('show.bs.modal', function (e) {
        //Tangkap data dari form
        var ProsesTabelTagihanSiswa=$('#ProsesTabelTagihanSiswa').serialize();

        //Loading
        $('#FormEditTagihanSiswaMultiple').html('Loading..');

        //Kosongkan Notifikasi
        $('#NotifikasiEditTagihanSiswaMultiple').html('');

        //Tampilkan Dengan Ajax
        $.ajax({
            type    : 'POST',
            url     : '_Page/Kelas/FormEditTagihanSiswaMultiple.php',
            data    : ProsesTabelTagihanSiswa,
            success: function(data) {
                $('#FormEditTagihanSiswaMultiple').html(data);
            }
        });
    });

    // ketika  'konfirmasi_edit_tagihan_siswa_multiple' di click
    $(document).on('click', '#konfirmasi_edit_tagihan_siswa_multiple', function() {
        
        //Tangkap data dari form
        var ProsesEditTagihanSiswaMultiple=$('#ProsesEditTagihanSiswaMultiple').serialize();

        //Loading
        $('#NotifikasiEditTagihanSiswaMultiple').html('Loading...');

        //Tampilkan Dengan Ajax
        $.ajax({
            type    : 'POST',
            url     : '_Page/Kelas/ProsesEditTagihanSiswaMultiple.php',
            data    : ProsesEditTagihanSiswaMultiple,
            success: function(data) {
                $('#NotifikasiEditTagihanSiswaMultiple').html(data);

                //Tangkap hasil Proses
                var NotifikasiEditTagihanSiswaMultipleBerhasil=$('#NotifikasiEditTagihanSiswaMultipleBerhasil').html();

                //Jika Berhasil
                if(NotifikasiEditTagihanSiswaMultipleBerhasil=="Berhasil"){
                    $('#NotifikasiEditTagihanSiswaMultiple').html('');

                    //Tutup Modal 'ModalHapusTagihanSiswaMultiple'
                    $('#ModalEditTagihanSiswaMultiple').modal('hide');

                    //Tampilkan Kembali modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');

                    //Reload Data
                    ShowTagihanSiswa();

                    //reload data kelas
                    filterAndLoadTable();
                }
            }
        });
    });

    //Ketika click 'HapusTagihanMultiple'
    $('#ModalHapusTagihanSiswaMultiple').on('show.bs.modal', function (e) {
        //Tangkap data dari form
        var ProsesTabelTagihanSiswa=$('#ProsesTabelTagihanSiswa').serialize();

        //Loading
        $('#FormHapusTagihanSiswaMultiple').html('Loading..');

        //Kosongkan Notifikasi
        $('#NotifikasiHapusTagihanSiswaMultiple').html('');

        //Tampilkan Dengan Ajax
        $.ajax({
            type    : 'POST',
            url     : '_Page/Kelas/KonfirmasiHapusMultiple.php',
            data    : ProsesTabelTagihanSiswa,
            success: function(data) {
                $('#FormHapusTagihanSiswaMultiple').html(data);
            }
        });
    });

    // ketika  'konfirmasi_hapus_tagihan_siswa_multiple' di click
    $(document).on('click', '#konfirmasi_hapus_tagihan_siswa_multiple', function() {
        
        //Tangkap data dari form
        var ProsesTabelTagihanSiswa=$('#ProsesTabelTagihanSiswa').serialize();

        //Loading
        $('#NotifikasiHapusTagihanSiswaMultiple').html('Loading...');

        //Tampilkan Dengan Ajax
        $.ajax({
            type    : 'POST',
            url     : '_Page/Kelas/ProsesHapusTagihanMulti.php',
            data    : ProsesTabelTagihanSiswa,
            success: function(data) {
                $('#NotifikasiHapusTagihanSiswaMultiple').html(data);

                //Tangkap hasil Proses
                var NotifikasiHapusTagihanSiswaMultipleBerhasil=$('#NotifikasiHapusTagihanSiswaMultipleBerhasil').html();

                //Jika Berhasil
                if(NotifikasiHapusTagihanSiswaMultipleBerhasil=="Berhasil"){
                    $('#NotifikasiTambahTagihanMulti').html('');

                    //Tutup Modal 'ModalHapusTagihanSiswaMultiple'
                    $('#ModalHapusTagihanSiswaMultiple').modal('hide');

                    //Tampilkan Kembali modal 'ModalTagihanSiswa'
                    $('#ModalTagihanSiswa').modal('show');

                    //Reload Data
                    ShowTagihanSiswa();

                    //reload data kelas
                    filterAndLoadTable();
                }
            }
        });
    });

    //Tombol Kembali
    $(document).on('click', '.Kembali_ke_tagihan_multi', function() {
        $('#ModalEditTagihanSiswaMultiple').modal('hide');
        $('#ModalHapusTagihanSiswaMultiple').modal('hide');
        $('#ModalTagihanSiswa').modal('show');
    });

    


});