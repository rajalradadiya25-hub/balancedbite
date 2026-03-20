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
    <div class="sidebar-sticky text-center">
        <!-- Profile Image -->
        <div style="padding-bottom: 30px; padding-top:0px;">
            <?php
            $user_id = $_SESSION['id'];
            $selectquery1 = "SELECT * FROM users WHERE id = {$user_id}";
            $resultquery1 = mysqli_query($conn, $selectquery1);
            while ($rowquery1 = mysqli_fetch_assoc($resultquery1)) {
                $profile_image1 = $rowquery1['profile_image'];
            }
            $profile_image = $profile_image1 ?: 'images/uploads/user_profile.png';
            ?>
            <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="rounded-circle" width="150" height="150">
        </div>
        <h2>
            <a href="index.php" class="d-flex align-items-center mb-3 px-4">
                BalancedBite
            </a>
        </h2>
        <ul class="list-unstyled ps-0">
            <!-- Dashboard Section -->
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed">
                    <a href="admin.php" class="link-light rounded fs-5">
                        <i class="bi bi-house-door fs-4"></i>
                        Dashboard
                    </a>
                </button>
            </li>

            <!-- Management Section -->
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed fs-5" data-bs-toggle="collapse" data-bs-target="#management-collapse" aria-expanded="false">
                    <i class="bi bi-gear-wide-connected fs-4"></i> General Management
                </button>
                <div class="collapse" id="management-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="admin_meal_plan.php" class="link-light rounded"><i class="fas fa-list-alt"></i>&nbsp; Meal Plan Management</a></li>
                        <li><a href="admin_recipe.php" class="link-light rounded"><i class="fas fa-utensils"></i>&nbsp; Recipes Management</a></li>
                        <li><a href="admin_faq.php" class="link-light rounded"><i class="fas fa-question-circle"></i>&nbsp; FAQs Management</a></li>
                        <li><a href="admin_yoga_session.php" class="link-light rounded"><i class="fas fa-spa"></i>&nbsp; Yoga Sessions Management</a></li>
                    </ul>
                </div>
            </li>

            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed fs-5" data-bs-toggle="collapse" data-bs-target="#payment-collapse" aria-expanded="false">
                    <i class="bi bi-credit-card fs-4"></i> Payment Management
                </button>
                <div class="collapse" id="payment-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="admin_view_payments.php" class="link-light rounded"><i class="fas fa-money-bill-wave"></i>&nbsp; Payment Information Management</a></li>
                        <li><a href="admin_view_coupons.php" class="link-light rounded"><i class="fas fa-tags"></i>&nbsp; Discount Management</a></li>
                    </ul>
                </div>
            </li>


            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed fs-5" data-bs-toggle="collapse" data-bs-target="#user-collapse" aria-expanded="false">
                    <i class="bi bi-people fs-4"></i>&nbsp; User Management
                </button>
                <div class="collapse" id="user-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="admin_user_info.php" class="link-light rounded"><i class="fas fa-user-circle"></i>&nbsp; User Information Management</a></li>
                        <li><a href="admin_contact.php" class="link-light rounded"><i class="fas fa-address-book"></i>&nbsp; Contact Data Management</a></li>
                    </ul>
                </div>
            </li>


            <!-- User Account Section -->
            <li class="border-top my-3"></li>
            <li class="mb-1">
                <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    <i class="bi bi-person"></i>&nbsp;Account
                </button>
                <div class="collapse" id="account-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="admin_profile.php" class="link-light rounded"><i class="bi bi-person-circle"></i>&nbsp;Profile</a></li>
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