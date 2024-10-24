<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$todo_id = $_GET['id'];

// Fetch the title of the to-do list
$stmt = $conn->prepare("SELECT title FROM todos WHERE id = ?");
$stmt->bind_param("i", $todo_id);
$stmt->execute();
$title_result = $stmt->get_result();
$title_row = $title_result->fetch_assoc();
$todo_title = htmlspecialchars($title_row['title']);

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>To-Do List</title>
</head>

<body class="text-white" style="background-image: url('images/1350790.png');">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8"> <!-- Adjust width of the column as needed -->
                <h3 class="text-center mb-4"><?= $todo_title; ?> </h3>
                <form method="POST" action="create_task.php" class="mb-4">
                    <input type="hidden" name="todo_id" value="<?= $todo_id; ?>">
                    <div class="input-group">
                        <input type="text" name="task" placeholder="New Task" class="form-control" required aria-label="New Task">
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>

                <div class="card mb-5">
                    <div class="card-body">
                        <ul class="list-group" id="taskList">
                            <?php while ($task = $tasks->fetch_assoc()) : ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                                    <span class="<?= $task['status'] === 'completed' ? 'text-decoration-line-through text-muted' : ''; ?>">
                                        <?= htmlspecialchars($task['task']); ?>
                                    </span>
                                    <div>
                                        <?php if ($task['status'] !== 'completed') : ?>
                                            <form method="POST" action="update_task.php" class="d-inline">
                                                <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-success" name="action" value="complete">✔️ Complete</button>
                                            </form>
                                        <?php endif; ?>
                                        <a href="delete_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <div class="text-center">
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
