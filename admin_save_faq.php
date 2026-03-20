<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "balancedbite";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql);

$category = $_POST["category"] ?: "";
$question = $_POST["question"] ?: "";
$answer = $_POST["answer"] ?: "";

if (!empty($category) && !empty($question) && !empty($answer)) {
    $stmt = $conn->prepare("INSERT INTO faqs (category, question, answer) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $category, $question, $answer);
    $stmt->execute();
}

$conn->close();
?>