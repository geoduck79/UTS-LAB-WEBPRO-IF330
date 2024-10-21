<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, status) VALUES (?, ?, 'incomplete')");
    $stmt->bind_param("is", $user_id, $title);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
