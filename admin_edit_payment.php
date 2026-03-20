<?php
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['admin_loggedin'])) { header("Location: ../admin_login.php"); exit; }
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM payments WHERE id=$id");
$payment = mysqli_fetch_assoc($res);

if (!$payment) { die("Payment not found!"); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['payment_status'];
    $method = $_POST['payment_method'];
    $query = "UPDATE payments SET payment_status='$status', payment_method='$method' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: admin_view_payments.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit payment</title>
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
            max-width: 650px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,128,0,0.2);
            animation: slideIn 0.8s ease-in-out;
        }
        .image-preview {
            width: 100%;
            height: 300px;
            border: 2px dashed rgb(0, 128, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-radius: 10px;
            overflow: hidden;
            background: #fafafa;
        }
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        label {
            font-weight: bold;
            color: rgb(0,128,0);
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-warning {
            width: 100%;
            padding: 10px;
            background: rgb(0,128,0);
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-warning:hover {
            background: darkgreen;
            transform: scale(1.03);
        }
        @keyframes slideIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
<?php require 'component/adminnav.php'; ?>
<div class="main-content">
    <div class="container">
            <div class="col-8">
                <h1 style="text-align:center;color:rgb(0,128,0);margin-bottom:20px;">Edit Payment <?= $payment['id'] ?></h1>

<form method="post">
    <label>Payment Method:</label>
    <input type="text" name="payment_method" value="<?= htmlspecialchars($payment['payment_method']) ?>"><br><br>

    <label>Status:</label>
    <select name="payment_status">
        <option value="pending" <?= ($payment['payment_status']=='pending'?'selected':'') ?>>Pending</option>
        <option value="success" <?= ($payment['payment_status']=='success'?'selected':'') ?>>Success</option>
        <option value="failed" <?= ($payment['payment_status']=='failed'?'selected':'') ?>>Failed</option>
    </select><br><br>

    <button type="submit" name="edit" class="btn-warning">Save Changes</button>
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
