<?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
    }else{
        if(empty($_POST['id_access_feature'])){
            echo '
                <div class="alert alert-danger">
                    <small>ID Fitur Tidak Boleh Kosong!</small>
                </div>
            ';
        }else{
            $id_access_feature = validateAndSanitizeInput($_POST['id_access_feature']);

             //Buka Data
            $feature_name           = GetDetailData($Conn, 'access_feature', 'id_access_feature', $id_access_feature,'feature_name');
            $feature_category       = GetDetailData($Conn, 'access_feature', 'id_access_feature', $id_access_feature,'feature_category');
            $feature_description    = GetDetailData($Conn, 'access_feature', 'id_access_feature', $id_access_feature,'feature_description');

            //Jumlah Akses
            $JumlahPengguna =mysqli_num_rows(mysqli_query($Conn, "SELECT id_permission FROM access_permission WHERE id_access_feature='$id_access_feature'"));
            if(empty($JumlahPengguna)){
                $label_jumlah_pengguna='<span class="badge badge-danger">NULL</span>';
            }else{
                $label_jumlah_pengguna='<span class="badge badge-success">'.$JumlahPengguna.' Orang</span>';
            }
            
            //Tampilkan Data
            echo '<input type="hidden" name="id_access_feature" value="'.$id_access_feature.'">';
            echo '
                <div class="row mb-2">
                    <div class="col-4"><small>Kode Fitur</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="badge badge-secondary">
                            <code class="text-dark">'.$id_access_feature.'</code>
                        </small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Nama Fitur</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-muted">'.$feature_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Kategori</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-muted">'.$feature_category.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Keterangan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-muted">'.$feature_description.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Jumlah Akses/User</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-muted">'.$label_jumlah_pengguna.'</small></div>
                </div>
            ';
            echo '
                <div class="row mb-2">
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">
                            <h3>Penting!</h3>
                            <small>Menghapus feature aplikasi ini mungkin dapat menyebabkan anda tidak bisa melakukan akses pada halaman tersebut.</small><br>
                            <b>Apakah anda yakin akan menghapusnya?</b>
                        </div>
                    </div>
                </div>
            ';
        }
    }
?>
