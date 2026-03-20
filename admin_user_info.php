<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
  //  header("location:Login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_Login.php");
    exit;}
$id = $_SESSION['id'];
$usersData = "";
$serialNumber = 1;

$usersQuery = "SELECT *FROM user_info";
$usersResult = mysqli_query($conn, $usersQuery);
while ($user = mysqli_fetch_assoc($usersResult)) {
    $createdDate = date('d-m-Y', strtotime($user['created_at']));
    $usersData .= "<tr><td>{$serialNumber}</td>
                        <td>{$user['username']}</td>
                        <td>{$user['goal']}</td>
                        <td>{$user['age']}</td>
                        <td>{$user['gender']}</td>
                        <td>{$user['height']}</td>
                        <td>{$user['weight']}</td>
                        <td>{$user['activity_level']}</td>
                        <td>{$user['calories']}</td>
                        <td>{$user['carbs']}</td>
                        <td>{$user['fats']}</td>
                        <td>{$user['proteins']}</td>
                        <td>{$user['last_yoga_date']}</td>
                        <td>{$user['yoga_streak']}</td></tr>";
    $serialNumber++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Info</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .table-responsive {
  width: 100%;
  overflow-x: auto;  /* Enable horizontal scroll */
  -webkit-overflow-scrolling: touch; /* For smooth scroll on touch devices */
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
            <div class="table-responsive">
            <h2>Users Information</h2>
            <?php if (!empty($usersData)) : ?>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Goal</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Height</th>
                        <th>Weight</th>
                        <th>Activitylevel</th>
                        <th>calories</th>
                        <th>Carbs</th>
                        <th>Fats</th>
                        <th>Proteins</th>
                        <th>Yoga Date</th>
                        <th>Yoga streak</th>
                    </tr>
                    
                    <?php
                    echo $usersData;
                    ?>

                </table>
                </div>
                 <div class="text-center mt-3">
      <a href="admin.php" class="btn btn-outline-success">⬅ Back to Dashboard</a>
    </div>
            <?php endif; ?>


        </div>
        <?php require('component/footer.php'); ?>
    </div>
</body>

</html>