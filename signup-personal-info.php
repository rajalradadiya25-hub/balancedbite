<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
//error_reporting(0);
// Save plan coming from signup.php
if (isset($_GET['plan_code'])) {
    $_SESSION['selected_plan'] = [
        'plan' => $_GET['plan_code'],
        'plan_name' => $_GET['plan_name'],
        'price' => floatval($_GET['price']),
        'discount_percent' => floatval($_GET['discount_percent'])
    ];
}
$selected_plan = $_SESSION['selected_plan'] ?? null;

require('db/dbconnect.php');
$details_saved = false; 
$showError = false;
$showAlert = false;

if ($_GET['username']) {
    $username = $_GET['username'];
    $query = "SELECT * FROM `$dbname`.`users` WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
    }
    // $sql = "CREATE TABLE user_info (
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     username VARCHAR(255) NOT NULL,
    //     uid INT NOT NULL,
    //     goal VARCHAR(255) NOT NULL,
    //     gender VARCHAR(50),
    //     height DECIMAL(5,2),  -- Height in decimals
    //     weight DECIMAL(5,2),  -- Weight in decimals
    //     age INT,  -- Age in years
    //     bodyfat VARCHAR(50),
    //     activitylevel VARCHAR(255),
    //     calories INT DEFAULT 1134,
    //     carbs INT DEFAULT 12,
    //     fats INT DEFAULT 21,
    //     proteins INT DEFAULT 18,
    //     FOREIGN KEY (uid) REFERENCES users(id)
    // )";

    // if ($conn->query($sql) === TRUE) {
    //     echo "Table user_info created successfully";
    // } else {
    //     echo "Error creating table: " . $conn->error;
    // }

    //insert data into the table if username is not exists
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //$diet = $_POST["diet"];
        //$exclusions = isset($_POST["exclusions"]) ? implode(", ", $_POST["exclusions"]) : "";
        // Allergy
$allergy = $_POST['allergy'] ?? [];
if (!is_array($allergy)) {
    $allergy = [$allergy];
}
$allergy = implode(', ', $allergy);

