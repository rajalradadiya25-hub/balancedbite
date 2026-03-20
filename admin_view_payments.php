<?php
require('db/dbconnect.php');
session_start();

// Access check
//if (!isset($_SESSION['admin_loggedin'])) {
    //header("Location: ../admin_login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "
    SELECT p.*, u.username 
    FROM payments p 
    JOIN user_info u ON p.user_id = u.id 
    WHERE u.username LIKE '%$search%' 
       OR p.transaction_id LIKE '%$search%' 
       OR p.payment_status LIKE '%$search%'
    ORDER BY p.created_at DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Payment Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
.main-content { margin-left: 280px; padding: 20px; width: calc(100% - 280px); flex: 1; transition: 0.3s; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); animation: fadeIn 0.5s ease-in-out; }
.table-responsive {
  width: 100%;
  overflow-x: auto;  /* Enable horizontal scroll */
  -webkit-overflow-scrolling: touch; /* For smooth scroll on touch devices */
}
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
        

@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
<body>
    <?php require('component/Adminnav.php'); ?>
<div class="main-content">
<div class="container">
    <div class="table-responsive">
<h2>Payments Management</h2>

<form method="get">
<a href="admin_add_payment.php" class="create-btn mb-4"><i class="fas fa-plus"></i> Add Payment</a>
<div class="filter-container">
                <input type="text" name="search" class="search-box" placeholder="Search user or transaction" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" background: #218838 >Search</button>
            </div>
</form>
<table border="1" cellpadding="10">
<tr>
<th>ID</th><th>User</th><th>Amount</th><th>Discount</th><th>Final</th>
<th>Status</th><th>Method</th><th>Txn ID</th><th>Date</th><th>Actions</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['username']) ?></td>
<td>₹<?= $row['amount'] ?></td>
<td>₹<?= $row['discount'] ?></td>
<td>₹<?= $row['final_amount'] ?></td>
<td><?= ucfirst($row['payment_status']) ?></td>
<td><?= htmlspecialchars($row['payment_method']) ?></td>
<td><?= htmlspecialchars($row['transaction_id']) ?></td>
<td><?= $row['created_at'] ?></td>

<td>
    <form action="admin_edit_payment.php" method="GET" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button type="submit" name="edit" class="edit-btn" style="border:none; cursor:pointer;">
            <i class="fas fa-edit"></i> Edit
        </button>
    </form>

    <a href="admin_delete_payment.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
<?php require('component/footer.php'); ?>
</div>
</body>
</html>
