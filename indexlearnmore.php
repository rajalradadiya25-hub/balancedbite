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
    <?php require 'component/Designlinks.php' ?>
    <title>Index-learn more page</title>

    <style>
        .background {
            background-image: url('images/indexlearnmore.jpg');
            background-position: left;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
        }

        .backgroundcreateaccount {
            background-image: url('images/learnmore-end.jpg');
            background-position: left;
            background-repeat: no-repeat;
            background-size: cover;
            height: 250px;
        }

        .img {
            z-index: 1;
        }

        .accordion-button::after {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/></svg>');
            transform: scale(1.7) !important;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16"><path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/></svg>') !important;
        }
    </style>
</head>

<body>
    <?php require("component/nav.php"); ?>
    <div class="container mt-5">
        <!-- hero section of learn more -->
        <div class="row background">
            <div class="col-md-6 mt-5 mx-5 py-5 px-5">
                <h1 style="color: rgb(0,128,0);">Discover Balanced Bite</h1>
                <p>"At Balanced Bite, we are dedicated to helping you achieve your health and wellness goals with personalized nutrition plans tailored to your needs. Our user-friendly platform offers a wide range to delicious recipes, making healthy eating enjoyable. With comprehensive tracking eating enjoyable. Start your transformation today and experience the benifits of a healthier lifestyle with Balanced Bite. Your path to wellness begins here."</p>
            </div>
        </div>
        <!-- transform health with balanced bite -->
        <h1 class="text-center p-1 mt-5">Transform Your Health with Balanced Nutrition</h1>
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="images/benefit1.jpg" class="card-img-top img" alt="..." height="280">
                    <div class="card-body">
                        <h5 class="card-title">Nutrition Tailored to You</h5>
                        <p class="card-text">Our personalized plans are specific needs, preferences, and health goals. Enjoy balanced, nutritious meals that fit seamlessly into your lifestyle. </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="images/benefit2.jpg" class="card-img-top img" alt="..." height="280">
                    <div class="card-body">
                        <h5 class="card-title">Extensive Recipe Library</h5>
                        <p class="card-text">Discover a wide variety of mouth watering recipes that make healthy eating enjoyable. Each recipe is designed to provide the perfect balance of flavor and nutrition.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="images/benefit3.jpg" class="card-img-top img" alt="..." height="280">
                    <div class="card-body">
                        <h5 class="card-title">Easy Meal Planning </h5>
                        <p class="card-text">intutive meal planner helps you organize your meals effortlessly. Plans your meals ahead of timw, generate shopping lists, and enjoy stress-free meal prep.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="images/benefit4.jpg" class="card-img-top img" alt="..." height="280">
                    <div class="card-body">
                        <h5 class="card-title">Comprehensive Progress Tracking</h5>
                        <p class="card-text">Track your metrics with ease. Log your food intake, monitor your weight, and visualize your progress with detailed charts and graphs.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- testimonial heading -->
        <div class="row mt-5 pt-5">
            <div class="col-md-5 offset-md-2">
                <h1>Real Results from <h1 style="color:rgb(0,128,0);">Real People</h1>
                </h1>
                <p>At Balanced Bite, we take pride in the positive
                    impact our personalized nutrition plans have
                    on our users' lives. Hear directly from members
                    of our community who have transformed their
                    health and well-being with the help of
                    Balanced Bite.</p>
            </div>
            <div class="col-md-5 ">
                <img src="images/successstoryheading.jpg" alt="" class="img" height="300" width="300">
            </div>
        </div>
        <!-- testimonial carasouls -->
        <div class="row mt-5 pt-5">
            <div id="carouselExampleDark" class="carousel carousel-light slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                        <img src="images/carasoul1.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h4 class="text-light">"A Healthier, Happier Me"</h4>
                            <p class="text-light fw-bold">Emily R.</p>
                            <p class="text-light">"Balanced Bite has completely transformed my approach to eating healthy. The personalized plans are easy to follow, and i've seen fantastic results in just three months! i've lost weight, feel more energetic, and truly enjoy the delicious recipes they provide. The support and guidence from the Balanced Bite community have been incredible, keeping me motivated every step of the way."</p>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="images/carasoul2.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h4 class="text-light">"Exciting and Tasty Meal Plans"</h4>
                            <h5 class="text-light">David M.</h5>
                            <p class="text-light">"The variety of delicious recipes
                                keeps me excited about eating healthy. I used
                                to struggle with meal planning, but Balanced
                                Bite makes it so simple. I've managed to lose
                                weight while enjoying my meals every day, and
                                I couldn't be happier with the results. The
                                progress tracking tools are fantastic, allowing
                                me to see my achievements and stay on track."</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/carasoul3.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h4 class="text-light">"Achieving My Fitness Goals"</h4>
                            <h5 class="text-light">Sarah T.</h5>
                            <p class="text-light">"I love how easy it is to track my
                                progress with Balanced Bite. The personalized
                                plans are tailored perfectly to my needs, and
                                the support from the community is incredible.
                                I've achieved my fitness goals faster than I
                                expected and feel more confident in my health
                                journey. The platform's comprehensive tools
                                make staying on top of my health effortless
                                and enjoyable."</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- FAQs questions -->
        <div class="row mt-5 pt-5">
            <h1 class="text-center">Common Questions Answered</h1>
            <div class="col-md-4 mt-4">
                <img src="images/learnmore-question.jpg" alt="" height="365" width="380">
            </div>
            <div class="col-md-8 mt-3">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item mt-2 border">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How can i contact support?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                You can reach our support via email at <br>
                                <strong class="text-success">support@balancedbite.com</strong> or call us at <br>
                                <strong class="text-success">+ 48 234 567 88</strong>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mt-2 border">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How do i create an account?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Click the<strong class="text-success"> Get Started</strong> button on the home page and follow the prompts to sign up.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mt-2 border">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Can i customize my meal plans
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong class="text-success">Yes,</strong> you can customize your meal plans based on your dietery preferences and health goals.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mt-2 border">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How do i track my progress
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Use our comprehensive tracking tools to log your food intake, monitor your weight, and visualize your progress.
                            </div>
                        </div>
                    </div>
                </div>
                <p> For more FAQs, visit our full FAQ page <a href="FAQ.php" class="text-success">here</a>.</p>
            </div>
        </div>
    </div>
    <?php require("component/footer.php"); ?>
</body>

</html>