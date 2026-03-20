<!--<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require('db/dbconnect.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
$user_id = $_SESSION['id'];

// Fetch user data
$query = "SELECT goal FROM user_info WHERE uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_goal = $user['goal'] ?: ''; // Default empty if no goal is set

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['goal'])) {
    $goal = trim($_POST['goal']);

    // Update the user info
    $update_query = "UPDATE user_info SET goal = ? WHERE uid = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $goal, $user_id);

    if ($stmt->execute()) {
        header('Location: planner.php');
        exit;
    } else {
        echo "<script>alert('Update failed. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>weight and goal</title>
    <?php require('component/Designlinks.php'); ?>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .container {
            text-align: center;
        }

        .cards {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .card {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card:hover,
        .card.selected {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 128, 0, 0.2);
            border: 2px solid rgb(0, 128, 0);
        }

        .card img {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        .card input {
            display: none;
        }

        .btn1 {
            background-color: rgb(0, 128, 0);
            padding: 10px 50px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn1:hover {
            background-color: rgb(0, 100, 0);
            transform: scale(1.05);
        }
    </style>
</head>

<body onload="highlightSelectedGoal()">
    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="container" style="margin-bottom: 150px; margin-top:150px;">
            <h2 style="color: #006600; font-weight:bold;">Select Your Goal</h2>
            <form action="" method="POST">
                <div class="cards">
                    <label class="card" onclick="selectCard(this)" data-value="maintain weight">
                        <img src="images/maintain weight.jpeg" alt="Maintain Weight">
                        <h4 style="font-weight: bold;">Maintain Weight</h4>
                        <input type="radio" name="goal" value="maintain weight" <?= ($current_goal == 'maintain weight') ? 'checked' : '' ?>>
                    </label>
                    <label class="card" onclick="selectCard(this)" data-value="build muscle">
                        <img src="images/build muscle.png" alt="Build Muscle">
                        <h4 style="font-weight: bold;">Build Muscle</h4>
                        <input type="radio" name="goal" value="build muscle" <?= ($current_goal == 'build muscle') ? 'checked' : '' ?>>
                    </label>
                    <label class="card" onclick="selectCard(this)" data-value="lose fat">
                        <img src="images/losefat.jpeg" alt="Lose Fat">
                        <h4 style="font-weight: bold;">Lose Fat</h4>
                        <input type="radio" name="goal" value="lose fat" <?= ($current_goal == 'lose fat') ? 'checked' : '' ?>>
                    </label>
                </div>
                <button class="btn1" type="submit"><i class="fa-regular fa-floppy-disk"></i> Save</button>
            </form>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <script>
        function selectCard(card) {
            document.querySelectorAll('.card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            card.querySelector('input').checked = true;
        }

        function highlightSelectedGoal() {
            let selectedGoal = "<?= $current_goal ?>";
            document.querySelectorAll('.card').forEach(card => {
                if (card.dataset.value === selectedGoal) {
                    card.classList.add('selected');
                }
            });
        }
    </script>
</body>

</html>-->