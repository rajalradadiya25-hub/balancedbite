<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
  header("location:admin_login.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AI Chatbot</title>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap" />
  <link rel="stylesheet" href="css/admin_create_faq.css" />
  <?php require('component/Designlinks.php'); ?>
</head>

<body>
  <?php require('component/Adminnav.php'); ?>
  <div class="main-content">
    <div class="chatbot-container">
      <div class="chat-header">
        <div class="header-info">
          <div class="chatbot-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024">
              <path
                d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z" />
            </svg>
          </div>
          <h2 class="logo-text">Chatbot</h2>
        </div>
        <!-- Beautiful Info Box -->
        <button class="info-button" onclick="toggleInfo()">
          <i class="fa-solid fa-question-circle"></i>
        </button>

        <div id="info-box" class="info-box">
          <div class="info-header">
            <h3>💡 Chatbot Help</h3>
            <button class="close-btn" onclick="toggleInfo()">✖</button>
          </div>
          <div class="info-content">
            <p><i class="fa-solid fa-comment-dots"></i> <b>How to Use the Chatbot?</b></p>
            <ul>
              <li><b>Ask</b> – Type your question.</li>
              <li><b>Choose</b> – Select a category.</li>
              <li><b>Send</b> – Submit your query.</li>
              <li><b>Reply</b> – Get an answer.</li>
              <li><b>Refine</b> – Rephrase if needed.</li>
              <li><b>Follow-up</b> –<a href="#" onclick="showMoreInfo()"> more details</a>.</li>
            </ul>
          </div>
        </div>

      </div>
      <div class="chat-body">
        <div class="message bot-message">
          <svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
            <path
              d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z" />
          </svg>
          <!-- prettier-ignore -->
          <div class="message-text"> Hey there <br /> How can I help you today? </div>
        </div>
      </div>

      <div class="chat-footer">
        <form action="#" class="chat-form">
          <select id="category-select" class="category-dropdown">
            <option value="">Select a Category</option>
            <option value="Common Concerns">Common Concerns</option>
            <option value="Diet Plans">Diet Plans</option>
            <option value="General Nutrition">General Nutrition</option>
            <option value="Health and Wellness">Health and Wellness</option>
            <option value="Recipes and Meal Planning">Recipes and Meal Planning</option>
            <option value="Special Dietary Needs">Special Dietary Needs</option>
            <option value="Supplements and Vitamins">Supplements and Vitamins</option>
            <option value="Support and Resources">Support and Resources</option>
          </select>
          <div class="input-container">
            <input type="text" placeholder="Message..." class="message-input" />
            <button type="submit" id="send-message" class="send-button">
              <i class="fa-solid fa-arrow-up" style=" color: white;"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <?php require('component/Footer.php'); ?>
  </div>
  <script src="js/admin_create_faq.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function toggleInfo() {
      var infoBox = document.getElementById("info-box");
      infoBox.style.display = infoBox.style.display === "block" ? "none" : "block";
    }

    function showMoreInfo() {
      Swal.fire({
        title: "More Help",
        html: `
      <div class="advanced-usage">
  <h3>💡 <b>Advanced Usage</b></h3>
  <p>Select a category that matches your question for better accuracy.</p>

  <div class="category-container">
    <div class="category-card">
      <h4>📌 Common Concerns</h4>
      <p>Get insights on food safety, side effects, allergies, additives, and labels.</p>
    </div>
    <div class="category-card">
      <h4>🥗 Diet Plans</h4>
      <p>Explore diets like keto, vegan, fasting, and more to meet your goals.</p>
    </div>
    <div class="category-card">
      <h4>🍏 General Nutrition</h4>
      <p>Learn about essential nutrients, metabolism, digestion, and hydration.</p>
    </div>
    <div class="category-card">
      <h4>🏋️ Health & Wellness</h4>
      <p>Improve your lifestyle with exercise, stress relief, sleep, and relaxation.</p>
    </div>
    <div class="category-card">
      <h4>🍽️ Recipes & Meal Planning</h4>
      <p>Find easy, healthy recipes and meal prep tips for a balanced diet.</p>
    </div>
    <div class="category-card">
      <h4>⚕️ Special Dietary Needs</h4>
      <p>Guidance on gluten-free, diabetic, heart-healthy, and allergy-friendly diets.</p>
    </div>
    <div class="category-card">
      <h4>💊 Supplements & Vitamins</h4>
      <p>Understand omega-3, vitamin D, probiotics, and other essential supplements.</p>
    </div>
    <div class="category-card">
      <h4>📚 Support & Resources</h4>
      <p>Access meal plans, coaching, fitness tools, and community support.</p>
    </div>
  </div>
</div>


    `,
        icon: "info",
        confirmButtonText: "Got it!",
      });
    }
  </script>

</body>

</html>