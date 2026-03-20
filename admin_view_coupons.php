<?php
require('db/dbconnect.php');
session_start();

//if (!isset($_SESSION['admin_loggedin'])) {
   // header("Location: ../admin_login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM discount_coupons WHERE code LIKE '%$search%' OR description LIKE '%$search%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Discount Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
.main-content { margin-left: 280px; padding: 20px; width: calc(100% - 280px); flex: 1; transition: 0.3s; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); animation: fadeIn 0.5s ease-in-out; }
th, td { padding: 15px; border-bottom: 1px solid #ddd; text-align: left; transition: background 0.3s ease; }
th { background: #28a745; color: white; }
tr:hover { background-color: rgba(76, 175, 80, 0.1); }
.create-btn, .edit-btn, .delete-btn { display: inline-block; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: 0.3s; }
.create-btn { background: #28a745; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
.create-btn:hover { background: #218838; }
.edit-btn { background: #007bff; color: white; }
.edit-btn:hover { background: #0056b3; }
.delete-btn { background: #dc3545; color: white; }
.delete-btn:hover { background: #c82333; }
.search-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 60%;}
            .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
</style>
</head>
<body>
    <?php require('component/Adminnav.php'); ?>
<div class="main-content">
<div class="container">
<h2>Discount Management</h2>

<form method="get">
<a href="admin_add_coupon.php" class="create-btn mb-4"><i class="fas fa-plus"></i> Add Coupon</a>
<div class="filter-container">
                <input type="text" name="search" class="search-box" placeholder="Search coupon" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" background: #218838 >Search</button>
            </div>
</form>

<table border="1" cellpadding="10">
<tr>
<th>ID</th><th>Coupon Code</th><th>Description</th><th>Discount (%)</th>
<th>Expired Date</th><th>Status</th><th>Created At</th><th>Actions</th>
</tr>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['code']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['discount_percent'] ?>%</td>
            <td><?= $row['expiry_date'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <form action="admin_edit_coupon.php" method="GET" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="edit" class="edit-btn" style="border:none; cursor:pointer;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </form>
                <a href="admin_delete_coupon.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="9" align="center">No coupons found</td></tr>
<?php endif; ?>
</table>
</div>
<?php require 'component/Footer.php'; ?>
</div>
</body>
</html>
