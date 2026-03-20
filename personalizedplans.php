<?php
session_start();
require('db/dbconnect.php');
// Reset plan if user clicked a new plan
if (isset($_GET['selected_plan'])) {
    unset($_SESSION['selected_plan']);
    unset($_SESSION['keep_selected_plan']);
}

// Step 1: If a plan is clicked (from GET), store in session for highlight
if(isset($_GET['selected_plan'])){
    $_SESSION['selected_plan'] = [
        'plan' => $_GET['selected_plan'],
        'plan_name' => $_GET['plan_name'],
        'price' => floatval($_GET['price']),
        'discount_percent' => floatval($_GET['discount_percent'])
    ];
    $_SESSION['keep_selected_plan'] = $_SESSION['selected_plan'];
}

// Step 2: Determine which plan to highlight
//$selected_plan = isset($_SESSION['selected_plan']['plan']) ? $_SESSION['selected_plan']['plan'] : '';
$selected_plan = null;

// Step 3: Function to get Buy Now link
function getBuyLink($plan_code, $plan_name, $price, $discount_percent){

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){

        // store correct selected plan
        $_SESSION['selected_plan'] = [
            'plan' => $plan_code,
            'plan_name' => $plan_name,
            'price' => $price,
            'discount_percent' => $discount_percent
        ];

        return "/final B/balancedbite/Newfolder/api/payment.php?plan_code=$plan_code&plan_name=" . urlencode($plan_name) . "&price=$price&discount_percent=$discount_percent";
    } 
    else {

    return "signup.php?plan_code=$plan_code&plan_name=" . urlencode($plan_name) . "&price=$price&discount_percent=$discount_percent";
}

}




// Step 4: Define plans with percentage discounts
$plans = [
    '7day' => [
        'plan_name' => '7 Day Plan',
        'price' => 399,
        'discount_percent' => 5,
        'image' => 'images/meal_plan/chickpea-salad.jpg'
    ],
    '1month' => [
        'plan_name' => '1 Month Plan',
        'price' => 999,
        'discount_percent' => 10,
        'image' => 'images/meal_plan/grilled-veggie-salad.jpg'
    ],
    '3month' => [
        'plan_name' => '3 Month Plan',
        'price' => 2499,
        'discount_percent' => 15,
        'image' => 'images/meal_plan/vegetable-paella.jpg'
    ],
    '6month' => [
        'plan_name' => '6 Month Plan',
        'price' => 4499,
        'discount_percent' => 20,
        'image' => 'images/meal_plan/tomato-basil-soup.jpg'
    ]
];


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Personalized Meal Plans - BalancedBite</title>
<?php require("component/Designlinks.php"); ?>
<style>
body { background: #f1f5f9; font-family: 'Poppins', sans-serif; }
.plan-container { width: 90%; margin: 40px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
.plan-card { background: #fff; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; overflow: hidden; }
.plan-card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }
.plan-image { width: 100%; height: 200px; object-fit: cover; }
.plan-content { padding: 20px; text-align: center; }
.plan-content h3 { color: #007b5e; font-weight: 600; }
.price { font-size: 1.3rem; font-weight: bold; color: #222; }
.discount { color: #e53935; font-weight: bold; }
.btn-buy { display: inline-block; margin-top: 15px; background: #007b5e; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; transition: background 0.3s; }
.btn-buy:hover { background: #005f47; }
.highlight-card { border: 3px solid #007b5e; transform: translateY(-5px); }
</style>
</head>
<body>
<?php require("component/nav.php"); ?>

<div class="text-center mt-5">
<h1 class="fw-bold text-success">Choose Your Personalized Meal Plan</h1>
<p class="text-muted">Healthy, Balanced & Tailored for You</p>
</div>

<div class="plan-container">

<?php foreach($plans as $code => $plan): ?>
<div class="plan-card <?php echo ($selected_plan == $code) ? 'highlight-card' : ''; ?>">
    <img src="<?php echo $plan['image']; ?>" alt="<?php echo $plan['plan_name']; ?>" class="plan-image">
    <div class="plan-content">
        <h3><?php echo $plan['plan_name']; ?></h3>
        <p class="price">
            ₹<?php echo $plan['price']; ?> 
            <span class="discount">(-<?php echo $plan['discount_percent']; ?>%)</span>
        </p>

        <a href="<?php echo getBuyLink($code, $plan['plan_name'], $plan['price'], $plan['discount_percent']); ?>" class="btn-buy">Buy Now</a>
    </div>
</div>

<?php endforeach; ?>

</div>

<?php require("component/footer.php"); ?>
</body>
</html>  