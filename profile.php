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
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><?= htmlspecialchars($user['username']); ?>'s To-Do Lists!</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="text-center mb-4">Your Profile</h2>
        <div class="card mb-2">
            <div class="card-body ">
                 <p class="fs-4"><strong>Username:</strong> <span id="displayUsername"><?= htmlspecialchars($user['username']); ?></span></p>
                <p class="fs-4"><strong>Email:</strong> <span id="displayEmail"><?= htmlspecialchars($user['email']); ?></span></p>
              
                <button class="btn btn-primary" id="editButton">Edit Profile</button>
            </div>
        </div>

        <div id="editForm" style="display: none;">
            <form method="POST" action="update_profile.php" class="card mb-4">
                <div class="card-body">
                    <div class="mb-3 ">
                        <label for="username" class="form-label fs-4">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-3 ">
                        <label for="email" class="form-label fs-4">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3 ">
                        <label for="password" class="form-label fs-4">New Password (leave blank if not changing)</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Update Profile</button>
                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                </div>
            </form>
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
