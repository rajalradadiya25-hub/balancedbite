<?php
session_start();
require('db/dbconnect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Week calculation (agar needed ho)
$start_date = new DateTime('2024-12-30');
$current_date = new DateTime();
$weeks_since_start = max(1, floor($start_date->diff($current_date)->days / 7) + 1);
$current_week_in_cycle = ($weeks_since_start - 1) % 24 + 1;
$current_month_in_cycle = ceil($current_week_in_cycle / 4);

// Fetch meals
$query = "
    SELECT 
        mp.day_of_week, 
        mp.meal_type, 
        r.name AS meal_name, 
        r.calories, 
        r.carbs, 
        r.fats, 
        r.proteins, 
        r.ingredients, 
        r.directions, 
        r.image
    FROM 
        meal_plan mp
    JOIN 
        recipes r ON mp.recipe_id = r.id
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    echo "<div class='row'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='col-md-4 mb-4'>";
        echo "<div class='card meal-card'>";
        echo "<div class='card-header meal-card-header'><h4>{$row['day_of_week']} ({$row['meal_type']}): {$row['meal_name']}</h4></div>";
        echo "<div class='card-body meal-card-body'>";
        echo "<img src='images/meal_plan/{$row['image']}' alt='{$row['meal_name']}' class='meal-image'>";
        echo "<p><strong>Calories:</strong> {$row['calories']}</p>";
        echo "<p><strong>Carbs:</strong> {$row['carbs']}g</p>";
        echo "<p><strong>Fat:</strong> {$row['fats']}g</p>";
        echo "<p><strong>Protein:</strong> {$row['proteins']}g</p>";
        echo "<p><strong>Ingredients:</strong> {$row['ingredients']}</p>";
        echo "<p><strong>Instructions:</strong> {$row['directions']}</p>";

        // Dynamic Buy Now button
        $plan = 'custom';       // yaha plan ka name rakh sakte ho
        $amount = 100;          // example amount
        $discount = 10;         // example discount
        $final = 90;            // final amount after discount
        $method = 'online';     

        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
            // Not logged in → store plan in session, go to signup
            $_SESSION['selected_plan'] = [
                'plan' => $plan,
                'amount' => $amount,
                'discount' => $discount,
                'final' => $final,
                'method' => $method
            ];
            $buyLink = "signup.php";
        } else {
            // Logged in → direct payment
            $buyLink = "api/payment.php?plan=$plan&amount=$amount&discount=$discount&final=$final&method=$method";
        }

        echo "<a href='$buyLink' class='btn btn-success mt-2'>Buy Now</a>";

        echo "</div></div></div>";
    }
    echo "</div>";
} else {
    echo "<p class='text-center'>No meal plans found.</p>";
}

$conn->close();
?>
