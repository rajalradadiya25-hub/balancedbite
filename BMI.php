<?php
session_start();
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');

$id = $_SESSION['id'];
//chech if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Height Selector</title>
    <?php require('component/Designlinks.php'); ?>
    <link rel="stylesheet" href="css/bmi.css">
</head>

<body>
    <?php require('component/usernav.php'); ?>
    <div class="main-content">
        <div class="container">
            <div class="content-box">
                <div class="row text-center">
                    <h1 style="color: rgb(0, 128, 0);"> BMI Calculator</h1>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-3 mt-3">
                        <div id="bmiResult" class="bmi-result text-center" style="display: none;">
                            <h3>Your BMI is: <span id="bmiValue"></span></h3>
                        </div>
                    </div>
                </div>
                <form id="bmiForm">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="gender-selection">
                                <div id="male" class="gender-option selected">
                                    <span>&#9794; Male</span>
                                </div>
                                <div id="female" class="gender-option unselected">
                                    <span>&#9792; Female</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-2 offset-md-3">
                            <div class="height-selection text-center">
                                <label for="height" class="m-3 mb-0 fw-bold" style="color: rgb(0, 128, 0);">Height</label>
                                <p><span id="selected-height">None</span></p>
                                <div class="height-selector">
                                    <button type="button" class="scroll-button" id="scroll-up">&uarr;</button>
                                    <div class="height-values" id="height-values"></div>
                                    <button type="button" class="scroll-button" id="scroll-down">&darr;</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mt-3">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-custom btn-outline-secondary" id="btnFeet">feet</button>
                                <button type="button" class="btn-custom btn-inches" id="btnInches">Inches</button>
                            </div>
                            <div class="character">
                                <img id="characterImage" src="images/male.jpg" alt="Character">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="weight-label mb-3 fw-bold m-3" style="color: rgb(0, 128, 0);">Weight</div>
                                <span id="selected-weight">None</span>
                                <button type="button" class="btn-custom" style="background-color: rgb(0, 128, 0); color:white;" id="btnFeet">kg</button>
                            </div>
                            <div class="weight-selector">
                                <button type="button" class="scroll-button-weight" id="scroll-left">&larr;</button>
                                <div class="weight-values" id="weight-values"></div>
                                <button type="button" class="scroll-button-weight" id="scroll-right">&rarr;</button>
                            </div>
                        </div>
                    </div>
                    <!-- Goal select -->
                    <div class="row mt-4">
                         <div class="col-md-6 offset-md-3">
                            <label for="goal" class="fw-bold" style="color: rgb(0,128,0);">Select Goal</label>
                            <select name="goal" id="goal" class="form-control">
                                <option value="weight_loss">Weight Loss</option>
                                <option value="maintain">Maintain Weight</option>
                                <option value="muscle_gain">Muscle Gain</option>
                            </select>
                        </div>
                     </div>

                    <!-- Activity Level select -->
                    <div class="row mt-4">
                        <div class="col-md-6 offset-md-3">
                            <label for="activitylevel" class="fw-bold" style="color: rgb(0,128,0);">Activity Level</label>
                            <select name="activitylevel" id="activitylevel" class="form-control">
                                <option value="sedentary">Sedentary (Little or no exercise)</option>
                                <option value="light">Lightly Active (1-3 days/week)</option>
                                <option value="moderate">Moderately Active (3-5 days/week)</option>
                                <option value="active">Active (6-7 days/week)</option>
                                <option value="very_active">Very Active (Hard exercise/physical job)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 offset-md-3 mt-3">
                            <button type="button" class="btn-calculate btn-block" id="btnCalculate">Calculate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require('component/footer.php'); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/bmi_calculator.js"></script>
</body>

</html>