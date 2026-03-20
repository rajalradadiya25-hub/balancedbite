<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

// Fetch meal plan record
if ($_GET['id']) {
    $id = $_GET['id'];
    $query = "SELECT * FROM yoga_poses WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $description = $row['description'];
        $difficulty = $row['difficulty'];
        $image_url = $row['image'];
    }

    // Handle edit submission
    if (isset($_POST['edit'])) {
        $name = $row['name'];
        $description = $row['description'];
        $difficulty = $row['difficulty'];

        $errors = [];

        // Validate Meal
        $name = trim($_POST['name']);
        if (empty($name)) {
            $errors['name'] = "Name cannot be empty.";
        }

        // Validate Instructions
        $description = trim($_POST['description']);
        if (empty($description)) {
            $errors['description'] = "Description field cannot be empty.";
        }
        $difficulty = trim($_POST['difficulty']);
        if (empty($difficulty)) {
            $errors['difficulty'] = "Difficulty field cannot be empty.";
        }

        // Validate Image Upload
        if ($_FILES['image']['name']) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $errors['image'] = "Only JPG, JPEG, and PNG images are allowed.";
            }
        }
        // If no errors, update the database
        if (empty($errors)) {
            // Handle image upload and delete old image
            if ($_FILES['image']['name']) {
                if ($image_url) {
                    unlink($image_url); // Delete old image file
                }
                $target_dir = "images/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image_url = $target_file;
            }

            $query = "UPDATE yoga_poses SET 
                name='$name', 
                image='$image_url' ,
                description='$description', 
                difficulty='$difficulty' 
              WHERE id='$id'";
            mysqli_query($conn, $query);
            header("location:admin_yoga_session.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Yoga Pose</title>
    <?php require 'component/Designlinks.php'; ?>
    <link rel="stylesheet" href="css/general.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
            transition: 0.3s;
        }

        .col-8 {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 128, 0, 0.2);
            animation: slideIn 0.8s ease-in-out;
        }

        .image-preview {
            width: 100%;
            height: 400px;
            border: 2px dashed rgb(0, 128, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-radius: 10px;
            overflow: hidden;
            background: #fafafa;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        label {
            font-weight: bold;
            color: rgb(0, 128, 0);
        }

        .btn-warning {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-warning:hover {
            background: darkgreen;
            transform: scale(1.05);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <?php require 'component/adminnav.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Edit Yoga Poses</h1>

                    <form method="POST" enctype="multipart/form-data">
                        <label class="mt-3">Yoga Pose Image:</label>
                        <div class="image-preview" id="imagePreview">
                            <?php if ($image_url) {
                                echo "<img src='images/$image_url' id='previewImg'>";
                            } else {
                                echo "<p>No Image Selected</p>";
                            } ?>
                        </div>
                        <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage(event)">
                        <p class="error"><?php echo $errors['image'] ?: ''; ?></p>
                        
                        <label class="mt-3">Name:</label>
                        <input type="text" name="name" value="<?php echo $name; ?>">
                        <p class="error"><?php echo $errors['name'] ?: ''; ?></p>
                        
                        <label class="mt-3">Description:</label>
                        <textarea name="description" id="description"><?php echo $description; ?></textarea>
                        <p class="error"><?php echo $errors['description'] ?: ''; ?></p>
                        
                        <label class="mt-3">Difficulty:</label>
                        <input type="text" name="difficulty" value="<?php echo $difficulty; ?>">
                        <p class="error"><?php echo $errors['difficulty'] ?: ''; ?></p>

                        <button type="submit" name="edit" class="btn-warning">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require 'component/Footer.php'; ?>
    </div>
    <script>
        ClassicEditor.create(document.querySelector('#description'));
    </script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.innerHTML = '<img src="' + reader.result + '" id="previewImg">';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>