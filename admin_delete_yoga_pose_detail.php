<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the meal plan record to get the image file
    $query = "SELECT id FROM yoga_pose_details WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    // Delete the meal plan record from the database
    $query = "DELETE FROM yoga_pose_details WHERE id='$id'";
    mysqli_query($conn, $query);
    
    header("location:admin_yoga_session.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
