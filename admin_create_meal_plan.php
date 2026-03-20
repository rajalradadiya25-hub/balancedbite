<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$errors = [];
$month = $_POST['month'] ?? '';
$week_number = $_POST['week_number'] ?? '';
$day_of_week = trim($_POST['day_of_week'] ?? '');
$meal_type = trim($_POST['meal_type'] ?? '');
$recipe_id = trim($_POST['recipe_id'] ?? '');
$image_url = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {

    // ✅ Validate Month (1 to 12)
    if (!filter_var($month, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 12]])) {
        $errors['month'] = "Month must be between 1 and 12.";
    }

    // ✅ Validate Week Number (1 to 5)
    if (!filter_var($week_number, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]])) {
        $errors['week_number'] = "Week number must be between 1 and 5.";
    }

    // ✅ Validate Day of Week
    $valid_days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    $day_of_week = ucfirst(strtolower($day_of_week));
    if (!in_array($day_of_week, $valid_days)) {
        $errors['day_of_week'] = "Enter a valid day of the week.";
    }

    // ✅ Validate Meal Type
    if (empty($meal_type)) {
        $errors['meal_type'] = "Meal type cannot be empty.";
    }

    // ✅ Validate Recipe ID
    if (!filter_var($recipe_id, FILTER_VALIDATE_INT)) {
        $errors['recipe_id'] = "Recipe ID must be a valid number.";
    }

    // ✅ Validate Image
    if (empty($_FILES['image']['name'])) {
        $errors['image'] = "An image is required.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $errors['image'] = "Only JPG, JPEG, and PNG images are allowed.";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $errors['image'] = "Image size must be under 2MB.";
        }
    }

    // ✅ If no errors, insert data
    if (empty($errors)) {
        $target_dir = "images/meal_plan/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;

            $query = "INSERT INTO meal_plan (month, week_number, day_of_week, meal_type, recipe_id, image_url)
                      VALUES (
                        '" . mysqli_real_escape_string($conn, $month) . "',
                        '" . mysqli_real_escape_string($conn, $week_number) . "',
                        '" . mysqli_real_escape_string($conn, $day_of_week) . "',
                        '" . mysqli_real_escape_string($conn, $meal_type) . "',
                        '" . mysqli_real_escape_string($conn, $recipe_id) . "',
                        '" . mysqli_real_escape_string($conn, $image_url) . "'
                      )";

            if (mysqli_query($conn, $query)) {
                header("Location: admin_meal_plan.php");
                exit;
            } else {
                $errors['database'] = "Failed to insert data into the database.";
            }
        } else {
            $errors['image'] = "Image upload failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create Meal Plan</title>
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
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 128, 0, 0.2);
            animation: slideIn 0.8s ease-in-out;
        }
        label {
            font-weight: bold;
            color: rgb(0, 128, 0);
        }
        .btn-success {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-success:hover {
            background: darkgreen;
            transform: scale(1.05);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <?php require 'component/adminnav.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Create Meal Plan</h1>
                    <form method="POST" enctype="multipart/form-data">

                        <label class="mt-3">Month:</label>
                        <input type="number" name="month" value="<?php echo htmlspecialchars($_POST['month'] ?? ''); ?>">
                        <p class="error"><?php echo $errors['month'] ?? ''; ?></p>

                        <label class="mt-3">Week Number:</label>
                        <input type="number" name="week_number" value="<?php echo htmlspecialchars($_POST['week_number'] ?? ''); ?>">
                        <p class="error"><?php echo $errors['week_number'] ?? ''; ?></p>

                        <label class="mt-3">Day of Week:</label>
                        <input type="text" name="day_of_week" value="<?php echo htmlspecialchars($_POST['day_of_week'] ?? ''); ?>">
                        <p class="error"><?php echo $errors['day_of_week'] ?? ''; ?></p>

                        <label class="mt-3">Meal Type:</label>
                        <input type="text" name="meal_type" value="<?php echo htmlspecialchars($_POST['meal_type'] ?? ''); ?>">
                        <p class="error"><?php echo $errors['meal_type'] ?? ''; ?></p>

                        <label class="mt-3">Recipe ID:</label>
                        <input type="number" name="recipe_id" value="<?php echo htmlspecialchars($_POST['recipe_id'] ?? ''); ?>">
                        <p class="error"><?php echo $errors['recipe_id'] ?? ''; ?></p>

                        <label class="mt-3">Image:</label>
                        <input type="file" name="image">
                        <p class="error"><?php echo $errors['image'] ?? ''; ?></p>

                        <button type="submit" name="create" class="btn-success mt-3">Create Meal Plan</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require 'component/Footer.php'; ?>
    </div>
</body>
</html>
