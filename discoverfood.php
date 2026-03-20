<?php
session_start();
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db/dbconnect.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:Login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Recipes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f3f6f9;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: calc(100% - 280px);
            flex: 1;
        }

        .container {
            max-width: 1100px;
        }

        .search-bar123 {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            /* Centers the element */
            display: block;
            /* Ensures it behaves like a block element */
            border-radius: 25px;
            border: 1px solid #ccc;
            padding: 10px 15px;
            font-size: 16px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            /* Aligns text inside the search bar to the left */
        }


        .recipe-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s;
            overflow: hidden;
            background: white;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .recipe-card img {
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-body {
            padding: 15px;
        }

        .card-title a {
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            color: #2d3e50;
            transition: color 0.3s;
        }

        .card-title a:hover {
            color: #0d6efd;
        }

        .card-text {
            font-size: 14px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .search-bar123 {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php require('component/Usernav.php'); ?>
    <div class="main-content">
        <div class="container mt-4">
            <input type="text" id="search" class="search-bar123" placeholder="🔍 Search recipes...">
            <div class="row mt-4" id="recipeContainer">
                <?php
                $sql = "SELECT name, image, calories FROM recipes";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4 recipe-item">';
                        echo '<div class="card recipe-card">';
                        echo '<img src="images/recipe/' . htmlspecialchars($row["image"]) . '" class="card-img-top" alt="' . htmlspecialchars($row["name"]) . '">';
                        echo '<div class="card-body text-center">';
                        echo '<h5 class="card-title">
                            <a href=recipe.php?name=' . urlencode($row["name"]) . '&&calories=' . urlencode($row["calories"]) . '">' . htmlspecialchars($row["name"]) . '</a>
                            
                        
                          </h5>';
                          
                        echo '<p class="card-text"><strong>Calories:</strong> ' . htmlspecialchars($row["calories"]) . '</p>';
                        echo '</div></div></div>';
                    }
                } else {
                    echo "<p class='text-center text-muted'>No recipes found.</p>";
                }
                

                $conn->close();
                ?>
            </div>
        </div>
        <?php require('component/Footer.php'); ?>
    </div>

    <script>
        document.getElementById("search").addEventListener("input", function() {
            let query = this.value.toLowerCase();
            document.querySelectorAll(".recipe-item").forEach(function(item) {
                let name = item.querySelector(".card-title").textContent.toLowerCase();
                item.style.display = name.includes(query) ? "block" : "none";
            });
        });
    </script>
</body>

</html>