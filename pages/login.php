<?php
include('../includes/db.php');  
session_start();

$error_message = ""; // Initialize error message as empty

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Fetching role from database
        
        // Redirect based on role
        if ($user['role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit(); // Exit AFTER redirection
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden; /* Prevent scrolling */
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

label {
    font-size: 1.1em;
    margin-bottom: 5px;
    display: block;
    text-align: left;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    background-color: white;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    font-size: 1.1em;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #218838;
}

.error-message {
    color: #e74c3c;
    font-size: 1em;
    margin-top: 10px;
    display: none;
}

.register-link {
    margin-top: 15px;
    font-size: 1em;
}

.register-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.register-link a:hover {
    text-decoration: underline;
}

footer {
    width: 100%;
    background: #2c3e50 ;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    left: 0;
    margin-top:auto;
}

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" autocomplete="email" required>

            <label>Password:</label>
            <input type="password" name="password" autocomplete="current-password" required>

            <button type="submit" name="login">Login</button>
        </form>
        
        <p class="error-message" id="error-message"><?= htmlspecialchars($error_message); ?></p>
        
        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <script>
        // Show error message only if login failed
        document.addEventListener("DOMContentLoaded", function () {
            let errorMessage = "<?= addslashes($error_message) ?>";
            if (errorMessage.trim() !== "") {
                document.getElementById("error-message").style.display = "block";
            }
        });
    </script>

<footer>
    &copy; <?= date('Y'); ?> Online Store. All rights reserved.
</footer>

</body>
</html>
