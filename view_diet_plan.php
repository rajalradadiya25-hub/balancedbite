<?php
session_start();
require('db/dbconnect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
$user_id = $_SESSION["id"] ?: null;

function getRecipesByMealTime($conn, $user_id, $meal_type)
{
    $sql = "SELECT r.id, r.name, r.calories, r.carbs, r.proteins, r.fats, r.ingredients, r.directions, r.image 
            FROM diet_plan dp
            JOIN recipes r ON dp.recipe_id = r.id
            WHERE dp.user_id = ? AND dp.meal_type = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $meal_type);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Diet Plan</title>
    <?php require('component/Designlinks.php'); ?>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f4f6f9, #e3eaf3);
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .heading {
            text-align: center;
            color: #007a3d;
            font-weight: 600;
            margin-top: 30px;
        }

        .meal-section {
            margin-bottom: 25px;
            margin-top: 30px;
            padding: 20px;
            border-radius: 12px;
            background: rgba(0, 128, 0, 0.08);
            box-shadow: 0 2px 8px rgba(0, 128, 0, 0.2);
        }

        .meal-section h3 {
            font-size: 22px;
            color: #007a3d;
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .meal-section h3::before {
            content: "🥗";
            margin-right: 10px;
            font-size: 24px;
        }

        .recipe-card {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.85);
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 15px;
        }

        .recipe-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 128, 0, 0.25);
        }

        .recipe-img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #007a3d;
        }

        .recipe-details {
            flex: 1;
        }

        .recipe-details h4 {
            margin: 0;
            color: #007a3d;
            font-size: 18px;
            font-weight: bold;
        }

        .recipe-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #444;
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            color: #777;
            padding: 25px;
        }

        .create-plan-btn {
            display: block;
            width: 250px;
            text-align: center;
            margin: 30px auto;
            background: #007a3d;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
            font-size: 16px;
        }

        .create-plan-btn:hover {
            background: #005f2c;
            color:white;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <?php require('component/Usernav.php'); ?>

        <div class="container">
            <h1 class="heading">Your Personalized Diet Plan</h1>

            <?php
            $meal_types = ["breakfast", "lunch", "dinner", "snack"];
            $hasMealPlan = false;

            foreach ($meal_types as $meal_type):
                $recipes = getRecipesByMealTime($conn, $user_id, $meal_type);
                if ($recipes->num_rows > 0) {
                    $hasMealPlan = true;
            ?>
                    <div class="meal-section">
                        <h3><?= ucfirst($meal_type) ?></h3>
                        <?php while ($recipe = $recipes->fetch_assoc()) { ?>
                            <div class="recipe-card">
                                <img src="images/recipe/<?= htmlspecialchars($recipe['image']) ?>" class="recipe-img" alt="<?= htmlspecialchars($recipe['name']) ?>">
                                <div class="recipe-details">
                                    <h4><?= htmlspecialchars($recipe['name']) ?></h4>
                                    <p><strong>Calories:</strong> <?= $recipe['calories'] ?> kcal</p>
                                    <p><strong>Carbs:</strong> <?= $recipe['carbs'] ?>g | <strong>Proteins:</strong> <?= $recipe['proteins'] ?>g | <strong>Fats:</strong> <?= $recipe['fats'] ?>g</p>
                                    <p><strong>Ingredients:</strong> <?= htmlspecialchars($recipe['ingredients']??'') ?></p>
                                    <p><strong>Directions:</strong> <?= htmlspecialchars($recipe['directions']??'') ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
            <?php
                }
            endforeach;

            if (!$hasMealPlan) {
                echo "<p class='empty-message'>No meal plan created yet.</p>";
                echo "<a href='make_diet_plan.php' class='create-plan-btn'>Create One Now</a>";
            }
            ?>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
</body>

</html>