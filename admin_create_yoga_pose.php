<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header("location:admin_login.php");
    exit;
}

$errors = [];
$name = $_POST['name'] ?: '';
$difficulty = isset($_POST['difficulty']) ? trim($_POST['difficulty']) : '';
$description = trim($_POST['description'] ?: '');
$image_url = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    // Validate Meal
    if (empty($name)) {
        $errors['name'] = "Name cannot be empty.";
    }
    if (empty($description)) {
        $errors['description'] = "Description field cannot be empty.";
    }
    if (empty($difficulty)) {
        $errors['difficulty'] = "Difficulty field cannot be empty.";
    }
    // Validate Image Upload
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

    // If no errors, insert into database
    if (empty($errors)) {
        $target_dir = "images/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $image_name;
        } else {
            $errors['image'] = "Image upload failed.";
        }

        if (empty($errors)) {
            $query = "INSERT INTO `yoga_poses` (`name`, `image`, `description`, `difficulty`) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $image_url, $description, $difficulty);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("location:admin_yoga_session.php");
                exit;
            } else {
                $errors['database'] = "Failed to insert data into the database: " . mysqli_error($conn);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create Yoga Pose</title>
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
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
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
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Create Yoga Pose</h1>
                    <form method="POST" enctype="multipart/form-data">

                        <label class="mt-3">Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?: ''); ?>">
                        <p class="error"><?php echo $errors['name'] ?: ''; ?></p>

                        <label class="mt-3">Description:</label>
                        <textarea name="description" id="description"><?php echo htmlspecialchars($_POST['description'] ?: ''); ?></textarea>
                        <p class="error"><?php echo $errors['description'] ?: ''; ?></p>

                        <label class="mt-3">Difficulty:</label>
                        <input type="text" name="difficulty" value="<?php echo htmlspecialchars($_POST['difficulty'] ?: ''); ?>">
                        <small class="text-muted">Enter from this values('Beginner', 'Intermediate', 'Advanced')</small>
                        <p class="error"><?php echo $errors['difficulty'] ?: ''; ?></p>


                        <label class="mt-3">Image:</label>
                        <input type="file" name="image">
                        <p class="error"><?php echo $errors['image'] ?: ''; ?></p>

                        <button type="submit" name="create" class="btn-success mt-3">Create Yoga Pose</button>
                    </form>

                </div>
            </div>
        </div>
        <script>
            ClassicEditor.create(document.querySelector('#description'));
        </script>
        <?php require 'component/Footer.php'; ?>
    </div>
</body>

</html>