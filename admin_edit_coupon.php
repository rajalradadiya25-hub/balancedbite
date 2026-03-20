<?php
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['admin_loggedin'])) { header("Location: ../admin_login.php"); exit; }
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header("location:admin_login.php");
    exit;
}
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM discount_coupons WHERE id=$id");
$coupon = mysqli_fetch_assoc($res);
if (!$coupon) { die("Coupon not found!"); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = strtoupper(trim($_POST['code']));
    $desc = $_POST['description'];
    $discount = $_POST['discount_percent'];
    //$min_amount = $_POST['min_amount'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $query = "UPDATE discount_coupons SET code='$code', description='$desc', discount_percent=$discount,  expiry_date='$expiry_date', status='$status' WHERE id=$id";
    mysqli_query($conn, $query);

    header("Location: admin_view_coupons.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Discount</title>
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
                <h1 style="text-align:center;color:rgb(0,128,0);margin-bottom:20px;">Edit Discount</h1>
<form method="post">
Coupon Code: <input type="text" name="code" value="<?= htmlspecialchars($coupon['code']) ?>" required><br><br>
Description: <input type="text" name="description" value="<?= htmlspecialchars($coupon['description']) ?>"><br><br>
Discount (%): <input type="number" name="discount_percent" min="1" max="100" value="<?= $coupon['discount_percent'] ?>" required><br><br>
Expried daye: <input type="date" name="expiry_date" value="<?= $coupon['expiry_date'] ?>" required><br><br>
Status:
<select name="status">
    <option value="active" <?= ($coupon['status']=='active'?'selected':'') ?>>Active</option>
    <option value="inactive" <?= ($coupon['status']=='inactive'?'selected':'') ?>>Inactive</option>
</select><br><br>
<button type="submit" name="edit" class="btn-warning">Save Changes</button>
</form>
<div class="text-center mt-3">
      <a href="admin_view_coupons.php" class="btn btn-outline-success">⬅ Back </a>
    </div>
</div>
</div>
<?php require 'component/Footer.php'; ?>
</div>
</body>
</html>
