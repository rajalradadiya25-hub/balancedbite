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
    $query = "SELECT * FROM yoga_pose_details WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $benefits = $row['benefits'];
        $how_to_perform = $row['how_to_perform'];
        $description = $row['description'];
    }

    // Handle edit submission
    if (isset($_POST['edit'])) {
        $benefits = $_POST['benefits'];
        $how_to_perform = $_POST['how_to_perform'];
        $description = $_POST['description'];

        $errors = [];
        if (empty($benefits)) {
            $errors['benefits'] = "Benefits field cannot be empty.";
        }
        if (empty($description)) {
            $errors['description'] = "Description field cannot be empty.";
        }
        if (empty($how_to_perform)) {
            $errors['how_to_perform'] = "How to perform field cannot be empty.";
        }
        // If no errors, update the database
        if (empty($errors)) {
            $query = "UPDATE yoga_pose_details SET 
                description='$description', 
                benefits='$benefits' ,
                how_to_perform='$how_to_perform'
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
    <title>Admin - Edit Yoga Pose Details</title>
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
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Edit Yoga Poses Details</h1>

                    <form method="POST" enctype="multipart/form-data">
                        <label class="mt-3">Benefits:</label>
                        <input type="text" name="benefits" id="benefits" value="<?php echo $benefits; ?>">
                        <p class="error"><?php echo $errors['benefits'] ?: ''; ?></p>

                        <label class="mt-3">Description:</label>
                        <textarea name="description" id="description"><?php echo $description; ?></textarea>
                        <p class="error"><?php echo $errors['description'] ?: ''; ?></p>

                        <label class="mt-3">How to perform:</label>
                        <textarea name="how_to_perform" id="how_to_perform"><?php echo $how_to_perform; ?></textarea>
                        <p class="error"><?php echo $errors['how_to_perform'] ?: ''; ?></p>

                        <button type="submit" name="edit" class="btn-warning">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require 'component/Footer.php'; ?>
    </div>
    <script>
        ClassicEditor.create(document.querySelector('#description'));
        ClassicEditor.create(document.querySelector('#how_to_perform'));
    </script>
</body>

</html>