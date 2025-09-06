<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $response = [
        'success' => false,
        'message' => ''
    ];

    //Validasi Akses Login
    if(empty($SessionIdAkses)){
        $response['message'] = 'Sesi Akses Sudah Berakhir, Silahkkan Login Ulang';
        echo json_encode($response);
        exit;
    }else{
        $now=date('Y-m-d H:i:s');
        //Tangkap data
        if(empty($_POST['kategori-before'])){
            $response['message'] = 'Kategori Bantuan Sebelumnya Tidak Boleh Kosong!';
            echo json_encode($response);
            exit;
        }else{
            if(empty($_POST['kategori'])){
                $response['message'] = 'Kategori Bantuan Tidak Boleh Kosong!';
                echo json_encode($response);
                exit;
            }else{
                //Buat Variabel
                $kategori_before=$_POST['kategori-before'];
                $kategori=$_POST['kategori'];
                //Bersihkan Variabel
                $kategori_before=validateAndSanitizeInput($kategori_before);
                $kategori=validateAndSanitizeInput($kategori);
                //Simpan data
                $Update = mysqli_query($Conn,"UPDATE help SET 
                    kategori='$kategori'
                WHERE kategori='$kategori_before'") or die(mysqli_error($Conn)); 
                if($Update){
                    //Apabila Berhasil, Simpan Log
                    $kategori_log="Bantuan";
                    $deskripsi_log="Edit Kategori Bantuan";
                    $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                    if($InputLog=="Success"){
                        $response['success'] = true;
                        $response['message'] = 'Kategori berhasil diperbarui.';
                        echo json_encode($response);
                        exit;
                    }else{
                        $response['message'] = 'Terjadi kesalahan pada saat menyimpan Log';
                        echo json_encode($response);
                        exit;
                    }
                }else{
                    $response['message'] = 'Terjadi kesalahan pada saat menyimpan data pada database';
                    echo json_encode($response);
                    exit;
                }
            }
        }
    }
?>