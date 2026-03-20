<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

// Initialize variables
$id = $month = $week_number = $day_of_week = $meal_type = "";
$calories = $carbohydrates = $fat = $protein = $ingredients = $instructions = $image_url = "";

// Fetch meal plan + recipe data
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $query = "
        SELECT mp.*, r.name AS recipe_name, r.calories, r.carbs, r.fats, r.proteins, 
               r.image, r.ingredients, r.directions
        FROM meal_plan mp
        LEFT JOIN recipes r ON mp.recipe_id = r.id
        WHERE mp.id = '$id'
    ";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $month = $row['month'] ?? '';
        $week_number = $row['week_number'] ?? '';
        $day_of_week = $row['day_of_week'] ?? '';
        $meal_type = $row['meal_type'] ?? '';
        $calories = $row['calories'] ?? '';
        $carbohydrates = $row['carbs'] ?? '';
        $fat = $row['fats'] ?? '';
        $protein = $row['proteins'] ?? '';
        $ingredients = $row['ingredients'] ?? '';
        $instructions = $row['directions'] ?? '';
        $image_url = $row['image'] ?? '';
    }
}

// Handle update
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $month = $_POST['month'];
    $week_number = $_POST['week_number'];
    $day_of_week = $_POST['day_of_week'];
    $meal_type = $_POST['meal_type'];
    $calories = $_POST['calories'];
    $carbohydrates = $_POST['carbohydrates'];
    $fat = $_POST['fat'];
    $protein = $_POST['protein'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $image_url = $_FILES['image']['name'];

    // Handle image upload
    if (!empty($image_url)) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($image_url);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image_url = $_POST['existing_image'];
    }

    // Update query for both tables
    $update_query = "
        UPDATE meal_plan mp
        LEFT JOIN recipes r ON mp.recipe_id = r.id
        SET 
            mp.month = '$month',
            mp.week_number = '$week_number',
            mp.day_of_week = '$day_of_week',
            mp.meal_type = '$meal_type',
            r.calories = '$calories',
            r.carbs = '$carbohydrates',
            r.fats = '$fat',
            r.proteins = '$protein',
            r.ingredients = '$ingredients',
            r.directions = '$instructions',
            r.image = '$image_url'
        WHERE mp.id = '$id'
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Meal Plan updated successfully!'); window.location='admin_meal_plan.php';</script>";
    } else {
        echo "<script>alert('Error updating meal plan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Meal Plan</title>
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
            max-width: 650px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,128,0,0.2);
            animation: slideIn 0.8s ease-in-out;
        }
        .image-preview {
            width: 100%;
            height: 300px;
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
            color: rgb(0,128,0);
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-warning {
            width: 100%;
            padding: 10px;
            background: rgb(0,128,0);
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-warning:hover {
            background: darkgreen;
            transform: scale(1.03);
        }
        @keyframes slideIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <?php require 'component/adminnav.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="col-8">
                <h1 style="text-align:center;color:rgb(0,128,0);margin-bottom:20px;">Edit Meal Plan</h1>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <label>Meal Image:</label>
                    <div class="image-preview" id="imagePreview">
                        <?php if (!empty($image_url)) { ?>
                                <img src="images/recipe/<?php echo $image_url; ?>" id="previewImg">
                            <?php } else { ?>
                                <p>No Image Selected</p>
                            <?php } ?>
                    </div>
                    <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage(event)">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($image_url); ?>">

                    <label>Month:</label>
                    <input type="text" name="month" value="<?php echo htmlspecialchars($month); ?>" required>

                    <label>Week Number:</label>
                    <input type="number" name="week_number" value="<?php echo htmlspecialchars($week_number); ?>" required>

                    <label>Day of Week:</label>
                    <select name="day_of_week" required>
                        <?php
                        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        foreach ($days as $day) {
                            echo "<option value='$day'" . ($day_of_week == $day ? ' selected' : '') . ">$day</option>";
                        }
                        ?>
                    </select>

                    <label>Meal Type:</label>
                    <select name="meal_type" required>
                        <?php
                        $meals = ['Breakfast','Lunch','Dinner','Snack'];
                        foreach ($meals as $meal) {
                            echo "<option value='$meal'" . ($meal_type == $meal ? ' selected' : '') . ">$meal</option>";
                        }
                        ?>
                    </select>

                    <label>Calories:</label>
                    <input type="number" name="calories" value="<?php echo htmlspecialchars($calories); ?>">

                    <label>Carbohydrates:</label>
                    <input type="number" name="carbohydrates" value="<?php echo htmlspecialchars($carbohydrates); ?>">

                    <label>Fat:</label>
                    <input type="number" name="fat" value="<?php echo htmlspecialchars($fat); ?>">

                    <label>Protein:</label>
                    <input type="number" name="protein" value="<?php echo htmlspecialchars($protein); ?>">

                    <label>Ingredients:</label>
                    <textarea name="ingredients" id="ingredients" rows="4"><?php echo htmlspecialchars($ingredients); ?></textarea>

                    <label>Instructions:</label>
                    <textarea name="instructions" id="instructions" rows="4"><?php echo htmlspecialchars($instructions); ?></textarea>

                    <button type="submit" name="edit" class="btn-warning">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <?php require 'component/Footer.php'; ?>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.innerHTML = '<img src="' + reader.result + '" id="previewImg">';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
        ClassicEditor.create(document.querySelector('#ingredients'));
        ClassicEditor.create(document.querySelector('#instructions'));
    </script>
</body>
</html>
