<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <code>Sesi Login Sudah Berakhir, Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        $id_akses=$SessionIdAkses;
        //Bersihkan Variabel
        $id_akses=validateAndSanitizeInput($id_akses);
        //Buka data askes
        $nama_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'nama_akses');
        $kontak_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'kontak_akses');
        $email_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'email_akses');
        $image_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'image_akses');
        $akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'akses');
        $datetime_daftar=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_daftar');
        $datetime_update=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_update');
        //Jumlah
        $JumlahAktivitas =mysqli_num_rows(mysqli_query($Conn, "SELECT id_akses FROM log WHERE id_akses='$id_akses'"));
        $JumlahRole =mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM akses_ijin WHERE id_akses='$id_akses'"));
        //Format Tanggal
        $strtotime1=strtotime($datetime_daftar);
        $strtotime2=strtotime($datetime_update);
        //Menampilkan Tanggal
        $DateDaftar=date('d/m/Y H:i:s T', $strtotime1);
        $DateUpdate=date('d/m/Y H:i:s T', $strtotime2);
        if(!empty($image_akses)){
            $image_akses=$image_akses;
        }else{
            $image_akses="No-Image.png";
        }
?>
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
<?php 
    } 
?>