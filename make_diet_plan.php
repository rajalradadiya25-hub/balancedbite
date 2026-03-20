<?php
session_start();
require('db/dbconnect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: Login.php");
    exit();
}



$user_id = $_SESSION['id']; // Ensure this session variable is correctly set

// Handle adding recipes to the database-based meal plan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["recipe_id"], $_POST["meal_type"])) {
    $meal_type = $_POST["meal_type"];
    $recipe_id = $_POST["recipe_id"];

    // Check if the recipe is already added for the user in the selected meal_time
    $check_sql = "SELECT * FROM diet_plan WHERE user_id = ? AND recipe_id = ? AND meal_type = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("iis", $user_id, $recipe_id, $meal_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If not already added, insert the recipe into the diet_plan table
        $insert_sql = "INSERT INTO diet_plan (user_id, recipe_id, meal_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iis", $user_id, $recipe_id, $meal_type);
        if ($stmt->execute()) {
            $_SESSION['alert'] = "Recipe Added Successfully!";
            $_SESSION['alert_type'] = "success";
        }
    }
}

// Handle removing recipes
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove"], $_POST["recipe_id"], $_POST["meal_type"])) {
    $meal_type = $_POST["meal_type"];
    $recipe_id = $_POST["recipe_id"];

    // Remove the recipe from the diet plan table
    $delete_sql = "DELETE FROM diet_plan WHERE user_id = ? AND recipe_id = ? AND meal_type = ?";
    $stmt = $conn->prepare($delete_sql);

    if ($stmt) {
        $stmt->bind_param("iis", $user_id, $recipe_id, $meal_type);
        if ($stmt->execute()) {
            $_SESSION['alert'] = "Recipe Removed Successfully!";
            $_SESSION['alert_type'] = "error";
        } else {
            echo "<script>alert('Error removing recipe: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error preparing statement for deletion.');</script>";
    }
}

// Fetch all recipes
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make My Diet Plan</title>
    <?php require('component/Designlinks.php'); ?>
    <link rel="stylesheet" href="css/make_diet_plan.css">
    <script>
        function searchRecipes() {
            let input = document.getElementById("search").value.toLowerCase();
            let recipes = document.querySelectorAll(".recipe-table tbody tr");

            recipes.forEach(recipe => {
                let name = recipe.querySelector("a").innerText.toLowerCase();
                recipe.style.display = name.includes(input) ? "table-row" : "none";
            });
        }
    </script>
</head>

<body>
    <?php require('component/usernav.php'); ?>
    <div class="main-content">
        <div class="container">
            <h1>Make My Diet Plan</h1>
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="text" id="search" class="search-box" placeholder="Search recipes..." onkeyup="searchRecipes()">
            </div>
            <div class="recipe-list">
                <div class="recipes-header">
                    <h2>Available Recipes</h2>
                    <a href="view_diet_plan.php" class="view-diet-btn">View My Diet Plan</a>
                </div>
                <table class="recipe-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recipe Name</th>
                            <th>Calories</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><a href="recipe.php?name=<?= urlencode($row['name']); ?>&calories=<?= $row['calories']; ?>">
                                        <?= $row["name"] ?>
                                    </a></td>
                                <td><?= $row["calories"] ?> kcal</td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="recipe_id" value="<?= $row["id"] ?>">
                                        <select name="meal_type">
                                            <option value="breakfast">Breakfast</option>
                                            <option value="lunch">Lunch</option>
                                            <option value="dinner">Dinner</option>
                                            <option value="snack">Snack</option>
                                        </select>
                                        <button type="submit">Add</button>
                                    </form>
                                </td>
                            </tr>
                        <?php $count++;
                        endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="meal-plan mt-5">
                <h2>Your Meal Plan</h2>
                <?php
                $meal_types = ["breakfast", "lunch", "dinner", "snack"];
                foreach ($meal_types as $meal_type):
                    echo "<div class='meal-section'><h4>" . ucfirst($meal_type) . "</h4><ul class='untitled-list'>";

                    // Fetch meal plan for the user
                    $sql = "SELECT r.id, r.name FROM diet_plan dp 
                            JOIN recipes r ON dp.recipe_id = r.id 
                            WHERE dp.user_id = ? AND dp.meal_type = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("is", $user_id, $meal_type);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<li class='list'>" . htmlspecialchars($row['name']) . " 
                                    <form method='POST'>
                                        <input type='hidden' name='recipe_id' value='{$row['id']}'>
                                        <input type='hidden' name='meal_type' value='$meal_type'>
                                        <button type='submit' name='remove'>Remove</button>
                                    </form>
                                </li>";
                            }
                        } else {
                            echo "<li>No recipes added yet.</li>";
                        }
                    } else {
                        die("Error fetching meal plan: " . $conn->error);
                    }
                    echo "</ul></div>";
                endforeach;
                ?>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
    <script>
        // Check if there's an alert message in PHP session
        <?php if (isset($_SESSION['alert'])) : ?>
            Swal.fire({
                icon: "<?php echo $_SESSION['alert_type']; ?>", // success or error
                title: "<?php echo $_SESSION['alert']; ?>",
                showConfirmButton: false,
                timer: 1500 // Auto-close after 1.5 seconds
            });

            <?php
            // Clear the alert after displaying it
            unset($_SESSION['alert']);
            unset($_SESSION['alert_type']);
            ?>
        <?php endif; ?>
    </script>

</body>

</html>