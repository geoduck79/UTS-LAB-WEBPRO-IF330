<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Profile</title>
</head>
<body style="background-image: url('images/1350790.png">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark " >
        <div class="container-fluid">
            <a class="navbar-brand" href="#">To-Do App</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item ">
                        <a class="navbar-brand" href="dashboard.php"><?= htmlspecialchars($user['username']); ?>'s To-Do List</a>
                    </li>
                    <li class="navbar-item">
                        <a class="navbar-brand" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Displayed profile container -->
    <div class="container my-4">
        <h2 class="text-center mb-4 text-white">Your Profile</h2>
        <div class="card mb-2 shadow-sm bg-dark">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fs-4 text-white"><strong>Username:</strong></label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fs-4 text-white"><strong>Email:</strong></label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <button class="btn btn-primary" id="editButton">Edit Profile</button>
            </div>
        </div>

        <!-- Edit Profile Form Container -->
        <div id="editForm" style="display: none;">
            <div class="card mb-4 shadow-sm bg-dark">
                <form method="POST" action="update_profile.php">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="username" class="form-label fs-4 text-white"><strong>Username</strong></label>
                            <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fs-4 text-white"><strong>Email</strong></label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fs-4 text-white"><strong>New Password </strong>(leave blank if not changing)</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Update Profile</button>
                        <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editButton').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editButton').style.display = 'none';
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none';
            document.getElementById('editButton').style.display = 'inline-block';
        });
    </script>
</body>
</html>
