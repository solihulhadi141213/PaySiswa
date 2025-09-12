<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($SessionIdAccess)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center text-danger">';
        echo '      Sesi Akses Sudah Berakhir, Silahkan Login Ulang';
        echo '  </div>';
        echo '</div>';
    }else{
        if(empty($_POST['id_help'])){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center text-danger">';
            echo '      ID Konten Bantuan Tidak Boleh Kosong';
            echo '  </div>';
            echo '</div>';
        }else{
            $id_help=$_POST['id_help'];
            //Bersihkan Variabel
            $id_help=validateAndSanitizeInput($id_help);
            //Validasi Keberadaan Data
            $id_help=GetDetailData($Conn,'help','id_help',$id_help,'id_help');
            if(empty($id_help)){
                echo '<div class="row">';
                echo '  <div class="col-md-12 mb-3 text-center text-danger">';
                echo '      ID Bantuan Yang Anda Gunakan Tidak Valid!';
                echo '  </div>';
                echo '</div>';
            }else{
                $author=GetDetailData($Conn,'help','id_help',$id_help,'author');
                $judul=GetDetailData($Conn,'help','id_help',$id_help,'judul');
                $kategori=GetDetailData($Conn,'help','id_help',$id_help,'kategori');
                $deskripsi=GetDetailData($Conn,'help','id_help',$id_help,'deskripsi');
                $datetime_creat=GetDetailData($Conn,'help','id_help',$id_help,'datetime_creat');
                $datetime_update=GetDetailData($Conn,'help','id_help',$id_help,'datetime_update');
                $status=GetDetailData($Conn,'help','id_help',$id_help,'status');
                //Format Waktu
                $strtotime1=strtotime($datetime_creat);
                $strtotime2=strtotime($datetime_update);
                $TanggalCreatFormat=date('d F Y H:i T',$strtotime1);
                $TanggalUpdateFormat=date('d/m/Y H:i:s T',$strtotime2);

                //Decode
                $deskripsi = html_entity_decode($deskripsi);
                echo '
                    <div class="row">
                        <div class="col-12">
                            <h2>'.$judul.'</h2>
                            <small>Kategori : <i class="text text-grayish">'.$kategori.'</i></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small>'.$TanggalCreatFormat.' | '.$author.'</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">'.$deskripsi.'</div>
                    </div>
                ';
            }
        }
    } 
?>