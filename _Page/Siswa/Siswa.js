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

    //Modal Export Data Tagihan Siswa
    $('#ModalExportTagihanSiswa').on('show.bs.modal', function (e) {
        var id_student = $(e.relatedTarget).data('id');
        $('#put_id_siswa_export_tagihan').val(id_student);
    });
    
});