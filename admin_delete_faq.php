<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

if($_GET['delete']){
    $id = $_GET['delete'];
    // Delete the meal plan record
    $query = "DELETE FROM faqs WHERE id='$id'";
    mysqli_query($conn, $query);
    
    header("location:admin_faq.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
