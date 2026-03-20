<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
//error_reporting(0);
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}
$pose = null; // Ensure $pose is initialized to avoid undefined variable warnings

if (isset($_GET['name']))
     {
    //$name= trim($_GET['name']); // Trim whitespace
    $name = trim($_GET['name']); // whitespace remove
    // Ensure the database connection exists
    if ($conn) {
        $query = "SELECT yp.name, ypd.description, ypd.benefits, ypd.how_to_perform, yp.image 
                  FROM yoga_poses yp 
                  JOIN yoga_pose_details ypd ON yp.id = ypd.pose_id 
                  WHERE yp.name = ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $pose = $result->fetch_assoc();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        die("Database connection failed.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pose['name']) ? htmlspecialchars($pose['name']) . " - Yoga Pose" : "Yoga Pose Details"; ?></title>
    <?php require('component/Designlinks.php'); ?>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .container1 {
            background: white;
            padding: 20px;
            /* Reduce padding */
            border-radius: 15px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            text-align: left;
            max-width: 800px;
            /* Reduce the max-width further */
            width: 100%;
            /* Ensures responsiveness */
            margin: 30px auto;
            /* Centers the container */
        }

        .container1:hover {
            transform: translateY(-5px);
        }

        h2 {
            font-size: 32px;
            color: #008000;
            text-align: center;
            margin-bottom: 20px;
        }

        .pose-image {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        p {
            font-size: 17px;
            line-height: 1.7;
            color: #444;
        }

        strong {
            color: #008000;
            font-weight: 600;
        }

        /* Back Button */
        .back-button {
            display: inline-block;
            text-decoration: none;
            background: #008000;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            transition: background 0.3s ease-in-out, transform 0.2s;
            margin-top: 20px;
        }

        .back-button:hover {
            background: #006600;
            transform: scale(1.05);
            color: white;
        }
    </style>
</head>

<body>
    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="container1">
            <?php if ($pose): ?>
                <h2><?php echo htmlspecialchars($pose['name']); ?></h2>

                <?php if (!empty($pose['image'])): ?>
                    <img src="images/<?php echo htmlspecialchars($pose['image']); ?>" alt="<?php echo htmlspecialchars($pose['name']); ?>" class="pose-image">
                <?php endif; ?>

                <div class="card">
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($pose['description']); ?></p>
                </div>

                <div class="card">
                    <p><strong>Benefits:</strong> <?php echo htmlspecialchars($pose['benefits']); ?></p>
                </div>

                <div class="card">
                    <p><strong>How to Perform:</strong></p>
                    <?php
                    $steps = explode(',', $pose['how_to_perform']); // Split text at commas
                    foreach ($steps as $index => $step) {
                        echo "<p><strong>Step " . ($index + 1) . ":</strong> " . htmlspecialchars(trim($step)) . "</p>";
                    }
                    ?>
                </div>

                <a href="yoga_sessions.php" class="back-button">🔙 Back to Yoga Poses</a>
            <?php else: ?>
                <p>No details found for this yoga pose.</p>
            <?php endif; ?>
        </div>

        <?php require('component/Footer.php'); ?>
    </div>
</body>

</html>