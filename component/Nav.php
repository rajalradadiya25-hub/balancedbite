<?php
//session_start(); 

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  $loggedin = true;
  $id = $_SESSION['id'] ?? null; 
} else {
  $loggedin = false;
  $id = null; 
}
?>


<style>
  .navbar-brand {
    font-size: 1.5rem;
    font-weight: bold;
  }

  .nav-link {
    font-size: 1.2rem;
    margin-right: 1rem;
  }

  .btn-dark {
    background-color: #000;
    color: #fff;
    padding: 0.75rem 1.5rem;
    font-size: 1.2rem;
  }

  .logo {
    padding-left: 100px;
    padding-right: 99px;
    margin-left: 150px;
    background-color: rgb(0, 128, 0);
  }

  .img-fluid {
    max-width: 100%;
    height: auto;
  }

  .rounded-circle {
    border-radius: 50%;
  }

  .black-bg {
    background-color: #000;
    padding: 1rem;
    border-radius: 50%;
  }

  @media (max-width: 768px) {
    .navbar-brand {
      margin-left: 0;
      padding-left: 15px;
      padding-right: 15px;
    }

    .logo {
      padding-left: 10px;
      padding-right: 10px;
      margin-left: 0;
    }

    .nav-link {
      margin-right: 0;
      text-align: center;
    }
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light p-0" style="position: sticky; top:0%; z-index: 1000">
  <div class="container-fluid">
    <a class="navbar-brand text-light logo" href="index.php">BalancedBite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="caloriecalculator.php">Calorie calculator</a>
        </li>
        <li class="nav-item">
          
  <a class="nav-link" href="personalizedplans.php">
     Meal Plans
  </a>
</li>

        <li class="nav-item">
          <a class="nav-link" href="deliciousrecipes.php">Recipes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="progresstracking.php">Progress Tracker</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about-us.php">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact-us.php">Contact Us</a>
        </li>
      </ul>
      <button class="btn" style="background-color: rgb(0, 128, 0);"><a href="<?php
                                                                              if ($loggedin && $id) {
                                                                                echo "dashboard.php?id={$id}";
                                                                              } else {
                                                                                echo "signup.php";
                                                                              }
                                                                              ?>" class="text-light text-decoration-none">Sign Up</a></button>
    </div>
  </div>
</nav>