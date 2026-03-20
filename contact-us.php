<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (isset($_POST['submitQuery'])) {
    //to store query in the database
    require('db/dbconnect.php');
    $createtable = "CREATE TABLE IF NOT EXISTS `$dbname`.`contact_querytbl` ( `id` INT NOT NULL AUTO_INCREMENT ,  `name` VARCHAR(50) NOT NULL ,  `email` VARCHAR(55) NOT NULL ,  `issue` VARCHAR(255) NOT NULL ,  `concern` VARCHAR(255) NOT NULL ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB";
    mysqli_query($conn, $createtable);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $issue = $_POST['issue'];
    $que = $_POST['que'];
    $query = "INSERT INTO `contact_querytbl` (`name`, `email`, `issue`, `concern`) VALUES ('$name', '$email', '$issue', '$que')";
    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact-us page</title>

    <?php require("component/Designlinks.php") ?>
</head>

<body>
    <?php require("component/nav.php") ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-5">
                <img src="images/contact-heading.jpg" alt="dish with salad" height="300" width="430">
            </div>
            <div class="col-md-7 mt-5">
                <h1 style="color: rgb(0,128,0);">Get in Touch with Us</h1>
                <h5>"We're here to help! Whether you have a question about our services, or just want to provide feedback, feel free to get in touch with us. Our team is dedicated to providing you with the best possible experience."</h5>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Send Us a Message!</h3>
                <h6>A contact form that users can fill out to send a message directly from the website.</h6>
                <p>Just fill the form below to reach out to us.</p>
                <form action="" method="POST">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo htmlspecialchars($username); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <input type="email" class="form-control" name="email" placeholder="Email"> <br>
                            </div>
                            <div class="col">
                                <select id="issues" class="form-control" name="issue">
                                    <option value="none" selected disabled hidden>Select issue</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Support">Support</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Others">Others</option>
                                </select> <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <textarea name="que" id="que" class="form-control" placeholder="Question/Concern" rows="6" cols="45"></textarea> <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2 d-grid gap-2 offset-md-4">
                                <button class="btn" type="submit" style="background-color: rgb(0,128,0); color:white;" name="submitQuery">SUBMIT YOUR QUERY</button>
                            </div>
                            <div class="col-md-2 d-grid gap-2">
                                <button class="btn" type="reset" style="background-color: rgb(0,128,0); color:white;">RESET</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
        <!-- map -->
        <div class="col-md-12 mt-4">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3718.9052836839087!2d72.84992747518224!3d21.23560418046601!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04f27ee8159e3%3A0xf6defb4d03e81080!2sSutex%20Bank%20College%20of%20Computer%20Applications%20%26%20Science!5e0!3m2!1sen!2sin!4v1689947343353!5m2!1sen!2sin" class="h-100 w-100" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <?php require("component/footer.php") ?>
</body>

</html>