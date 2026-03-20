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

if($_GET['id']){
    $id = $_GET['id'];
    
    // Fetch the meal plan to delete image file
    $query = "SELECT image_url FROM meal_plan WHERE id='$id'";
    //$query = "SELECT r.image AS image_url FROM meal_plan m JOIN recipes r ON m.recipe_id = r.id WHERE m.id='$id'";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['image_url']) {
        unlink($row['image_url']); // Delete image file from server
    }
    
    // Delete the meal plan record
    $query = "DELETE FROM meal_plan WHERE id='$id'";
    mysqli_query($conn, $query);
    
    header("location:admin_meal_plan.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
