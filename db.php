<?php
$host = 'localhost';
$dbname = 'todolist';  // Nama database yang sudah dibuat di phpMyAdmin
$user = 'root';        // Username default MySQL di XAMPP
$pass = '';            // Password default biasanya kosong di XAMPP

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
