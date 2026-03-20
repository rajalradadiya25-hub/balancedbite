<?php
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['admin_loggedin'])) {
    //header("Location: ../admin_login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM payments WHERE id=$id");
header("Location: admin_view_payments.php");
exit;
?>
