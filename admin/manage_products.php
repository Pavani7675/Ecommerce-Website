<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Fixed Navigation Bar */
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 20px 35px;
            text-align: center;
            font-size: 33px;
            font-weight: bold;
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar .logout {
            background: #ff5733;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-right: 20px; /* Move it slightly to the left */
        }

        .navbar .logout:hover {
            background: darkred;
        }

        /* Full-Width Container */
        .container {
            width: 100%;
            margin-top: 80px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Full-Width Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: middle; /* Aligns text and content in center */
        }

        th {
            background-color: #28a745;
            color: white;
            text-align: center;
        }

        td img {
            width: 50px;
            height: auto;
            display: block;
            margin: auto; /* Centers image */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Buttons Styling */
        .actions {
            display: flex;
            justify-content: center; /* Centers buttons horizontally */
            align-items: center; /* Centers buttons vertically */
            gap: 8px;
        }

        .actions a {
            padding: 6px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            text-align: center;
            width: 70px; /* Ensures uniform button size */
        }

        .edit-btn {
            background-color: #007bff;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: red;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        .btn-back {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        /* Footer Styling */
        .footer {
            width: 100%;
            background:  #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<!-- Fixed Navigation Bar -->
<div class="navbar">
    <span>Welcome to Our Store</span>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- Main Content -->
<div class="container">
    <h2>Manage Products</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
            <th>Stock</th>
        </tr>

        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $product['id']; ?></td>
                <td><?= htmlspecialchars($product['name']); ?></td>
                <td>$<?= number_format($product['price'], 2); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td><img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image"></td>
                <td>
                    <div class="actions">
                        <a href="edit_product.php?id=<?= $product['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_product.php?id=<?= $product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                </td>
                <td><?= ($product['stock'] > 0) ? $product['stock'] : '<span style="color:red;">Out of Stock</span>'; ?></td>

            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

<!-- Footer -->
<footer class="footer">
    &copy; <?= date('Y'); ?> Online Store. All rights reserved.
</footer>

</body>
</html>
