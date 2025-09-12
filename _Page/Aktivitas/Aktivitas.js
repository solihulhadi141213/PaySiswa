//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();

    // Efek transisi: fadeOut dulu
    $('#MenampilkanTabel').fadeOut(200, function () {
        $.ajax({
            type    : 'POST',
            url     : '_Page/Aktivitas/TabelAktivitas.php',
            data    : ProsesFilter,
            success : function(data) {
                $('#MenampilkanTabel').html(data);

                //Uncheck checkbox utama
                $('input[name="check_all"]').prop('checked', false);

                // Setelah ganti konten → fadeIn lagi
                $('#MenampilkanTabel').fadeIn(200);
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
            url 	    : '_Page/Aktivitas/FormFilter.php',
            data        : {KeywordBy: KeywordBy},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    // Check/uncheck semua siswa
    $('input[name="check_all"]').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('#MenampilkanTabel input[name="id_access_log[]"]').prop('checked', isChecked);
    });

    // Jika semua siswa di-check manual, otomatis check_all ikut tercentang
    $(document).on('change', '#MenampilkanTabel input[name="id_access_log[]"]', function() {
        let total = $('#MenampilkanTabel input[name="id_access_log[]"]').length;
        let checked = $('#MenampilkanTabel input[name="id_access_log[]"]:checked').length;
        $('input[name="check_all"]').prop('checked', total === checked);
    });


    //Modal Hapus
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id_access_log = $(e.relatedTarget).data('id');
        $('#FormHapus').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Aktivitas/FormHapus.php',
            data        : {id_access_log: id_access_log},
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
            url 	    : '_Page/Aktivitas/ProsesHapus.php',
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
                        'Hapus Log Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Hapus Multiple
    $('#ModalHapusMultiple').on('show.bs.modal', function (e) {
        $('#FormHapusMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesMulti')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Aktivitas/FormHapusMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormHapusMultiple').html(data);
                $('#NotifikasiHapusMultiple').html('');
            }
        });
    });

    //Proses Hapus Multiple
    $('#ProsesHapusMultiple').submit(function(){
        $('#NotifikasiHapusMultiple').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapusMultiple')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Aktivitas/ProsesHapusMultiple.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusMultiple').html(data);
                var NotifikasiHapusMultipleBerhasil=$('#NotifikasiHapusMultipleBerhasil').html();
                if(NotifikasiHapusMultipleBerhasil=="Success"){
                    $('#NotifikasiHapusMultiple').html('');

                    //Tutup Modal
                    $('#ModalHapusMultiple').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Hapus Multi Log Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    //Modal Hapus Semua
    $('#ModalHapusSemua').on('show.bs.modal', function (e) {
        $('#NotifikasiHapusSemua').html('');
    });

    //Proses Hapus Semua
    $('#ProsesHapusSemua').submit(function(){
        $('#NotifikasiHapusSemua').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapusSemua')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Aktivitas/ProsesHapusSemua.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusSemua').html(data);
                var NotifikasiHapusSemuaBerhasil=$('#NotifikasiHapusSemuaBerhasil').html();
                if(NotifikasiHapusSemuaBerhasil=="Success"){
                    $('#NotifikasiHapusSemua').html('');

                    //Tutup Modal
                    $('#ModalHapusSemua').modal('hide');

                    //Tampilkan Swal
                     Swal.fire(
                        'Success!',
                        'Hapus Semua Log Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();
                }
            }
        });
    });

    // Load data dari PHP via AJAX
    $.ajax({
        url: '_Page/Aktivitas/GrafikAktivitas.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // response harus JSON berisi kategori & data
            // contoh: { "categories":["Senin","Selasa","Rabu"], "series":[10,20,15] }

            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Aktivitas',
                    data: response.series
                }],
                xaxis: {
                    categories: response.categories
                },
                title: {
                    text: 'Grafik Aktivitas Log',
                    align: 'center'
                }
            };

            var chart = new ApexCharts(document.querySelector("#GrafikAktivitas"), options);
            chart.render();
        },
        error: function(xhr, status, error) {
            $("#GrafikAktivitas").html(
                '<div class="alert alert-danger">Gagal memuat grafik: ' + error + '</div>'
            );
        }
    });


});




