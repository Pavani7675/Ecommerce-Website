<?php
include('../includes/db.php');  
session_start();

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before saving
    $role = isset($_POST['role']) ? $_POST['role'] : 'user'; // Default role for regular users

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $error_message = "Email is already registered!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);

        // After registration, log the user in and redirect
        $_SESSION['user_id'] = $conn->lastInsertId();
        header("Location: " . ($role == 'admin' ? "../admin/dashboard.php" : "../index.php"));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .register-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
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
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            background-color: white;
        }
        input[readonly] {
            background-color: #f8f8f8;
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
            text-align: center;
            margin-top: 10px;
        }
        /* Login Link */
        .login-link {
            margin-top: 15px;
            font-size: 1em;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .footer {
            width: 100%;
            background:  #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            position: absolute;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Register</h2>
        <form method="POST" autocomplete="off">
            <label>Email:</label>
            <input type="email" name="email" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">

            <label>Password:</label>
            <input type="password" name="password" required autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');">

            <label for="role">Role:</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="register">Register</button>
        </form>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <!-- Login Link Below Register Button -->
        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
    </div>

    <footer class="footer">
        &copy; <?= date('Y'); ?> Online Store. All rights reserved.
    </footer>

</body>
</html>
