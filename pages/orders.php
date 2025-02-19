<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* General Page Styling */
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Main Content Container */
        .main-container {
            flex-grow: 1; /* Allows content to grow and push footer down */
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Orders Table Styling */
        table {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
            text-transform: uppercase;
        }

        .no-orders {
            font-size: 20px;
            color: red;
            margin-top: 20px;
        }

        /* Back to Shop Button */
        .cart-actions {
            margin-top: 10px;
            display: flex;
            justify-content: center; /* Centers button */
            width: 100%;
        }

        .cart-actions a {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .cart-actions a:hover {
            background-color: #218838;
        }

        /* Footer - Stays at Bottom */
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Fixed Navigation Bar -->
    <header>
        <div class="header-container">
            <h1>Welcome to Our Store</h1>
            <nav>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="cart.php" class="cart-link">
                    <img src="../images/cart-icon.png" alt="Cart" class="cart-icon">
                    Cart
                </a>
                <a href="../index.php">BACK TO SHOP</a>
                <!-- Logout button -->
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="logout-button">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <h2>Your Orders</h2>

        <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                        <td><?= $order['status'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders made by you.</p>
        <?php endif; ?>

        <!-- Back to Shop Button (Will Move Along with Table) -->
        <div class="cart-actions">
            <a href="../index.php">Back to Shop</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
