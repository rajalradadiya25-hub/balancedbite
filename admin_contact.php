<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();
//if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
    //header("location:Login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;}
$id = $_SESSION['id'];
$queriesData = "";

// Fetch data from contact_querytbl
$usersQuery = "SELECT * FROM contact_querytbl";
$usersResult = mysqli_query($conn, $usersQuery);

while ($user = mysqli_fetch_assoc($usersResult)) {
    $statusChecked = ($user['status'] == 'Done') ? 'checked' : '';
    $statusText = ($user['status'] == 'Done') ? 'Completed' : 'Pending';
    $statusClass = ($user['status'] == 'Done') ? 'status-done' : 'status-pending';

    $queriesData .= "
    <div class='query-card' data-status='{$user['status']}'>
        <div class='query-header'>
            <h3>{$user['name']}</h3>
            <span class='status-badge {$statusClass}'>{$statusText}</span>
        </div>
        <p><strong>Email:</strong> {$user['email']}</p>
        <p><strong>Issue:</strong> {$user['issue']}</p>
        <p><strong>Concern:</strong> {$user['concern']}</p>
        <div class='toggle-container'>
            <label class='switch'>
                <input type='checkbox' class='status-toggle' data-id='{$user['id']}' {$statusChecked}>
                <span class='slider round'></span>
            </label>
            <span class='toggle-label'>Mark as Done</span>
        </div>
    </div>";
}

// Handle AJAX request for updating status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE contact_querytbl SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Status updated successfully!";
    } else {
        echo "Failed to update status: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Queries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
            transition: 0.3s;
        }

        .heading {
            text-align: center;
            color: rgb(0,128,0);
            margin: 4%;
            font-weight: bold;
            font-size: 50px;
        }

        /* Filter & Search */
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-box {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 60%;
        }

        .filter-select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: white;
        }

        /* Query Card Layout */
        .query-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .query-card:hover {
            transform: translateY(-5px);
        }

        .query-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .status-done {
            background-color: #28a745;
            color: white;
        }

        .status-pending {
            background-color: #dc3545;
            color: white;
        }

        p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .toggle-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .toggle-label {
            margin-left: 10px;
            font-size: 14px;
            color: #333;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #dc3545;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #28a745;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>
</head>

<body>
    <?php require('component/Adminnav.php'); ?>
    <div class="main-content">
        <div class="container">
            <h1 class="heading">Contact Queries</h1>

            <!-- Search & Filter -->
            <div class="filter-container">
                <input type="text" id="searchQuery" class="search-box" placeholder="Search by name, email, or issue">
                <select id="filterStatus" class="filter-select">
                    <option value="all">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Done">Completed</option>
                </select>
            </div>

            <div id="queriesContainer">
                <?php echo (!empty($queriesData)) ? $queriesData : "<p style='text-align: center; color: #888;'>No contact queries found.</p>"; ?>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <script>
        $(document).ready(function() {
            $(".status-toggle").change(function() {
                var queryId = $(this).data("id");
                var newStatus = $(this).is(":checked") ? "Done" : "Pending";

                $.ajax({
                    url: "",
                    type: "POST",
                    data: {
                        id: queryId,
                        status: newStatus
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            });

            $("#searchQuery, #filterStatus").on("input change", function() {
                var search = $("#searchQuery").val().toLowerCase();
                var filter = $("#filterStatus").val();

                $(".query-card").each(function() {
                    var text = $(this).text().toLowerCase();
                    var status = $(this).data("status");

                    $(this).toggle(text.includes(search) && (filter === "all" || status === filter));
                });
            });
        });
    </script>
</body>

</html>