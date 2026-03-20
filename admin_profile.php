<?php
session_start();
error_reporting(0);
include 'db/dbconnect.php'; // Database connection

$user_id = $_SESSION['id'];
// Fetch user details
$sql = "SELECT * FROM users WHERE id = {$user_id}";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $profile_image1 = $row['profile_image'];
    $password = $row['password'];
}
$profile_image = $profile_image1 ?: 'images/uploads/user_profile.png';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Handle Profile Image Upload
    if (!empty($_FILES['profileImage']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES['profileImage']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG, PNG, and JPEG files are allowed.";
        }

        if ($_FILES['profileImage']['size'] > 2 * 1024 * 1024) {
            $errors[] = "File size must be less than 2MB.";
        }

        if (empty($errors)) {
            $imageName = "profile_" . $user_id . "_" . time() . "." . pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
            $targetDir = "images/uploads/";
            $targetFile = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
                $updateimage = "UPDATE users SET profile_image = '{$targetFile}' WHERE id = {$user_id}";
                $resultimage = mysqli_query($conn, $updateimage);
                $profile_image = $targetFile; // Update the displayed image
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }
    // Handle Password Update
    if (!empty($_POST['currentPassword']) && !empty($_POST['newPassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        if (!password_verify($currentPassword, $password)) {
            $errors[] = "Current password is incorrect.";
        }

        if (strlen($newPassword) < 6) {
            $errors[] = "New password must be at least 6 characters.";
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatepassword = "UPDATE users SET password = '{$hashedPassword}' WHERE id ={$user_id} ";
            $resultpassword = mysqli_query($conn, $updatepassword);
        }
    }

    if (empty($errors)) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['errors'] = $errors;
    }

    header("Location: admin_profile.php");
    exit;
}

$image = $profile_image;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/general.css">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .box {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2),
                0 6px 20px rgba(0, 0, 0, 0.19);
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
            animation: fadeIn 1s ease-in-out forwards;
            transition: transform 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .box:hover {
            transform: scale(1.05);
        }

        .btn-update {
            background-color: rgb(0, 128, 0);
            color: white;
            border: none;
            padding: 8px 16px;
            /* Reduced padding */
            cursor: pointer;
            /* Smaller font size */
            transition: transform 0.3s ease-in-out, background-color 0.3s;
            border-radius: 5px;
        }

        .btn-update:hover {
            transform: scale(1.05);
            background-color: rgb(0, 100, 0);
            /* Slightly darker green on hover */
        }

        #profilePreview {
            transition: transform 0.3s ease-in-out;
        }

        #profilePreview:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <?php require('component/Adminnav.php'); ?>
    <div class="main-content">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 box p-4">
                    <h2 class="text-center fw-bold" style="color: rgb(0, 128, 0);">Update Profile</h2>

                    <?php
                    if (isset($_SESSION['errors'])) {
                        echo "<div class='alert alert-danger'>";
                        foreach ($_SESSION['errors'] as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div>";
                        unset($_SESSION['errors']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                        unset($_SESSION['success']);
                    }
                    ?>

                    <!-- Profile Picture Section -->
                    <div class="text-center">
                        <img id="profilePreview" src="<?php echo $image; ?>" alt="Profile Picture" class="rounded-circle m-3" height="200" width="200">
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profileImage" id="profileImage" class="mt-2" accept="image/*">

                        <!-- Password Update Section -->
                        <div class="mb-3 mt-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="" name="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="" name="newPassword" required>
                        </div>
                        <button type="submit" class="btn-update w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <script>
        const profilePreview = document.getElementById("profilePreview");
        const profileImage = document.getElementById("profileImage");

        profileImage.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>