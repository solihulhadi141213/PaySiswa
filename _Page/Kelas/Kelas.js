//Fungsi Menampilkan Data
function filterAndLoadTable() {
    $.ajax({
        type    : 'POST',
        url     : '_Page/Kelas/TabelKelas.php',
        success: function(data) {
            $('#TabelKelas').html(data);
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
            url 	    : '_Page/Kelas/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Ketika Modal Tambah Fitur Muncul
    $('#ModalTambah').on('show.bs.modal', function (e) {
        var class_level = $(e.relatedTarget).data('id');
        $('#class_level').val(class_level);
        ShowListLevel();
        $('#NotifikasiTambah').html('');
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
        $('#FormKomponenBiaya').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/FormKomponenBiaya.php',
            data        : {id_organization_class: id_organization_class},
            success     : function(data){
                $('#FormKomponenBiaya').html(data);
            }
        });
    });

    //Proses Komponen Biaya
    $('#ProsesKomponenBiaya').submit(function(){
        $('#NotifikasiKomponenBiaya').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesKomponenBiaya')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Kelas/ProsesKomponenBiaya.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiKomponenBiaya').html(data);
                var NotifikasiKomponenBiayaBerhasil=$('#NotifikasiKomponenBiayaBerhasil').html();
                if(NotifikasiKomponenBiayaBerhasil=="Success"){
                    $('#NotifikasiKomponenBiaya').html('');

                    //Swal
                    Swal.fire(
                        'Success!',
                        'Komponen Biaya Berhasil Diatur!',
                        'success'
                    )

                    //Tutup Modal
                    $('#ModalKomponenBiaya').modal('hide');

                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

});