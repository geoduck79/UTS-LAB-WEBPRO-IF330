<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$todo_id = $_GET['id'];

// Fetch tasks
$stmt = $conn->prepare("SELECT * FROM tasks WHERE todo_id = ?");
$stmt->bind_param("i", $todo_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>To-Do List</title>
</head>
<body>
    <div class="container">
        <h3>Your Tasks</h3>
        <ul class="list-group">
            <?php while ($task = $tasks->fetch_assoc()) : ?>
                <li class="list-group-item">
                    <?= $task['task']; ?> 
                    <a href="delete_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <form method="POST" action="create_task.php">
            <input type="hidden" name="todo_id" value="<?= $todo_id; ?>">
            <input type="text" name="task" placeholder="New Task" class="form-control" required>
            <button type="submit" class="btn btn-primary mt-2">Add Task</button>
        </form>
    </div>
</body>
</html>
