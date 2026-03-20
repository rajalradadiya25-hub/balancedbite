<?php
session_start();
require('db/dbconnect.php');

// Authentication check
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Initialize variables to avoid "undefined variable" warnings
$total_users = 0;
$latestResult = false;
$recentResult = false;

// --- Total users count ---
$countQuery = "SELECT COUNT(*) AS total_users FROM users";
$countResult = mysqli_query($conn, $countQuery);
if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $total_users = isset($countRow['total_users']) ? $countRow['total_users'] : 0;
} else {
    // Debug: uncomment the line below while testing to see SQL error
    // echo "Count query error: " . mysqli_error($conn);
}

// --- Latest users (for the small box) ---
$latestQuery = "SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$latestResult = mysqli_query($conn, $latestQuery);
if ($latestResult === false) {
    // Debug: uncomment while testing
    // echo "Latest query error: " . mysqli_error($conn);
}

// --- Recent users for the table (you can change LIMIT if needed) ---
$recentQuery = "SELECT id, username, phno, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$recentResult = mysqli_query($conn, $recentQuery);
if ($recentResult === false) {
    // Debug: uncomment while testing
    // echo "Recent query error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | BalancedBite</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; }
    .sidebar {
      height: 100vh;
      width: 200px;
      background: #008000;
      position: fixed;
      left: 0;
      top: 0;
      padding: 20px;
      color: #fff;
    }
    .sidebar a { display: block; padding: 12px; color: white; text-decoration: none; margin: 10px 0; border-radius: 6px; }
    .sidebar a:hover { background: #006400; }
    .content { margin-left: 300px; padding: 20px; }
    .card-box { padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); background: #fff; }
  </style>
</head>
<body>
    <?php require('component/Adminnav.php'); ?>

  <div class="content">
    <h1 class="display-4 fw-semibold" style="padding-top: 2%; padding-left: 25%;">Welcome, Admin 👋</h1>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card-box text-center">
          <h4>Total Users</h4>
          <h2 style="color: green;"><?php echo (int)$total_users; ?></h2>
          <a href="admin_user_info.php" class="btn btn-outline-success">View Users</a>
        </div>
      </div>
      

      <div class="col-md-6">
          <div class="card-box text-center">
  <h4>Latest Users</h4>
  <?php
  // Count latest 5 users
  $latestCountQuery = "SELECT COUNT(*) AS count_latest FROM users ORDER BY created_at DESC LIMIT 7";
  $latestCountResult = mysqli_query($conn, $latestCountQuery);

  // MySQL ka COUNT() LIMIT ke sath sahi kaam nahi karta — isliye fallback query:
  $latestFixedQuery = "SELECT id FROM users ORDER BY created_at DESC LIMIT 4";
  $latestFixedResult = mysqli_query($conn, $latestFixedQuery);
  $latestCount = ($latestFixedResult) ? mysqli_num_rows($latestFixedResult) : 0;

  echo "<h2 style='color:green;'>$latestCount</h2>";
  ?>
  <a href="admin_latest_users.php" class="btn btn-outline-success mt-2">View Latest Users</a>
</div>

      </div>

    <h3>Recent Users</h3>
    <table class="table table-bordered">
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
      if ($recentResult && mysqli_num_rows($recentResult) > 0) {
          $no = 1;
          while ($row = mysqli_fetch_assoc($recentResult)) {
              $username = htmlspecialchars($row['username']);
              $phone = htmlspecialchars($row['phno']);
              $created = htmlspecialchars($row['created_at']);
              echo "<tr>
                      <td>{$no}</td>
                      <td>{$username}</td>
                      <td>{$phone}</td>
                      <td>{$created}</td>
                    </tr>";
              $no++;
          }
      } else {
          echo "<tr><td colspan='4' class='text-center'>No recent users found</td></tr>";
      }
      ?>
      </tbody>
    </table>

    <?php require('component/Footer.php'); ?>
  </div>
</body>
</html>
