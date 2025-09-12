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
    $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period  ORDER BY academic_period_start ASC");
     while ($data = mysqli_fetch_array($query)) {
        $id_academic_period = $data['id_academic_period'];
        $academic_period= $data['academic_period'];

        //Hitung jumlah tagihan
        $SqlFee = "SELECT SUM(fee_nominal) AS total_fee FROM fee_component";
        $ResultFee = $Conn->query($SqlFee);
        if ($ResultFee) {
            $RowFee= $ResultFee->fetch_assoc();
            $TotalFee = $RowFee['total_fee'] ?? 0;
            $TotalFee_format = "Rp " . number_format($TotalFee,0,',','.');
        }else{
            $TotalFee=0;
            $TotalFee_format = "Rp " . number_format($TotalFee,0,',','.');
        }
        echo '
            <tr>
                <td>
                    <small class="text text-grayish">'.$academic_period.'</small>
                </td>
                <td class="text-end">
                    <small class="text text-grayish">'.$TotalFee_format.'</small>
                </td>
            </tr>
        ';
     }
?>