<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
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
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Profile</title>
</head>
<body>
    <div class="container">
        <h2>Your Profile</h2>
        <p><strong>Username:</strong> <?= $user['username']; ?></p>
        <p><strong>Email:</strong> <?= $user['email']; ?></p>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    </div>
</body>
</html>
