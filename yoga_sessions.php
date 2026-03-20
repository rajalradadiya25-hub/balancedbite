<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "db/dbconnect.php"; // Ensure you include your DB connection

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
$is_premium=true;
$user_id = $_SESSION['id'];
$current_date = date("Y-m-d");

// Fetch last yoga date and streak
$query = $conn->prepare("SELECT last_yoga_date, yoga_streak FROM user_info WHERE uid = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($last_yoga_date, $current_streak);
$query->fetch();
$query->close();

// Default to zero if no streak exists
if ($last_yoga_date === null || $last_yoga_date == "0000-00-00") {
    $last_yoga_date = null;
    $current_streak = 0;
}

$update_needed = false;

// Determine the new streak value
if ($last_yoga_date === null) {
    $current_streak = 1; // First-time yoga entry
    $update_needed = true;
} elseif ($last_yoga_date == $current_date) {
    // Already updated today, no changes needed
} elseif ($last_yoga_date == date("Y-m-d", strtotime("-1 day"))) {
    // Continue streak if last session was yesterday
    $current_streak += 1;
    $update_needed = true;
} else {
    // Reset streak if a day was missed
    $current_streak = 1;
    $update_needed = true;
}

// Update last_yoga_date and streak in database if needed
if ($update_needed) {
    $update_query = $conn->prepare("UPDATE user_info SET last_yoga_date = ?, yoga_streak = ? WHERE uid = ?");
    $update_query->bind_param("sii", $current_date, $current_streak, $user_id);

    if ($update_query->execute()) {
        $response = ["success" => "✅ Yoga streak updated successfully!", "streak" => $current_streak];
    } else {
        $response = ["error" => "❌ Error updating: " . $update_query->error];
    }
    $update_query->close();
} else {
    $response = ["message" => "⚠️ Yoga streak already updated today.", "streak" => $current_streak];
}

// Update session variables
$_SESSION['last_yoga_date'] = $current_date;
$_SESSION['streak'] = $current_streak;

// echo json_encode($response);
// Fetch all yoga poses from the database
$query = "SELECT * FROM yoga_poses";
$result = mysqli_query($conn, $query);

$yoga_poses = [];
$intermediate_poses = [];
$premium_poses = [];

while ($row = mysqli_fetch_assoc($result)) {
    $difficulty = strtolower(trim($row['difficulty'])); // lowercase & trim
    if ($difficulty == 'beginner') {
        $yoga_poses[] = $row;
    } elseif ($difficulty == 'intermediate') {
        $intermediate_poses[] = $row;
    } elseif ($difficulty == 'advanced') {
        $premium_poses[] = $row;
    }
}


// Handle progress tracking
if (!isset($_SESSION['yoga_progress'])) {
    $_SESSION['yoga_progress'] = [];
}
if (isset($_POST['completed_pose'])) {
    // Ensure session array exists
    if (!isset($_SESSION['yoga_progress'])) {
        $_SESSION['yoga_progress'] = [];
    }

    // Normalize pose name (case-insensitive + trim)
    $pose_name = strtolower(trim($_POST['completed_pose']));

    // Add only if not already completed
    if (!in_array($pose_name, $_SESSION['yoga_progress'])) {
        $_SESSION['yoga_progress'][] = $pose_name;
    }

    // Calculate total poses
    $total_poses = count($yoga_poses) + count($intermediate_poses) + ($is_premium ? count($premium_poses) : 0);
    $completed = count($_SESSION['yoga_progress']);

    // Prevent going over 100%
    $progress_percent = ($total_poses > 0)
        ? min(($completed / $total_poses) * 100, 100)
        : 0;

    header('Content-Type: application/json');
    echo json_encode(['progress' => round($progress_percent, 2)]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yoga - sessions</title>
    <link rel="stylesheet" href="css/yoga_sessions.css">
    <?php require('component/Designlinks.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <div class="difficulty-container">
                <h2 class="timer_heading">Select Your Difficulty Level</h2>
                <select id="difficulty-selector" class="selector">
                    <option value="all">All Levels</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
        
                </select>
            </div>

            <section class="pose-section">
                <h2 class="section-title">Beginner Poses</h2>
                <div class="row pose-container">
                    <?php foreach ($yoga_poses as $pose): ?>
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

    <script>
        document.getElementById("difficulty-selector").addEventListener("change", function() {
            let selectedDifficulty = this.value.toLowerCase();
            let allCards = document.querySelectorAll(".pose-card");

            allCards.forEach(card => {
                let cardDifficulty = card.getAttribute("data-difficulty").toLowerCase().trim();

                if (selectedDifficulty === "all" || cardDifficulty === selectedDifficulty) {
                    card.classList.remove("hidden"); // Show relevant cards
                } else {
                    card.classList.add("hidden"); // Hide others
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".complete-pose-btn").forEach(button => {
                button.addEventListener("click", function() {
                    fetch("", {
                            method: "POST",
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Session Logged!",
                                text: `Your last yoga session was on ${data.last_yoga_date}. Keep it up!`,
                                icon: "success",
                                confirmButtonColor: "#28a745"
                            });

                            // Update date on the page
                            document.querySelector(".streak-count").innerText = data.streak;

                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });
    </script>

    <style>
        .hidden {
            display: none !important;
        }
    </style>
    <script>
        function startTimer() {
            let minutes = parseInt(document.getElementById('timer').value);
            let time = minutes * 60;

            if (isNaN(time) || time <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Please enter a valid number of minutes.',
                    confirmButtonColor: 'red'
                });
                return;
            }

            let hoursElem = document.getElementById('hours');
            let minutesElem = document.getElementById('minutes');
            let secondsElem = document.getElementById('seconds');

            function updateTimer() {
                let hours = Math.floor(time / 3600);
                let mins = Math.floor((time % 3600) / 60);
                let secs = time % 60;

                hoursElem.innerText = hours.toString().padStart(2, '0');
                minutesElem.innerText = mins.toString().padStart(2, '0');
                secondsElem.innerText = secs.toString().padStart(2, '0');

                if (time > 0) {
                    time--;
                    setTimeout(updateTimer, 1000);
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Yoga Completed!',
                        text: 'Great job! Your yoga session is complete. 🧘‍♂️',
                        confirmButtonColor: 'rgb(0,128,0)'
                    });
                }
            }

            updateTimer();
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".complete-pose-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let form = this.closest(".pose-form");
                    let formData = new FormData(form);

                    fetch("yoga_sessions.php", { // Send request to the same PHP file
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Well Done!",
                                text: `You have completed ${data.progress}% of your yoga poses! Keep going!`,
                                icon: "success",
                                confirmButtonColor: "#28a745"
                            });
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });
    </script>
    


</body>

</html>