<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch To-Do Lists
$stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$todos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <h2>Your To-Do Lists</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <h3>Your To-Do Lists</h3>
        <ul class="list-group">
            <?php while ($todo = $todos->fetch_assoc()) : ?>
                <li class="list-group-item">
                    <a href="todo.php?id=<?= $todo['id']; ?>"><?= $todo['title']; ?></a>
                    <a href="delete_todo.php?id=<?= $todo['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <form method="POST" action="create_todo.php">
            <input type="text" name="title" placeholder="New To-Do List" class="form-control" required>
            <button type="submit" class="btn btn-primary mt-2">Add</button>
        </form>
    </div>
</body>
</html>
