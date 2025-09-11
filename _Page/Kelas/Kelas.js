//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var id_academic_period=$('#id_academic_period').val();
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/TabelKelas.php',
        data    : {id_academic_period: id_academic_period},
        success: function(data) {
            $('#TabelKelas').html(data);
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
                var id_academic_period=$('#id_academic_period').val();
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
                var id_academic_period=$('#id_academic_period').val();
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

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambah').on('show.bs.modal', function (e) {

        //Tangkap class_level
        var class_level = $(e.relatedTarget).data('id');

        //Tangkap id_academic_period
        var id_academic_period=$('#id_academic_period').val();

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

    //Modal Komponen Biaya
    $('#ModalKomponenBiaya').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        var id_academic_period=$('#id_academic_period').val();
        ShowKomponenBiaya(id_organization_class,id_academic_period);
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
    $('#ModalListKomponenBiaya').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        $('#TabelKomponenBiaya').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/TabelKomponenBiaya.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#TabelKomponenBiaya').html(data);
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

    //Modal Menampilkan Matrix Tagihan
    $('#ModalMatrixTagihan').on('show.bs.modal', function (e) {
        var id_organization_class = $(e.relatedTarget).data('id');
        ShowMatrixTagihan(id_organization_class);
    });

    //Modal Tambah Tagihan
    $('#ModalTambahTagihan').on('show.bs.modal', function (e) {
        var id_organization_class   = $(e.relatedTarget).data('id1');
        var id_student              = $(e.relatedTarget).data('id2');
        var id_fee_component        = $(e.relatedTarget).data('id3');

        // simpan ke data modal
        $(this).data('org-class', id_organization_class);

        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormTambahTagihan.php',
            data        : {
                id_organization_class: id_organization_class, 
                id_student: id_student, 
                id_fee_component: id_fee_component
            },
            success     : function(data){
                $('#FormTambahTagihan').html(data);
                $('#NotifikasiTambahTagihan').html('');

                //Format uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Tambah Tagihan
    $('#ProsesTambahTagihan').submit(function(){
        //Tangkap get_id_organization_class_for_back
        var get_id_organization_class_for_back = $('#ModalTambahTagihan').data('org-class');

        //Jika tidak ada get_id_organization_class_for_back
        if(get_id_organization_class_for_back==""){
            $('#NotifikasiTambahTagihan').html('');
        }else{

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
                success     : function(data){
                    $('#NotifikasiTambahTagihan').html(data);

                    //Tangkap Variabel
                    var NotifikasiTambahTagihanBerhasil=$('#NotifikasiTambahTagihanBerhasil').html();

                    //Jika Berhasil
                    if(NotifikasiTambahTagihanBerhasil=="Success"){
                        $('#NotifikasiTambahTagihan').html('');

                        //Tutup Modal
                        $('#ModalTambahTagihan').modal('hide');

                        //Menampilkan Data
                        $('#ModalMatrixTagihan').modal('show');

                        ShowMatrixTagihan(get_id_organization_class_for_back);

                        filterAndLoadTable();
                    }
                }
            });
        }
    });

    //Modal Hapus Tagihan
    $('#ModalHapusTagihan').on('show.bs.modal', function (e) {
        var id_organization_class   = $(e.relatedTarget).data('id1');
        var id_student              = $(e.relatedTarget).data('id2');
        var id_fee_component        = $(e.relatedTarget).data('id3');

        // simpan ke data modal
        $(this).data('org-class', id_organization_class);

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
        //Tangkap get_id_organization_class_for_back
        var get_id_organization_class_for_back2 = $('#ModalHapusTagihan').data('org-class');

        //Jika tidak ada get_id_organization_class_for_back
        if(get_id_organization_class_for_back2==""){
            $('#NotifiikasiHapusTagihan').html('');
        }else{

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
                success     : function(data){
                    $('#NotifiikasiHapusTagihan').html(data);

                    //Tangkap Variabel
                    var NotifiikasiHapusTagihanBerhasil=$('#NotifiikasiHapusTagihanBerhasil').html();

                    //Jika Berhasil
                    if(NotifiikasiHapusTagihanBerhasil=="Success"){
                        $('#NotifiikasiHapusTagihan').html('');

                        //Tutup Modal
                        $('#ModalHapusTagihan').modal('hide');

                        //Menampilkan Data
                        $('#ModalMatrixTagihan').modal('show');

                        ShowMatrixTagihan(get_id_organization_class_for_back2);

                        filterAndLoadTable();
                    }
                }
            });
        }
    });

    //Modal Tambah Tagihan Multi
    $('#ModalTambahTagihanMulti').on('show.bs.modal', function (e) {
        var id_academic_period      = $(e.relatedTarget).data('id1');
        var id_organization_class   = $(e.relatedTarget).data('id2');
        var id_fee_component        = $(e.relatedTarget).data('id3');

        // simpan ke data modal
        $(this).data('org-class', id_organization_class);

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
                $('#NotifikasiTambahTagihanMulti').html('');

                //Format uang
                initializeMoneyInputs();
            }
        });
    });

    //Proses Tambah Tagihan Multi
    $('#ProsesTambahTagihanMulti').submit(function(){
        //Tangkap get_id_organization_class_for_back
        var get_id_organization_class_for_back = $('#ModalTambahTagihanMulti').data('org-class');

        //Jika tidak ada get_id_organization_class_for_back
        if(get_id_organization_class_for_back==""){
            $('#NotifikasiTambahTagihanMulti').html('');
        }else{

            //Loading notifikasi
            $('#NotifikasiTambahTagihanMulti').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

            //Ambil data dari form
            var form = $('#ProsesTambahTagihanMulti')[0];
            var data = new FormData(form);

            //Proses Dengan AJAX
            $.ajax({
                type 	    : 'POST',
                url 	    : '_Page/Kelas/ProsesTambahTagihanMulti.php',
                data 	    :  data,
                cache       : false,
                processData : false,
                contentType : false,
                enctype     : 'multipart/form-data',
                success     : function(data){
                    $('#NotifikasiTambahTagihanMulti').html(data);

                    //Tangkap Variabel
                    var NotifikasiTambahTagihanMultiBerhasil=$('#NotifikasiTambahTagihanMultiBerhasil').html();

                    //Jika Berhasil
                    if(NotifikasiTambahTagihanMultiBerhasil=="Success"){
                        $('#NotifikasiTambahTagihanMulti').html('');

                        //Tutup Modal
                        $('#ModalTambahTagihanMulti').modal('hide');

                        //Menampilkan Data
                        $('#ModalMatrixTagihan').modal('show');

                        ShowMatrixTagihan(get_id_organization_class_for_back);

                        filterAndLoadTable();
                    }
                }
            });
        }
    });

});