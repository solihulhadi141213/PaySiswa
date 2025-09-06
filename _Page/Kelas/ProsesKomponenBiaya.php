<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    //Validasi Form Required
    $required = ['id_organization_class'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_fee_component       = $_POST['id_fee_component'] ?? [];

    //Hapus Semua Komponen
    $HapusSemuaKomponen = mysqli_query($Conn, "DELETE FROM  fee_by_class WHERE id_organization_class='$id_organization_class'") or die(mysqli_error($Conn));
    if (!$HapusSemuaKomponen) {
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menghapus komponen biaya berdasarkan kelas!</small></div>';
        exit;
    }

    //Jika ada yang dipilih
    if(!empty(count($id_fee_component))){
       
        //Looping Daftar Komponen Biaya Yang Dipilih
        $berhasil_insert_class=0;
        foreach ($id_fee_component as $id_fee_component_list) {
            
            // Insert Data Menggunakan Prepared Statement
            $stmt = $Conn->prepare("INSERT INTO fee_by_class (id_organization_class, id_fee_component) VALUES (?, ?)");
            $stmt->bind_param("ii", $id_organization_class, $id_fee_component_list);
            $Input = $stmt->execute();
            $stmt->close();

            if($Input){

                //Hapus Fee By Student
                $HapusSemuaKomponenStudent = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_fee_component='$id_fee_component_list'") or die(mysqli_error($Conn));
                if ($HapusSemuaKomponenStudent) {
                    
                    //Looping Student
                    $query = mysqli_query($Conn, "SELECT*FROM student WHERE id_organization_class='$id_organization_class'");
                    while ($data = mysqli_fetch_array($query)) {
                        $id_student = $data['id_student'];

                        //Insert Data fee_by_student
                        $stmt2 = $Conn->prepare("INSERT INTO fee_by_student (id_student, id_fee_component) VALUES (?, ?)");
                        $stmt2->bind_param("ii", $id_student, $id_fee_component_list);
                        $Input2 = $stmt2->execute();
                        $stmt2->close();
                        if($Input){
                        }else{

                        }
                    }
                }

                $berhasil_insert_class=$berhasil_insert_class+1;
            }else{
                $berhasil_insert_class=$berhasil_insert_class+0;
            }
        }
        if($berhasil_insert_class==count($id_fee_component)){
            echo '<span class="text-success" id="NotifikasiKomponenBiayaBerhasil">Success</span>';
        }else{
            echo '<div class="alert alert-danger"><small>Kemungkinan tidak semua komponen biaya berhasil disimpan!</small></div>';
        }
    }else{
        //Jika Tidak Ada Yang Dipilih Maka Hapus Semua Komponen
        $HapusSemuaKomponen = mysqli_query($Conn, "DELETE FROM  fee_by_class WHERE id_organization_class='$id_organization_class'") or die(mysqli_error($Conn));
        if (!$HapusSemuaKomponen) {
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menghapus komponen biaya berdasarkan kelas!</small></div>';
            exit;
        }

        //Looping Student
        $query = mysqli_query($Conn, "SELECT*FROM student WHERE id_organization_class='$id_organization_class'");
        while ($data = mysqli_fetch_array($query)) {
            $id_student = $data['id_student'];
            $HapusSemuaKomponenStudent = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_student='$id_student'") or die(mysqli_error($Conn));
            if ($HapusSemuaKomponenStudent) {
            }
        }
        echo '<span class="text-success" id="NotifikasiKomponenBiayaBerhasil">Success</span>';
    }
?>