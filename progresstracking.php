<?php

//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress tracking page</title>
    <?php require 'component/Designlinks.php' ?>
    <style>
        .background {
            background-image: url('images/backg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
        }

        .background1 {
            background-image: url('images/output1.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
        }
    </style>
</head>

<body>
    <?php require 'component/nav.php' ?>

    <div class="container p-0">
        <div class="row mt-5  background">
            <div class="col-md-8 mt-3 offset-md-2 text-center" style="padding: 40px; padding-bottom:25% ;">
                <h1 style="color: rgb(0,128,0);">Monitor Your Health Journey</h1>
                <p>At Balanced Bite, we believe that tracking your progress is key to achieving your health and wellness goals. Our comprehensive progress tracking tools are designed to help you stay motivated, monitor your achievements, and make informed decisions about your nutrition and fitness.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-weight.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">1. Weight Tracking</h5>
                        <strong>Track Your Weight Journey</strong>
                        <p>Log your weight refularly to see your progress over time. Our weight tracking tool aloows you to input your weight daily, weekly, or monthly. View detailed charts and graphs that illustrate your weught trends, helping you stay on track and motivated.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-body.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">2. Body Measurement</h5>
                        <strong>Monitor Your Body Changes</strong>
                        <p>Trck various body measurements, including tracker helps you see changes in your body composition beyond just weight, giving you a more complete picture of your progress.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-BMI.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">3. BMI Calculation</h5>
                        <strong>Understand Your Body Mass Index</strong>
                        <p>Calculate your Body Mass Index(BMI) to understand where you stand in terms of healthy weight. Our BMI calculator provides you with insights into your overall health and helps you set realistic goals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-calorie.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">4. Calorie Intake Tracking</h5>
                        <strong>Log Your Daily Caloric Intake</strong>
                        <p>Keep track of your daily calorie intake to ensure you are meeting your nutritional goals. Our calorie tracker helps you monitor the calories you consume and compare them to your target intake, making it easier to manage your diet effectively.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-nutrient.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">5. Nutrient Tracking</h5>
                        <strong>Balance Your Nutrient Intake</strong>
                        <p>Monitor the intake of essential nutrients, including proteins, carbohydrates, fats, vitamins, and minerals, Our nutrient tracker provides detailed breakdowns of your diet, helping you maintain a balanced and healthy nutrition plan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <img src="images/progress tracking-physical tracking.jpg" alt="" height="150" width="150">
                    </div>
                    <div class="col-md-9">
                        <h5 style="color:rgb(0,128,0);">6. Physical Activity Tracking</h5>
                        <strong>Log Your Workouts and Activities</strong>
                        <p>Track your physical activities and workouts to ensure you are staying active. Our activity tracker allows you to log exercises, view your workout history, and see how your physical activity contributes to your overall health goals.</p>
                    </div>
                </div>
            </div>
           
        <div class="row mt-5">
            <h1 class="text-center" style="color: rgb(0,128,0);">How It Works</h1>
        </div>
        <div class="row mt-3">
            <div class="col-md-8 offset-md-2">
                <h4 style="color: rgb(0,128,0);">Step 1: Log Your Data</h4>
                <h5>Input Your Health Data</h5>
                <p>Regularly log your weight, body measurements, calorie intake, nutrient intake, and physical activities. Consistent logging helps you track your progress accurately.</p>
            </div>
            <div class="col-md-8 offset-md-2">
                <h4 style="color: rgb(0,128,0);">Step 2: Analyze Your Progress</h4>
                <h5>Review Your Progress</h5>
                <p>Use our detailed charts, graphs, and reports to analyze your progress. Identify patterns and trends that can help you understand what's working and where you might need to make adjustments.</p>
            </div>
            <div class="col-md-8 offset-md-2">
                <h4 style="color: rgb(0,128,0);">Step 3: Stay Motivated</h4>
                <h5>Stay Inspired on Your Journey</h5>
                <p>Celebrate your achievements and milestones. Use visual progress tools and detailed reports to stay motivated and committed to your health goals.</p>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-10 mt-3 background1 offset-md-1 text-center" style="padding:13% ;">
                <p>Ready to take control of your health journey? Sign up for <strong>Balanced Bite</strong> and <br>start tracking your progress today. Our comprehensive tools and expert support <br> will help you achieve your health and wellness goals.</p>
                <button class="btn" style="background-color: rgb(0,128,0);" type="submit" name="login"><a href="signup.php" class="text-decoration-none text-light">Start Tracking Now</a></button>
            </div>
        </div>
    </div>

    <?php require 'component/footer.php' ?>
</body>

</html>