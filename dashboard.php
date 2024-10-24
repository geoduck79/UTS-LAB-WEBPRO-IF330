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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashboard</title>
</head>
<body>
    <!-- Full-screen background image -->
    <div class="bg-image" style="background-image: url('images/1350790.png'); min-height: 100vh; background-size: cover; background-position: center;">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">To-Do App</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="navbar-brand" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-5 bg-gray bg-opacity-75 rounded p-4">
        <h3 class="text-center text-white mb-4"><?= htmlspecialchars($_SESSION['username']) ?>'s To-Do Lists</h3>
            
            <form method="POST" action="create_todo.php" class="input-group mb-3">
                <input type="text" name="title" placeholder="New To-Do List" class="form-control rounded-start-3  " required>
                <button type="submit" class="btn btn-primary rounded-end-3 ">Add</button>
            </form>

            <!-- Search Bar -->
            <div class="input-group mb-4 ">
                <input type="text" id="searchTask" class="form-control rounded-start-5" placeholder="Search for tasks">
                <select id="filterStatus" class="form-select rounded-end-5 bg-primary border-0 text-light">
                    <option value="">All Tasks</option>
                    <option value="completed">Completed</option>
                    <option value="incomplete">Ongoing</option>
                </select>
            </div>

            <div class="row">
                <?php while ($todo = $todos->fetch_assoc()) : ?>
                    <?php
                    // Fetch tasks for each to-do list
                    $todo_id = $todo['id'];
                    $taskStmt = $conn->prepare("SELECT * FROM tasks WHERE todo_id = ?");
                    $taskStmt->bind_param("i", $todo_id);
                    $taskStmt->execute();
                    $tasks = $taskStmt->get_result();
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-dark">
                            <div class="card-body bg-dark">
                                <h5 class="card-title text-white"><?= htmlspecialchars($todo['title']); ?></h5>
                                <ul class="list-group mb-3 taskList " data-todo-id="<?= $todo_id; ?>">
                                    <?php while ($task = $tasks->fetch_assoc()) : ?>
                                        <li class="list-group-item task-item <?= $task['status']; ?>">
                                            <span class="<?= $task['status'] === 'completed' ? 'text-decoration-line-through' : '' ?>">
                                                <?= htmlspecialchars($task['task']); ?>
                                            </span>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <a href="todo.php?id=<?= $todo['id']; ?>" class="btn btn-primary">Add Tasks</a>
                                <a href="delete_todo.php?id=<?= $todo['id']; ?>" class="btn btn-danger"onclick="return confirm('Are you sure you want to delete this to-do list?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search and Filter Functionality
        $(document).ready(function() {
            $('#searchTask').on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                const selectedStatus = $('#filterStatus').val();

                $('.task-item').each(function() {
                    const taskText = $(this).text().toLowerCase();
                    const taskStatus = $(this).attr('class').includes('completed') ? 'completed' : 'incomplete';

                    const matchesSearch = taskText.includes(searchValue);
                    const matchesStatus = selectedStatus === '' || selectedStatus === taskStatus;

                    if (matchesSearch && matchesStatus) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#filterStatus').on('change', function() {
                $('#searchTask').trigger('input');
            });
        });
    </script>
</body>
</html>
