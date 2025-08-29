<?php
    session_start();
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    if(empty($_SESSION["id_akses"])){
        if(empty($_SESSION["id_akses_anggota"])){
            session_destroy();   
            session_unset();
            header('Location:../../Login.php');
        }else{
            if(empty($_SESSION["login_token"])){
                session_destroy();   
                session_unset();
                header('Location:../../Login.php');
            }else{
                $SessionIdAkses=$_SESSION ["id_akses_anggota"];
                $SessionLoginToken=$_SESSION ["login_token"];
                $HapusAksesToken = mysqli_query($Conn, "DELETE FROM akses_login WHERE id_akses='$SessionIdAkses' AND token='$SessionLoginToken'") or die(mysqli_error($Conn));
                if($HapusAksesToken){
                    session_destroy();   
                    session_unset();
                    header('Location:../../Login.php');
                }else{
                    session_destroy();   
                    session_unset();
                    header('Location:../../Login.php');
                }
            }
        }
    }else{
        if(empty($_SESSION["login_token"])){
            session_destroy();   
            session_unset();
            header('Location:../../Login.php');
        }else{
            $SessionIdAkses=$_SESSION ["id_akses"];
            $SessionLoginToken=$_SESSION ["login_token"];
            $HapusAksesToken = mysqli_query($Conn, "DELETE FROM akses_login WHERE id_akses='$SessionIdAkses' AND token='$SessionLoginToken'") or die(mysqli_error($Conn));
            if($HapusAksesToken){
                session_destroy();   
                session_unset();
                header('Location:../../Login.php');
            }else{
                session_destroy();   
                session_unset();
                header('Location:../../Login.php');
            }
        }
    }
?>