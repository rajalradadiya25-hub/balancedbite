<?php
session_start();
require('db/dbconnect.php');
error_reporting(0);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
    exit;
}

$id = $_SESSION['id'];
$query = "SELECT * FROM user_info WHERE uid={$id}";
$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $calories = trim($_POST['calories']);
    $carbs = trim($_POST['carbs']);
    $fats = trim($_POST['fats']);
    $proteins = trim($_POST['proteins']);

    $errors = [];

    // Validation
    if (!is_numeric($calories) || $calories <= 0) {
        $errors[] = "Calories must be a positive number.";
    }
    if (!is_numeric($carbs) || $carbs < 0) {
        $errors[] = "Carbs must be a non-negative number.";
    }
    if (!is_numeric($fats) || $fats < 0) {
        $errors[] = "Fats must be a non-negative number.";
    }
    if (!is_numeric($proteins) || $proteins < 0) {
        $errors[] = "Proteins must be a non-negative number.";
    }

    if (empty($errors)) {
        // ✅ Store in session (no database)
        $_SESSION['daily_calories'] = $calories;
        $_SESSION['daily_carbs'] = $carbs;
        $_SESSION['daily_fats'] = $fats;
        $_SESSION['daily_proteins'] = $proteins;

        echo "<script>alert('Nutrition targets saved successfully!');</script>";
        header("Location: planner.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Target Selection</title>
    <?php require('component/Designlinks.php'); ?>
    <link rel="stylesheet" href="css/general.css">

    <script>
        function validateForm() {
            let calories = document.forms["nutritionForm"]["calories"].value;
            let carbs = document.forms["nutritionForm"]["carbs"].value;
            let fats = document.forms["nutritionForm"]["fats"].value;
            let proteins = document.forms["nutritionForm"]["proteins"].value;

            if (calories == "" || isNaN(calories) || calories <= 0) {
                alert("Calories must be a positive number.");
                return false;
            }
            if (carbs == "" || isNaN(carbs) || carbs < 0) {
                alert("Carbs must be a non-negative number.");
                return false;
            }
            if (fats == "" || isNaN(fats) || fats < 0) {
                alert("Fats must be a non-negative number.");
                return false;
            }
            if (proteins == "" || isNaN(proteins) || proteins < 0) {
                alert("Proteins must be a non-negative number.");
                return false;
            }
            return true;
        }
    </script>

    <style>
        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .title {
            font-size: 60px;
            font-weight: bold;
            opacity: 0;
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        .title span {
            color: rgb(0, 128, 0);
        }

        .button1 {
            background-color: rgb(0, 128, 0);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 128, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .button1:hover {
            background-color: rgb(0, 100, 0);
            transform: scale(1.07);
            box-shadow: 0 6px 12px rgba(0, 128, 0, 0.3);
        }

        input {
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            transform: scale(1.05);
            border-color: rgb(0, 128, 0);
        }

        .container {
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
            margin: 40px auto;
            padding: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-light p-6">
    <?php require('component/Usernav.php'); ?>
    <div class="main-content p-5">
        <div class="container bg-white rounded shadow-lg overflow-hidden d-flex p-0">
            <div class="w-50 position-relative m-0">
                <img src="images/target-user.jpeg" alt="Salad" class="w-100 h-100 object-fit-cover">
                <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded shadow m-2">
                    <h2 class="fs-5 fw-bold title">Nutrition <span>Time</span></h2>
                </div>
            </div>

            <div class="w-50 p-4 d-flex flex-column">
                <h1 class="fs-4 fw-bold text-dark">Edit "Nutrition Targets"</h1>

                <?php if (!empty($errors)) {
                    echo '<div class="alert alert-danger"><ul>';
                    foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    echo '</ul></div>';
                } ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <form name="nutritionForm" action="" method="POST" onsubmit="return validateForm()">
                        <p class="mt-2">Calories</p>
                        <input type="number" class="w-50" min="1" name="calories" placeholder="Calories"
                            value="<?php echo htmlspecialchars($_SESSION['daily_calories'] ?? $row['calories']); ?>">

                        <h3 class="fs-5 fw-semibold mt-3">Target Macros</h3>
                        <p class="text-muted">Select the number of grams of each macronutrient you want in your diet.</p>

                        <div class="mt-3">
                            <label class="d-flex align-items-center justify-content-between p-2 border rounded">
                                <span>Carbs</span>
                                <input type="number" class="w-25" name="carbs" min="0" placeholder="In grams"
                                    value="<?php echo htmlspecialchars($_SESSION['daily_carbs'] ?? $row['carbs']); ?>">
                            </label>
                            <label class="d-flex align-items-center justify-content-between p-2 border rounded mt-2">
                                <span>Fats</span>
                                <input type="number" class="w-25" name="fats" min="0" placeholder="In grams"
                                    value="<?php echo htmlspecialchars($_SESSION['daily_fats'] ?? $row['fats']); ?>">
                            </label>
                            <label class="d-flex align-items-center justify-content-between p-2 border rounded mt-2">
                                <span>Proteins</span>
                                <input type="number" class="w-25" name="proteins" min="0" placeholder="In grams"
                                    value="<?php echo htmlspecialchars($_SESSION['daily_proteins'] ?? $row['proteins']); ?>">
                            </label>
                        </div>

                        <button type="submit" class="mt-4 button1" name="submit">
                            <i class="fa-regular fa-floppy-disk"></i> Save
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
</body>
</html>
