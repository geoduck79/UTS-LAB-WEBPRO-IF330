<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Register</title>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100" style="background-image: url('images/1350790.png');">
        <div class="bg-gray text-light p-4 rounded shadow" style="max-width: 400px; width: 100%;">
            <h2 class="text-light text-center">Register</h2>
            <form method="POST">
                <div class="mb-3 position-relative">
                    <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 10px;" id="username-icon"></i>
                    <input type="text" name="username" class="form-control" id="username" required placeholder=" " onfocus="hideIcon('username-icon')" onblur="showIcon('username-icon')">
                    <label for="username" class="form-label">Username</label>
                </div>
                <div class="mb-3 position-relative">
                    <i class="fas fa-envelope position-absolute text-muted" style="left: 10px; top: 10px;" id="email-icon"></i>
                    <input type="email" name="email" class="form-control" id="email" required placeholder=" " onfocus="hideIcon('email-icon')" onblur="showIcon('email-icon')">
                    <label for="email" class="form-label">Email</label>
                </div>
                <div class="mb-3 position-relative">
                    <i class="fas fa-lock position-absolute text-muted" style="left: 10px; top: 10px;" id="password-icon"></i>
                    <input type="password" name="password" class="form-control" id="password" required placeholder=" " onfocus="hideIcon('password-icon')" onblur="showIcon('password-icon')">
                    <label for="password" class="form-label">Password</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <p class="mb-0 me-2">Already have an account?</p>
                <a href="index.php" class="text-light">Login?</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function hideIcon(iconId) {
            document.getElementById(iconId).style.display = 'none';
        }

        function showIcon(iconId) {
            const inputField = document.querySelector(`#${iconId.replace('-icon', '')}`);
            if (inputField.value === '') {
                document.getElementById(iconId).style.display = 'block';
            }
        }
    </script>
</body>
</html>
