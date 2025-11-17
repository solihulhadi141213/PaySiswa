<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
        ]);
        exit;
    }

    //Validasi 'id_fee_component'
    if (empty($_POST['id_fee_component'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Komponen Biaya Tidak Boleh Kosong!',
        ]);
        exit;
    }

    //Validasi 'form_name'
    if (empty($_POST['form_name'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Nama Form Edit parsial Tidak Boleh Kosong!',
        ]);
        exit;
    }

    //Validasi 'form_value'
    if (empty($_POST['form_value'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Form tersebut tidak boleh kosong!',
        ]);
        exit;
    }
    
    //Buat Variabel
    $id_fee_component   = validateAndSanitizeInput($_POST['id_fee_component']);
    $form_name          = validateAndSanitizeInput($_POST['form_name']);
    $form_value          = validateAndSanitizeInput($_POST['form_value']);

    //Routing nama kolom database
    if($form_name=="nama"){
        $col_name ="component_name";
    }else{
        if($form_name=="kategori"){
            $col_name ="component_category";
        }else{
            if($form_name=="bulan"){
                $col_name ="periode_month";
            }else{
                if($form_name=="tahun"){
                    $col_name ="periode_year";
                }else{
                    if($form_name=="tempo_awal"){
                        $col_name ="periode_start";
                    }else{
                        if($form_name=="tempo_akhir"){
                            $col_name ="periode_end";
                        }else{
                            if($form_name=="nominal"){
                                $col_name ="fee_nominal";

                                //Format nominal
                                $fee_nominal        = str_replace('.', '', $fee_nominal);
                            }else{
                                $col_name ="";
                            }
                        }
                    }
                }
            }
        }
    }
    
    if(empty($col_name)){
        echo json_encode([
            'status' => 'error',
            'message' => 'Parameter Nama Form Tidak Valid!',
        ]);
        exit;
    }

    //Update Data
    $UpdateEntitias = mysqli_query($Conn,"UPDATE fee_component SET 
        $col_name='$form_value'
    WHERE id_fee_component='$id_fee_component'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){

        //Jika Update Berhasil, Simpan LOG
        $kategori_log="Komponen Biaya";
        $deskripsi_log="Update Komponen Biaya Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);

        //Jika Simpan LOG berhasil
        if($InputLog=="Success"){
            echo json_encode([
                'status' => 'success',
                'message' => 'Update komponen biaya berhasil',
            ]);
        }else{

            //Apabila simpan LOG gagal
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada saat menyimpan log aplikasi',
            ]);
        }
    }else{
        
        //Apabila gagal menyimpan Update Data
         echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat update data komponen biaya',
        ]);
    }
?>