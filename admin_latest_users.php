<?php
session_start();
require('db/dbconnect.php');

// Check admin authentication
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch only 5 latest users
$query = "SELECT id, username, phno, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latest Users | BalancedBite</title>
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
    <h2 class="text-center">Latest Registered Users</h2>
    <table class="table table-bordered table-hover">
      <thead class="table-success">
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Phone Number</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>".htmlspecialchars($row['username'])."</td>
                        <td>".htmlspecialchars($row['phno'])."</td>
                        <td>".htmlspecialchars($row['created_at'])."</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No latest users found</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <div class="text-center mt-3">
      <a href="admin.php" class="btn btn-outline-success">⬅ Back to Dashboard</a>
    </div>
  </div>

  <?php require('component/Footer.php'); ?>
</body>
</html>
