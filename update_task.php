<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    // Fetch the current status of the task
    $stmt = $conn->prepare("SELECT status FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {
        // Toggle status
        $new_status = ($task['status'] === 'completed') ? 'incomplete' : 'completed';

        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $task_id);
        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error updating task: " . $stmt->error;
        }
    } else {
        echo "Task not found.";
    }
} else {
    echo "Invalid request.";
}
?>
