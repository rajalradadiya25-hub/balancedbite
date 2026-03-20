<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();
$calorieNeeds = 0;
$carbs = 0;
$fats = 0;
$proteins = 0;
$mealPlan = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $activity = $_POST['activity'];
    $goal = $_POST['goal'];

    // BMR calculation
    if ($gender == 'male') {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
    } else {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
    }

    // Activity multiplier
    switch ($activity) {
        case 'sedentary':
            $activityMultiplier = 1.2;
            break;
        case 'lightly active':
            $activityMultiplier = 1.375;
            break;
        case 'moderately active':
            $activityMultiplier = 1.55;
            break;
        case 'active':
            $activityMultiplier = 1.725;
            break;
        case 'very active':
            $activityMultiplier = 1.9;
            break;
    }

    $calorieNeeds = $bmr * $activityMultiplier;

    // Adjust calorie needs based on goal
    switch ($goal) {
        case 'lose weight':
            $calorieNeeds -= 500;  // Reduce 500 calories per day
            break;
        case 'build muscle':
            $calorieNeeds += 500;  // Add 500 calories per day
            break;
    }

    // Calculate macronutrients
    $carbs = $calorieNeeds * 0.50 / 4;  // 50% of calories from carbs, 4 calories per gram
    $fats = $calorieNeeds * 0.30 / 9;   // 30% of calories from fats, 9 calories per gram
    $proteins = $calorieNeeds * 0.20 / 4; // 20% of calories from protein, 4 calories per gram

    // Distribute calories across meals
    $breakfastCalories = round($calorieNeeds * 0.25); // 25% of total calories 
    $lunchCalories = round($calorieNeeds * 0.35); // 35% of total calories 
    $dinnerCalories = round($calorieNeeds * 0.25); // 25% of total calories 
    $snackCalories = round($calorieNeeds * 0.15); // 10% of total calories

    // Get recipes from the database
    $sql = "SELECT * FROM recipes";
    $result = $conn->query($sql);
    $recipes = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    } else {
        echo "No recipes found";
    }

    // Function to get meal recipes based on target calories
    function getMealRecipes($recipes, $targetCalories)
    {
        $selectedRecipes = [];
        $totalCalories = 0;

        foreach ($recipes as $recipe) {
            if ($totalCalories + $recipe['calories'] <= $targetCalories) {
                $selectedRecipes[] = $recipe;
                $totalCalories += $recipe['calories'];
            }
        }

        return $selectedRecipes;
    }

    // Group recipes by meal and match calories
    $mealPlan = [
        'Breakfast' => getMealRecipes(array_filter($recipes, function ($recipe) {
            return $recipe['meal'] === 'Breakfast';
        }), $breakfastCalories),
        'Lunch' => getMealRecipes(array_filter($recipes, function ($recipe) {
            return $recipe['meal'] === 'Lunch';
        }), $lunchCalories),
        'Dinner' => getMealRecipes(array_filter($recipes, function ($recipe) {
            return $recipe['meal'] === 'Dinner';
        }), $dinnerCalories),
        'Snacks' => getMealRecipes(array_filter($recipes, function ($recipe) {
            return $recipe['meal'] === 'Snack';
        }), $snackCalories)
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calories calculator page</title>
    <?php require("component/designlinks.php") ?>
    <style>
        .link {
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php require("component/nav.php") ?>
    <form method="POST" action="">
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row mt-2">
                <div class="row">
                    <div class="col-md-8 offset-md-1">
                        <h1 class="fw-bold" style="color: rgb(0,128,0);">Calculate Your Daily Calorie Needs</h1>
                        <div>
                            <p>Discover your perfect Calorie and macronutrient targets with our easy-to-use calculator.</p>
                            <p>Struggling to set realistic weight goals? Our tool simplifies the process, guiding you towards a customized nutrition plan. Combine it with our Automatic Meal Planner for delicious, goal-aligned recipes, and make starting your health journey a snap.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <img src="images/calorie-calc.jpg" alt="" height="200" width="200">
                    </div>
                </div>
                <div class="col-md-6 offset-md-3">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="height">Height (cm):</label>
                            <input type="number" id="height" name="height" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="weight">Weight (kg):</label>
                            <input type="number" id="weight" name="weight" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="activity">Activity Level:</label>
                            <select id="activity" name="activity" class="form-control">
                                <option value="sedentary">Sedentary</option>
                                <option value="lightly active">Lightly active</option>
                                <option value="moderately active">Moderately active</option>
                                <option value="active">Active</option>
                                <option value="very active">Very active</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="goal">Goal:</label>
                            <select id="goal" name="goal" class="form-control">
                                <option value="lose weight">Lose Weight</option>
                                <option value="maintain weight">Maintain Weight</option>
                                <option value="build muscle">Build Muscle</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button class="btn text-light" style="background-color: rgb(0,128,0);" type="submit" name="signup">Calculate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- display calories with meal plan -->
    <div class="container">
        <div class="row">
            <div class="col-md-5 offset-md-1">
                <?php
                echo "<h2>Total Calories: " . number_format($calorieNeeds, 2) . "</h2>";
                echo "<p>Carbs: " . number_format($carbs, 2) . "g</p>";
                echo "<p>Fats: " . number_format($fats, 2) . "g</p>";
                echo "<p>Proteins: " . number_format($proteins, 2) . "g</p>";

                foreach ($mealPlan as $meal => $recipes) {
                ?>
                    <h2>
                        <?php echo $meal;
                        $mealCalories = 0;
                        foreach ($recipes as $recipe) {
                            $mealCalories += $recipe['calories'];
                        ?>
                    </h2>
                    <div class="row">
                        <div class="col-2">
                            <img src="images/recipe/<?php echo $recipe['image']; ?>" alt="<?php echo $recipe['name']; ?>" height="50" width="50">
                        </div>
                        <div class="col-10">
                            <h5><?php echo "<a href='recipe.php?id={$recipe['id']}' class='text-dark link'>" . $recipe['name'] . "</a>"; ?></h5>
                            <p><?php echo $recipe['calories'] . " " . "calories"; ?></p>
                        </div>
                    </div>
            <?php  }
                        echo "<p>Total for $meal: $mealCalories Calories</p>";
                    } ?>
            </div>
            <div class="col-md-5">
                <h2>Ready for more?</h2>
                <p>With a free account, you can customize your preferences, track your intake, create recipes, and much more.</p>
                <div class="d-grid gap-2">
                    <button class="btn" style="background-color: rgb(0,128,0);" type="submit" name="signup"><a href="signup.php" class="text-decoration-none text-light">Register</a></button>
                </div>
                <div class="text-center mt-5">
                    <img src="images/calorie-signup.jpg" alt="" height="400" width="300">
                </div>
            </div>
        </div>

    </div>
    <?php require("component/footer.php") ?>
</body>

</html>