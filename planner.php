<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
    exit;
}

$id = $_SESSION['id'];

// Default nutrition targets
//$daily_calories = 2300;
//$daily_carbs = 120;
//$daily_fats = 64;
//$daily_proteins = 38;
$daily_calories = $_SESSION['daily_calories'] ?? 2300;
$daily_carbs = $_SESSION['daily_carbs'] ?? 120;
$daily_fats = $_SESSION['daily_fats'] ?? 64;
$daily_proteins = $_SESSION['daily_proteins'] ?? 38;


// Meals array
$meals = [
    "Breakfast" => [],
    "Lunch" => [],
    "Dinner" => [],
    "Snack" => []
];

// Week days
$weekDays = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

// Get selected day from URL or default to today
$day = isset($_GET['day']) ? ucfirst(strtolower($_GET['day'])) : ucfirst(strtolower(date("l")));

// Fetch meals for selected day (multiple meals per meal_type)
$sql = "SELECT mp.*, r.name, r.calories, r.carbs, r.fats, r.proteins, r.image 
        FROM meal_plan mp
        JOIN recipes r ON mp.recipe_id = r.id
        WHERE mp.day_of_week = '$day'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mealType = ucfirst(strtolower($row['meal_type']));
        if ($mealType && isset($meals[$mealType])) {
            $meals[$mealType][] = $row; // multiple meals
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Planner</title>
    <?php require('component/designlinks.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .main-content { margin-left: 280px; padding: 20px; width: calc(100% - 280px); }
        .meal-card { background: #fff; border-radius: 8px; padding: 10px; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .meal-card img { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; margin-right: 10px; }
        .progress { height: 25px; margin-bottom: 20px; }
        .day-selection .btn { margin-right: 5px; margin-bottom:5px; }
    </style>
</head>
<body>
    <?php require('component/Usernav.php'); ?>

    <div class="main-content">
        <div class="container-fluid">
            <!-- Day selection buttons -->
            

            <h2>Meals for <?php echo $day; ?></h2>
            <div class="progress">
                <div id="calorieProgress" class="progress-bar bg-success" role="progressbar" 
                     style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?php echo $daily_calories; ?>">
                    0%
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?php foreach ($meals as $type => $mealList): ?>
                        <div class="meal-card">
                            <h5><?php echo $type; ?></h5>
                            <?php if (count($mealList) > 0): ?>
                                <?php foreach ($mealList as $meal): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="checkbox" class="meal-check" 
                                               data-calories="<?php echo $meal['calories'] ?? 0; ?>"
                                               data-carbs="<?php echo $meal['carbs'] ?? 0; ?>"
                                               data-fats="<?php echo $meal['fats'] ?? 0; ?>"
                                               data-protein="<?php echo $meal['proteins'] ?? 0; ?>">
                                        <img src="<?php echo !empty($meal['image']) ? 'images/recipe/'.$meal['image'] : 'images/default.jpg'; ?>" 
                                             alt="<?php echo $meal['name']; ?>">
                                        <span><?php echo $meal['name']; ?> - <?php echo $meal['calories']; ?> kcal</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No meal planned for <?php echo $type; ?>.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <h4>Nutrition</h4>
                    <canvas id="nutritionChart"></canvas>
                    <table class="table mt-3">
                        <tr><th>Calories</th><td id="calVal">0</td><td><?php echo $daily_calories; ?> kcal</td></tr>
                        <tr><th>Carbs</th><td id="carbVal">0 g</td><td><?php echo $daily_carbs; ?> g</td></tr>
                        <tr><th>Fats</th><td id="fatVal">0 g</td><td><?php echo $daily_fats; ?> g</td></tr>
                        <tr><th>Protein</th><td id="proVal">0 g</td><td><?php echo $daily_proteins; ?> g</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <script>
        const checks = document.querySelectorAll(".meal-check");
        let cal=0, carbs=0, fats=0, protein=0;

        const ctx = document.getElementById('nutritionChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Carbs', 'Protein', 'Fat'],
                datasets: [{
                    data: [0,0,0],
                    backgroundColor: ['#f39c12','#27ae60','#e74c3c']
                }]
            }
        });

        checks.forEach(chk => {
            chk.addEventListener('change', () => {
                if(chk.checked){
                    cal += parseInt(chk.dataset.calories);
                    carbs += parseInt(chk.dataset.carbs);
                    fats += parseInt(chk.dataset.fats);
                    protein += parseInt(chk.dataset.protein);
                } else {
                    cal -= parseInt(chk.dataset.calories);
                    carbs -= parseInt(chk.dataset.carbs);
                    fats -= parseInt(chk.dataset.fats);
                    protein -= parseInt(chk.dataset.protein);
                }

                document.getElementById('calVal').innerText = cal + " kcal";
                document.getElementById('carbVal').innerText = carbs + " g";
                document.getElementById('fatVal').innerText = fats + " g";
                document.getElementById('proVal').innerText = protein + " g";

                let percent = Math.min(100, (cal/<?php echo $daily_calories; ?>)*100);
                const prog = document.getElementById('calorieProgress');
                prog.style.width = percent + "%";
                prog.innerText = Math.round(percent) + "%";

                chart.data.datasets[0].data = [carbs, protein, fats];
                chart.update();
            });
        });
    </script>
</body>
</html>
