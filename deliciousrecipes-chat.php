<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipeName = $_POST['recipe_name'] ?? '';

    if (!$recipeName) {
        echo "Please enter a recipe name.";
        exit;
    }

    // Fetch recipe from database
    $stmt = $conn->prepare("SELECT ingredients, directions, foodcat FROM recipes WHERE name = ?");
    $stmt->bind_param("s", $recipeName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "No recipe found for '{$recipeName}'.";
        exit;
    }

    $row = $result->fetch_assoc();

    // Ingredients
    if (!empty($row['ingredients'])) {
        echo "<b>📝 Ingredients:</b><br>" . nl2br(htmlspecialchars($row['ingredients'])) . "<br><br>";
    } else {
        echo "<b>📝 Ingredients:</b> Not available.<br><br>";
    }

    // Directions
    if (!empty($row['directions'])) {
        echo "<b>👨‍🍳 Directions:</b><br>" . nl2br(htmlspecialchars($row['directions'])) . "<br><br>";
    } else {
        echo "<b>👨‍🍳 Directions:</b> Not available.<br><br>";
    }

    // Food Category
    if (!empty($row['foodcat'])) {
        echo "<b>🍴 Food Category:</b> " . htmlspecialchars($row['foodcat']) . "<br>";
    } else {
        echo "<b>🍴 Food Category:</b> Not available.<br>";
    }
}
