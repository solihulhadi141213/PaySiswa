//Modal Ubah Identitias Profil
$('#ModalUbahIdentitasProfil').on('show.bs.modal', function (e) {
    $('#FormUbahIdentitasProfil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahIdentitasProfil.php',
        success     : function(data){
            $('#FormUbahIdentitasProfil').html(data);
        }
    });
});
//Proses Ubah Identitias Profil
$('#ProsesUbahIdentitasProfil').submit(function(){
    $('#NotifikasiUbahIdentitasProfil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahIdentitasProfil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahIdentitasProfil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahIdentitasProfil').html(data);
            var NotifikasiUbahIdentitasProfilBerhasil=$('#NotifikasiUbahIdentitasProfilBerhasil').html();
            if(NotifikasiUbahIdentitasProfilBerhasil=="Success"){
                location.reload();
            }
        }
    });
});
//Modal Ubah Foto Profil
$('#ModalUbahFotoProfil').on('show.bs.modal', function (e) {
    $('#FormUbahFotoProfil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahFotoProfil.php',
        success     : function(data){
            $('#FormUbahFotoProfil').html(data);
        }
    });
});
//Proses Foto Profil
$('#ProsesUbahFotoProfil').submit(function(){
    $('#NotifikasiUbahFotoProfil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahFotoProfil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahFotoProfil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahFotoProfil').html(data);
            var NotifikasiUbahFotoProfilBerhasil=$('#NotifikasiUbahFotoProfilBerhasil').html();
            if(NotifikasiUbahFotoProfilBerhasil=="Success"){
                location.reload();
            }
        }
    });
});
//Modal Ubah Password
$('#ModalUbahPasswordProfil').on('show.bs.modal', function (e) {
    $('#FormUbahPasswordProfil').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahPasswordProfil.php',
        success     : function(data){
            $('#FormUbahPasswordProfil').html(data);
        }
    });
});
//Proses Ubah Password Profil
$('#ProsesUbahPasswordProfil').submit(function(){
    $('#NotifikasiUbahPasswordProfil').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahPasswordProfil')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahPasswordProfil.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahPasswordProfil').html(data);
            var NotifikasiUbahPasswordProfilBerhasil=$('#NotifikasiUbahPasswordProfilBerhasil').html();
            if(NotifikasiUbahPasswordProfilBerhasil=="Success"){
                location.reload();
            }
        }
    });
});
//Edit Akses  Anggota
$('#ProsesEditAksesAnggota').submit(function(){
    $('#NotifikasiEditProfileAnggota').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesEditAksesAnggota')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesEditAksesAnggota.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditProfileAnggota').html(data);
            var NotifikasiEditProfileAnggotaBerhasil=$('#NotifikasiEditProfileAnggotaBerhasil').html();
            if(NotifikasiEditProfileAnggotaBerhasil=="Success"){
                location.reload();
            }
        }
    });
});
//Edit Password 
$('#ProsesEditPassword').submit(function(){
    $('#NotifikasiEditPassword').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesEditPassword')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesEditPassword.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditPassword').html(data);
            var NotifikasiEditPasswordBerhasil=$('#NotifikasiEditPasswordBerhasil').html();
            if(NotifikasiEditPasswordBerhasil=="Success"){
                window.location.href = "index.php?Page=MyProfile";
            }
        }
    });
});
//Edit Password Anggota
$('#ProsesEditPasswordAnggota').submit(function(){
    $('#NotifikasiEditPasswordAnggota').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesEditPasswordAnggota')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesEditPasswordAnggota.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditPasswordAnggota').html(data);
            var NotifikasiEditPasswordAnggotaBerhasil=$('#NotifikasiEditPasswordAnggotaBerhasil').html();
            if(NotifikasiEditPasswordAnggotaBerhasil=="Success"){
                window.location.href = "index.php?Page=MyProfile";
            }
        }
    });
});
//Kondisi saat tampilkan password
$('#TampilkanPassword2').click(function(){
    if($(this).is(':checked')){
        $('#old_password').attr('type','text');
        $('#password1').attr('type','text');
        $('#password2').attr('type','text');
    }else{
        $('#old_password').attr('type','password');
        $('#password1').attr('type','password');
        $('#password2').attr('type','password');
    }
});
//Modal Ubah Email Anggota
$('#ModalUbahEmailAnggota').on('show.bs.modal', function (e) {
    $('#FormUbahEmailAnggota').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahEmailAnggota.php',
        success     : function(data){
            $('#FormUbahEmailAnggota').html(data);
            $('#NotifikasiUbahEmailAnggota').html('Pastikan Alamat Email Yang Anda Gunakan Sudah Benar');
        }
    });
});
//Proses Edit Email Anggota
$('#ProsesUbahEmailAnggota').submit(function(){
    $('#NotifikasiUbahEmailAnggota').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahEmailAnggota')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahEmailAnggota.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahEmailAnggota').html(data);
            var NotifikasiUbahEmailAnggotaBerhasil=$('#NotifikasiUbahEmailAnggotaBerhasil').html();
            if(NotifikasiUbahEmailAnggotaBerhasil=="Success"){
                window.location.href = "index.php?Page=MyProfile";
            }
        }
    });
});
//Modal Ubah Password Anggota
$('#ModalUbahPasswordAnggota').on('show.bs.modal', function (e) {
    $('#FormUbahPasswordAnggota').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahPasswordAnggota.php',
        success     : function(data){
            $('#FormUbahPasswordAnggota').html(data);
            $('#NotifikasiUbahPasswordAnggota').html('Pastikan Password Yang Anda Gunakan Sudah Benar');
        }
    });
});
//Proses Ubah Password Anggota
$('#ProsesUbahPasswordAnggota').submit(function(){
    $('#NotifikasiUbahPasswordAnggota').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahPasswordAnggota')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahPasswordAnggota.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahPasswordAnggota').html(data);
            var NotifikasiUbahPasswordAnggotaBerhasil=$('#NotifikasiUbahPasswordAnggotaBerhasil').html();
            if(NotifikasiUbahPasswordAnggotaBerhasil=="Success"){
                window.location.href = "index.php?Page=MyProfile";
            }
        }
    });
});
//Modal Ubah Foto Anggota
$('#ModalUbahFotoAnggota').on('show.bs.modal', function (e) {
    $('#FormUbahFotoAnggota').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/FormUbahFotoAnggota.php',
        success     : function(data){
            $('#FormUbahFotoAnggota').html(data);
            $('#NotifikasiUbahFotoAnggota').html('Pastikan Foto Yang Anda Gunakan Sudah Sesuai');
        }
    });
});
//Proses Ubah Foto Anggota
$('#ProsesUbahFotoAnggota').submit(function(){
    $('#NotifikasiUbahFotoAnggota').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUbahFotoAnggota')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/MyProfile/ProsesUbahFotoAnggota.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUbahFotoAnggota').html(data);
            var NotifikasiUbahFotoAnggotaBerhasil=$('#NotifikasiUbahFotoAnggotaBerhasil').html();
            if(NotifikasiUbahFotoAnggotaBerhasil=="Success"){
                window.location.href = "index.php?Page=MyProfile";
            }
        }
    });
});