<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT c.*, p.price, p.stock FROM cart c 
                        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    $_SESSION['error_message'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}

// Check if stock is available
foreach ($cart_items as $item) {
    if ($item['quantity'] > $item['stock']) {
        $_SESSION['error_message'] = "Some items are out of stock.";
        header("Location: cart.php");
        exit();
    }
}

// Calculate total
$total_cost = 0;
foreach ($cart_items as $item) {
    $total_cost += $item['price'] * $item['quantity'];
}

try {
    $conn->beginTransaction();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->execute([$user_id, $total_cost]);
    $order_id = $conn->lastInsertId();

    // Insert order items & update stock
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);

        // Reduce stock safely
        $stmt = $conn->prepare("UPDATE products SET stock = GREATEST(stock - ?, 0) WHERE id = ?");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }

    // **Delete cart items**
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Ensure cart is empty
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart_check = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($cart_check)) {
        echo "<p style='color:red;'>⚠️ Cart not cleared! Debugging required.</p>";
        print_r($cart_check);
    } else {
        echo "<p>✅ Cart successfully cleared.</p>";
    }

    $conn->commit();
    header("Location: order_success.php");
    exit();
} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header("Location: cart.php");
    exit();
}
?>
