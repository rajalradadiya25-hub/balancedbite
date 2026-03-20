<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    header("location:admin_login.php");
    exit;
}

// Fetch FAQ record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM faqs WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $category = $row['category'];
        $question = $row['question'];
        $answer = $row['answer'];
    }

    // Handle edit submission
    if (isset($_POST['edit'])) {
        $category = trim($_POST['category']);
        $question = trim($_POST['question']);
        $answer = trim($_POST['answer']);

        $errors = [];

        if (empty($category)) {
            $errors['category'] = "Please select a category.";
        }
        if (empty($question)) {
            $errors['question'] = "Question cannot be empty.";
        }
        if (empty($answer)) {
            $errors['answer'] = "Answer cannot be empty.";
        }

        // If no errors, update the database
        if (empty($errors)) {
            $query = "UPDATE faqs SET category='$category', question='$question', answer='$answer' WHERE id='$id'";
            mysqli_query($conn, $query);
            header("location:admin_faq.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit FAQ</title>
    <?php require 'component/Designlinks.php'; ?>
    <link rel="stylesheet" href="css/general.css">
    <style>
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

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .btnsave {
            width: 100%;
            padding: 10px;
            background: rgb(0, 128, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btnsave:hover {
            background: darkgreen;
            transform: scale(1.05);
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInAnimation 0.6s ease-in-out forwards;
        }

        @keyframes fadeInAnimation {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <?php require 'component/adminnav.php'; ?>
    <div class="main-content">
        <div class="container fade-in mt-5">
            <div class="row">
                <div class="col-8">
                    <h1 style="text-align: center; color: rgb(0, 128, 0); margin-bottom: 20px;">Edit FAQ</h1>
                    <form method="POST">

                        <!-- Category Dropdown -->
                        <label class="mt-3">Category:</label>
                        <select name="category" class="">
                            <option value="">Select Category</option>
                            <option value="Common Concerns" <?php echo ($category === "Common Concerns") ? 'selected' : ''; ?>>Common Concerns</option>
                            <option value="Diet Plans" <?php echo ($category === "Diet Plans") ? 'selected' : ''; ?>>Diet Plans</option>
                            <option value="General Nutrition" <?php echo ($category === "General Nutrition") ? 'selected' : ''; ?>>General Nutrition</option>
                            <option value="Health and Wellness" <?php echo ($category === "Health and Wellness") ? 'selected' : ''; ?>>Health and Wellness</option>
                            <option value="Recipes and Meal Planning" <?php echo ($category === "Recipes and Meal Planning") ? 'selected' : ''; ?>>Recipes and Meal Planning</option>
                            <option value="Special Dietary Needs" <?php echo ($category === "Special Dietary Needs") ? 'selected' : ''; ?>>Special Dietary Needs</option>
                            <option value="Supplements and Vitamins" <?php echo ($category === "Supplements and Vitamins") ? 'selected' : ''; ?>>Supplements and Vitamins</option>
                            <option value="Support and Resources" <?php echo ($category === "Support and Resources") ? 'selected' : ''; ?>>Support and Resources</option>
                        </select>
                        <!-- Common Concerns,Diet Plans,General Nutrition,Health and Wellness,Recipes and Meal Planning,Special Dietary Needs,Supplements and Vitamins,Support and Resources -->
                        <p class="error"><?php echo $errors['category'] ?: ''; ?></p>

                        <!-- Question Input -->
                        <label class="mt-3">Question:</label>
                        <input type="text" name="question" class="" value="<?php echo htmlspecialchars($question); ?>">
                        <p class="error"><?php echo $errors['question'] ?: ''; ?></p>

                        <!-- Answer Input -->
                        <label class="mt-3">Answer:</label>
                        <textarea name="answer" id="answer" class=""><?php echo htmlspecialchars($answer); ?></textarea>
                        <p class="error"><?php echo $errors['answer'] ?: ''; ?></p>

                        <!-- Submit Button -->
                        <button type="submit" name="edit" class="btnsave mt-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <?php require 'component/Footer.php'; ?>
    </div>
    <script>
        ClassicEditor.create(document.querySelector('#answer'));
    </script>
</body>

</html>