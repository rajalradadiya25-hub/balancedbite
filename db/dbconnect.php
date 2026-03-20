<?php
   
    $server="localhost";
    $username="root";
    $password="";
    $dbname = 'balancedbite';

    
    $conn=mysqli_connect($server,$username,$password);
    if(!$conn)
    {
        die("Error".mysqli_connect_error());
    }  
    else
    {
        $createdb="CREATE DATABASE IF NOT EXISTS `$dbname`";
        mysqli_query($conn,$createdb);


        $usedb="USE `$dbname`";
        mysqli_query($conn,$usedb);
    }
?>