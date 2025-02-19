<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            font-size: 32px;
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
        main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        h2 {
            color: #333;
            font-size: 22px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            width: 250px;
            text-align: center;
        }

        .btn.manage {
            background: #007bff;
        }

        .btn.manage:hover {
            background: #0056b3;
        }

        .btn.add {
            background: #28a745;
        }

        .btn.add:hover {
            background: #218838;
        }

        .btn.logout {
            background: red;
        }

        .btn.logout:hover {
            background: darkred;
        }

        footer {
            width: 100%;
            background-color:   #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            position: absolute;
            bottom: 0;
        }
    </style>
</head>
<body>

    <header>Welcome to E-commerce</header>
    <div class="navbar">
        <span>Welcome to Our Store</span>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <main>
        <h2>Welcome Admin!</h2>
        <a href="manage_products.php" class="btn manage">📦 Manage Products</a>
        <a href="add_product.php" class="btn add">➕ Add Product</a>
        <a href="logout.php" class="btn logout">LOGOUT</a>
    </main>

    <footer>&copy; <?= date("Y"); ?> Online Store. All rights reserved.</footer>

</body>
</html>
