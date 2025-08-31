//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/AksesEntitas/TabelAksesEntitas.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelAksesEntitas').html(data);
        }
    });
}
//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();
    // Ketika class=KelasKategori di check
    $('.KelasKategori').change(function() {
        var kategoriId = $(this).val();
        var isChecked = $(this).is(':checked');
        
        // Check/uncheck semua ListFitur dengan kategori yang sesuai
        $('.ListFitur[kategori="' + kategoriId + '"]').prop('checked', isChecked);
    });

    // Ketika salah satu class="ListFitur" di check
    $('.ListFitur').change(function() {
        var kategoriId = $(this).attr('kategori');
        
        // Jika salah satu ListFitur dalam kategori tersebut tidak dicheck, uncheck KelasKategori
        if ($('.ListFitur[kategori="' + kategoriId + '"]:not(:checked)').length > 0) {
            $('#IdKategori' + kategoriId).prop('checked', false);
        } else {
            // Jika semua ListFitur dalam kategori tersebut dicheck, check KelasKategori
            $('#IdKategori' + kategoriId).prop('checked', true);
        }
    });
});
//Filter Data
$('#ProsesFilter').submit(function(){
    $('#page').val("1");
    filterAndLoadTable();
    $('#ModalFilter').modal('hide');
});
//Proses Tambah AksesEntitas
$('#ProsesTambahAksesEntitas').submit(function(){
    $('#NotifikasiTambahAksesEntitias').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesTambahAksesEntitas = $('#ProsesTambahAksesEntitas').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/ProsesTambahAksesEntitas.php',
        data 	    :  ProsesTambahAksesEntitas,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTambahAksesEntitias').html(data);
            var NotifikasiTambahAksesEntitiasBerhasil=$('#NotifikasiTambahAksesEntitiasBerhasil').html();
            if(NotifikasiTambahAksesEntitiasBerhasil=="Success"){
                $('#NotifikasiTambahAksesEntitias').html('');
                $('#page').val("1");
                $("#ProsesFilter")[0].reset();
                $("#ProsesTambahAksesEntitas")[0].reset();
                $('#ModalTambahAksesEntitas').modal('hide');
                Swal.fire(
                    'Success!',
                    'Tambahh Entitas Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Ketika Modal Detail Entitias Akses
$('#ModalDetailEntitias').on('show.bs.modal', function (e) {
    var uuid_akses_entitas = $(e.relatedTarget).data('id');
    $('#FormDetailEntitias').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/FormDetailEntitias.php',
        data        : {uuid_akses_entitas: uuid_akses_entitas},
        success     : function(data){
            $('#FormDetailEntitias').html(data);
        }
    });
});
//Ketika Modal Hapus AksesEntitas Muncul
$('#ModalHapusAksesEntitas').on('show.bs.modal', function (e) {
    var uuid_akses_entitas = $(e.relatedTarget).data('id');
    $('#FormHapusAksesEntitas').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/FormHapusAksesEntitas.php',
        data        : {uuid_akses_entitas: uuid_akses_entitas},
        success     : function(data){
            $('#FormHapusAksesEntitas').html(data);
            $('#NotifikasiHapusAksesEntitas').html('');
        }
    });
});
//Proses Hapus AksesEntitas
$('#ProsesHapusAksesEntitas').submit(function(){
    $('#NotifikasiHapusAksesEntitas').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesHapusAksesEntitas = $('#ProsesHapusAksesEntitas').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/ProsesHapusAksesEntitas.php',
        data 	    :  ProsesHapusAksesEntitas,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiHapusAksesEntitas').html(data);
            var NotifikasiHapusAksesEntitasBerhasil=$('#NotifikasiHapusAksesEntitasBerhasil').html();
            if(NotifikasiHapusAksesEntitasBerhasil=="Success"){
                $("#ProsesHapusAksesEntitas")[0].reset();
                $('#ModalHapusAksesEntitas').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Entitas Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Ketika Modal Edit AksesEntitas Muncul
$('#ModalEditAksesEntitas').on('show.bs.modal', function (e) {
    var uuid_akses_entitas = $(e.relatedTarget).data('id');
    $('#FormEditAksesEntitas').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/FormEditAksesEntitas.php',
        data        : {uuid_akses_entitas: uuid_akses_entitas},
        success     : function(data){
            $('#FormEditAksesEntitas').html(data);
            $('#NotifikasiEditAksesEntitas').html('');
        }
    });
});
//Proses Edit AksesEntitas
$('#ProsesEditAksesEntitas').submit(function(){
    $('#NotifikasiEditAksesEntitas').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesEditAksesEntitas = $('#ProsesEditAksesEntitas').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesEntitas/ProsesEditAksesEntitas.php',
        data 	    :  ProsesEditAksesEntitas,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditAksesEntitas').html(data);
            var NotifikasiEditAksesEntitasBerhasil=$('#NotifikasiEditAksesEntitasBerhasil').html();
            if(NotifikasiEditAksesEntitasBerhasil=="Success"){
                $('#NotifikasiEditAksesEntitas').html('');
                $('#ModalEditAksesEntitas').modal('hide');
                Swal.fire(
                    'Success!',
                    'Edit AksesEntitas Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});