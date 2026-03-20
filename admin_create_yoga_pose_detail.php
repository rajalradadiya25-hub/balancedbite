<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header("location:admin_login.php");
    exit;
}

$errors = [];
$benefits = $_POST['benefits'] ?: '';
$how_to_perform = $_POST['how_to_perform'] ?: '';
$description = $_POST['description'] ?: '';
$pose_id = $_POST['pose_id'] ?: '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    // Validate inputs
    if (empty($benefits)) {
        $errors['benefits'] = "Benefits field cannot be empty.";
    }
    if (empty($description)) {
        $errors['description'] = "Description field cannot be empty.";
    }
    if (empty($how_to_perform)) {
        $errors['how_to_perform'] = "How to perform field cannot be empty.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        // Debug: Print the Query
        $query = "INSERT INTO `yoga_pose_details` (`pose_id`, `description`, `benefits`, `how_to_perform`) 
                      VALUES ('$pose_id', '$description', '$benefits', '$how_to_perform')";

        echo "SQL Query: " . $query . "<br>"; // Print the query for debugging

        if (mysqli_query($conn, $query)) {
            header("location:admin_yoga_session.php");
            exit;
        } else {
            $errors['database'] = "Failed to insert data into the database: " . mysqli_error($conn);
        }
    }
}

$selectquery = "SELECT * FROM yoga_poses";
$result = mysqli_query($conn, $selectquery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create Yoga Pose Details</title>
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

        label {
            font-weight: bold;
            color: rgb(0, 128, 0);
        }

        .btn-success {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-success:hover {
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
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Create Yoga Poses Details</h1>
                    <form method="POST">
                        <label class="mt-3">Pose ID:</label>
                        <select name="pose_id" id="pose_id">
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></option>
                            <?php } ?>
                        </select>

                        <label class="mt-3">Benefits:</label>
                        <input type="text" name="benefits" id="benefits" value="<?php echo htmlspecialchars($benefits); ?>">
                        <p class="error"><?php echo $errors['benefits'] ?: ''; ?></p>

                        <label class="mt-3">Description:</label>
                        <textarea name="description" id="description"><?php echo htmlspecialchars($description); ?></textarea>
                        <p class="error"><?php echo $errors['description'] ?: ''; ?></p>

                        <label class="mt-3">How to perform:</label>
                        <textarea name="how_to_perform" id="how_to_perform"><?php echo htmlspecialchars($how_to_perform); ?></textarea>
                        <p class="error"><?php echo $errors['how_to_perform'] ?: ''; ?></p>

                        <button type="submit" name="create" class="btn-success mt-3">Create Yoga Pose Details</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            ClassicEditor.create(document.querySelector('#description'));
            ClassicEditor.create(document.querySelector('#how_to_perform'));
        </script>

        <?php require 'component/Footer.php'; ?>
    </div>
</body>

</html>