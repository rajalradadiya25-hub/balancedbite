<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the meal plan record to get the image file
    $query = "SELECT image FROM yoga_poses WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (!empty($row['image'])) {
        $imagePath = "images/" . $row['image']; // Set the full path
        
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete image file from server
        }
    }
    
    // Delete the meal plan record from the database
    $query = "DELETE FROM yoga_poses WHERE id='$id'";
    mysqli_query($conn, $query);
    
    header("location:admin_yoga_session.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
