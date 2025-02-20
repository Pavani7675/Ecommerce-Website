### **Project Overview**
---

This e-commerce website is designed for Customers and Admins to facilitate online shopping and product management. Below is a structured explanation of the project components.

### ***1. User Section***

This section explains how users interact with the platform.

**1.1. User Registration**

Users can register on the platform via register.php by providing their name, email, and password.

**1.2. User Login & Logout**

Login: Users log in via login.php using their email and password.

Logout: Users can securely log out through logout.php.

**1.3. Viewing Products**

The homepage (index.php) displays all available products with details such as name, price, and description.

**1.4. Adding Products to Cart**

Users can add products to their cart using the "Add to Cart" button on the index.php page.

**1.5. Managing the Cart**

The cart.php page allows users to view, update, or remove items from their cart.

**1.6. Proceeding to Checkout**

Users can proceed to checkout from cart.php, placing an order for selected items.

---

### ***2. Admin Section***

This section explains how administrators manage the e-commerce platform.

**2.1. Admin Login & Logout**

Login: Admins log in through admin/login.php.

Logout: Admins securely log out via admin/logout.php.

**2.2. Admin Dashboard**

The admin/dashboard.php page provides a control panel for managing products and orders.

**2.3. Adding New Products**

Admins can add products using admin/add_product.php by entering product details and uploading images.

**2.4. Managing Products**

The admin/manage_products.php page allows editing and deleting existing products.

**2.5. Managing Orders**

Admins can view, process, and update order statuses in the admin panel.

---

### ***3. Database Section***

This section explains the database structure used in the project.

**3.1. Users Table**

Stores user information: id, name, email, password, and role (customer/admin).

**3.2. Products Table**

Stores product details: id, name, price, description, and image.

**3.3. Cart Table**

Tracks items in users’ carts: id, user_id, product_id, and quantity.

**3.4. Orders & Order Items Tables**

orders table stores placed orders with order_id, user_id, and total_amount.

order_items table links orders to products with order_id, product_id, quantity, and price.

---

### ***4. Website Flow***

This section explains how different components interact.

**4.1. User Registration & Login**

User details are validated and stored in the users table with hashed passwords.

**4.2. Product Addition & Management**

Admins add products, which get stored in the products table and displayed on the homepage.

**4.3. Cart & Checkout Process**

Users add products to the cart, which stores entries in the cart table.

At checkout, an order is placed, updating the orders and order_items tables.

**4.4. Admin Order Management**

Admins update order statuses, ensuring smooth transactions.

---

### ***5. Security Measures***

This section explains how the website ensures security.

**5.1. Password Security**

Passwords are hashed before storage to enhance security.

**5.2. Session Management**

Sessions ($_SESSION['user_id'] or $_SESSION['admin_id']) control user access.

**5.3. Admin Access Restriction**

The system checks the role field to allow only admins into the dashboard.

---

### ***Conclusion***

This documentation provides a structured overview of the E-commerce Website, covering user and admin functionalities, database structure, workflow, and security measures.

