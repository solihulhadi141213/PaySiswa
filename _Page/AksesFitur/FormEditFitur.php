<?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
    }else{

        //Validasi id_access_feature
        if(empty($_POST['id_access_feature'])){
            echo '
                <div class="alert alert-danger">
                    <small class="text-danger">ID Feature Tidak Boleh Kosong!</small>
                </div>
            ';
        }else{

            //Buat Variabel
            $id_access_feature = validateAndSanitizeInput($_POST['id_access_feature']);
            
            //Buka Data
            $Qry = $Conn->prepare("SELECT * FROM access_feature WHERE id_access_feature = ?");
            $Qry->bind_param("i", $id_access_feature);
            if (!$Qry->execute()) {
                $error=$Conn->error;
                echo '
                    <div class="alert alert-danger">
                        <small>Terjadi kesalahan pada saat membuka data fitur dari database!<br>Keterangan : '.$error.'</small>
                    </div>
                ';
            }else{
                $Result = $Qry->get_result();
                $Data = $Result->fetch_assoc();
                $Qry->close();

                //Buat Variabel
                $feature_name           =$Data['feature_name'];
                $feature_category       =$Data['feature_category'];
                $feature_description    =$Data['feature_description'];

                //Tampilkan Form
                echo '<input type="hidden" name="id_access_feature" value="'.$id_access_feature.'">';
                echo '
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nama_edit">
                                <small>Nama Fitur</small>
                            </label>
                            <input type="text" class="form-control" name="nama" id="nama_edit" value="'.$feature_name.'">
                        </div>
                    </div>
                ';
                echo '<div class="row mb-3">';
                echo '
                        <div class="col-md-12">
                            <label for="kategori">
                                <small>Kategori Fitur</small>
                            </label>
                ';
                echo '      <input type="text" class="form-control" name="kategori" id="kategori_edit" list="ListKategoriEdit" value="'.$feature_category.'">';
                echo '      <datalist id="ListKategoriEdit">';
                            $query = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $feature_category= $data['feature_category'];
                                echo '<option value="'.$feature_category.'">';
                            }
                echo '      </datalist>';
                echo '  </div>';
                echo '</div>';
                echo '
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="keterangan_edit">
                                <small>Deskripsi Fitur</small>
                            </label>
                            <textarea name="keterangan" id="keterangan_edit" class="form-control">'.$feature_description.'</textarea>
                        </div>
                    </div>
                ';
            }
        }
    }
?>