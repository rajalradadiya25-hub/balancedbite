<?php
require('db/dbconnect.php');
session_start();

//if (!isset($_SESSION['admin_loggedin'])) {
   // header("Location: ../admin_login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header("location:admin_login.php");
    exit;
}

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM discount_coupons WHERE id=$id");
header("Location: admin_view_coupons.php");
exit;
?>
