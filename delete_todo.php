<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$todo_id = $_GET['id'];

// Hapus todo dan semua task yang terkait
$stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $todo_id, $_SESSION['user_id']);

if ($stmt->execute()) {
    header('Location: dashboard.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
