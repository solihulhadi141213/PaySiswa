<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Hitung jumlah data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period  FROM academic_period"));
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="2" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Tampilkan Data
    $total_pembayaran=0;
    $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period  ORDER BY academic_period_start ASC");
     while ($data = mysqli_fetch_array($query)) {
        $id_academic_period = $data['id_academic_period'];
        $academic_period= $data['academic_period'];

        //Hitung jumlah pembayaran
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class = $data_kelas['id_organization_class'];

            //Hitung Jumlah Pembayaran
            $SumPembayaran = mysqli_fetch_array(mysqli_query(
                $Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class'"
            ));
            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
            $total_pembayaran=$total_pembayaran+$jumlah_pembayaran;
        }
        $total_pembayaran_format = "Rp " . number_format($total_pembayaran,0,',','.');


        echo '
            <tr>
                <td>
                    <small class="text text-grayish">'.$academic_period.'</small>
                </td>
                <td class="text-end">
                    <small class="text text-grayish">'.$total_pembayaran_format.'</small>
                </td>
            </tr>
        ';
     }
?>