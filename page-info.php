<?php
session_start();
error_reporting(0);
require('db/dbconnect.php');
$showError = false;
$showAlert = false;
$_SESSION['signup_info'] = false;

$username = $_SESSION['username'];
$query = "SELECT * FROM `$dbname`.`users` WHERE username = '$username'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
}

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS user_info (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        uid INT NOT NULL,
        goal VARCHAR(255) NOT NULL,
        gender VARCHAR(50),
        height DECIMAL(5,2),  -- Height in decimals
        weight DECIMAL(5,2),  -- Weight in decimals
        age INT,  -- Age in years
        bodyfat VARCHAR(50),
        activitylevel VARCHAR(255),
        calories INT DEFAULT 1134,
        carbs INT DEFAULT 12,
        fats INT DEFAULT 21,
        proteins INT DEFAULT 18,
        foodcat VARCHAR(255),
        FOREIGN KEY (uid) REFERENCES users(id)
    )";
mysqli_query($conn, $sql);

// Insert data into the table if username does not exist
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diet = $_POST["diet"];
    $goal = $_POST["goal"];
    $sex = $_POST["sex"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $age = $_POST["age"];
    $bodyfat = $_POST["bodyfat"];
    $activity = $_POST["activity"];
    $calories = !empty($_POST["calories"]) ? $_POST["calories"] : NULL;
    $carbs = !empty($_POST["carbs"]) ? $_POST["carbs"] : NULL;
    $fats = !empty($_POST["fats"]) ? $_POST["fats"] : NULL;
    $proteins = !empty($_POST["proteins"]) ? $_POST["proteins"] : NULL;

    $existSql = "SELECT * FROM `$dbname`.`user_info` WHERE username='$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showError = "Username already exists";
    } else {
        $sql = "INSERT INTO `$dbname`.`user_info` (username, uid, goal, gender, height, weight, age, bodyfat, activitylevel, calories, carbs, fats, proteins, foodcat)
                VALUES ('$username', '$id', '$goal', '$sex', '$height', '$weight', '$age', '$bodyfat', '$activity', 
                " . ($calories !== NULL ? "'$calories'" : "DEFAULT") . ", 
                " . ($carbs !== NULL ? "'$carbs'" : "DEFAULT") . ", 
                " . ($fats !== NULL ? "'$fats'" : "DEFAULT") . ", 
                " . ($proteins !== NULL ? "'$proteins'" : "DEFAULT") . ", 
                '$diet')";
        $_SESSION['signup_info'] = true;
        $result = mysqli_query($conn, $sql);
        if($_SESSION['loggedin'] == true)
        {
            header("location:dashboard.php");
        }
        else
        {
            $showAlert = true;
        }
    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup-personal-info page</title>

    <?php require('component/Designlinks.php'); ?>
    <!-- <link rel="stylesheet" href="css/signup-personal-info.css"> -->
    <style>
    .section {
        display: none;
    }

    .section.active {
        display: block;
    }

    .progress-step {
        cursor: pointer;
        display: inline-block;
        width: 25px;
        height: 25px;
        background-color: lightgray;
        border-radius: 50%;
        text-align: center;
        line-height: 25px;
        margin: 5px;
    }

    .progress-step.active {
        background-color: green;
        color: white;
    }

    .box {
        background: #ffffff;
        border-radius: 15px;
        box-shadow:
            0 4px 8px rgba(0, 0, 0, 0.2),
            /* Main shadow */
            0 6px 20px rgba(0, 0, 0, 0.19),
            /* Larger shadow */
            0 12px 12px rgba(0, 0, 0, 0.15);
        /* Softer shadow */
        opacity: 0;
        /* Initially invisible */
        transform: translateY(-50px);
        /* Start position */
        animation: appear 2s ease-in-out forwards;
        /* Animation */
        height: 800px;
    }

    @keyframes appear {
        to {
            opacity: 1;
            /* Fully visible */
            transform: translateY(0);
            /* Final position */
        }
    }

    .box:hover {
        transform: scale(1.05);
        /* Slightly enlarge on hover */
    }

    .form-check {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
    }

    .form-check.selected {
        background-color: rgba(16, 63, 44, 0.15);
        border-radius: 10px;
        transition: all 0.3s;
    }


    .form-check:hover,
    .form-check.selected {
        background: rgba(16, 63, 44, 0.15);
    }

    .form-check-label {
        width: 100%;
        /* padding: px; */
        border-radius: 10px;
    }

    .option {
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 10px;
    }

    .option:hover,
    .option.selected {
        background: rgba(16, 63, 44, 0.15);
    }

    .form-group label {
        flex: 1;
        font-weight: bold;
        text-align: left;
    }

    .radio-group,
    .bodyfat-group {
        flex: 2;
        /* Gives space for the inputs */
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        /* Aligns options to the right */
    }

    /* Style for labels to look like buttons */
    .radio-group label,
    .bodyfat-group label {
        padding: 8px 20px;
        cursor: pointer;
        border: 1px solid lightgray;
        border-radius: 8px;
        text-align: center;
        background-color: white;
        color: black;
        transition: 0.3s ease;
    }

    /* Hide default radio buttons */
    .radio-group input[type="radio"],
    .bodyfat-group input[type="radio"] {
        display: none;
    }

    /* Hover effect: Green background & white text */
    .radio-group label:hover,
    .bodyfat-group label:hover {
        background-color: rgb(0, 128, 0);
        color: white;
    }

    /* Selected (Checked) effect */
    .radio-group input[type="radio"]:checked+label,
    .bodyfat-group input[type="radio"]:checked+label {
        background-color: rgb(0, 128, 0);
        color: white;
        border: 1px solid rgb(0, 128, 0);
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .radio-label input {
        margin-right: 5px;
    }

    /* Style for labels to look like buttons */
    .radio-group label,
    .bodyfat-group label {
        padding: 8px 20px;
        cursor: pointer;
        border: 1px solid lightgray;
        border-radius: 8px;
        text-align: center;
        background-color: white;
        /* Default background */
        color: black;
        /* Default text color */
        transition: 0.3s ease;
    }

    /* Hides default radio button */
    .radio-group input[type="radio"],
    .bodyfat-group input[type="radio"] {
        display: none;
    }

    /* Hover effect: Green background & white text */
    .radio-group label:hover,
    .bodyfat-group label:hover {
        background-color: rgb(0, 128, 0);
        /* Green */
        color: white;
    }

    /* Selected (Checked) effect */
    .radio-group input[type="radio"]:checked+label,
    .bodyfat-group input[type="radio"]:checked+label {
        background-color: rgb(0, 128, 0);
        /* Green */
        color: white;
        border: 1px solid rgb(0, 128, 0);
    }

    .form-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* Pushes label left, input right */
        margin-bottom: 15px;
    }

    .range-container {
        width: 100%;
        display: flex;
        align-items: center;
        position: relative;
        padding-right: 120px;
        /* To ensure range doesn't overlap inputs */
    }

    .form-range {
        width: 100%;
        margin-right: 10px;
    }

    .input-group {
        position: absolute;
        right: 0;
        display: flex;
        gap: 5px;
    }

    .input-group input {
        width: 60px;
        text-align: center;
    }
    </style>
</head>

<body>
    <?php

    if ($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showError . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    //if account is create.
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your account is now created and You can <a href="login.php" class="text-decoration-none">login</a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    ?>
    <div class="container">
        <div class="container text-center">
            <div class="row m-5 box"
                style="background-image: url('images/signup.jpg'); background-repeat:no-repeat; background-size:cover;">
                <div class="col-md-2">
                </div>
                <div class="col-md-12">
                    <div class="container rounded mt-5 mb-5">
                        <div class="row fixed header">
                            <div class="col-md-7 offset-md-4 text-center">
                                <h1>
                                    <a href="index.php" class="text-decoration-none"
                                        style="color: rgb(0,128,0);">BalancedBite</a>
                                    <div class="progress-container mt-4">
                                        <div class="progress-step active" id="step1"
                                            onclick="goToSection('section1', 'step1')">1</div>
                                        <div class="progress-step" id="step2"
                                            onclick="goToSection('section2', 'step2')">2
                                        </div>
                                        <div class="progress-step" id="step3"
                                            onclick="goToSection('section3', 'step3')">3
                                        </div>
                                        <div class="progress-step" id="step4"
                                            onclick="goToSection('section4', 'step4')">4
                                        </div>
                                        <div class="progress-step" id="step5"
                                            onclick="goToSection('section5', 'step5')">5
                                        </div>
                                    </div>
                                </h1>
                                <hr>
                            </div>
                        </div>
                        <form action="" method="POST">
                            <!-- First Form Section -->
                            <section id="section1" class="section active">
                                <div class="row mt-2">
                                    <div class="col-md-7 offset-md-4">
                                        <div>
                                            <h2>What do you like to eat?</h2>
                                            <p>Choose from a pre-set diet. You can fine-tune the excluded foods later.
                                            </p>
                                            <form id="dietForm" method="POST" action="save_diet.php">
                                                <input type="hidden" name="selectedDiet" id="selectedDiet">
                                                <div class="form-check" onclick="selectDiet('Anything', this)">
                                                    <label class="form-check-label d-block">
                                                        🍳 <strong>Anything</strong> <br> <small>Excludes:
                                                            Nothing</small>
                                                    </label>
                                                </div>
                                                <div class="form-check" onclick="selectDiet('Keto', this)">
                                                    <label class="form-check-label d-block">
                                                        🥑 <strong>Keto</strong> <br> <small>Excludes: Legumes, Starchy
                                                            Vegetables, High-carb Grains</small>
                                                    </label>
                                                </div>
                                                <div class="form-check" onclick="selectDiet('Mediterranean', this)">
                                                    <label class="form-check-label d-block">
                                                        🫒 <strong>Mediterranean</strong> <br> <small>Excludes: Fruit
                                                            juice,
                                                            Starchy Vegetables</small>
                                                    </label>
                                                </div>
                                                <div class="form-check" onclick="selectDiet('Paleo', this)">
                                                    <label class="form-check-label d-block">
                                                        🥩 <strong>Paleo</strong> <br> <small>Excludes: Dairy, Grains,
                                                            Legumes, Soy, Starchy Vegetables</small>
                                                    </label>
                                                </div>
                                                <div class="form-check" onclick="selectDiet('Vegan', this)">
                                                    <label class="form-check-label d-block">
                                                        🥦 <strong>Vegan</strong> <br> <small>Excludes: Dairy, Mayo,
                                                            Honey</small>
                                                    </label>
                                                </div>
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light"
                                                style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button"
                                                onclick="goToNextSection('section1', 'section2')"><i
                                                    class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
                </section>
                <!-- Third Form Section -->
                <section id="section2" class="section">
                    <div class="row mt-2">
                        <div class="col-md-7 offset-md-4">
                            <div>
                                <h2>What is your goal?</h2>
                                <p>This information lets us suggest meals to help you reach your goal.</p>
                                <div class="options">
                                    <div class="option" onclick="selectGoal('Maintain weight', this)">
                                        🎚️ <strong>Maintain weight</strong>
                                    </div>
                                    <div class="option" onclick="selectGoal('Build muscle', this)">
                                        💪 <strong>Build muscle</strong>
                                    </div>
                                    <div class="option" onclick="selectGoal('Lose fat', this)">
                                        📉 <strong>Lose fat</strong>
                                    </div>
                                </div>
                                <div class="text-center mt-4 mb-5">
                                    <button class="btn text-light"
                                        style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button"
                                        onclick="goToNextSection('section2', 'section3')">
                                        <i class="fa-solid fa-greater-than px-2"></i>Continue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Four Form Section -->
                <section id="section3" class="section" style="padding: 20px;">
                    <div class="row mt-2">
                        <div class="col-md-7 offset-md-4">
                            <div>
                                <h2 class="text-center">Tell us about yourself</h2>
                                <p class="text-center">This information lets us estimate your nutrition requirements for
                                    each day.</p>

                                <!-- Form -->
                                <div class="form-container">

                                    <!-- Sex Selection -->
                                    <div class="form-group">
                                        <label>Sex</label>
                                        <div class="radio-group d-flex gap-3">
                                            <input type="radio" id="male" name="sex" value="male">
                                            <label for="male">Male</label>

                                            <input type="radio" id="female" name="sex" value="female">
                                            <label for="female">Female</label>
                                        </div>
                                    </div>

                                    <!-- Height -->
                                    <div class="form-group d-flex align-items-center mt-3">
                                        <label class="col-md-4">Height</label>
                                        <div class="col-md-8 d-flex gap-2">
                                            <input type="number" class="form-control" placeholder="ft"
                                                style="width: 80px;" />
                                            <input type="number" class="form-control" placeholder="in"
                                                style="width: 80px;" />
                                        </div>
                                    </div>

                                    <!-- Weight -->
                                    <div class="form-group d-flex align-items-center mt-3">
                                        <label class="col-md-4">Weight</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" placeholder="lbs" />
                                        </div>
                                    </div>

                                    <!-- Age -->
                                    <div class="form-group d-flex align-items-center mt-3">
                                        <label class="col-md-4">Age</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" placeholder="years" />
                                        </div>
                                    </div>

                                    <!-- Bodyfat -->
                                    <div class="form-group">
                                        <label>Bodyfat</label>
                                        <div class="bodyfat-group d-flex gap-3">
                                            <input type="radio" id="low" name="bodyfat" value="low">
                                            <label for="low">Low</label>

                                            <input type="radio" id="medium" name="bodyfat" value="medium">
                                            <label for="medium">Medium</label>

                                            <input type="radio" id="high" name="bodyfat" value="high">
                                            <label for="high">High</label>
                                        </div>
                                    </div>

                                    <!-- Activity Level -->
                                    <div class="form-group d-flex align-items-center mt-3">
                                        <label class="col-md-4">Activity Level</label>
                                        <div class="col-md-8">
                                            <select class="form-control">
                                                <option value="sedentary">Desk job, light exercise</option>
                                                <option value="moderate">Moderate activity</option>
                                                <option value="active">Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Continue Button -->
                                    <div class="text-center mt-4 mb-5">
                                        <button class="btn text-light"
                                            style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button"
                                            onclick="goToNextSection('section3', 'section4')">
                                            <i class="fa-solid fa-greater-than px-2"></i>Continue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Five Form Section -->




                <section id="section4" class="section">
                    <div class="row mt-2">
                        <div class="col-md-7 offset-md-4">
                            <div>
                                <h2>Set Nutrition Target</h2>
                                <p>Take a look at the daily nutrition targets we've estimated for you based on your
                                    profile.
                                    You can adjust the targets by clicking the edit button or do it later on your meal
                                    planner page.</p>

                                <!-- Carbs -->
                                <div class="form-group">
                                    <label class="form-label">Carbs</label>
                                    <div class="range-container">
                                        <input type="range" min="0" max="300" id="carbsRange" class="form-range"
                                            oninput="updateValues('carbs')">
                                        <div class="input-group">
                                            <input type="number" id="carbsFrom" placeholder="From"
                                                oninput="adjustRange('carbs')">
                                            <span>to</span>
                                            <input type="number" id="carbsTo" placeholder="To"
                                                oninput="adjustRange('carbs')">
                                        </div>
                                    </div>
                                </div>

                                <!-- Fats -->
                                <div class="form-group">
                                    <label class="form-label">Fats</label>
                                    <div class="range-container">
                                        <input type="range" min="0" max="300" id="fatsRange" class="form-range"
                                            style="accent-color: blue;" oninput="updateValues('fats')">
                                        <div class="input-group">
                                            <input type="number" id="fatsFrom" placeholder="From"
                                                oninput="adjustRange('fats')">
                                            <span>to</span>
                                            <input type="number" id="fatsTo" placeholder="To"
                                                oninput="adjustRange('fats')">
                                        </div>
                                    </div>
                                </div>

                                <!-- Proteins -->
                                <div class="form-group">
                                    <label class="form-label">Protein</label>
                                    <div class="range-container">
                                        <input type="range" min="0" max="300" id="proteinsRange" class="form-range"
                                            style="accent-color: purple;" oninput="updateValues('proteins')">
                                        <div class="input-group">
                                            <input type="number" id="proteinsFrom" placeholder="From"
                                                oninput="adjustRange('proteins')">
                                            <span>to</span>
                                            <input type="number" id="proteinsTo" placeholder="To"
                                                oninput="adjustRange('proteins')">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 mb-5">
                                    <button class="btn text-light"
                                        style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button"
                                        onclick="goToNextSection('section4', 'section5')">
                                        <i class="fa-solid fa-greater-than px-2"></i>Continue
                                    </button>
                                </div>

                                <script>
                                function updateValues(type) {
                                    const range = document.getElementById(type + 'Range');
                                    const fromInput = document.getElementById(type + 'From');
                                    const toInput = document.getElementById(type + 'To');

                                    let value = parseInt(range.value);
                                    fromInput.value = Math.max(0, value - 30);
                                    toInput.value = value;
                                }

                                function adjustRange(type) {
                                    const fromInput = document.getElementById(type + 'From');
                                    const toInput = document.getElementById(type + 'To');
                                    const range = document.getElementById(type + 'Range');

                                    let fromValue = parseInt(fromInput.value) || 0;
                                    let toValue = parseInt(toInput.value) || 0;

                                    if (fromValue > toValue) {
                                        toValue = fromValue;
                                        toInput.value = toValue;
                                    }

                                    range.value = toValue;
                                }
                                </script>
                            </div>
                        </div>
                </section>



                <!-- sixth Form Section -->
                <div class="row mt-2 section" id="section5">
                    <div class="col-md-7 offset-md-4">
                        <div>
                            <h1>Welcome!</h1>
                            <p class="message">Your account is set up and you’re ready to begin using Eat This Much.</p>
                            <div class="animated-characters">
                               <img src="images/veggies.webp" alt="veggies" class="mb-3" style="width: 7s00px; height: 300px ; border-radius : 15px;">
                            </div>
                            <button type="submit" class="continue-button" name="submit" style="background-color: rgb(0,128,0); padding: 5px 89px;"><i
                                    class="fa-solid fa-greater-than px-2"></i>Continue</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
    function goToSection(sectionId, stepId) {
        var sections = document.querySelectorAll('.section');
        var steps = document.querySelectorAll('.progress-step');

        // Hide all sections and remove active class from all steps
        sections.forEach(section => section.classList.remove('active'));
        steps.forEach(step => step.classList.remove('active'));

        // Show the clicked section and add active class to the clicked step
        document.getElementById(sectionId).classList.add('active');
        document.getElementById(stepId).classList.add('active');
    }

    function goToNextSection(currentSectionId, nextSectionId) {
        goToSection(nextSectionId, nextSectionId.replace('section', 'step'));
    }

    function selectDiet(diet, element) {
        // Remove the 'selected' class from all options
        document.querySelectorAll('.form-check').forEach(el => el.classList.remove('selected'));

        // Add the 'selected' class to the clicked option
        element.classList.add('selected');

        // Set the hidden input value
        document.getElementById('selectedDiet').value = diet;
    }

    function selectGoal(goal, element) {
        // Remove the 'selected' class from all options
        document.querySelectorAll('.option').forEach(el => el.classList.remove('selected'));

        // Add the 'selected' class to the clicked option
        element.classList.add('selected');

        // Store the selected goal (if needed for form submission)
        console.log("Selected Goal:", goal);
    }
    </script>
</body>

</html>