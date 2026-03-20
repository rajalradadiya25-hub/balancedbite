<?php
require('db/dbconnect.php');
session_start();

// Access check
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// DB connection check
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Handle Delete Request
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM faqs WHERE id = $delete_id";
    if (!mysqli_query($conn, $deleteQuery)) {
        die("Delete failed: " . mysqli_error($conn));
    }
    header("Location: admin_faq.php?page=$page");
    exit;
}

// Fetch total number of records
$totalQuery = "SELECT COUNT(*) AS total FROM faqs";
$totalResult = mysqli_query($conn, $totalQuery);
if (!$totalResult) {
    die("Count Query Failed: " . mysqli_error($conn));
}
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);
$serialNumber = $offset + 1;

// Fetch paginated faqs
$query = "SELECT * FROM faqs ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - FAQs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* === DESIGN KEHISAAB SE WAISA HI === */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; margin:0; padding:0; }
        .main-content { margin-left:280px; padding:20px; width: calc(100% - 280px); flex:1; transition:0.3s; }
        table { width:100%; border-collapse: collapse; background:white; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1); animation:fadeIn 0.5s ease-in-out; }
        th, td { padding:15px; border-bottom:1px solid #ddd; text-align:left; transition: background 0.3s ease; }
        th { background: #28a745; color:white; }
        tr:hover { background-color: rgba(76,175,80,0.1); }
        .create-btn, .edit-btn, .delete-btn { display:inline-block; padding:10px 15px; border-radius:5px; text-decoration:none; font-weight:bold; transition:0.3s; }
        .create-btn { background:#28a745; color:white; box-shadow:0 4px 10px rgba(0,0,0,0.2); }
        .create-btn:hover { background:#218838; }
        .action-buttons { display:flex; gap:10px; }
        .edit-btn { background:#007bff; color:white; }
        .edit-btn:hover { background:#0056b3; }
        .delete-btn { background:#dc3545; color:white; }
        .delete-btn:hover { background:#c82333; }
        .pagination { margin-top:20px; text-align:center; }
        .pagination a { display:inline-block; padding:10px 15px; margin:0 5px; color:white; background:#28a745; text-decoration:none; border-radius:5px; }
        .pagination a:hover { background:#218838; }
        .current-page { background:#218838; color:white; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>
    <?php require('component/Adminnav.php'); ?>
    <div class="main-content">
        <div class="container">
            <h2>FAQs</h2>
            <a href="admin_create_faq.php" class="create-btn mb-4"><i class="fas fa-plus"></i> Create New FAQ</a>
            <table>
                <tr>
                    <th>No</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>

                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $serialNumber; ?></td>
                            <td><?php echo $row['category']; ?></td>
<td><?php echo $row['question']; ?></td>
<td><?php echo $row['answer']; ?></td>
<td><?php echo date('d-m-Y', strtotime($row['created_at']));?></td>

                        
                            <td>
                                <div class="action-buttons">
                                    <a href="admin_edit_faq.php?id=<?php echo $row['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php $serialNumber++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No FAQs found.</td>
                    </tr>
                <?php endif; ?>

            </table>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">Prev</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'current-page' : ''; ?>"> <?php echo $i; ?> </a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>

        </div>
        <?php require('component/footer.php'); ?>
    </div>
</body>
</html>
