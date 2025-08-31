<?php
    if(empty($_GET['Page'])){
        $PageMenu="";
    }else{
        $PageMenu=$_GET['Page'];
    }
    if(empty($_GET['Sub'])){
        $SubMenu="";
    }else{
        $SubMenu=$_GET['Sub'];
    }
    if($access_group=="Admin"){
        include "_Partial/MenuAdmin.php";
    }else{
        include "_Partial/MenuClient.php";
    }
?>