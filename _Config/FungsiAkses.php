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
        $id_access_group="";
        $access_group="None";
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
        $id_access_group=$DataAccessSession['id_access_group'];

        //Buka Akses Group
        $QryAccessGroupSession = mysqli_query($Conn,"SELECT group_name FROM access_group WHERE id_access_group='$id_access_group'")or die(mysqli_error($Conn));
        $DataAccessGroupSession = mysqli_fetch_array($QryAccessGroupSession);
        if(empty($DataAccessGroupSession['group_name'])){
            $access_group="None";
        }else{
            $access_group=$DataAccessGroupSession['group_name'];
        }
    }
?>