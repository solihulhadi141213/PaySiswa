<div class="row">
    <div class="col-md-12 mb-3">
        <label for="password1">Password Baru</label>
        <input type="password" name="password1" id="password1_edit" class="form-control">
        <small class="credit">Password hanya boleh terdiri dari 6-20 karakter angka dan huruf</small>
    </div>
    <div class="col-md-12 mb-3">
        <label for="password2">Ulangi Password</label>
        <input type="password" name="password2" id="password2_edit" class="form-control">
        <small class="credit">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword2" name="TampilkanPassword2">
                <label class="form-check-label" for="TampilkanPassword2">
                    Tampilkan Password
                </label>
            </div>
        </small>
    </div>
</div>
<script>
    //Kondisi saat tampilkan password
    $('#TampilkanPassword2').click(function(){
        if($(this).is(':checked')){
            $('#password1_edit').attr('type','text');
            $('#password2_edit').attr('type','text');
        }else{
            $('#password1_edit').attr('type','password');
            $('#password2_edit').attr('type','password');
        }
    });
</script>