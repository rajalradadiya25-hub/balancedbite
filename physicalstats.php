<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
$user_id = $_SESSION['id'];

// Fetch user data
$query = "SELECT * FROM user_info WHERE uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get values from form
    //$sex = trim($_POST['sex']);
    //$height = trim($_POST['height']);
    //$weight = trim($_POST['weight']);
    //$age = trim($_POST['age']);
    //$bodyfat = trim($_POST['bodyfat']);
    //$activity = trim($_POST['activity']);
    $sex = trim($_POST['sex'] ?? '');
$height = trim($_POST['height'] ?? '');
$weight = trim($_POST['weight'] ?? '');
$age = trim($_POST['age'] ?? '');
$bodyfat = trim($_POST['bodyfat'] ?? '');
$activity = trim($_POST['activity'] ?? '');



    // Validation
    if (empty($sex)) $errors['sex'] = "Please select your sex.";
    if (empty($height)) $errors['height'] = "Height is required.";
    if (empty($weight)) $errors['weight'] = "Weight is required.";
    if (empty($age)) $errors['age'] = "Age is required.";
    if (empty($bodyfat)) $errors['bodyfat'] = "Please select your body fat level.";
    if (empty($activity)) $errors['activity'] = "Please select your activity level.";

    if (empty($errors)) {
        // Update the user info
        $update_query = "UPDATE user_info SET gender = ?, height = ?, weight = ?, age = ?, bodyfat = ?, activity_level = ? WHERE uid = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("siiissi", $sex, $height, $weight, $age, $bodyfat, $activity, $user_id);

        if ($stmt->execute()) {
            header('location:planner.php');
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical stats selection</title>
    <?php require('component/Designlinks.php'); ?>
    <link rel="stylesheet" href="css/general.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .form-section {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .form-title {
            text-align: center;
            font-size: 26px;
            font-weight: 600;
            color: #006600;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(0, 128, 0, 0.3);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #006600;
        }

        .form-input {
            border: none;
            outline: none;
            font-size: 16px;
            padding: 5px 0;
            background: transparent;
        }

        .custom-radio-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .custom-radio {
            display: none;
        }

        .custom-radio-label {
            flex: 1;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            background: white;
            color: #006600;
            border: 2px solid #006600;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }

        .custom-radio:checked+.custom-radio-label {
            background: #006600;
            color: white;
        }

        .btnsave {
            display: block;
            width: 100%;
            background-color: #006600;
            border: none;
            padding: 8px;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btnsave:hover {
            background-color: rgb(0, 80, 0);
            transform: scale(1.05);
        }
    </style>
</head>

<body>
<?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="form-section">
            <h2 class="form-title">Personal Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Sex</label>
                    <div class="custom-radio-group">
                        <input type="radio" id="male" name="sex" class="custom-radio" value="male" <?= ($user['gender'] == 'male') ? 'checked' : '' ?>>
                        <label for="male" class="custom-radio-label">Male</label>
                        <input type="radio" id="female" name="sex" class="custom-radio" value="female" <?= ($user['gender'] == 'female') ? 'checked' : '' ?>>
                        <label for="female" class="custom-radio-label">Female</label>
                    </div>
                    <small class="error"> <?= $errors['sex'] ?? '' ?> </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Height</label>
                    <input type="number" name="height" value="<?= $user['height'] ?>">
                    <small class="error"> <?= $errors['height'] ?? '' ?> </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Weight</label>
                    <input type="number" name="weight" value="<?= $user['weight'] ?>">
                    <small class="error"> <?= $errors['weight'] ?? '' ?> </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" value="<?= $user['age'] ?>">
                    <small class="error"> <?= $errors['age'] ?? '' ?> </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Bodyfat</label>
                    <div class="custom-radio-group">
                        <input type="radio" id="low" name="bodyfat" class="custom-radio"  value="low" <?= ($user['bodyfat'] == 'low') ? 'checked' : '' ?>>
                        <label for="low" class="custom-radio-label">Low</label>
                        <input type="radio" id="medium" name="bodyfat" class="custom-radio"  value="medium" <?= ($user['bodyfat'] == 'medium') ? 'checked' : '' ?>>
                        <label for="medium" class="custom-radio-label">Medium</label>
                        <input type="radio" id="high" name="bodyfat" class="custom-radio"  value="high" <?= ($user['bodyfat'] == 'high') ? 'checked' : '' ?>>
                        <label for="high" class="custom-radio-label">High</label>
                    </div>
                    <small class="error"> <?= $errors['bodyfat'] ?? '' ?> </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Activity Level</label>
                    <select name="activity" class="form-input">
                        <option value="sedentary" <?= ($user['activity_level'] == 'sedentary') ? 'selected' : '' ?>>Sedentary</option>
                        <option value="lightly active" <?= ($user['activity_level'] == 'lightly active') ? 'selected' : '' ?>>Lightly active</option>
                        <option value="moderately active" <?= ($user['activity_level'] == 'moderately active') ? 'selected' : '' ?>>Moderately active</option>
                        <option value="active" <?= ($user['activity_level'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="very active" <?= ($user['activity_level'] == 'very active') ? 'selected' : '' ?>>Very active</option>
                    </select>
                    <!-- <small class="error"> <?= $errors['activity'] ?? '' ?> </small> -->
                </div>
                
                <button type="submit" class="btnsave"><i class="fa-regular fa-floppy-disk"></i> Save</button>
            </form>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
</body>

</html>

<style>
    .error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>