// Diet
$diet = $_POST['diet'] ?? [];
if (!is_array($diet)) {
    $diet = [$diet];
}
$diet = implode(', ', $diet);

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
            $showError = "username already exists";
        } else {

            //$sql = "INSERT INTO `$dbname`.`user_info` (username, uid, goal, gender, height, weight, age, bodyfat, activity_level, calories, carbs, fats, proteins)
                //VALUES ('$username', '$id', '$goal', '$sex', '$height', '$weight', '$age', '$bodyfat', '$activity', 
                //" . ($calories !== NULL ? "'$calories'" : "DEFAULT") . ", 
                //" . ($carbs !== NULL ? "'$carbs'" : "DEFAULT") . ", 
                //" . ($fats !== NULL ? "'$fats'" : "DEFAULT") . ", 
                //" . ($proteins !== NULL ? "'$proteins'" : "DEFAULT") . ")";
            $sql = "INSERT INTO `$dbname`.`user_info`
(username, uid, name, age, gender, height, weight, activity_level, timeframe, options, calories, meals, goal, bodyfat, carbs, fats, proteins, foodcat, last_yoga_date, yoga_streak)
VALUES (
    '$username',
    '$id',
    '$username',
    '$age',
    '$sex',
    '$height',
    '$weight',
    '$activity',
    0,
    '',
    " . ($calories !== NULL ? "'$calories'" : "0") . ",
    0,
    '$goal',
    0,
    " . ($carbs !== NULL ? "'$carbs'" : "0") . ",
    " . ($fats !== NULL ? "'$fats'" : "0") . ",
    " . ($proteins !== NULL ? "'$proteins'" : "0") . ",
    '$diet',
    NULL,
    0
)";
$result = mysqli_query($conn, $sql);
if ($result) {
    $details_saved = true;  // <-- Success flag

    // Redirect logic
    if (isset($_SESSION['selected_plan'])) {
         $p = $_SESSION['selected_plan'];
        header("Location: /final B/balancedbite/Newfolder/api/payment.php?plan_code={$p['plan']}&plan_name={$p['plan_name']}&price={$p['price']}&discount_percent={$p['discount_percent']}");
        exit;
        
    } else {
        header("Location: personalizedplans.php");
        exit;
    }

} else {
    $showError = "Error: " . mysqli_error($conn);
}
//if ($details_saved) {
    //if (isset($_SESSION['selected_plan'])) {
        //header("location: /final B/balancedbite/Newfolder/api/payment.php");
        //exit;
    //} else {
        //header("location:personalizedplans.php");
        //exit;
    //}

//}


           // $result = mysqli_query($conn, $sql);
           // $showAlert = true;
            //$result = mysqli_query($conn, $sql);
            //if ($result) {
                //header("Location: dashboard.php");
               // exit();
            //} 
            //else 
                //{
                    //echo "Error inserting data: " . mysqli_error($conn);
                //}
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
        <div class="row m-5 box" style="background-image: url('images/signup.jpg'); background-repeat:no-repeat; background-size:cover;">
            <div class="col-md-2">
            </div>
            <div class="col-md-12">
                <div class="container rounded mt-5 mb-5">
                    <div class="row fixed header">
                        <div class="col-md-7 offset-md-4 text-center">
                            <h1>
                                <a href="index.php" class="text-decoration-none" style="color: rgb(0,128,0);">BalancedBite</a>
                                <div class="progress-container mt-4">
                                    <div class="progress-step active" id="step1" onclick="goToSection('section1', 'step1')">1</div>
                                    <div class="progress-step" id="step2" onclick="goToSection('section2', 'step2')">2</div>
                                    <div class="progress-step" id="step3" onclick="goToSection('section3', 'step3')">3</div>
                                    <div class="progress-step" id="step4" onclick="goToSection('section4', 'step4')">4</div>
                                    <div class="progress-step" id="step5" onclick="goToSection('section5', 'step5')">5</div>
                                    <div class="progress-step" id="step6" onclick="goToSection('section6', 'step6')">6</div>
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
                                        <p>Choose from a pre-set diet. You can fine-tune the excluded foods later.</p>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="diet" id="anything" value="anything">
                                                <label class="form-check-label" for="anything">
                                                    Anything <br> <small>Excludes: Nothing</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="diet" id="keto" value="keto">
                                                <label class="form-check-label" for="keto">
                                                    Keto <br> <small>Excludes: Legumes, Starchy Vegetables, High-carb Grains</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="diet" id="mediterranean" value="mediterranean">
                                                <label class="form-check-label" for="mediterranean">
                                                    Mediterranean <br> <small>Excludes: Fruit juice, Starchy Vegetables</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="diet" id="paleo" value="paleo">
                                                <label class="form-check-label" for="paleo">
                                                    Paleo <br> <small>Excludes: Dairy, Grains, Legumes, Soy, Starchy Vegetables</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="diet" id="keto" value="keto">
                                                <label class="form-check-label" for="keto">
                                                    Vegan <br> <small>Excludes: Dairy, Mayo, Honey</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button" onclick="goToNextSection('section1', 'section2')"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Second Form Section -->
                        <section id="section2" class="section">
                            <div class="row mt-2">
                                <div class="col-md-7 offset-md-4">
                                    <div>
                                        <h2>Are there foods you avoid?</h2>
                                        <p>This may be due to allergijes or any other reason.</p>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exclusions" id="soy" value="soy">
                                                <label class="form-check-label" for="soy">Soy</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exclusions" id="dairy" value="dairy">
                                                <label class="form-check-label" for="dairy">Dairy</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exclusions" id="treeNuts" value="treeNuts">
                                                <label class="form-check-label" for="treeNuts">Tree Nuts</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exclusions" id="gluten" value="gluten">
                                                <label class="form-check-label" for="gluten">Gluten</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exclusions" id="peanuts" value="peanuts">
                                                <label class="form-check-label" for="peanuts">Peanuts</label>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button" onclick="goToNextSection('section2', 'section3')"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Third Form Section -->
                        <section id="section3" class="section">
                            <div class="row mt-2">
                                <div class="col-md-7 offset-md-4">
                                    <div>
                                        <h2>What is your goal?</h2>
                                        <p>This information lets us suggest meals to help you reach your goal.</p>
                                        <div class="options">
                                            <label class="option">
                                                <input type="radio" name="goal" value="maintain weight" /> Maintain weight
                                            </label><br>
                                            <label class="option">
                                                <input type="radio" name="goal" value="build muscle" /> Build muscle
                                            </label><br>
                                            <label class="option">
                                                <input type="radio" name="goal" value="lose fat" /> Lose fat
                                            </label>
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button" onclick="goToNextSection('section3', 'section4')"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Four Form Section -->
                        <section id="section4" class="section">
                            <div class="row mt-2">
                                <div class="col-md-7 offset-md-4">
                                    <div>
                                        <h2>Tell us about yourself</h2>
                                        <p>This information lets us estimate your nutrition requirements for each day.</p>
                                        <div class="form-group">
                                            <label for="sex">Sex</label>
                                            <select id="sex" name="sex">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="height">Height (cm)</label>
                                            <input type="text" id="height" name="height" />
                                        </div>
                                        <div class="form-group">
                                            <label for="weight">Weight (kg)</label>
                                            <input type="number" id="weight" name="weight" />
                                        </div>
                                        <div class="form-group">
                                            <label for="age">Age (years)</label>
                                            <input type="number" id="age" name="age" />
                                        </div>
                                        <div class="form-group">
                                            <label for="bodyfat">Bodyfat</label>
                                            <select id="bodyfat" name="bodyfat">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">HIgh</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="activity">Activity Level</label>
                                            <select id="activity" name="activity">
                                                <option value="sedentary">Sedentary</option>
                                                <option value="lightly active">Lightly active</option>
                                                <option value="moderately active">Moderately active</option>
                                                <option value="active">Active</option>
                                                <option value="very active">Very active</option>
                                            </select>
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button" onclick="goToNextSection('section4', 'section5')"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Five Form Section -->
                        <section id="section5" class="section">
                            <div class="row mt-2">
                                <div class="col-md-7 offset-md-4">
                                    <div>
                                        <h2>Set Nutrition Target</h2>
                                        <p>Take a look at the daily nutrition targets we've estimated for you based on your profile. You can adjust the targets by clicking the edit button or do it later on your meal planner page.</p>
                                        <div class="form-group">
                                            <label for="calories">Calories per day</label>
                                            <input type="number" id="calories" name="calories" placeholder="2000" />
                                        </div>
                                        <div class="form-group">
                                            <label for="carbsRange" class="form-label">Carbs (grams)</label>
                                            <div class="range-label">
                                                <span id="carbsValue">0</span>
                                            </div>
                                            <input type="range" class="form-range" min="0" max="300" id="carbsRange" name="carbs" oninput="document.getElementById('carbsValue').innerText = this.value" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="fatsRange" class="form-label">Fats (grams)</label>
                                            <div class="range-label">
                                                <span id="fatsValue">0</span>
                                            </div>
                                            <input type="range" class="form-range" min="0" max="300" id="fatsRange" name="fats" oninput="document.getElementById('fatsValue').innerText = this.value" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="proteinsRange" class="form-label">Proteins (grams)</label>
                                            <div class="range-label">
                                                <span id="proteinsValue">0</span>
                                            </div>
                                            <input type="range" class="form-range" min="0" max="300" id="proteinsRange" name="proteins" oninput="document.getElementById('proteinsValue').innerText = this.value" value="0">
                                        </div>
                                        <div class="text-center mt-4 mb-5">
                                            <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 89px;" type="button" onclick="goToNextSection('section5', 'section6')"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
                                        </div>
                                    </div>
                                    <script>
                                        function goToNextSection(currentSection, nextSection) {
                                            document.getElementById(currentSection).classList.remove('active');
                                            document.getElementById(nextSection).classList.add('active');
                                        }

                                        // Update range value display on page load
                                        window.onload = function() {
                                            document.getElementById('carbsValue').innerText = document.getElementById('carbsRange').value;
                                            document.getElementById('fatsValue').innerText = document.getElementById('fatsRange').value;
                                            document.getElementById('proteinsValue').innerText = document.getElementById('proteinsRange').value;
                                        };
                                    </script>
                                </div>
                            </div>
                        </section>
                        <!-- sixth Form Section -->
                        <div class="row mt-2 section" id="section6">
                            <div class="col-md-7 offset-md-4">
                                <div>
                                    <h1>Welcome!</h1>
                                    <p class="message">Your account is set up and you’re ready to begin using Eat This Much.</p>
                                    <div class="animated-characters">
                                        <img src="broccoli3.png" alt="Broccoli3" />
                                                                               
                                    </div>
                                    <button type="submit" class="continue-button" name="submit"><i class="fa-solid fa-greater-than px-2"></i>Continue</button>
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
    </script>
</body>

</html> 