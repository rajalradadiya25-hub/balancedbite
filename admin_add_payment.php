<?php
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['admin_loggedin'])) { header("Location: ../admin_login.php"); exit; }
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $discount = $_POST['discount'];
    $final = $_POST['final_amount'];
    $status = $_POST['payment_status'];
    $method = $_POST['payment_method'];
    $txn = $_POST['transaction_id'];

    $stmt = $conn->prepare("INSERT INTO payments (user_id, amount, discount, final_amount, payment_status, payment_method, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idddsss", $user_id, $amount, $discount, $final, $status, $method, $txn);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_view_payments.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Payment</title>
    <?php require 'component/Designlinks.php'; ?>
    <link rel="stylesheet" href="css/general.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
            transition: 0.3s;
        }
        .col-8 {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 128, 0, 0.2);
            animation: slideIn 0.8s ease-in-out;
        }
        label {
            font-weight: bold;
            color: rgb(0, 128, 0);
        }
        .btn-success {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-success:hover {
            background: darkgreen;
            transform: scale(1.05);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php require 'component/adminnav.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="col-8">
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Add Payment</h1>

<form method="post">
User ID: <input type="number" name="user_id" required><br>
Amount: <input type="number" step="0.01" name="amount" required><br>
Discount: <input type="number" step="0.01" name="discount" value="0"><br>
Final Amount: <input type="number" step="0.01" name="final_amount" required><br>
Status: <select name="payment_status"><option>pending</option><option>success</option><option>failed</option></select><br>
Method: <input type="text" name="payment_method"><br>
Txn ID: <input type="text" name="transaction_id"><br>
<button type="submit" name="create" class="btn-success mt-3">Add Payment</button>
</form>
<div class="text-center mt-3">
      <a href="admin_view_payments.php" class="btn btn-outline-success">⬅ Back </a>
    </div>
        </div>
    </div>
<?php require 'component/Footer.php'; ?>
    </div>
</body>
</html>
