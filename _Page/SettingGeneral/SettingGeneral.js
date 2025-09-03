//Proses Setting Aplikasi
$('#ProsesSettingAplikasi').submit(function(){
    $('#NotifikasiSimpanSettingGeneral').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesSettingAplikasi')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingGeneral/ProsesSettingAplikasi.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanSettingGeneral').html(data);
            var NotifikasiSimpanSettingGeneralBerhasil=$('#NotifikasiSimpanSettingGeneralBerhasil').html();
            if(NotifikasiSimpanSettingGeneralBerhasil=="Success"){
                window.location.href = "index.php?Page=SettingGeneral";
            }
        }
    });
});

//Proses Update Favicon
$('#ProsesUpdateFavicon').submit(function(){
    $('#NotifikasiUpdateFavicon').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUpdateFavicon')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingGeneral/ProsesUpdateFavicon.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdateFavicon').html(data);
            var NotifikasiUpdateFaviconBerhasil=$('#NotifikasiUpdateFaviconBerhasil').html();
            if(NotifikasiUpdateFaviconBerhasil=="Success"){
                window.location.href = "index.php?Page=SettingGeneral";
            }
        }
    });
});

//Proses Update Logo
$('#ProsesUpdateLogo').submit(function(){
    $('#NotifikasiUpdateLogo').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUpdateLogo')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingGeneral/ProsesUpdateLogo.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdateLogo').html(data);
            var NotifikasiUpdateLogoBerhasil=$('#NotifikasiUpdateLogoBerhasil').html();
            if(NotifikasiUpdateLogoBerhasil=="Success"){
                window.location.href = "index.php?Page=SettingGeneral";
            }
        }
    });
});

//Proses Update Company
$('#ProsesUpdateCompany').submit(function(){
    $('#NotifikasiUpdateCompany').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUpdateCompany')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingGeneral/ProsesUpdateCompany.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdateCompany').html(data);
            var NotifikasiUpdateCompanyBerhasil=$('#NotifikasiUpdateCompanyBerhasil').html();
            if(NotifikasiUpdateCompanyBerhasil=="Success"){
                window.location.href = "index.php?Page=SettingGeneral";
            }
        }
    });
});