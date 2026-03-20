<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
 //   header("location:Login.php");
   // exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_Login.php");
    exit;}
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';

if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $searchCondition = "WHERE r.name LIKE '%$searchEscaped%' OR mp.day_of_week LIKE '%$searchEscaped%'";
}


// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM recipes WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
    header("Location: admin_recipe.php");
    exit;
}

// Fetch total number of records
$totalQuery = "SELECT COUNT(*) AS total FROM recipes";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);
$serialNumber = $offset + 1; 

// Fetch paginated meal plans
$query = "SELECT * FROM recipes ORDER BY id LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Recipe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            transition: background 0.3s ease;
        }

        th {
            background: #28a745;
            color: white;
        }

        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }

        .create-btn,
        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .create-btn {
            background: #28a745;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .create-btn:hover {
            background: #218838;
        }

        .edit-btn {
            background: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            color: white;
            background: #28a745;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background: #218838;
        }
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
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <?php require('component/Adminnav.php'); ?>
    <div class="main-content">
        <div class="container">
            <h2>Recipes</h2>
            <form method="get">
                <a href="admin_create_recipe.php" class="create-btn mb-4"><i class="fas fa-plus"></i> Create New Recipes</a>
                    <div class="filter-container">
                        <input type="text" name="search" class="search-box" placeholder="Search Recipes " value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" background: #218838 >Search</button>
                    </div>
            </form>
            <table>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Calories</th>
                    <th>Ingredients</th>
                    <th>Directions</th>
                    <th>Meal</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $serialNumber; ?></td>
                        <td style="width: 100px;"><?php echo $row['name']; ?></td>
                        <td><?php echo $row['calories']; ?></td>
                        <td style="width: 300px;"><?php echo $row['ingredients']; ?></td>
                        <td style="width: 300px;"><?php echo $row['directions']; ?></td>
                        <td><?php echo $row['meal']; ?></td>
                        <td>
                            <a href="admin_edit_recipe.php?id=<?php echo $row['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="admin_delete_recipe.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php $serialNumber++;  endwhile; ?>
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