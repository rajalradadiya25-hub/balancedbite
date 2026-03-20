<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

// Fetch meal plan record
if ($_GET['id']) {
    $id = $_GET['id'];
    $query = "SELECT * FROM recipes WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $calories = $row['calories'];
        $carbs = $row['carbs'];
        $fats = $row['fats'];
        $proteins = $row['proteins'];
        //$cholesterol = $row['cholesterol'];
        //$sodium = $row['sodium'];
        //$vitamin_D = $row['vitamin D'];
        //$vitamin_C = $row['vitamin C'];
        //$glucose = $row['glucose'];
        $foodcat = $row['foodcat'];
        //$foodtype = $row['foodcat'];
        $prep_time = $row['prep_time'];
        $cook_time = $row['cook_time'];
        $ingredients = $row['ingredients'];
        $directions = $row['directions'];
        $meal = $row['meal'];
        $image_url = $row['image'];
    }

    // Handle edit submission
    if (isset($_POST['edit'])) {
        $name = $_POST['name'];
        $calories = $_POST['calories'];
        $carbs = $_POST['carbs'];
        $fats = $_POST['fats'];
        $proteins = $_POST['proteins'];
        //$cholesterol = $_POST['cholesterol'];
        //$sodium = $_POST['sodium'];
        //$vitamin_D = $_POST['vitamin_D'];
        //$vitamin_C = $_POST['vitamin_C'];
        //$glucose = $_POST['glucose'];
        $foodcat = $_POST['foodcat'];
        $prep_time = $_POST['prep_time'];
        $cook_time = $_POST['cook_time'];
       // $foodtype = $_POST['foodcat'];
        $ingredients = $_POST['ingredients'];
        $directions = $_POST['directions'];
        $meal = $_POST['meal'];

        $errors = [];

        // Validate required fields
        if (empty($name)) $errors['name'] = "Recipe name is required.";
        if (empty($calories)) {
            $errors['calories'] = "Calories field cannot be empty.";
        } elseif (!is_numeric($calories)) {
            $errors['calories'] = "Calories must be a number.";
        }
        if (empty($carbs)) {
            $errors['carbs'] = "Carbohydrates field cannot be empty.";
        } elseif (!is_numeric($carbs)) {
            $errors['carbs'] = "Carbohydrates must be a number.";
        }
        if (empty($fats)) {
            $errors['fats'] = "Fats field cannot be empty.";
        } elseif (!is_numeric($fats)) {
            $errors['fats'] = "Fats must be a number.";
        }
        if (empty($proteins)) {
            $errors['proteins'] = "Proteins field cannot be empty.";
        } elseif (!is_numeric($proteins)) {
            $errors['proteins'] = "Proteins must be a number.";
        }
        //if (empty($cholesterol)) {
            //$errors['cholesterol'] = "Cholesterol field cannot be empty.";
       // }
        if (empty($prep_time)) {
            $errors['prep_time'] = "Preperation time field cannot be empty.";
        }
        if (empty($cook_time)) {
            $errors['cook_time'] = "Cooking time field cannot be empty.";
        }
        if (empty($meal)) {
            $errors['meal'] = "Meal field cannot be empty.";
        }
        if (empty($foodcat)) {
            $errors['foodcat'] = "Food type field cannot be empty.";
        }
        
        

        if (empty($ingredients)) $errors['ingredients'] = "Ingredients cannot be empty.";
        if (empty($directions)) $errors['directions'] = "Directions cannot be empty.";



        // Validate Image Upload
        if ($_FILES['image']['name']) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $errors['image'] = "Only JPG, JPEG, and PNG images are allowed.";
            }
        }

        // If no errors, update the database
        if (empty($errors)) {
            // Handle image upload and delete old image
            if ($_FILES['image']['name']) {
                $target_dir = "images/recipe/";
                $new_image_name = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $new_image_name;

                // Ensure old image is deleted only if it exists
                if (!empty($image_url) && file_exists($image_url)) {
                    unlink($image_url);
                }

                // Move the new uploaded file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = $target_file;
                } else {
                    $errors['image'] = "Failed to upload new image.";
                }
            }


            $sql = "UPDATE recipes SET 
            name = '$name', 
            calories = $calories, 
            carbs = $carbs, 
            fats = $fats, 
            proteins = $proteins, 
            
            foodcat = '$foodcat', 
            prep_time = '$prep_time',
            cook_time = '$cook_time',
            ingredients = '$ingredients', 
            directions = '$directions', 
            image = '$image_url', 
            meal = '$meal'
        WHERE id = '$id'";
            mysqli_query($conn, $sql);
            header("location:admin_recipe.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin - Edit Recipes</title>
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

        .image-preview {
            width: 100%;
            height: 400px;
            border: 2px dashed rgb(0, 128, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-radius: 10px;
            overflow: hidden;
            background: #fafafa;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        label {
            font-weight: bold;
            color: rgb(0, 128, 0);
        }

        .btn-warning {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-warning:hover {
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
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Edit Recipes</h1>

                    <form method="POST" enctype="multipart/form-data">
                        <label class="mt-3">Recipe Image:</label>
                        <div class="image-preview" id="imagePreview">
                            <?php if (!empty($image_url)) { ?>
                                <img src="images/recipe/<?php echo $image_url; ?>" id="previewImg">
                            <?php } else { ?>
                                <p>No Image Selected</p>
                            <?php } ?>
                        </div>
                        <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage(event)">
                        <p class="error"><?php echo $errors['image'] ?? ''; ?></p>

                        <label class="mt-3">Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        <p class="error"><?php echo $errors['name'] ?? ''; ?></p>

                        <label class="mt-3">Calories:</label>
                        <input type="number" name="calories" value="<?php echo htmlspecialchars($calories); ?>">
                        <p class="error"><?php echo $errors['calories'] ?? ''; ?></p>

                        <label class="mt-3">Carbohydrates:</label>
                        <input type="text" name="carbs" value="<?php echo htmlspecialchars($carbs); ?>">
                        <p class="error"><?php echo $errors['carbs'] ?? ''; ?></p>

                        <label class="mt-3">Fats:</label>
                        <input type="text" name="fats" value="<?php echo htmlspecialchars($fats); ?>">
                        <p class="error"><?php echo $errors['fats'] ?? ''; ?></p>

                        <label class="mt-3">Proteins:</label>
                        <input type="text" name="proteins" value="<?php echo htmlspecialchars($proteins); ?>">
                        <p class="error"><?php echo $errors['proteins'] ?? ''; ?></p>

                        

                        <label class="mt-3">Food Type:</label>
                        <input type="text" name="foodcat" value="<?php echo htmlspecialchars($foodcat); ?>">
                        <p class="error"><?php echo $errors['foodcat'] ?? ''; ?></p>

                        <label class="mt-3">Prep Time:</label>
                        <input type="text" name="prep_time" value="<?= htmlspecialchars($_POST['prep_time'] ?? '') ?>">
                        <p class="error"><?= $errors['prep_time'] ?? '' ?></p>

                        <label class="mt-3">Cook Time:</label>
                        <input type="text" name="cook_time" value="<?= htmlspecialchars($_POST['cook_time'] ?? '') ?>">
                        <p class="error"><?= $errors['cook_time'] ?? '' ?></p>

                        <label class="mt-3">Meal:</label>
                        <input type="text" name="meal" value="<?php echo htmlspecialchars($meal); ?>">
                        <p class="error"><?php echo $errors['meal'] ?? ''; ?></p>

                        <label class="mt-3">Ingredients:</label>
                        <textarea name="ingredients" id="ingredients"><?php echo htmlspecialchars($ingredients); ?></textarea>
                        <p class="error"><?php echo $errors['ingredients'] ?? ''; ?></p>

                        <label class="mt-3">Directions:</label>
                        <textarea name="directions" id="directions"><?php echo htmlspecialchars($directions); ?></textarea>
                        <p class="error"><?php echo $errors['directions'] ?? ''; ?></p>

                        <button type="submit" name="edit" class="btn-warning">Save Changes</button>
                    </form>

                </div>
            </div>
        </div>
        <?php require 'component/Footer.php'; ?>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.innerHTML = '<img src="' + reader.result + '" id="previewImg">';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        ClassicEditor.create(document.querySelector('#ingredients'));
        ClassicEditor.create(document.querySelector('#directions'));
    </script>
</body>

</html>