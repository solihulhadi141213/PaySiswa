<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    //Validasi Session Akses Masih ADa
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 text-center">';
        echo '      <code class="text-danger">';
        echo '          Sesi akses sudah berakhir, silahkan login ulang!';
        echo '      </code>';
        echo '  </div>';
        echo '</div>';
    }else{
        //Keyword_by
        if(!empty($_POST['keyword_by'])){
            $keyword_by=$_POST['keyword_by'];
        }else{
            $keyword_by="";
        }
        //keyword
        if(!empty($_POST['keyword'])){
            $keyword=$_POST['keyword'];
        }else{
            $keyword="";
        }
        //batas
        if(!empty($_POST['batas'])){
            $batas=$_POST['batas'];
        }else{
            $batas="10";
        }
        //ShortBy
        if(!empty($_POST['ShortBy'])){
            $ShortBy=$_POST['ShortBy'];
            if($ShortBy=="ASC"){
                $NextShort="DESC";
            }else{
                $NextShort="ASC";
            }
        }else{
            $ShortBy="DESC";
            $NextShort="ASC";
        }
        //OrderBy
        if(!empty($_POST['OrderBy'])){
            $OrderBy=$_POST['OrderBy'];
        }else{
            $OrderBy="id_order_transaksi";
        }
        //Atur Page
        if(!empty($_POST['page'])){
            $page=$_POST['page'];
            $posisi = ( $page - 1 ) * $batas;
        }else{
            $page="1";
            $posisi = 0;
        }
        //Buka Pengaturan
        $api_key = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_key');
        $server_key = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'server_key');
        $production = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'production');
        $api_payment_url =GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_payment_url');
        $headers = array(
            'Content-Type: Application/x-www-form-urlencoded',
        );
        // Array data untuk dikirim via CURL
        $filter = array(
            "page" => $page,
            "limit" => $batas,
            "ShortBy" => $ShortBy,
            "OrderBy" => $OrderBy,
            "keyword_by" => $keyword_by,
            "keyword" => $keyword
        );
        $arr = array(
            "api_key" => $api_key,
            "filter" => $filter
        );
        $json = json_encode($arr);
        // CURL init
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$api_payment_url/order_transaksi.php");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // Timeout lebih lama
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        // Eksekusi CURL dan cek error
        $response_curl = curl_exec($curl);
        if ($response_curl === false) {
            $response['message'] = 'CURL Error: ' . curl_error($curl);
            echo '<div class="row">';
            echo '  <div class="col-md-12 text-center">';
            echo '      <code class="text-danger">';
            echo '          CURL Error: ' . curl_error($curl);
            echo '      </code>';
            echo '  </div>';
            echo '</div>';
        } else {
            // Decode response JSON
            $data = json_decode($response_curl, true); 
            //Validasi Format JSON
            if (json_last_error() === JSON_ERROR_NONE) {
                //Apabila Tidak Ada Response Code
                if(empty($data["code"])){
                    echo '<div class="row">';
                    echo '  <div class="col-md-12 text-center">';
                    echo '      <code class="text-danger">';
                    echo '         Tidak Ada Response Dari Server';
                    echo '      </code>';
                    echo '  </div>';
                    echo '</div>';
                }else{
                    $code = $data["code"];
                    //Apabila Response Code Tidak Sesuai
                    if($code!==200){
                        echo '<div class="row">';
                        echo '  <div class="col-md-12 text-center">';
                        echo '      <code class="text-danger">';
                        echo            $data["status"];
                        echo '      </code>';
                        echo '  </div>';
                        echo '</div>';
                    }else{
                        $jml_data=$data["jumlah_data"];
                        $JmlHalaman=$data["jumlah_halaman"];
                        //Mengatur Halaman
                        $prev=$page-1;
                        $next=$page+1;
                        if($next>$JmlHalaman){
                            $next=$page;
                        }else{
                            $next=$page+1;
                        }
                        if($prev<"1"){
                            $prev="1";
                        }else{
                            $prev=$page-1;
                        }
                        if(empty($jml_data)){
                            echo '<div class="row">';
                            echo '  <div class="col-md-12 text-center text-danger">';
                            echo '      <code class="text-danger">';
                            echo '          Tidak Ada Data Yang Ditampilkan';
                            echo '      </code>';
                            echo '  </div>';
                            echo '</div>';
                        }else{
                            $list=$data["list"];
                            $no=1;
                            foreach($list as $list_data){
                                $id_order_transaksi=$list_data['id_order_transaksi'];
                                $kode_transaksi=$list_data['kode_transaksi'];
                                $order_id=$list_data['order_id'];
                                $datetime=$list_data['datetime'];
                                $ServerKey=$list_data['ServerKey'];
                                $Production=$list_data['Production'];
                                $gross_amount=$list_data['gross_amount'];
                                $name=$list_data['name'];
                                $email=$list_data['email'];
                                $phone=$list_data['phone'];
                                $snapToken=$list_data['snapToken'];
                                //Format Tanggal
                                $strtotime=strtotime($datetime);
                                $datetime_format=date('d M Y H:i:s',$strtotime);
                                //Potong Karakter Yang terlalu panjang
                                $kode_transaksi_short = substr($list_data['kode_transaksi'], 0, 10) . '...';
                                $order_id_short = substr($list_data['order_id'], 0, 10) . '...';
                                $ServerKeyShort = substr($list_data['ServerKey'], 0, 10) . '...';
                                $SnapTokenShort = substr($list_data['snapToken'], 0, 10) . '...';
                                //Format Rupiah
                                $gross_amount_rupiah = 'Rp ' . number_format($gross_amount, 0, ',', '.');
                                //Menampilkan Data List
                                echo '<div class="row border-1 border-bottom hover-shadow-soft">';
                                echo '  <div class="col-md-12 mt-4 mb-4">';
                                echo '      <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetailOrderTransaksi" data-id="'.$id_order_transaksi.'">';
                                echo '          <div class="row">';
                                echo '              <div class="col-md-4">';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Kode</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$kode_transaksi_short.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Order ID</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$order_id_short.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Datetime</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$datetime_format.'</code></small></div>';
                                echo '                  </div>';
                                echo '              </div>';
                                echo '              <div class="col-md-4">';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Amount</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$gross_amount_rupiah.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Snap Token</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$SnapTokenShort.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Production</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$Production.'</code></small></div>';
                                echo '                  </div>';
                                echo '              </div>';
                                echo '              <div class="col-md-4">';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Customer</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$name.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Email</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$email.'</code></small></div>';
                                echo '                  </div>';
                                echo '                  <div class="row">';
                                echo '                      <div class="col col-md-4"><small class="text-dark">Phone</small></div>';
                                echo '                      <div class="col col-md-8"><small><code class="text text-grayish">'.$phone.'</code></small></div>';
                                echo '                  </div>';
                                echo '              </div>';
                                echo '          </div>';
                                echo '      </a>';
                                echo '  </div>';
                                echo '</div>';
                                $no++;
                            }
                        }
?>
                        <div class="row mt-4">
                            <div class="col-md-12 text-center mt-5">
                                <div class="btn-group shadow-0" role="group" aria-label="Basic example">
                                    <button class="btn btn-md btn-grayish" id="PrevPage" value="<?php echo $prev;?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <button class="btn btn-md btn-outline-grayish">
                                        <?php echo "$page of $JmlHalaman"; ?>
                                    </button>
                                    <button class="btn btn-md btn-grayish" id="NextPage" value="<?php echo $next;?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
<?php
                    }
                }
            } else {
                echo '<div class="row">';
                echo '  <div class="col-md-12 text-center">';
                echo '      <code class="text-danger">';
                echo '          Error pada decoding JSON: '.$response_curl.' ' . json_last_error_msg();
                echo '      </code>';
                echo '  </div>';
                echo '</div>';
            }
        }
    }
?>
    <script>
        //ketika klik next
        $('#NextPage').click(function() {
            var page=$('#NextPage').val();
            $('#page').val(page);
            filterAndLoadTable();
        });
        //Ketika klik Previous
        $('#PrevPage').click(function() {
            var page = $('#PrevPage').val();
            $('#page').val(page);
            filterAndLoadTable();
        });
    </script>