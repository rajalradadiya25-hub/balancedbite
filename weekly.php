<?php
session_start();
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0");
$id = $_SESSION['id'];

// Use a prepared statement to prevent SQL injection
$user_sql = "SELECT * FROM user_info WHERE uid = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User data not found.");
}

// User dietary preferences
$goal = $user['goal'];
$gender = $user['gender'];
$height = $user['height']; // in cm
$weight = $user['weight']; // in kg
$age = $user['age'];
$bodyfat = $user['bodyfat'];
$activity_level = $user['activity_level']??'';
$food_category = $user['foodcat'] ?? ''; // Ensure this column exists

// 1. Calculate BMR (Basal Metabolic Rate)
if ($gender == "male") {
    $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
} else {
    $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
}

// 2. Adjust BMR based on activity level
$activity_multipliers = [
    "Sedentary" => 1.2,
    "Lightly Active" => 1.375,
    "Moderately Active" => 1.55,
    "Very Active" => 1.725
];

//$maintenance_calories = $bmr * ($activity_multipliers[$activity_level] ?: 1.2);
$maintenance_calories = $bmr * ($activity_multipliers[$activity_level] ?? 1.2);


// 3. Adjust Target Calories Based on Goal
if ($goal == "build muscle") {
    $calories = $maintenance_calories + 500;
} elseif ($goal == "lose fat") {
    $calories = max($maintenance_calories - 500, 1200);
} else {
    $calories = $maintenance_calories;
}

// 4. Macronutrient Ratios Based on Goal & Body Fat
$macros = [
    "build muscle" => [
        "low" => [0.30, 0.50, 0.20],
        "medium" => [0.35, 0.45, 0.20],
        "high" => [0.40, 0.40, 0.20]
    ],
    "lose fat" => [
        "low" => [0.40, 0.30, 0.30],
        "medium" => [0.45, 0.25, 0.30],
        "high" => [0.50, 0.20, 0.30]
    ],
    "maintain weight" => [0.30, 0.40, 0.30]
];
echo '<pre>';
print_r($macros[$goal][$bodyfat] ?? $macros[$goal] ?? $macros["maintain weight"]);
echo '</pre>';
$bodyfat = strtolower(trim($user['bodyfat']));
$valid_bodyfat = ['low', 'medium', 'high'];
if (!in_array($bodyfat, $valid_bodyfat)) {
    $bodyfat = 'medium';
}

//list($protein_ratio, $carb_ratio, $fat_ratio) = $macros[$goal][$bodyfat] ?: $macros["maintain weight"];
// 4. Macronutrient Ratios Based on Goal & Body Fat (SAFE)
$protein_ratio = 0.3;
$carb_ratio = 0.4;
$fat_ratio = 0.3;

$macro_source = null;

if (isset($macros[$goal][$bodyfat]) && is_array($macros[$goal][$bodyfat]) && count($macros[$goal][$bodyfat]) === 3) {
    list($protein_ratio, $carb_ratio, $fat_ratio) = $macros[$goal][$bodyfat];
    $macro_source = 'goal+bodyfat';
} elseif (isset($macros[$goal]) && is_array($macros[$goal]) && count($macros[$goal]) === 3) {
    list($protein_ratio, $carb_ratio, $fat_ratio) = $macros[$goal];
    $macro_source = 'goal fallback';
} elseif (isset($macros['maintain weight']) && is_array($macros['maintain weight']) && count($macros['maintain weight']) === 3) {
    list($protein_ratio, $carb_ratio, $fat_ratio) = $macros['maintain weight'];
    $macro_source = 'maintain fallback';
} else {
    die("❌ No valid macronutrient ratio found.");
}

// 5. Macronutrient Calculation
$protein = round(($calories * $protein_ratio) / 4);
$carbs = round(($calories * $carb_ratio) / 4);
$fats = round(($calories * $fat_ratio) / 9);

// 6. Meal Distribution Ratios
$meal_ratios = ["Breakfast" => 0.25, "Lunch" => 0.30, "Dinner" => 0.30, "Snacks" => 0.15];

// 7. Fetch Recipes Excluding User's Food Preferences
$sql = "SELECT * FROM recipes WHERE foodcat != ? ORDER BY RAND()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $food_category);
$stmt->execute();
$result = $stmt->get_result();

$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[$row['meal']][] = $row;
}
$stmt->close();

// 8. Define Meal Plan Variables
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$meal_types = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];

$meal_calories = [
    "Breakfast" => round($calories * 0.25),
    "Lunch" => round($calories * 0.30),
    "Dinner" => round($calories * 0.30),
    "Snack" => round($calories * 0.15)
];

