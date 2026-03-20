<?php
session_start();
require('db/dbconnect.php');

// agar login nahi hai to Login.php pe bhej do
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
    exit;
}

$recipe = null;

// URL me recipe ka name aya hai to uska data nikaal lo
if (isset($_GET['name'])) {
    $name = urldecode($_GET['name']);
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $recipe = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($recipe['name'] ?? 'Recipe Details') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body 
        { 
            background-color: #f5f8fa; 
            font-family: 'Poppins', sans-serif; 
        }
        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
            transition: 0.3s;}

        .container 
        { 
            max-width: 800px;
             margin-top: 40px; 
        }

        .recipe-img 
        { 
            border-radius: 12px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            width: 90%; 
            height: 80%; 
            margin-left: 20px; 
            margin-top: -10px; 
        } 
        
        
        .chart-box {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    min-height: 400px;
    margin-top: -100px;
}

        
        .section-title 
        { 
            background-color: #198754; 
            color: white; 
            font-weight: bold; 
            font-size: 20px; 
            padding: 8px 25px; 
            border-radius: 8px; 
            margin-top: 25px; 
            margin-bottom: 10px; 
            margin-left: 10px; 
        } 
        
        table 
        { 
            background: white; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.1); 
            margin-left: 10px; 
        } 
        
        td, th 
        { 
            vertical-align: right; 
        }

        @media (max-width: 500px) {
            .chart-box {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
    <div class="container my-5">
    <?php if ($recipe): ?>
        <div class="row align-items-center gy-4">
            <!-- Image Section -->
            <div class="col-md-6 d-flex justify-content-center">
                <div>
                    <img src="images/recipe/<?= htmlspecialchars($recipe['image']) ?>"
                         alt="<?= htmlspecialchars($recipe['name']) ?>"
                         class="recipe-img img-fluid mb-3">
                    <p class="text-left">
                        <b>Prep Time:</b> <?= htmlspecialchars($recipe['prep_time'] ?? 'N/A') ?><br>
                        <b>Cook Time:</b> <?= htmlspecialchars($recipe['cook_time'] ?? 'N/A') ?>
                    </p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="chart-box w-100 text-center">
                    <canvas id="nutritionChart" style="max-width: 320px; height: auto;"></canvas>
                </div>
            </div>
        </div>

        <h5 class="text-center mt-4 mb-4"><b>Percent Calories</b></h5>

        <div class="row">
            <div class="col-md-6">
                <div class="section-title">Ingredients</div>
                <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

                <div class="section-title">Directions</div>
                <p><?= nl2br(htmlspecialchars($recipe['directions'])) ?></p>
            </div>

            <div class="col-md-6">
                <div class="section-title">Nutrition Facts</div>
                <table class="table table-bordered">
                    <tr><th>Nutrient</th><th>Value</th></tr>
                    <tr><td>Calories</td><td><?= $recipe['calories'] ?></td></tr>
                    <tr><td>Carbs</td><td><?= $recipe['carbs'] ?></td></tr>
                    <tr><td>Fats</td><td><?= $recipe['fats'] ?></td></tr>
                    <tr><td>Protein</td><td><?= $recipe['proteins'] ?></td></tr>
                </table>
            </div>
        </div>
        <div class="text-center mt-4">
                <a href="discoverfood.php" class="btn btn-success px-4 py-2">← Back to Discover</a>
            </div>
    <?php else: ?>
        <p class="text-center text-muted">Recipe not found!</p>
    <?php endif; ?>
</div>


    <script>
        const ctx = document.getElementById('nutritionChart');
        const data = {
            labels: ['Carbs', 'Fats', 'Protein'],
            datasets: [{
                data: [
                    <?= $recipe['carbs'] ?? 0 ?>,
                    <?= $recipe['fats'] ?? 0 ?>,
                    <?= $recipe['proteins'] ?? 0 ?>
                ],
                backgroundColor: ['#f87171', '#60a5fa', '#4ade80'],
                borderWidth: 1
            }]
        };
        //new Chart(ctx, { type: 'pie', data: data });
        new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

    </script>

    <?php require('component/Footer.php'); ?>
    </div>
</body>

</html> 