<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];

    if ($height && $weight && $gender) {
        // Check if height is in feet and inches format
        if (strpos($height, "'") !== false) {
            // Convert height from feet and inches to inches
            $heightParts = explode("'", $height);
            $feet = intval($heightParts[0]);
            $inches = intval(str_replace('"', '', $heightParts[1]));
            $totalInches = ($feet * 12) + $inches;
        } else {
            // Assume height is in inches
            $totalInches = intval($height);
        }

        // Convert height to meters
        $heightInMeters = $totalInches * 0.0254;

        // Calculate BMI
        $bmi = $weight / ($heightInMeters * $heightInMeters);

        // Round BMI to 2 decimal places
       // $bmi = round($bmi, 2);
       echo round($bmi, 2);

        // Output the result
        echo $bmi;
    } else {
        echo "Height, weight, and gender must be provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