$meal_macros = [
    "Breakfast" => [
        "carbs" => round($carbs * 0.25),
        "fats" => round($fats * 0.25),
        "proteins" => round($protein * 0.25)
    ],
    "Lunch" => [
        "carbs" => round($carbs * 0.30),
        "fats" => round($fats * 0.30),
        "proteins" => round($protein * 0.30)
    ],
    "Dinner" => [
        "carbs" => round($carbs * 0.30),
        "fats" => round($fats * 0.30),
        "proteins" => round($protein * 0.30)
    ],
    "Snack" => [
        "carbs" => round($carbs * 0.15),
        "fats" => round($fats * 0.15),
        "proteins" => round($protein * 0.15)
    ]
];

$weekly_meal_plan = [];

// 9. Generate Meal Plan
foreach ($days as $day) {
    $daily_meal_plan = ['Breakfast' => [], 'Lunch' => [], 'Dinner' => [], 'Snack' => []];

    foreach ($meal_types as $meal)
    {
        if (!isset($recipes[$meal])) continue;

        shuffle($recipes[$meal]);

        $meal_cal_limit = $meal_calories[$meal];
        $meal_carb_limit = $meal_macros[$meal]['carbs'];
        $meal_fat_limit = $meal_macros[$meal]['fats'];
        $meal_protein_limit = $meal_macros[$meal]['proteins'];

        foreach ($recipes[$meal] as $recipe) {
            if (
                ($meal_cal_limit - $recipe['calories'] >= 0) &&
                ($meal_carb_limit - $recipe['carbs'] >= 0) &&
                ($meal_fat_limit - $recipe['fats'] >= 0) &&
                ($meal_protein_limit - $recipe['proteins'] >= 0)
            ) {
                $daily_meal_plan[$meal][] = $recipe;
                $meal_cal_limit -= $recipe['calories'];
                $meal_carb_limit -= $recipe['carbs'];
                $meal_fat_limit -= $recipe['fats'];
                $meal_protein_limit -= $recipe['proteins'];
            }
        }

        //if (empty($daily_meal_plan[$meal]) && !empty($recipes[$meal])) {
            //$daily_meal_plan[$meal][] = reset($recipes[$meal]);
        if (empty($daily_meal_plan[$meal]) && !empty($recipes[$meal])) {
             $first_recipe = reset($recipes[$meal]);  
                if ($first_recipe) {
                 $daily_meal_plan[$meal][] = $first_recipe;
    }
}
    
        
    }

    $weekly_meal_plan[$day] = $daily_meal_plan;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('component/Designlinks.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <!--Include required libraries-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!--Include jsPDF and autoTable-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <title>7-Day Meal Plan</title>
    <link rel="stylesheet" href="css/weekly.css">
</head>

<body>
    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="container">
            <div class="header">
                <h1 class="fw-bold">7-Day Meal Plan for <?php echo htmlspecialchars($user['username']); ?></h1>
                <label class="switch1">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider1 round"></span>
                </label>
            </div>
            <button class="btn1" onclick="downloadPDF()"><i class="fas fa-file-pdf"></i> Download PDF</button>
            <p class="mt-3">
                Goal: <strong><?php echo htmlspecialchars($user['goal']); ?></strong>| Adjusted Calories: <strong><?php echo round($calories); ?> kcal</strong></p>

            <div class="meal-grid">
                <?php foreach ($weekly_meal_plan as $day => $meals) { ?>
                    <div class="meal-day">
                        <h3><?php echo $day; ?></h3>
                        <div class="meal-container">
                            <?php
                            $day_calories = 0;
                            $day_carbs = 0;
                            $day_fats = 0;
                            $day_proteins = 0;

                            foreach ($meal_types as $meal) {
                                if (!empty($meals[$meal])) {
                                    foreach ($meals[$meal] as $recipe) {
                                        $day_calories += $recipe['calories'];
                                        $day_carbs += $recipe['carbs'];
                                        $day_fats += $recipe['fats'];
                                        $day_proteins += $recipe['proteins'];
                            ?>
                                        <div class="meal-card">
                                            <h4><?php echo $meal; ?></h4>
                                            <img src="images/recipe/<?php echo $recipe['image']; ?>" class="recipe-img" onerror="this.style.display='none'">
                                            <h5 class="name_heading"><a href="recipe.php?name=<?php echo $recipe['name']; ?>&&calories=<?php echo $recipe['calories']; ?>"><?php echo $recipe['name']; ?></a></h5>
                                            <p><strong>Calories:</strong> <?php echo $recipe['calories']; ?> kcal</p>
                                            <p><strong>Carbs:</strong> <?php echo $recipe['carbs']; ?> g</p>
                                            <p><strong>Fats:</strong> <?php echo $recipe['fats']; ?> g</p>
                                            <p><strong>Proteins:</strong> <?php echo $recipe['proteins']; ?> g</p>
                                        </div>
                            <?php
                                    }
                                }
                            } ?>
                        </div>
                        <div class="daily-summary">
                            <p><strong>Total for <?php echo $day; ?>:</strong></p>
                            <p>Calories: <?php echo $day_calories; ?> kcal</p>
                            <p>Carbs: <?php echo $day_carbs; ?> g</p>
                            <p>Fats: <?php echo $day_fats; ?> g</p>
                            <p>Proteins: <?php echo $day_proteins; ?> g</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>
    <script>
        document.getElementById('darkModeToggle').addEventListener('change', function() {
            document.body.classList.toggle('dark-mode', this.checked);
            localStorage.setItem('darkMode', this.checked);
        });
        window.onload = function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            document.getElementById('darkModeToggle').checked = darkMode;
            document.body.classList.toggle('dark-mode', darkMode);
        };

    
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF("p", "mm", "a4");

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    // --- 🔰 Header Section ---
    const logoUrl = "images/contact-heading.jpg"; // <- apna logo path yaha daalein
    const title = "BalancedBite Nutrition Plan"; // ✅ emoji hata diya (safe text)
    const subtitle = "Generated via BalancedBite Nutrition Platform";

    // Load logo image first (asynchronously)
    const img = new Image();
    img.src = logoUrl;
    img.onload = function () {
        // Logo (top-left)
        doc.addImage(img, "PNG", 10, 8, 25, 25);

        // Title
        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.setTextColor(0, 0, 0);
        doc.text(title, 40, 18);

        // Subtitle
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text(subtitle, 40, 25);

        // Line below header
        doc.setDrawColor(0, 128, 0);
        doc.line(10, 33, pageWidth - 10, 33);

        let y = 40; // Start content below header

        // --- 🗓️ Meal Plan Content ---
        doc.setFont("helvetica", "bold");
        doc.setFontSize(18);
        doc.setTextColor(0, 0, 0);
        doc.text("7-Day Personalized Meal Plan", 14, y);
        y += 10;

        document.querySelectorAll('.meal-day').forEach((dayDiv) => {
            let dayName = dayDiv.querySelector('h3').innerText;
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(0, 100, 0);
            doc.text(dayName, 14, y);
            y += 6;

            let data = [];
            let headers = ["Meal", "Recipe", "Calories", "Carbs", "Fats", "Proteins"];

            let totalCalories = 0, totalCarbs = 0, totalFats = 0, totalProteins = 0;

            dayDiv.querySelectorAll('.meal-card').forEach((card) => {
                let mealType = card.querySelector('h4').innerText;
                let recipeName = card.querySelector('h5').innerText;
                let calories = parseFloat(card.querySelector('p:nth-of-type(1)').innerText.split(": ")[1]) || 0;
                let carbs = parseFloat(card.querySelector('p:nth-of-type(2)').innerText.split(": ")[1]) || 0;
                let fats = parseFloat(card.querySelector('p:nth-of-type(3)').innerText.split(": ")[1]) || 0;
                let proteins = parseFloat(card.querySelector('p:nth-of-type(4)').innerText.split(": ")[1]) || 0;

                data.push([mealType, recipeName, calories, carbs, fats, proteins]);

                totalCalories += calories;
                totalCarbs += carbs;
                totalFats += fats;
                totalProteins += proteins;
            });

            data.push(["Total", "", totalCalories, totalCarbs, totalFats, totalProteins]);

            doc.autoTable({
                startY: y,
                head: [headers],
                body: data,
                theme: "grid",
                styles: { fontSize: 9 },
                headStyles: { fillColor: [46, 139, 87], textColor: [255, 255, 255] },
                margin: { left: 14, right: 14 },
                didDrawPage: function (data) {
                    // --- ⚙️ Footer Section ---
                    const pageCount = doc.internal.getNumberOfPages();
                    const currentPage = doc.internal.getCurrentPageInfo().pageNumber;

                    // Footer Line
                    doc.setDrawColor(200);
                    doc.line(10, pageHeight - 15, pageWidth - 10, pageHeight - 15);

                    doc.setFontSize(9);
                    doc.setTextColor(100);
                    doc.text("© 2025 BalancedBite.in | Confidential - For personal health use only", 14, pageHeight - 10);
                    doc.text(`Page ${currentPage} of ${pageCount}`, pageWidth - 40, pageHeight - 10);
                }
            });

            y = doc.autoTable.previous.finalY + 10;
        });

        doc.save("7-Day-Meal-Plan.pdf");
    };
}
    </script>
</body>

</html>