<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$showAlert = false;
$showError = false;
$showUserError = false;

//error_reporting(0);
require('db/dbconnect.php');
session_start();

if (isset($_GET['plan_code'])) {
    $_SESSION['selected_plan'] = [
        'plan' => $_GET['plan_code'],
        'plan_name' => $_GET['plan_name'],
        'price' => floatval($_GET['price']),
        'discount_percent' => floatval($_GET['discount_percent'])
    ];
}

//if (isset($_SESSION['selected_plan'])) {
   // $_SESSION['keep_selected_plan'] = $_SESSION['selected_plan'];
//
//if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
   // $loggedin = true;
//} else {
    //$loggedin = false;
//}
//if ($loggedin) {
    //$id = $_SESSION['id'];
    //header("location:dashboard.php?id={$id}");
//}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require 'db/dbconnect.php';

    //create users table
    $createtable = "CREATE TABLE IF NOT EXISTS `$dbname`.`users` ( `id` INT(11) NOT NULL AUTO_INCREMENT,  `username` VARCHAR(11) NOT NULL , `phno` VARCHAR(10) NOT NULL,`password` VARCHAR(255) NOT NULL,`role` VARCHAR(20) NOT NULL ,    PRIMARY KEY  (`id`),    UNIQUE  (`username`)) ENGINE = InnoDB";
    mysqli_query($conn, $createtable);

    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $phno = $_POST["phno"];

    //check whether username already exists or not
    $existSql = "SELECT * FROM `$dbname`.`users` WHERE username='$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showError = "username already exists";
    } else {
        //check if username and password are inputted or not
        if (!$username) {
            $showUserError = true;
        } elseif (!$password) {
            $showPassError = true;
        } elseif ($password && $username) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";
            $sql = "INSERT INTO `$dbname`.`users` (`username`, `phno`,`password`, `role`) VALUES ('$username', '$phno','$hash', '$role')";
            $result = mysqli_query($conn, $sql);
           // if ($password) {
                //$showAlert = true;
                //header("location:signup-personal-info.php?username={$username}");
                if ($result) {
    $_SESSION['loggedin'] = true;
    $_SESSION['id'] = mysqli_insert_id($conn);
    $_SESSION['username'] = $username;

    header("Location: signup-personal-info.php?username={$username}");
    exit;
}

            
        }
    }
}
//if(isset($_SESSION['selected_plan'])) 
if(isset($_POST['submit']) && isset($_SESSION['selected_plan'])){
    $plan = $_SESSION['selected_plan'];
    unset($_SESSION['selected_plan']); // clear session

    // Redirect to payment page automatically
    header("location:/final B/balancedbite/Newfolder/api/payment.php?plan={$plan['plan']}&amount={$plan['amount']}&discount={$plan['discount']}&final={$plan['final']}");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up page</title>
    <style>
        .error-valid {
            color: red;
        }

        .font-size {
            font-size: 13px;
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
    <?php require 'component/Designlinks.php' ?>
    <script src="js/captcha.js"></script>
</head>

<body onload="generate()">
    <script>
        $(document).ready(function() {
            // Call validation functions on input
            $("#username").on("change", function() {
                validateUsername();
            });

            $("#password").on("change", function() {
                validatePassword();
            });

            $("#cpassword").on("change", function() {
                validateConfirmPassword();
            });

            $("#phno").on("change", function() {
                validatePhoneNumber();
            });

            $("#myForm").on("submit", function(event) {
                if (!validatePassword()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });

            $("#myForm").on("submit", function(event) {
                if (!validatePhoneNumber()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });

            $("#myForm").on("submit", function(event) {
                if (!validateConfirmPassword()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });
            $("#myForm").on("submit", function(event) {
                if (!validateUsername()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });


            function validateUsername() {
                var username = $("#username").val();
                var regex = /^[a-zA-Z0-9_]{3,15}$/;
                if (username === "") {
                    $("#usernameHelp").text("Please enter username.").css("color", "red");
                    return false;
                } else if (!regex.test(username)) {
                    $("#usernameHelp").text("username must be at least 3 to 15 characters long, contain letters,numbers and underscores.").css("color", "red");
                    return false;
                } else {
                    $("#usernameHelp").text("").css("color", "green");
                    return true;
                }
            }

            function validatePassword() {
                var password = $("#password").val();
                var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (password === "") {
                    $("#passwordHelp").text("Please enter password.").css("color", "red");
                    return false;
                } else if (!regex.test(password)) {
                    $("#passwordHelp").text("Password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, one digit, and one special character.").css("color", "red");
                    return false;
                } else {
                    $("#passwordHelp").text("").css("color", "green");
                    return true;
                }
            }

            function validateConfirmPassword() {
                var password = $("#password").val();
                var confirmPassword = $("#cpassword").val();
                if (confirmPassword === "") {
                    $("#confirmPasswordHelp").text("Please enter confirm password.").css("color", "red");
                    return false;
                } else if (password !== confirmPassword) {
                    $("#confirmPasswordHelp").text("Passwords do not match!").css("color", "red");
                    return false;
                } else {
                    $("#confirmPasswordHelp").text("").css("color", "green");
                    return true;
                }
            }

            function validatePhoneNumber() {
                var phone = $("#phno").val();
                var regex = /^[0-9]{10}$/;
                if (phone === "") {
                    $("#phoneHelp").text("Please enter phone number.").css("color", "red");
                    return false;
                } else if (!regex.test(phone)) {
                    $("#phoneHelp").text("Phone number must be exactly 10 digits.").css("color", "red");
                    return false;
                } else {
                    $("#phoneHelp").text("").css("color", "green");
                    return true;
                }
            }
        });
    </script>


    <?php
    
    //if pass word and confirm password are not matched.
    if ($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $showError . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    //if username is not entered.
    if ($showUserError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Please enter username.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    ?>
    <div class="container">
        <div class="row m-5 box" style="background-image: url('images/signup.jpg'); background-repeat:no-repeat; background-size:cover;">
            <div class="col-md-2"></div>
            <div class="col-md-12">
                <form method="POST" action="" id="myForm">
                    <div class="container rounded mt-5 mb-5">
                        <div class="row">
                            <div class="col-md-4 offset-md-4 text-center">
                                <h1>
                                    <a href="index.php" class="text-decoration-none" style="color: rgb(0,128,0);">BalancedBite</a>
                                </h1>
                                <hr>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4 offset-md-4">
                                <div>
                                    <div class="mb-3">
                                        <h3 class="fw-bold">Register</h3>
                                        <div>
                                            <p>Already have an account? <a href="login.php" style="color: rgb(0,128,0);">Log In</a></p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="username">Username</label>
                                            <input type="text" maxlength="11" class="form-control" id="username" name="username">
                                            <span id="usernameHelp" class="error-valid font-size"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="phno">Phone number</label>
                                            <input type="number" maxlength="10" class="form-control" id="phno" name="phno">
                                            <span id="phoneHelp" class="error-valid font-size"></span>
                                        </div>
                                    </div>
                                
    
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="password">Password</label>
                                            <input type="password" maxlength="11" class="form-control" id="password" name="password">
                                            <span id="passwordHelp" class="error-valid font-size"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="cpassword">Confirm Password</label>
                                            <input type="password" class="form-control" id="cpassword" name="cpassword">
                                            <span id="emailHelp" class="form-text">Make sure to type the same password</span><br>
                                            <span id="confirmPasswordHelp" class="error-valid font-size"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="captcha">Captcha</label>
                                            <input type="text" class="form-control" id="submit" name="captcha">
                                            <span id="key" class="error-valid font-size"></span>
                                            <div onclick="generate()">
                                                <i class="fas fa-sync"></i>
                                            </div>
                                            <div id="image" selectable="False"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 text-center mb-5">
                            <div class="col-md-4 offset-md-4">
                                <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 109px;" type="submit" name="submit" id="btn">
                                    <i class="fa-regular fa-user text-light"></i> Create Account
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript here -->
    <script>
        let captcha;

        function generate() {
            document.getElementById("submit").value = "";
            captcha = document.getElementById("image");
            let uniquechar = "";
            const randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (let i = 0; i < 5; i++) {
                uniquechar += randomchar.charAt(Math.floor(Math.random() * randomchar.length));
            }
            captcha.innerHTML = uniquechar;
        }

        function validateCaptcha(event) {
            const usr_input = document.getElementById("submit").value;
            let keyElement = document.getElementById("key");
            keyElement.innerHTML = "";
            keyElement.style.color = "";
            if (usr_input === "") {
                keyElement.innerHTML = "Please enter captcha";
                keyElement.style.color = "red";
                generate();
                return false;
            } else if (usr_input != captcha.innerHTML) {
                keyElement.innerHTML = "Not Matched";
                keyElement.style.color = "red";
                generate();
                return false;
            } else {
                keyElement.innerHTML = "Matched";
                keyElement.style.color = "green";
                return true;
            }
        }

        document.getElementById("myForm").addEventListener("submit", function(event) {
            event.preventDefault();
            if (validateCaptcha(event)) {
                this.submit();
            }
        });

        window.onload = generate;
    </script>

</body>

</html>