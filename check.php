<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "db/dbconnect.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
    exit;
}

$is_premium = true;
$user_id = $_SESSION['id'];
$current_date = date("Y-m-d");

// Fetch last yoga date and streak
$query = $conn->prepare("SELECT last_yoga_date, yoga_streak FROM user_info WHERE uid = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($last_yoga_date, $current_streak);
$query->fetch();
$query->close();

// Default values
if ($last_yoga_date === null || $last_yoga_date == "0000-00-00") {
    $last_yoga_date = null;
    $current_streak = 0;
}

$update_needed = false;

// Determine streak logic
if ($last_yoga_date === null) {
    $current_streak = 1;
    $update_needed = true;
} elseif ($last_yoga_date == $current_date) {
    // same-day no change
} elseif ($last_yoga_date == date("Y-m-d", strtotime("-1 day"))) {
    $current_streak += 1;
    $update_needed = true;
} else {
    $current_streak = 1;
    $update_needed = true;
}

if ($update_needed) {
    $update_query = $conn->prepare("UPDATE user_info SET last_yoga_date = ?, yoga_streak = ? WHERE uid = ?");
    $update_query->bind_param("sii", $current_date, $current_streak, $user_id);
    $update_query->execute();
    $update_query->close();
}

$_SESSION['last_yoga_date'] = $current_date;
$_SESSION['streak'] = $current_streak;

// Fetch yoga poses
$query = "SELECT * FROM yoga_poses";
$result = mysqli_query($conn, $query);

$yoga_poses = [];
$intermediate_poses = [];
$premium_poses = [];

while ($row = mysqli_fetch_assoc($result)) {
    $difficulty = strtolower(trim($row['difficulty']));
    if ($difficulty == 'beginner') {
        $yoga_poses[] = $row;
    } elseif ($difficulty == 'intermediate') {
        $intermediate_poses[] = $row;
    } elseif ($difficulty == 'advanced') {
        $premium_poses[] = $row;
    }
}

// ✅ Mark as done progress
if (!isset($_SESSION['yoga_progress'])) {
    $_SESSION['yoga_progress'] = [];
}

if (isset($_POST['completed_pose'])) {
    $pose_name = $_POST['completed_pose'];
    if (!in_array($pose_name, $_SESSION['yoga_progress'])) {
        $_SESSION['yoga_progress'][] = $pose_name;
    }

    $total_poses = count($yoga_poses) + count($intermediate_poses) + ($is_premium ? count($premium_poses) : 0);
    $progress_percent = ($total_poses > 0) ? (count($_SESSION['yoga_progress']) / $total_poses) * 100 : 0;

    echo json_encode(['progress' => round($progress_percent, 2)]);
    exit;
}

$completed = $_SESSION['yoga_progress'];
$total_poses = count($yoga_poses) + count($intermediate_poses) + ($is_premium ? count($premium_poses) : 0);
$completed_count = count($completed);
$progress_percent = ($total_poses > 0) ? round(($completed_count / $total_poses) * 100, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yoga - sessions</title>
    <link rel="stylesheet" href="css/yoga_sessions.css">
    <?php require('component/Designlinks.php'); ?>
</head>

<body>
    <?php require('component/usernav.php'); ?>
    <div class="main-content">
        <div class="container py-5">

            <?php if (!$is_premium): ?>
                <div class="alert alert-warning text-center">
                    Upgrade to <strong>Premium</strong> to unlock exclusive yoga routines!
                    <a href="subscribe.php" class="btn btn-primary">Upgrade Now</a>
                </div>
            <?php endif; ?>

            <div class="yoga-header">
                <h1>Yoga for Wellness</h1>
                <p>Improve your flexibility, strength, and mindfulness with these yoga practices.</p>
            </div>

            <!-- Timer -->
            <div class="body mt-5">
                <div class="timer-container">
                    <h2 class="timer_heading">Set a Yoga Timer</h2>
                    <img src="images/yoga_timer.jpg" alt="Yoga Timer" height="400" width="400">
                    <div class="countdown-container">
                        <div class="countdown-item"><span id="hours">00</span> <span class="timer-label">Hours</span></div>
                        <div class="countdown-item"><span id="minutes">00</span> <span class="timer-label">Minutes</span></div>
                        <div class="countdown-item"><span id="seconds">00</span> <span class="timer-label">Seconds</span></div>
                    </div>
                    <input type="number" id="timer" placeholder="Enter time in minutes" class="mt-5">
                    <button onclick="startTimer()" class="btnstart">Start Timer</button>
                </div>
            </div>

            <!-- Difficulty filter -->
            <div class="difficulty-container">
                <h2 class="timer_heading">Select Your Difficulty Level</h2>
                <select id="difficulty-selector" class="selector">
                    <option value="all">All Levels</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                </select>
            </div>

            <!-- Yoga poses -->
            <section class="pose-section">
                <h2 class="section-title">Beginner Poses</h2>
                <div class="row pose-container">
                    <?php foreach ($yoga_poses as $pose): ?>
                        <div class="col-lg-4 col-md-6 pose-card" data-difficulty="<?= strtolower($pose['difficulty']); ?>">
                            <div class="card">
                                <img src="images/<?= $pose['image']; ?>" class="pose-img card-img-top" alt="<?= $pose['name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="yoga_view.php?name=<?= $pose['name']; ?>" class="name_link"><?= $pose['name']; ?></a>
                                    </h5>
                                    <p class="card-text"><?= $pose['description']; ?></p>
                                    <form class="pose-form">
                                        <input type="hidden" name="completed_pose" value="<?= $pose['name']; ?>">
                                        <button type="button" class="btn-success1 complete-pose-btn">Mark as Done</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h2 class="section-title">Intermediate Poses</h2>
                <div class="row pose-container">
                    <?php foreach ($intermediate_poses as $pose): ?>
                        <div class="col-lg-4 col-md-6 pose-card" data-difficulty="<?= strtolower($pose['difficulty']); ?>">
                            <div class="card">
                                <img src="images/<?= $pose['image']; ?>" class="pose-img card-img-top" alt="<?= $pose['name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><a href="yoga_view.php?name=<?= $pose['name']; ?>" class="name_link"><?= $pose['name']; ?></a></h5>
                                    <p class="card-text"><?= $pose['description']; ?></p>
                                    <form class="pose-form">
                                        <input type="hidden" name="completed_pose" value="<?= $pose['name']; ?>">
                                        <button type="button" class="btn-success1 complete-pose-btn">Mark as Done</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($is_premium && !empty($premium_poses)): ?>
                    <h2 class="section-title">Premium Poses</h2>
                    <div class="row pose-container">
                        <?php foreach ($premium_poses as $pose): ?>
                            <div class="col-lg-4 col-md-6 pose-card" data-difficulty="<?= strtolower(trim($pose['difficulty'])); ?>">
                                <div class="card border-warning">
                                    <img src="images/<?= $pose['image']; ?>" class="pose-img card-img-top" alt="<?= $pose['name']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="yoga_view.php?name=<?= urlencode($pose['name']); ?>" class="name_link"><?= $pose['name']; ?></a>
                                        </h5>
                                        <p class="card-text"><?= $pose['description']; ?></p>
                                        <form class="pose-form">
                                            <input type="hidden" name="completed_pose" value="<?= $pose['name']; ?>">
                                            <button type="button" class="btn-warning1 complete-pose-btn">Mark as Done</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- ✅ Yoga Progress Card (new added section) -->
            <div class="card shadow-lg p-4 rounded-4 border-0 mt-5 mb-5">
                <div class="card-body text-center">
                    <h4 class="fw-bold text-success mb-3">Your Yoga Progress</h4>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                            style="width: <?= $progress_percent ?>%">
                            <?= $progress_percent ?>%
                        </div>
                    </div>
                    <p class="text-muted mb-4">
                        Completed <strong><?= $completed_count ?></strong> of <strong><?= $total_poses ?></strong> poses.
                    </p>

                    <?php if (!empty($completed)): ?>
                        <ul class="list-group text-start">
                            <?php foreach ($completed as $pose): ?>
                                <li class="list-group-item border-0 border-bottom">
                                    ✅ <?= htmlspecialchars($pose); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-secondary">You haven’t completed any poses yet. 🌿</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Streak Card -->
            <div class="yoga-streak-card">
                <div class="streak-content">
                    <div class="streak-icon-placeholder">🔥</div>
                    <h2>Your Streak</h2>
                    <p class="streak-count"><?php echo $_SESSION['streak']; ?> Days</p>
                </div>
                <div class="last-session-content">
                    <div class="calendar-icon-placeholder">📅</div>
                    <h2>Last Yoga Session</h2>
                    <p class="last-session-date">
                        <?php echo isset($_SESSION['last_yoga_date']) ? $_SESSION['last_yoga_date'] : "No sessions yet"; ?>
                    </p>
                </div>
            </div>

        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <!-- Existing JS kept same -->
    <script>
        document.getElementById("difficulty-selector").addEventListener("change", function() {
            let selectedDifficulty = this.value.toLowerCase();
            let allCards = document.querySelectorAll(".pose-card");

            allCards.forEach(card => {
                let cardDifficulty = card.getAttribute("data-difficulty").toLowerCase().trim();
                card.style.display = (selectedDifficulty === "all" || cardDifficulty === selectedDifficulty) ? "" : "none";
            });
        });

        document.querySelectorAll(".complete-pose-btn").forEach(button => {
            button.addEventListener("click", function() {
                let form = this.closest(".pose-form");
                let formData = new FormData(form);

                fetch("", { method: "POST", body: formData })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            title: "Well Done!",
                            text: `You have completed ${data.progress}% of your yoga poses!`,
                            icon: "success",
                            confirmButtonColor: "#28a745"
                        });
                        setTimeout(() => location.reload(), 1000);
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>

</body>
</html>
