<?php
require 'db/dbconnect.php'; // Include database connection
$showError=false;
$showUserError=false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

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
            $role = "admin";
            $sql = "INSERT INTO `$dbname`.`users` (`username`,`password`, `role`) VALUES ('$username','$hash', '$role')";
            $result = mysqli_query($conn, $sql);
            if ($password) {
                //$showAlert = true;
                header("location:admin_login.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Register</title>
    <link rel="stylesheet" href="css/general.css">
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
</head>

<body>
    <script>
        $(document).ready(function() {
            // Call validation functions on input
            $("#username").on("change", function() {
                validateUsername();
            });

            $("#password").on("change", function() {
                validatePassword();
            });

            $("#myForm").on("submit", function(event) {
                var cptcha = document.getElementById("submit").val();
                alert(cptcha);
                if (!validatePassword()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
                if (!validateUsername()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
                // if()
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
                                            <p>Already have an account? <a href="admin_login.php" style="color: rgb(0,128,0);">Log In</a></p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="username">Username</label>
                                            <input type="text" maxlength="11"  id="username" name="username">
                                            <span id="usernameHelp" class="error-valid font-size"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="password">Password</label>
                                            <input type="password" maxlength="11"  id="password" name="password">
                                            <span id="passwordHelp" class="error-valid font-size"></span>
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

</body>

</html>