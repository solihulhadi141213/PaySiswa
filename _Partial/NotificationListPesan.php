<?php
    //Karena Ini Di running Dengan JS maka Panggil Ulang Koneksi
    include "../_Config/Connection.php";
    include "../_Config/GlobalFunction.php";
    include "../_Config/Session.php";
    $JumlahTestimoniPending = 0;
    //Apabila Tidak ada notifgikasi
    if(empty($JumlahTestimoniPending)){
        echo '<li class="dropdown-header">';
        echo '  Tidak Ada Pemberitahuan Yang Tersedia';
        echo '</li>';
    }else{
        //Apabila Ada
        echo '<li class="dropdown-header">';
        echo '  Ada '.$JumlahTestimoniPending.' Testimoni Menunggu Moderasi';
        echo '</li>';
        echo '<li><hr class="dropdown-divider"></li>';
        $QryTestimoni = mysqli_query($Conn, "SELECT * FROM web_testimoni WHERE status='Draft'");
        while ($DataTestimoni = mysqli_fetch_array($QryTestimoni)) {
            $id_web_testimoni= $DataTestimoni['id_web_testimoni'];
            $id_member= $DataTestimoni['id_member'];
            $nik_name= $DataTestimoni['nik_name'];
            $testimoni= $DataTestimoni['testimoni'];
            $foto_profil= $DataTestimoni['foto_profil'];
            $datetime= $DataTestimoni['datetime'];
            //Format Testimoni
            if (strlen($testimoni) > 30) {
                $shortTestimoni = substr($testimoni, 0, 30). '...';
            }else{
                $shortTestimoni =$testimoni;
            }
            //Buka Data Member
            $NamaMember=GetDetailData($Conn,'member','id_member',$id_member,'nama');
            $foto=GetDetailData($Conn,'member','id_member',$id_member,'foto');
            //Path URL Foto Profil
            if(empty($foto)){
                $path_foto="assets/img/no_image.jpg";
            }else{
                $path_foto="assets/img/member/$foto";
            }
            //Format Tanggal
            $strtotime=strtotime($datetime);
            $tanggal_testimoni=date('d F Y H:i',$strtotime);
            echo '<li class="message-item">';
            echo '  <a href="index.php?Page=Testimoni&Sub=Detail&id='.$id_web_testimoni.'">';
            echo '      <img src="'.$path_foto.'" alt="" class="rounded-circle">';
            echo '      <div>';
            echo '          <h4>'.$NamaMember.'</h4>';
            echo '          <p><code class="text text-grayish">'.$shortTestimoni.'</code></p>';
            echo '          <p class="text text-dark">'.$tanggal_testimoni.'</p>';
            echo '      </div>';
            echo '  </a>';
            echo '</li>';
        }
    }
?>