<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}

$id = $_SESSION['id'];
$query = "SELECT * FROM user_info WHERE uid={$id}";
$result = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($result);
$selected_diet = $user['foodcat'] ?: 'anything'; // Default to "anything"

//if (isset($_POST['submit'])) {
    //$exclusion = $_POST['diet'];
    //$updatequery = "UPDATE user_info SET foodcat='$exclusion' WHERE uid='$id'";
    //mysqli_query($conn, $updatequery);
    //header('location:planner.php');
//}
if (isset($_POST['submit'])) {
    $exclusion = $_POST['diet'];
    $updatequery = "UPDATE user_info SET foodcat='$exclusion' WHERE uid='$id'";
    mysqli_query($conn, $updatequery);

    // Set default nutrition targets per diet type
    switch ($exclusion) {
        case 'keto':
            $_SESSION['daily_calories'] = 1800;
            $_SESSION['daily_carbs'] = 50;
            $_SESSION['daily_fats'] = 130;
            $_SESSION['daily_proteins'] = 90;
            break;
        case 'mediterranean':
            $_SESSION['daily_calories'] = 2200;
            $_SESSION['daily_carbs'] = 200;
            $_SESSION['daily_fats'] = 80;
            $_SESSION['daily_proteins'] = 100;
            break;
        case 'paleo':
            $_SESSION['daily_calories'] = 2000;
            $_SESSION['daily_carbs'] = 150;
            $_SESSION['daily_fats'] = 70;
            $_SESSION['daily_proteins'] = 110;
            break;
        case 'vegan':
            $_SESSION['daily_calories'] = 2100;
            $_SESSION['daily_carbs'] = 230;
            $_SESSION['daily_fats'] = 60;
            $_SESSION['daily_proteins'] = 80;
            break;
        default: // anything
            $_SESSION['daily_calories'] = 2300;
            $_SESSION['daily_carbs'] = 120;
            $_SESSION['daily_fats'] = 64;
            $_SESSION['daily_proteins'] = 38;
            break;
    }

    header('location:planner.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primary Diet Selection</title>
    <?php require('component/designlinks.php'); ?>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .diet-option {
            background: white;
            border: 2px solid #ddd;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 10px;
            position: relative;
        }

        .diet-option:hover {
            border-color: rgb(0, 128, 0);
            transform: scale(1.05);
        }

        .diet-option input {
            display: none;
        }

        .diet-option label {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .diet-option input:checked+label {
            background-color: #e6ffe6;
            border: 2px solid rgb(0, 128, 0);
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0, 128, 0, 0.5);
        }

        .update-section {
            background-color: rgb(0, 128, 0);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .btn-update {
            background-color: #006600;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            transition: 0.3s;
        }

        .btn-update:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="container mt-5">
            <form action="" method="post">
                <div class="card p-4 rounded" style="width: 70%; margin-left: 165px;">
                    <h2 class="text-center" style="color: rgb(0,128,0);">Primary Diet</h2>
                    <p class="text-muted text-center">We'll base your meals off this main diet type. Choose "Anything" to customize your own unique diet from scratch.</p>

                    <div class="list-group">
                        <div class="diet-option mb-2">
                            <input type="radio" name="diet" id="anything" value="anything" <?php echo ($selected_diet == 'anything') ? 'checked' : ''; ?>>
                            <label for="anything">🥖 <b>Anything</b> - Excludes: Nothing</label>
                        </div>
                        <div class="diet-option mb-2">
                            <input type="radio" name="diet" id="keto" value="keto" <?php echo ($selected_diet == 'keto') ? 'checked' : ''; ?>>
                            <label for="keto">🌾 <b>Keto</b> - Excludes: Legumes, Starchy Vegetables, High-carb Grains</label>
                        </div>
                        <div class="diet-option mb-2">
                            <input type="radio" name="diet" id="mediterranean" value="mediterranean" <?php echo ($selected_diet == 'mediterranean') ? 'checked' : ''; ?>>
                            <label for="mediterranean">🫒<b>Mediterranean</b> - Excludes: Red Meat, Fruit Juice, Starchy Vegetables</label>
                        </div>
                        <div class="diet-option mb-2">
                            <input type="radio" name="diet" id="paleo" value="paleo" <?php echo ($selected_diet == 'paleo') ? 'checked' : ''; ?>>
                            <label for="paleo">🥩 <b>Paleo</b> - Excludes: Dairy, Grains, Legumes, Soy, Starchy Vegetables</label>
                        </div>
                        <div class="diet-option mb-2">
                            <input type="radio" name="diet" id="vegan" value="vegan" <?php echo ($selected_diet == 'vegan') ? 'checked' : ''; ?>>
                            <label for="vegan">💚 <b>Vegan</b> - Excludes: Red Meat, Poultry, Fish, Shellfish, Dairy, Eggs, Mayo, Honey</label>
                        </div>
                    </div>

                    <div class="update-section mt-4">
                        <p>Your primary diet has been updated and will be used the next time you generate a plan. You may want to adjust your <a href="nutritiontarget.php" class="text-light">Nutrition Targets</a> to better fit your new diet.</p>
                        <button class="btn-update" name="submit">Update Nutrition Targets</button>
                    </div>
                </div>
            </form>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
</body>

</html>
