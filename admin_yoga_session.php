<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('db/dbconnect.php');
session_start();

$id = $_SESSION['id'];
// Check if user is logged in
//if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] != true) {
  //  header("location:Login.php");
    //exit;
//}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;}

// Fetch users for display
$posedata = "";
$serialNumber = 1;
if (isset($_POST['show_yoga_pose'])) {
    $poseQuery = "SELECT * FROM yoga_poses";
    $poseResult = mysqli_query($conn, $poseQuery);
    while ($pose = mysqli_fetch_assoc($poseResult)) {
        $posedata .= "<tr>
            <td>{$serialNumber}</td>
            <td>{$pose['name']}</td>
            <td>{$pose['description']}</td>
            <td>{$pose['difficulty']}</td>
            <td>
                <a href='admin_edit_yoga_pose.php?id={$pose['id']};' class='edit-btn'><i class='fas fa-edit'></i> Edit</a>
               
                <a href='admin_delete_yoga_pose.php?id={$pose['id']};' class='delete-btn' onclick='confirmDelete({$pose['id']})'>
    <i class='fas fa-trash'></i> Delete
</a>
            </td>
        </tr>";
        $serialNumber++;
    }
}

if (isset($_POST['create_poses'])) {
    header('Location:admin_create_yoga_pose.php');
}
if (isset($_POST['create_poses_details'])) {
    header('Location:admin_create_yoga_pose_detail.php');
}

$serialNo = 1;
$posedetaildata = "";
if (isset($_POST['show_yoga_pose_details'])) {
    $posedetailQuery = "SELECT *FROM yoga_pose_details";
    $posedetailResult = mysqli_query($conn, $posedetailQuery);
    while ($posedetail = mysqli_fetch_assoc($posedetailResult)) {
        $posedetaildata .= "<tr><td>{$serialNo}</td><td>{$posedetail['description']}</td><td>{$posedetail['benefits']}</td><td>{$posedetail['how_to_perform']}</td> <td>
                <a href='admin_edit_yoga_pose_detail.php?id={$posedetail['id']};' class='edit-btn'><i class='fas fa-edit'></i> Edit</a>
               
                <a href='admin_delete_yoga_pose_detail.php?id={$posedetail['id']};' class='delete-btn' onclick='confirmDelete1({$posedetail['id']})'>
    <i class='fas fa-trash'></i> Delete
</a>
            </td></tr>";
        $serialNo++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - yoga session</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
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

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: rgb(0, 128, 0);
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: rgb(0, 128, 0);
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.5s ease-in-out;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            transition: background 0.3s;
        }

        th {
            background-color: #28a745;
            color: white;
            text-align: left;
        }

        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        .viewbtn {
            outline: 2px solid rgb(0, 128, 0);
            color: rgb(0, 128, 0);
            font-size: 1rem;
            font-weight: 600;
            padding: 5px 10px;
            border: none;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            justify-content: center;
        }

        .viewbtn:hover {
            background-color: #006400 !important;
            transform: scale(1.01);
            color: white;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .edit-btn {
            background: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this pose?")) {
            window.location.href = 'admin_delete_yoga_pose.php?id=' + id;
        }
    }
    function confirmDelete1(id) {
        if (confirm("Are you sure you want to delete this pose details?")) {
            window.location.href = 'admin_delete_yoga_pose_detail.php?id=' + id;
        }
    }
</script>
<body>
    <?php require('component/Adminnav.php'); ?>
    <div class="main-content">
        <div class="container">
            <h2 class="text-center">Yoga Sessions Management</h2>
            <form method="POST">

                <!-- Dashboard Stats Cards -->
                <div class="row text-center mt-5">
                    <div class="col-md-6">
                        <div class="card shadow p-3">
                            <h3 class="mt-3">Manage Yoga Poses</h3>
                            <div class="btn-container mt-3">
                                <button class="w-50 viewbtn m-2" name="show_yoga_pose">View Yoga Poses</button>
                                <button class="w-50 viewbtn m-2" name="create_poses">Create Yoga Poses</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow p-3">
                            <h3 class="mt-3">Manage Yoga Poses Details</h3>
                            <div class="btn-container mt-3">
                                <button class="w-50 viewbtn m-2" name="show_yoga_pose_details">View Yoga Poses Details</button>
                                <button class="w-50 viewbtn m-2" name="create_poses_details">Create Yoga Poses Details</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <?php if (!empty($posedata)) : ?>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Difficulty</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    echo $posedata;
                    ?>

                </table>
            <?php endif; ?>
            <?php if (!empty($posedetaildata)) : ?>
                <table>
                    <tr>
                        <th>No</th>
                        <th style="width: 300px;">Description</th>
                        <th style="width: 300px;">Benefits</th>
                        <th style="width: 300px;">How to Perform</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    echo $posedetaildata;
                    ?>
                </table>
            <?php endif; ?>
        </div>


        <?php require('component/footer.php'); ?>
    </div>
</body>

</html>