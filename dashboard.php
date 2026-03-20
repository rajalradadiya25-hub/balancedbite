<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();


// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
    exit;
}

$id = $_SESSION['id'];
$usernameget = ""; // ✅ Always initialize with empty string

// Fetch username from session id
$sql = "SELECT username FROM `$dbname`.`users` WHERE id='$id' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $usernameget = $row['username'] ?? "";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard page</title>
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s ease;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-title {
            font-size: 1.5em;
            text-align: center;
            padding-top: 60px;
        }

        .card-description {
            font-size: 1em;
            color: #666;
        }

        .bg-opacity {
            position: relative;
            background-color: #000;
        }

        .bg-opacity::before {
            content: ' ';
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.6;
            background: url("images/dashboard-head.jpg") no-repeat center center;
            background-size: cover;
        }

        .content {
            position: relative;
            width: 100%;
            height: 350px;
        }

        .btn-success {
            background-color: #8BC34A;
            border-color: #8BC34A;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }
    </style>

</head>

<body>
    <?php require('component/usernav.php'); ?>
    <div class="main-content">
        <div class="container">
            <div class="bg-opacity">
                <div class="content text-light">
<h1 class="display-4 fw-semibold" style="padding-top: 10%; padding-left: 5%;">
    Hi, <?php echo htmlspecialchars($usernameget ?? "Guest"); ?>!
</h1>

                                        <p class="lead fw-semibold" style="padding-left: 5%;">What would you like to do?</p>
                </div>
            </div>
            
          <!-- Calculate BMI -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card shadow-lg mb-5 bg-white rounded" style="border: 1px solid black ;">
                        <a href="BMI.php" class="card-link">
                            <img src="images/dashboard-card.jpg" class="card-img" alt="..." height="200">
                            <div class="card-img-overlay">
                                <h5 class="card-title fw-bold" style="color: rgb(0, 128, 0);">
                                <i class="fa-solid fa-calculator"></i><br>Calculate My BMI
                                </h5>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Make My Diet Plan -->
                <div class="col-md-4">
                    <div class="card shadow-lg mb-5 bg-white rounded" style="border: 1px solid black ;">
                        <a href="make_diet_plan.php" class="card-link">
                            <img src="images/dashboard-card.jpg" class="card-img" alt="..." height="200">
                            <div class="card-img-overlay">
                                 <h5 class="card-title fw-bold" style="color: rgb(0, 128, 0);">
                                    <i class="fa-regular fa-calendar"></i><br>Make My Diet Plan
                                </h5>
                            </div>
                        </a>
                    </div>
                </div>

        <!-- View My Diet Plan -->
                <div class="col-md-4">
                    <div class="card shadow-lg mb-5 bg-white rounded" style="border: 1px solid black ;">
                        <a href="view_diet_plan.php" class="card-link">
                            <img src="images/dashboard-card.jpg" class="card-img" alt="..." height="200">
                            <div class="card-img-overlay">
                                <h5 class="card-title fw-bold" style="color: rgb(0, 128, 0);">
                                    <i class="fa-solid fa-calendar-days"></i><br>View My Diet Plan
                                </h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php require('component/Footer.php'); ?>
    </div>
</body>

</html>