<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found!";
    exit();
}

// Handle form submission
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Debugging: Check received values
    echo "<p>Stock value received: " . htmlspecialchars($stock) . "</p>";

    // If a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");

        // Update query with image
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ?, image = ? WHERE id = ?");
        $result = $stmt->execute([$name, $price, $description, $stock, $image, $product_id]);
    } else {
        // Update query without changing the image
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ? WHERE id = ?");
        $result = $stmt->execute([$name, $price, $description, $stock, $product_id]);
    }

    // Debugging: Check if query executed successfully
    if ($result) {
        $successMessage = "Product updated successfully!";
    } else {
        echo "<p style='color:red;'>Error updating product!</p>";
        print_r($stmt->errorInfo()); // Print error details
    }

    // Refresh product details after update
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            width: 50%;
            margin: 80px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin: 10px 0 5px;
            display: block;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            font-size: 16px;
            color: green;
            margin-top: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }
        .current-image {
            text-align: center;
            margin-bottom: 15px;
        }
        .current-image img {
            max-width: 100px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <span>Welcome to Our Store</span>
        <a href="manage_products.php" style="color:white; text-decoration:none;">Manage Products</a>
    </div>

    <!-- Edit Product Form -->
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($product['description']); ?></textarea>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock']); ?>" required>

            <label for="image">Image:</label>
            <div class="current-image">
                <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Current Image">
            </div>
            <input type="file" name="image" id="image">

            <button type="submit" name="update_product">Update Product</button>
        </form>

        <!-- Success Message -->
        <?php if (isset($successMessage)): ?>
            <div class="message"><?= $successMessage; ?></div>
        <?php endif; ?>

        <!-- Back Link -->
        <div class="back-link">
            <a href="manage_products.php">Back to Manage Products</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; <?= date('Y'); ?> Online Store. All rights reserved.
    </footer>

</body>
</html>
