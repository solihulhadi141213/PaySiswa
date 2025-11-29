<?php
    //Koneksi
    include "../../_Config/Connection.php";

    if(empty($_POST['keyword_by'])){
        echo '
            <div class="row mb-3">
                <div class="col-4">
                    <label for="keyword">
                        <small>Kata Kunci</small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="keyword" id="keyword" class="form-control">
                </div>
            </div>
        ';
    }else{
        $keyword_by=$_POST['keyword_by'];

        // Pencarian Berdasarkan Periode Akademik
        if($keyword_by=="id_academic_period"){
            echo '<div class="row mb-3">';
            echo '  
                    <div class="col-4">
                        <label for="keyword">
                            <small>Periode Akademik</small>
                        </label>
                    </div>
            ';
            echo '  <div class="col-8">';
            echo '      <select name="id_academic_period" id="keyword" class="form-control">';
            echo '          <option value="">Pilih</option>';
            //Tampilkan Periode Akademik
            $qry = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period ORDER BY id_academic_period ASC");
            while ($data = mysqli_fetch_array($qry)) {
                $id_academic_period = $data['id_academic_period'];
                $academic_period = $data['academic_period'];
                echo '      <option value="'.$id_academic_period.'">'.$academic_period.'</option>';
            }
            echo '      </select>';
            echo '</div>';
        }else{
            if($keyword_by=="id_organization_class"){
                echo '<div class="row mb-3">';
                echo '  
                        <div class="col-4">
                            <label for="select_periode_akademik_for_class">
                                <small>Periode Akdemik</small>
                            </label>
                        </div>
                ';
                echo '  <div class="col-8">';
                echo '      <select name="select_periode_akademik" id="select_periode_akademik_for_class" class="form-control">';
                echo '          <option value="">Pilih</option>';
                                //Tampilkan Periode Akademik
                                $qry = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period ORDER BY id_academic_period ASC");
                                while ($data = mysqli_fetch_array($qry)) {
                                    $id_academic_period = $data['id_academic_period'];
                                    $academic_period = $data['academic_period'];
                                    echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                }
                echo '      </select>';
                echo '  </div>';
                echo '</div>';
                echo '
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="select_organization_class">
                                <small>Kelas/Rombel</small>
                            </label>
                        </div>
                        <div class="col-8">
                            <select class="form-control" name="id_organization_class" id="select_organization_class">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                    </div>
                ';
            }else{
                if($keyword_by=="id_fee_component"){
                    echo '<div class="row mb-3">';
                    echo '  
                            <div class="col-4">
                                <label for="select_periode_akademik_for_komponen">
                                    <small>Periode Akdemik</small>
                                </label>
                            </div>
                    ';
                    echo '  <div class="col-8">';
                    echo '      <select name="id_academic_period" id="select_periode_akademik_for_komponen" class="form-control">';
                    echo '          <option value="">Pilih</option>';
                                    //Tampilkan Periode Akademik
                                    $qry = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period ORDER BY id_academic_period ASC");
                                    while ($data = mysqli_fetch_array($qry)) {
                                        $id_academic_period = $data['id_academic_period'];
                                        $academic_period = $data['academic_period'];
                                        echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                    }
                    echo '      </select>';
                    echo '  </div>';
                    echo '</div>';
                    echo '
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="select_komponen">
                                    <small>Komponen Biaya</small>
                                </label>
                            </div>
                            <div class="col-8">
                                <select class="form-control" name="id_fee_component" id="select_komponen">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>
                    ';
                }else{
                    if($keyword_by=="payment_datetime"){
                        echo '
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="keyword">
                                        <small>Tanggal Pembayaran</small>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <input type="date" name="keyword" id="keyword" class="form-control">
                                </div>
                            </div>
                        ';
                    }else{
                        if($keyword_by=="payment_method"){
                            echo '
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="keyword">
                                            <small>Metode Pembayaran</small>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <select name="keyword" id="keyword" class="form-control">
                                            <option value="">Pilih</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="E-wallet">E-wallet</option>
                                        </select>
                                    </div>
                                </div>
                            ';
                        }else{
                            echo '
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="keyword">
                                            <small>Kata Kunci</small>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" name="keyword" id="keyword" class="form-control">
                                    </div>
                                </div>
                            ';
                        }
                    }
                }
            }
        }
    }
?>