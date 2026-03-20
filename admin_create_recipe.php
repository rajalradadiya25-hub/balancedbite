<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header("location:admin_login.php");
    exit;
}

$errors = [];

$name = trim($_POST['name'] ?? '');
$calories = $_POST['calories'] ?? '';
$carbs = $_POST['carbs'] ?? '';
$fats = $_POST['fats'] ?? '';
$proteins = $_POST['proteins'] ?? '';
$foodtype = trim($_POST['foodcat'] ?? '');
$prep_time = trim($_POST['prep_time'] ?? '');
$cook_time = trim($_POST['cook_time'] ?? '');
$ingredients = trim($_POST['ingredients'] ?? '');
$directions = trim($_POST['directions'] ?? '');
$meal = trim($_POST['meal'] ?? '');
$image_url = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {

    // Validation
    if (empty($name)) {
        $errors['name'] = "Recipe name cannot be empty.";
    }

    foreach (['calories', 'carbs', 'fats', 'proteins'] as $field) {
        if (!empty($_POST[$field]) && (!filter_var($_POST[$field], FILTER_VALIDATE_FLOAT) || $_POST[$field] < 0)) {
            $errors[$field] = ucfirst($field) . " must be a positive number.";
        }
    }

    foreach (['foodcat','prep_time','cook_time', 'ingredients', 'directions', 'meal'] as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = ucfirst($field) . " cannot be empty.";
        }
    }

    // Image validation
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

    // If no validation errors
    if (empty($errors)) {
        $target_dir = "images/recipe/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $image_name;
        } else {
            $errors['image'] = "Image upload failed.";
        }

        if (empty($errors)) {
            $query = "INSERT INTO `recipes` 
                      (`name`, `calories`, `carbs`, `fats`, `proteins`, `foodcat`, `prep_time`,`cook_time`,`ingredients`, `directions`, `image`, `meal`)
                      VALUES 
                      ('$name', '$calories', '$carbs', '$fats', '$proteins', '$foodcat','$prep_time','$cook_time', '$ingredients', '$directions', '$image_url', '$meal')";
            
            if (mysqli_query($conn, $query)) {
                header("location:admin_recipe.php");
                exit;
            } else {
                $errors['database'] = "Database error: " . mysqli_error($conn);
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
    <title>Admin - Create Recipe</title>
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
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Create Recipes</h1>
                    <form method="POST" enctype="multipart/form-data">
                        <label class="mt-3">Name:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        <p class="error"><?= $errors['name'] ?? '' ?></p>

                        <label class="mt-3">Calories:</label>
                        <input type="number" name="calories" value="<?= htmlspecialchars($_POST['calories'] ?? '') ?>">
                        <p class="error"><?= $errors['calories'] ?? '' ?></p>

                        <label class="mt-3">Carbs:</label>
                        <input type="number" name="carbs" value="<?= htmlspecialchars($_POST['carbs'] ?? '') ?>">
                        <p class="error"><?= $errors['carbs'] ?? '' ?></p>

                        <label class="mt-3">Fats:</label>
                        <input type="number" name="fats" value="<?= htmlspecialchars($_POST['fats'] ?? '') ?>">
                        <p class="error"><?= $errors['fats'] ?? '' ?></p>

                        <label class="mt-3">Proteins:</label>
                        <input type="number" name="proteins" value="<?= htmlspecialchars($_POST['proteins'] ?? '') ?>">
                        <p class="error"><?= $errors['proteins'] ?? '' ?></p>

                        <label class="mt-3">Food Type:</label>
                        <input type="text" name="foodcat" value="<?= htmlspecialchars($_POST['foodcat'] ?? '') ?>">
                        <p class="error"><?= $errors['foodcat'] ?? '' ?></p>
                
                        <label class="mt-3">Prep Time:</label>
                        <input type="text" name="prep_time" value="<?= htmlspecialchars($_POST['prep_time'] ?? '') ?>">
                        <p class="error"><?= $errors['prep_time'] ?? '' ?></p>

                        <label class="mt-3">Cook Time:</label>
                        <input type="text" name="cook_time" value="<?= htmlspecialchars($_POST['cook_time'] ?? '') ?>">
                        <p class="error"><?= $errors['cook_time'] ?? '' ?></p>

                        <label class="mt-3" id="ingredients">Ingredients:</label>
                        <textarea name="ingredients"><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>
                        <p class="error"><?= $errors['ingredients'] ?? '' ?></p>

                        <label class="mt-3" id="directions">Directions:</label>
                        <textarea name="directions"><?= htmlspecialchars($_POST['directions'] ?? '') ?></textarea>
                        <p class="error"><?= $errors['directions'] ?? '' ?></p>

                        <label class="mt-3">Meal:</label>
                        <input type="text" name="meal" value="<?= htmlspecialchars($_POST['meal'] ?? '') ?>">
                        <p class="error"><?= $errors['meal'] ?? '' ?></p>

                        <label class="mt-3">Image:</label>
                        <input type="file" name="image">
                        <p class="error"><?= $errors['image'] ?? '' ?></p>

                        <button type="submit" name="create" class="btn-success mt-3">Create Recipe</button>

                        <?php if (!empty($errors['database'])): ?>
                        <p class="error"><?= $errors['database'] ?></p>
                        <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <script>
        ClassicEditor.create(document.querySelector('#ingredients'));
        ClassicEditor.create(document.querySelector('#directions'));
    </script>
    <?php require 'component/Footer.php'; ?>
</div>
```

</body>
</html>
