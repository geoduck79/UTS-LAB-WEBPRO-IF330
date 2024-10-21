<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = htmlspecialchars($_POST['task']);
    $todo_id = $_POST['todo_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (todo_id, task, status) VALUES (?, ?, 'incomplete')");
    $stmt->bind_param("is", $todo_id, $task);

    if ($stmt->execute()) {
        header('Location: todo.php?id=' . $todo_id);
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
