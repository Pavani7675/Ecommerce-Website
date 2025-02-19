<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { text-align: center; font-family: Arial, sans-serif }
         h2{color: darkgreen}
        .container { border-radius:30px; width: 50%; margin:120px auto; padding: 14.5px; border: 1px solid #ddd; box-shadow: 15px 15px 10px rgba(0,0,0,0.1); background: white; }
        .btn {border-radius:7px; background: #007bff; color: white; padding: 10px; text-decoration: none; display: inline-block; margin-top: 20px; }
        footer{
            margin-top:auto;
        }
    </style>
</head>
<body>
<header>
        <div class="header-container">
            <h1>Welcome to Our Store</h1>
            <nav>
                <a href="pages/login.php">Login</a>
                <a href="pages/register.php">Register</a>
                <a href="pages/cart.php" class="cart-link">
                    <img src="../images/cart-icon.png" alt="Cart" class="cart-icon">
                    Cart
                </a>
                <a href="orders.php">your orders</a>
                <!-- Logout button -->
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="logout-button">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Thank You!</h2>
        <p>Your order has been placed successfully.</p>
        <a href="orders.php" class="btn">View Orders</a>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
