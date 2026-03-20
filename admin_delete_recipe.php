<?php
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

    // Step 1: Delete all related meal_plan entries first
    $delete_mealplan = "DELETE FROM meal_plan WHERE recipe_id = '$id'";
    mysqli_query($conn, $delete_mealplan);

    // Step 2: Fetch image path before deleting recipe
    $img_query = mysqli_query($conn, "SELECT image FROM recipes WHERE id='$id'");
    $row = mysqli_fetch_assoc($img_query);
    if (!empty($row['image']) && file_exists($row['image'])) {
        unlink($row['image']); // delete image from folder
    }

    // Step 3: Delete recipe itself
    $delete_recipe = "DELETE FROM recipes WHERE id = '$id'";
    mysqli_query($conn, $delete_recipe);

    // Step 4: Redirect back to admin recipe page
    header("location:admin_recipe.php");
    exit;
} else {
    header("location:admin_recipe.php");
    exit;
}
?>
