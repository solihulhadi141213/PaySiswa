<?php
    //Buka Akses Pengguna Berdasarkan SessionIdAccess
    $QryAccessSession = mysqli_query($Conn,"SELECT * FROM access WHERE id_access='$SessionIdAccess'")or die(mysqli_error($Conn));
    $DataAccessSession = mysqli_fetch_array($QryAccessSession);
    if(empty($DataAccessSession['id_access'])){
        $access_name="";
        $access_email="";
        $access_contact="";
        $access_foto="";
        $access_client="";
        $access_group="";
    }else{
        $access_name=$DataAccessSession['access_name'];
        $access_email=$DataAccessSession['access_email'];
        $access_contact=$DataAccessSession['access_contact'];
        if(empty($DataAccessSession['access_foto'])){
            $access_foto="No-Image.png";
        }else{
            $access_foto=$DataAccessSession['access_foto'];
        }
        $access_client=$DataAccessSession['access_client'];
        $access_group=$DataAccessSession['access_group'];
    }
?>