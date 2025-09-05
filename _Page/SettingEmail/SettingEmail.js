//Proses Simpan Setting Email
$('#ProsesSettingEmail').submit(function(){
    $('#NotifikasiSimpanSettingEmail').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesSettingEmail')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingEmail/ProsesSettingEmail.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanSettingEmail').html(data);
            var NotifikasiSimpanSettingEmailBerhasil=$('#NotifikasiSimpanSettingEmailBerhasil').html();
            if(NotifikasiSimpanSettingEmailBerhasil=="Success"){
                window.location.href = "index.php?Page=SettingEmail";
            }
        }
    });
});
//Proses Test Kirim Email
$('#ProsesTestKirimEmail').submit(function(){
    $('#NotifikasiTestKirimEmail').html("Loading..");
    var form = $('#ProsesTestKirimEmail')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/SettingEmail/ProsesTestSendEmail.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTestKirimEmail').html(data);
        }
    });
});