<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['id_access_group'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }
    
    //Buat Variabel
    $id_access_group=validateAndSanitizeInput($_POST['id_access_group']);

    //Buka Data
    $Qry = $Conn->prepare("SELECT * FROM access_group WHERE id_access_group = ?");
    $Qry->bind_param("i", $id_access_group);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
    }else{
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $group_name             =$Data['group_name'];
        $group_description      =$Data['group_description'];

        //Tampilkan Detail Entitas Group
        echo '
            <input type="hidden" name="id_access_group" value="'.$id_access_group.'">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="akses_edit">
                        <small>Nama Group/Entitias</small>
                    </label>
                    <input type="text" class="form-control" name="akses" id="akses_edit" value="'.$group_name.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="keterangan_edit">
                        <small>Deskripsi/Keterangan</small>
                    </label>
                    <textarea name="keterangan" id="keterangan_edit" class="form-control">'.$group_description.'</textarea>
                </div>
            </div>
        ';
        echo '<div class="row mb-3">';
        echo '  <div class="col-12">';

            //Apakah Ada Data Fitur
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM  access_feature"));
            if(empty($jml_data)){
                echo '
                    <div class="alert alert-danger">
                        <small>
                            Belum ada data fitur aplikasi, silahkan tambahkan fitur aplikasi terlebih dulu
                        </small>
                    </div>
                ';
            }else{
                echo '<ul>';
                
                //Level Kategori
                $no_kategori=1;
                $QryKategoriFitur = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
                while ($DataKategori = mysqli_fetch_array($QryKategoriFitur)) {
                    $feature_category= $DataKategori['feature_category'];
                    echo '<li class="mb-3">';
                    echo '  
                        <input type="checkbox" class="KelasKategoriEdit" id="IdKategoriEdit'.$no_kategori.'" value="'.$no_kategori.'">
                        <label for="IdKategoriEdit'.$no_kategori.'"><b>'.$feature_category.'</b></label>
                    ';
                        echo '<ul>';
                        //Level Feature
                        $no_fitur=1;
                        $QryFitur = mysqli_query($Conn, "SELECT * FROM access_feature WHERE feature_category='$feature_category' ORDER BY feature_name ASC");
                        while ($DataFitur = mysqli_fetch_array($QryFitur)) {
                            $id_access_feature= $DataFitur['id_access_feature'];
                            $nama= $DataFitur['feature_name'];
                            $keterangan= $DataFitur['feature_description'];
                            $kode= $DataFitur['id_access_feature'];
                            $CekFiturEntitias=CekFiturEntitias($Conn,$id_access_group,$id_access_feature);
                            if($CekFiturEntitias=="Ada"){
                                $checked="checked";
                            }else{
                                $checked="";
                            }
                            echo ' 
                                <li class="mb-2">
                                    <input '.$checked.' type="checkbox" name="rules[]" class="ListFiturEdit" kategori="'.$no_kategori.'" id="IdFiturEdit'.$id_access_feature.'" value="'.$id_access_feature.'">
                                    <label for="IdFiturEdit'.$id_access_feature.'">'.$nama.'</label><br>
                                    <code class="text text-grayish">'.$keterangan.'</code>
                                </li>
                            ';
                            $no_fitur++;
                        }
                        echo '</ul>';
                    echo '</li>';
                    $no_kategori++;
                }
                echo '</ul>';
            }
        echo '  </div>';
        echo '</div>';
    }
?>

<script>
    // Ketika class=KelasKategori di check
    $('.KelasKategoriEdit').change(function() {
        var kategoriId = $(this).val();
        var isChecked = $(this).is(':checked');
        
        // Check/uncheck semua ListFitur dengan kategori yang sesuai
        $('.ListFiturEdit[kategori="' + kategoriId + '"]').prop('checked', isChecked);
    });

    // Ketika salah satu class="ListFitur" di check
    $('.ListFiturEdit').change(function() {
        var kategoriId = $(this).attr('kategori');
        
        // Jika salah satu ListFitur dalam kategori tersebut tidak dicheck, uncheck KelasKategori
        if ($('.ListFiturEdit[kategori="' + kategoriId + '"]:not(:checked)').length > 0) {
            $('#IdKategoriEdit' + kategoriId).prop('checked', false);
        } else {
            // Jika semua ListFitur dalam kategori tersebut dicheck, check KelasKategori
            $('#IdKategoriEdit' + kategoriId).prop('checked', true);
        }
    });
</script>