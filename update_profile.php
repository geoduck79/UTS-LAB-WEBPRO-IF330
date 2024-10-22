<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Update query
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?");
    
    if ($password) {
        // Hash password if it's being changed
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($password) {
            $stmt->bind_param("sssi", $username, $email, $hashedPassword, $user_id);
        }
    } else {
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

    $stmt->close();
    header('Location: profile.php');
    exit();
}
?>
