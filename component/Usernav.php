<!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        display: flex;
        flex-wrap: wrap;
        height: 100vh;
        overflow-x: hidden;
        font-family: 'Roboto', sans-serif;
    }

    .sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 48px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1);
        background: linear-gradient(to bottom, rgb(0, 128, 0), rgb(0, 102, 0));
        color: white;
        transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
    }

    .sidebar:hover {
        width: 300px;
        padding-left: 20px;
    }

    .sidebar-sticky {
        position: relative;
        top: 0;
        height: calc(100vh - 48px);
        padding-top: .5rem;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .sidebar h2 a {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .sidebar ul {
        padding-left: 0;
        list-style: none;
    }

    .sidebar ul li {
        padding: 12px 24px;
        transition: background-color 0.3s ease-in-out;
    }

    .sidebar ul li:hover {
        background-color: rgba(0, 77, 0, 0.6);
    }

    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        transition: color 0.3s ease-in-out;
    }

    .sidebar ul li a i {
        margin-right: 15px;
        font-size: 1.25rem;
    }

    .sidebar ul li a:hover {
        color: #d2f4ea;
    }

    .sidebar .btn-toggle {
        display: inline-flex;
        align-items: center;
        width: 100%;
        padding: 0.5rem;
        font-size: 1.3rem;
        color: white;
        background: none;
        border: none;
        outline: none;
        transition: color 0.3s ease-in-out;
    }

    .sidebar .btn-toggle i {
        font-size: 1.5rem;
    }

    .sidebar .btn-toggle:hover {
        color: #d2f4ea;
    }

    .btn-toggle-nav a {
        display: inline-flex;
        padding: 0.5rem 1rem;
        margin-top: 0.25rem;
        text-decoration: none;
        color: #d2f4ea;
        border-radius: 4px;
        transition: background-color 0.3s ease-in-out;
    }

    .btn-toggle-nav a i {
        font-size: 1rem;
    }

    .btn-toggle-nav a:hover {
        background-color: rgba(0, 77, 0, 0.6);
    }

    .dropdown-toggle::after {
        content: '\f078';
        font-family: FontAwesome;
        transition: transform 0.35s ease;
        transform-origin: .5em 50%;
    }

    .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(90deg);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: static;
            height: auto;
            box-shadow: none;
        }

        .sidebar-sticky {
            height: auto;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }
</style>

<div class="sidebar">
    <div class="sidebar-sticky">
        <?php
$profile_image = isset($_SESSION['profile_image']) && file_exists($_SESSION['profile_image'])
    ? $_SESSION['profile_image']
    : 'images/uploads/user_profile.png';

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>
<div class="text-center mb-4">
    <img src="<?php echo htmlspecialchars($profile_image); ?>" 
         alt="Profile Picture"
         class="rounded-circle border border-light shadow-sm"
         width="80" height="80"
         style="object-fit: cover;">
    <h5 class="mt-2 mb-0"><?php echo htmlspecialchars($username); ?></h5>
    <small class="text-light opacity-75">User</small>
    <hr class="border-light opacity-50 mx-4">
</div>

        <h2>
            <a href="index.php" class="d-flex align-items-center mb-3 px-4">
                BalancedBite
            </a>
        </h2>
        <ul class="list-unstyled ps-0">
        <li class="mb-1">
    <!-- Home direct link -->
            <a href="dashboard.php" class="link-light rounded d-flex align-items-center"><i class="bi bi-house"></i>&nbsp;Home
            </a>
        </li>    
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                    <i class="bi bi-card-checklist"></i>&nbsp;Meal plannning
                </button>
                <div class="collapse" id="orders-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="planner.php" class="link-light rounded"><i class="bi bi-calendar2-week"></i>&nbsp;Planner</a></li>
                        <li><a href="weekly.php" class="link-light rounded"><i class="bi bi-calendar3-week"></i>&nbsp;Weekly</a></li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                    <i class="bi bi-speedometer2"></i>&nbsp;Diet & Nutrition
                </button>
                <div class="collapse" id="dashboard-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="nutritiontarget.php" class="link-light rounded"><i class="bi bi-nut"></i>&nbsp;Nutrition Targets</a></li>
                        <li><a href="primarydiet.php" class="link-light rounded"><i class="bi bi-patch-check"></i>&nbsp;Primary Diet</a></li>
                        <li><a href="discoverfood.php" class="link-light rounded"><i class="bi bi-x-circle"></i>&nbsp;Food Exclusion</a></li>
                    </ul>
                </div>
</li>
                <li class="mb-1">
    <!-- Home direct link -->
    <a href="yoga_sessions.php" class="link-light rounded d-flex align-items-center">
        <i class="bi bi-heart-pulse"></i>&nbsp;Health & Fitness
    </a>
</li>
<!--<li class="nav-item">
    <a class="nav-link" href="New folder/api/payment.php?plan=basic&amount=10&discount=0&final=10&method=paypal">
        💳 Payment / Subscription
    </a>
</li>-->



                
            <li class="border-top my-3"></li>
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    <i class="bi bi-person"></i>&nbsp;Account
                </button>
                <div class="collapse" id="account-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="profile.php" class="link-light rounded"><i class="bi bi-person-circle"></i>&nbsp;Profile</a></li>
                        <li><a href="signout.php" class="link-light rounded"><i class="bi bi-box-arrow-right"></i>&nbsp;Sign out</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* global bootstrap: false */
    (function() {
        'use strict'
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })()
</script>