<?php

error_reporting(0);
require('db/dbconnect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us page</title>

    <?php require 'component/Designlinks.php' ?>

    <style>
        .background {
            background-image: url('images/about-heading.jpg');
            background-position: left;
            background-repeat: no-repeat;
            background-size: cover;
            height: 250px;
        }

        .backgroundcreateaccount {
            background-image: url('images/about-joinus.jpg');
            background-position: left;
            background-repeat: no-repeat;
            background-size: cover;
            height: 250px;
        }
    </style>

</head>

<body>
    <?php require("component/Nav.php"); ?>

    <div class="container">
        <div class="row background mt-5">
            <div class="col-md-12 py-5 px-5 text-center">
                <h1 style="color: rgb(0,128,0);">About Us</h1>
                <h5>"Discover Our Mission, Story, and Values"</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-3">
                <img src="images/about-mission.jpg" alt="" height="250" width="250">
            </div>
            <div class="col-md-9">
                <h3 style="color:rgb(0,128,0);">Our Mission</h3>
                <p>At Balanced Bite, our mission is to empower
                    individuals to achieve their health and wellness
                    goals through personalized nutrition and
                    balanced diet plans. We believe that healthy
                    eating should be accessible, enjoyable, and
                    sustainable for everyone. By providing tailored
                    meal plans, delicious recipes, and
                    comprehensive tracking tools, we aim to make
                    he journey to better health a rewarding and
                    fulfilling experience.</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-9">
                <h3 style="color:rgb(0,128,0);">Our Story</h3>
                <p>Balanced Bite was founded on the belief that
                    proper nutrition is the cornerstone of a healthy
                    and happy life. Our founders, a team of
                    passionate nutritionists and wellness experts,
                    saw a need for a more personalized approach
                    to diet and nutrition. Frustrated with one-size-
                    its-all diets and fad trends, they set out to
                    create a platform that offers customized meal
                    plans designed to meet the unique needs and
                    goals of each individual.
                    <br><br>
                    Since our inception, we have helped thousands
                    of users transform their eating habits and
                    improve their overall health. Our commitment
                    to innovation and excellence has driven us to
                    continually enhance our platform, ensuring
                    that our users have access to the best tools
                    and resources available.
                </p>
            </div>
            <div class="col-md-3">
                <img src="images/about-story.jpg" alt="" height="250" width="250">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-3">
                <img src="images/about-team.jpg" alt="" height="250" width="250">
            </div>
            <div class="col-md-9">
                <h3 style="color:rgb(0,128,0);">Our Team</h3>
                <p>Our team at Balanced Bite is a diverse group
                    of experts dedicated to helping you succeed
                    on your health journey. We bring together
                    nutritionists, dietitians, chefs, and fitness
                    experts, all working collaboratively to create a
                    holistic approach to wellness.
                    <br><br>
                    <strong>Nutritionists and Dietitians:</strong> Our
                    nutrition experts design personalized meal
                    plans based on the latest scientific
                    research, ensuring that you receive
                    balanced and nutritious meals tailored to
                    your specific needs.
                    <br>
                    <strong>Chefs:</strong> Our culinary team crafts delicious
                    and healthy recipes that make eating well
                    a delightful experience. They continuously
                    experiment with new ingredients and
                    cooking techniques to keep your meals
                    exciting and enjoyable.
                    <br>
                    <strong>Fitness Experts:</strong> Our fitness professionals
                    provide guidance on incorporating
                    physical activity into your daily routine,
                    complementing your nutrition plan to help
                    you achieve your overall health goals.
                </p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-9">
                <h3 style="color:rgb(0,128,0);">Our Values</h3>
                <p>At Balanced Bite, our values guide everything
                    we do. They are the foundation of our
                    commitment to helping you lead a healthier,
                    happier life.
                    <br>
                    <strong>Personalization:</strong> We understand that
                    every individual is unique, and so are their
                    nutritional needs. Our personalized
                    approach ensures that your meal plans are
                    tailored specifically to you.
                    <br>
                    <strong>Quality:</strong> We prioritize quality in all aspects
                    of our service, from the ingredients in our
                    recipes to the information and support we
                    provide. We strive for excellence and
                    continuous improvement.
                    <br>
                    <strong>Accessibility:</strong> We believe that healthy
                    eating should be accessible to everyone,
                    regardless of their background or lifestyle.
                    Our platform is designed to be user-
                    friendly and inclusive.
                    <br>
                    <strong>Community:</strong> We foster a supportive and
                    inclusive community where users can
                    share their experiences, seek advice, and
                    find motivation. Together, we inspire and
                    uplift each other on our health journeys.
                </p>
            </div>
            <div class="col-md-3">
                <img src="images/about-value.jpg" alt="" height="250" width="250">
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-12 backgroundcreateaccount">
                <div class="mt-3 text-center text-light">
                    <h4>Join Us</h4>
                    <p>Ready to embark on your journey to better
                        health with <br> <strong>Balanced Bite?</strong> Sign up today and
                        discover the benefits <br>of personalized nutrition,
                        delicious meal plans, and <br>comprehensive
                        support. Together, we can achieve your health
                        and <br> wellness goals.</p>
                    <button class="btn" style="background-color: rgb(0, 128, 0);"><a href="signup.php" class="text-light text-decoration-none">Get Started</a></button>
                </div>
            </div>
        </div>
    </div>

    <?php require("component/Footer.php") ?>
</body>

</html>