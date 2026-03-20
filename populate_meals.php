<?php
require('db/dbconnect.php');

// ⚡ Step 1: Purana meal_plan data clear karo
$conn->query("TRUNCATE TABLE meal_plan");

// Step 2: All recipe IDs (1 to 53)
$recipes = range(1, 53);
shuffle($recipes); // Random order

// Agar recipes < 56 hai toh repeat karwa do
while (count($recipes) < 56) {
    $recipes = array_merge($recipes, $recipes); // repeat
}
$recipes = array_slice($recipes, 0, 56); // sirf 56 hi chahiye

// Step 3: Week days & meal types
$weekDays = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
$mealTypes = ["Breakfast","Lunch","Dinner","Snack"];

$i = 0;

// Step 4: Loop through days & meals
foreach ($weekDays as $day) {
    foreach ($mealTypes as $meal) {
        // Har meal ke liye 2 recipes
        for ($j = 0; $j < 2; $j++) {
            $recipe_id = $recipes[$i];

            $sql = "INSERT INTO meal_plan (day_of_week, meal_type, recipe_id) 
                    VALUES ('$day', '$meal', $recipe_id)";
            
            if ($conn->query($sql) === TRUE) {
                echo "✅ Inserted: $day - $meal - Recipe ID $recipe_id <br>";
            } else {
                echo "❌ Error: " . $conn->error . "<br>";
            }

            $i++;
        }
    }
}

echo "<br>🎉 Meal plan reset & repopulated successfully with 56 slots filled!";
?>
