<?php
    //Jika tidak ada data yang dipilih
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                Tidak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.
            </div>
            <script>$("#konfirmasi_hapus_tagihan_siswa_multiple").prop("disabled", true);</script>
        ';
    }else{
        if(empty(count($_POST['id_fee_by_student']))){
            echo '
                <div class="alert alert-danger">
                    Tidak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.
                </div>
                <script>$("#konfirmasi_hapus_tagihan_siswa_multiple").prop("disabled", true);</script>
            ';
        }else{
            echo '
                <div class="alert alert-warning">
                    Apakah anda yakin akan menghapus data tersebut?
                </div>
                <script>$("#konfirmasi_hapus_tagihan_siswa_multiple").prop("disabled", false);</script>
            ';
        }
    }
?>