<?php
error_reporting(0);
require("db/dbconnect.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require 'component/Designlinks.php' ?>
    <title>Index page</title>


    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .display-4 {
            font-size: 2rem;
            font-weight: bold;
            padding-top:50px;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 300px;
        }

        .background {
            position: absolute;
            top: 72%;
            left: 226px;
            width: 350px;
            height: 530px;
            z-index: -1;
            background-color: rgb(0, 128, 0);
            transform: translate(-50%, -50%);
        }

        .larger-image {
            position: absolute;
            top: 70%;
            left: 230px;
            width: 600px;
            height: auto;
            transform: translate(-50%, -50%);
            z-index: -1;
        }


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .image-container {
                height: auto;
                margin-bottom: 20px;
            }

            .background {
                width: 0%;
                height: 0;
                top: 100%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .larger-image {
                width: 0%;
                top: 100%;
                left: 50%;
                transform: translate(-50%, -50%);
            }


        }
    </style>
</head>

<body>

    <?php require("component/nav.php"); ?>
    <!-- hero section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="image-container">
                    <div class="background"></div>
                    <img src="images/herosectiondish.png" alt="Larger Image" class="img-fluid larger-image">
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="display-4 text-success">Personalized Nutrition Plans for a Healthier You</h1>
                <p>" Embark on a transformative journey with Balanced Bite, where personalized nutrition meets delectable flavors. Our expertly designed meal plans, delicious recipes, and comprehensive tracking tools empower you to achieve your health goals effortlessly. Join our vibrant community, savor every bite, and experience the joy of a balanced, healthy lifestyle today."</p>
                <button class="btn" style="background:rgb(0, 128, 0);"><a href="signup.php" style="text-decoration: none;" class="text-light">Get Started</a></button>
                <button class="btn" style="background:rgb(0, 128, 0);"><a href="indexlearnmore.php" style="text-decoration: none;" class="text-light">Learn More</a></button>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 200px;">
        <!-- welcome section -->
        <div class="row">
            <p class="text-center">Welcome to Balanced Bite!<br>We're dedicated to helping you achieve your health and <br> wellnes goals through personalized nutrition plans and easy-to-follow recipes.</p>
            <img src="images/herosectionbackimage.png" alt="fruits" height="350" class="ms-4">
        </div>
        <!-- features -->
        <div>
            <h1 class="text-center m-5 text-light p-2" style="background-color: rgb(0, 128, 0);">Features</h1>
        </div>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="images/personalizedplans.jpg" alt="pesonalized plans" class="rounded-circle mx-auto d-block" height="150" width="150">
                <h5 class="mt-3 mb-0 fw-bold">Personalized Plans</h5>
                <p style="word-break: break-all; padding:10px 40px;">Get diet plans tailored to your unique needs and preferences. Whether you're looking to lose weight,gain muscle, or just eat healthier, we've got you covered.</p>
                <button class="btn" style="background:rgb(0, 128, 0);"><a href="personalizedplans.php" style="text-decoration: none;" class="text-light">Click Here</a></button>
            </div>
            <div class="col-md-4 text-center">
                <img src="images/deliciousrecipe.jpg" alt="delicious recipe" class="rounded-circle mx-auto d-block" height="150" width="150">
                <h5 class="mt-3 mb-0 fw-bold">Delicious Recipes</h5>
                <p style="word-break: break-all; padding:10px 40px;">Explore our extensive library of delicious and nutritious recipes. From breakfast to dinner, we've got meals that will keep you satisfied and on track.</p>
                <button class="btn" style="background:rgb(0, 128, 0);"><a href="deliciousrecipes.php" style="text-decoration: none;" class="text-light">Click Here</a></button>
            </div>
            <div class="col-md-4 text-center">
                <img src="images/progresstracking.jpg" alt="progress tracking" class="rounded-circle mx-auto d-block" height="150" width="150">
                <h5 class="mt-3 mb-0 fw-bold">Progress Tracking</h5>
                <p style="word-break: break-all; padding:10px 40px;"> Track your progress with easy-to-use tools. Monitor your weight, BMI, and other health metrics to stay motivated and reach your goals.</p><br>
                <button class="btn" style="background:rgb(0, 128, 0);"><a href="progresstracking.php" style="text-decoration: none;" class="text-light">Click Here</a></button>
            </div>
        </div>
    </div>
    <!-- what users says -->
    <div class="container-fluid" style="background-color: rgb(0, 128, 0);">
        <div class="row m-4 text-light">
            <div>
                <h1 class="text-center p-5 text-light">What Our Users Says</h1>
            </div>
            <div class="row">
                <div class="col-6 offset-md-2 ">
                    <i class="fa-solid fa-quote-left fs-4 text-dark"></i> "Balanced Bite offers a perfect
                    blend of personalized nutrition and
                    convenience. The meal plans are tailored to my
                    specific goals, and I love the flexibility to
                    adjust them as needed. The progress tracking
                    feature is fantastic, and I've never felt more in
                    control of my health." <br><br>
                    <p class="fst-italic">Jessica K.</p>
                </div>
                <div class="col-2">
                    <img src="images/testimonial1.jpg" alt="testimonial1" height="250" width="200">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 offset-md-2">
                    <img src="images/testimonial2.jpg" alt="testimonial2" height="200" width="200">
                </div>
                <div class="col-md-6">
                    <i class="fa-solid fa-quote-left fs-4 text-dark"></i>"Thanks to Balanced Bite, I've
                    learned how to make healthier choices that suit
                    my lifestyle. The personalized plans have
                    introduced me to new and exciting recipes,
                    making me look forward to every meal. I've
                    achieved my weight loss goals and feel better
                    than ever!" <br><br>
                    <p class="fst-italic">Anthony S.</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6 offset-md-2">
                    <i class="fa-solid fa-quote-left fs-4 text-dark"></i> "Balanced Bite's user-friendly
                    platform makes it easy to stay committed to my
                    health goals. The detailed meal plans and
                    progress tracking keep me motivated, and the
                    community support is truly inspiring. I'm so
                    grateful for the positive impact Balanced Bite
                    has had on my life." <br><br>
                    <p class="fst-italic">Olivia H.</p>
                </div>
                <div class="col-2">
                    <img src="images/testimonial3.jpeg" alt="testimonial3" height="200" width="200">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-2 offset-md-2">
                    <img src="images/testimonial4.jpeg" alt="testimonial4" height="200" width="200">
                </div>
                <div class="col-md-6">
                    <i class="fa-solid fa-quote-left fs-4 text-dark"></i>"The transformation I've seen in
                    my health since joining Balanced Bite is
                    unbelievable. The personalized plans,
                    combined with the delicious recipes, have
                    helped me achieve a balanced diet and a
                    healthier lifestyle. It's the best decision I've
                    ever made for my well-being."<br><br>
                    <p class="fst-italic">Michael W.</p>
                </div>
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-md-8 offset-md-2">
                    Disclaimer: These are real testimonials, but we're required to tell you that results aren't guaranteed. Balanced Bite is a tool for planning your meals, and your success will depend on adhering to your planned meals and nutrition goals. Only you can make yourself improve your diet, but we'll be here to help.
                    <br><br>
                    Balanced Bite is not a substitute for professional medical advice. You should consult with a medical professional before making significant changes to your diet.
                </div>
            </div>
        </div>
    </div>
    <!-- meal planning and steps -->
   
    
    <?php require("component/footer.php"); ?>
</body>

</html>