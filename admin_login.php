<?php
// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
require('db/dbconnect.php');

$login = false;
$showError = false;
$usernameget = "";

// Prefill username if id is passed via GET
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `$dbname`.`users` WHERE username='$username' AND role='admin'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin_admin'] = true;
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'admin';
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            header("Location: admin.php");
            exit;
        } else {
            $showError = "Invalid Credentials";
        }
    } else {
        $showError = "Invalid Credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php require 'component/Designlinks.php' ?>
    <title>Login Page</title>

    <style>
        .error-valid { color: red; }
        .font-size { font-size: 13px; }
        .box {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                        0 6px 20px rgba(0,0,0,0.19),
                        0 12px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateY(-50px);
            animation: appear 2s ease-in-out forwards;
        }
        @keyframes appear { to { opacity: 1; transform: translateY(0); } }
        .box:hover { transform: scale(1.05); }
    </style>
</head>
<body>
<script>
$(document).ready(function() {
    function validateUsername() {
        var username = $("#username").val();
        var regex = /^[a-zA-Z0-9_]{3,15}$/;
        if (username === "") { $("#usernameHelp").text("Please enter username.").css("color", "red"); return false; }
        else if (!regex.test(username)) { $("#usernameHelp").text("Username must be 3-15 characters, letters/numbers/underscores.").css("color","red"); return false; }
        else { $("#usernameHelp").text(""); return true; }
    }

    function validatePassword() {
        var password = $("#password").val();
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (password === "") { $("#passwordHelp").text("Please enter password.").css("color","red"); return false; }
        else if (!regex.test(password)) { $("#passwordHelp").text("Password must be at least 8 chars, include uppercase, lowercase, digit, special char.").css("color","red"); return false; }
        else { $("#passwordHelp").text(""); return true; }
    }

    $("#username, #password").on("change", function() {
        validateUsername(); validatePassword();
    });

    $("#myForm").on("submit", function(event) {
        if (!validateUsername() || !validatePassword()) { event.preventDefault(); }
    });
});
</script>

<?php
if ($login) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are logged in.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if ($showError) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showError . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<div class="container">
    <div class="row m-5 box" style="background-image: url('images/signup.jpg'); background-repeat:no-repeat; background-size:cover; height:629px;">
        <div class="col-md-12">
            <form method="POST" action="" id="myForm">
                <div class="container rounded mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-4 offset-md-4 text-center">
                            <h1><a href="index.php" class="text-decoration-none" style="color: rgb(0,128,0);">BalancedBite</a></h1>
                            <hr>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4 offset-md-4">
                            <div class="mb-3">
                                <h3 class="fw-bold"> Admin Log In</h3>
                                <p>Don't have an account? <a href="admin_register.php" style="color: rgb(0,128,0);">Register</a></p>
                            </div>

                            

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($usernameget); ?>">
                                    <span id="usernameHelp" class="error-valid font-size"></span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <span id="passwordHelp" class="error-valid font-size"></span>
                                </div>
                            </div>

                            <div class="row mt-2 text-center mb-5">
                                <div class="col-md-12">
                                    <button class="btn text-light" style="background-color: rgb(0,128,0); padding: 5px 140px;" type="submit" name="login"><i class="fa-solid fa-arrow-right-to-bracket text-light"></i> Log In</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>  