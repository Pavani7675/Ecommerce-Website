<?php
session_start();
include '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items with stock check
$stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.stock 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
$out_of_stock = false;
$error_message = "";

foreach ($cart_items as $item) {
    // Check if requested quantity exceeds available stock
    if ($item['quantity'] > $item['stock']) {
        $out_of_stock = true;
        $error_message .= "Item '{$item['name']}' is out of stock! Available stock: {$item['stock']}.<br>";
    }
    $total_cost += $item['price'] * $item['quantity'];
}

// Redirect if cart is empty
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$out_of_stock) {
    $address = $_POST['address'];
    $payment_method = $_POST['payment'];
    
    // Redirect to process checkout
    $_SESSION['checkout_address'] = $address;
    $_SESSION['checkout_payment'] = $payment_method;
    header("Location: process_checkout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { width: 50%; margin: 50px auto; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        label { margin: 10px 0 5px; }
        input, textarea, select { padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { background: #28a745; color: white; padding: 10px; border: none; cursor: pointer; }
        .btn:hover { background: #218838; }
        .error-message { color: red; font-weight: bold; text-align: center; }
        footer { margin-top: 100vh; }
    </style>
</head>
<body>
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
            <a href="orders.php">Your Orders</a>
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Checkout</h2>
    
    <?php if ($out_of_stock): ?>
        <p class="error-message"><?= $error_message; ?></p>
    <?php else: ?>
        <p><strong>Total Amount: $<?= number_format($total_cost, 2) ?></strong></p>
        <form method="POST">
            <label for="address">Shipping Address</label>
            <textarea name="address" id="address" required></textarea>

            <label for="payment">Payment Method</label>
            <select name="payment" required>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>

            <button type="submit" class="btn">Place Order</button>
        </form>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
</footer>
</body>
</html>
