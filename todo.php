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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>To-Do List</title>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-primary text-center mb-4">Your Tasks</h3>
        
        <!-- Search bar for filtering tasks -->
        <div class="input-group mb-4">
            <input type="text" id="searchTask" class="form-control" placeholder="Search for tasks">
        </div>

        <!-- Filter Checkboxes -->
        <div class="mb-3">
            <label><input type="checkbox" id="showCompleted"> Show Completed</label>
            <label><input type="checkbox" id="showIncomplete"> Show Incomplete</label>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <ul class="list-group" id="taskList">
                    <?php while ($task = $tasks->fetch_assoc()) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="<?= $task['status'] === 'completed' ? 'text-decoration-line-through' : '' ?>">
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

        <form method="POST" action="create_task.php" class="mb-4">
            <input type="hidden" name="todo_id" value="<?= $todo_id; ?>">
            <div class="input-group">
                <input type="text" name="task" placeholder="New Task" class="form-control" required>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>

        <div class="text-center">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script>
        // Search function for filtering tasks
        document.getElementById('searchTask').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const tasks = document.getElementById('taskList').getElementsByTagName('li');
            Array.from(tasks).forEach(function(task) {
                const taskText = task.textContent.toLowerCase();
                if (taskText.includes(filter)) {
                    task.style.display = '';
                } else {
                    task.style.display = 'none';
                }
            });
        });

        // Show/Hide tasks based on checkboxes
        document.getElementById('showCompleted').addEventListener('change', function() {
            filterTasks();
        });

        document.getElementById('showIncomplete').addEventListener('change', function() {
            filterTasks();
        });

        function filterTasks() {
            const showCompleted = document.getElementById('showCompleted').checked;
            const showIncomplete = document.getElementById('showIncomplete').checked;
            const tasks = document.getElementById('taskList').getElementsByTagName('li');

            Array.from(tasks).forEach(function(task) {
                const isCompleted = task.querySelector('span').classList.contains('text-decoration-line-through');
                if ((showCompleted && isCompleted) || (showIncomplete && !isCompleted)) {
                    task.style.display = '';
                } else {
                    task.style.display = 'none';
                }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
