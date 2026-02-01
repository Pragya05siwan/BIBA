<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

// Handle demo mode
if (isset($_GET['demo']) && $_GET['demo'] == '1') {
    $order_id = $_GET['order_id'];
    $payment_id = $_GET['payment_id'];
    $order_query = "SELECT * FROM orders WHERE id = $order_id";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);
} else {
    // Handle real payment
    if (!isset($_SESSION['order_details']) || !isset($_POST['razorpay_payment_id'])) {
        header('Location: checkout.php');
        exit();
    }
    
    $order_details = $_SESSION['order_details'];
    $payment_id = $_POST['razorpay_payment_id'];
    
    // Insert order with payment details
    $name = mysqli_real_escape_string($conn, $order_details['name']);
    $email = mysqli_real_escape_string($conn, $order_details['email']);
    $phone = mysqli_real_escape_string($conn, $order_details['phone']);
    $address = mysqli_real_escape_string($conn, $order_details['address']);
    $total = $order_details['total'];
    $payment_method = $order_details['payment_method'];
    
    $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, payment_id, status) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method', '$payment_id', 'paid')";
    if (!mysqli_query($conn, $order_query)) {
        // If columns don't exist, try without them
        $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method')";
        mysqli_query($conn, $order_query);
    }
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items
    $cart_items = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id");
    while ($item = mysqli_fetch_assoc($cart_items)) {
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
    }
    
    // Clear cart and session
    mysqli_query($conn, "DELETE FROM cart");
    unset($_SESSION['order_details']);
    
    $order = [
        'customer_email' => $email,
        'total_amount' => $total,
        'payment_method' => $payment_method
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success - BIBA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo"><a href="index.php" style="color: white; text-decoration: none;">BIBA</a></div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="register.php" class="nav-link">Create Account</a></li>
            <li><a href="product.php" class="nav-link">Products</a></li>
            <li><a href="cart.php" class="nav-link">Cart</a></li>
            <li><a href="orders.php" class="nav-link">Orders</a></li>
            <li><a href="wishlist.php" class="nav-link">Wishlist</a></li>
        </ul>
    </div>
</nav>

<div style="max-width: 600px; margin: 2rem auto; padding: 0 2rem;">
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
        <div style="color: #27ae60; font-size: 4rem; margin-bottom: 1rem;">✓</div>
        <h2 style="color: #27ae60; margin-bottom: 1rem;">Payment Successful!</h2>
        
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
            <p><strong>Order ID:</strong> #<?= $order_id ?></p>
            <p><strong>Payment ID:</strong> <?= htmlspecialchars($payment_id) ?></p>
            <p><strong>Amount Paid:</strong> ₹<?= $order['total_amount'] ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p></p>
        </div>
        
        <p>Thank you for your purchase! Your order has been confirmed and will be processed shortly.</p>
        <p>You will receive an email confirmation at <strong><?= htmlspecialchars($email) ?></strong></p>
        
        <div style="margin-top: 2rem;">
            <a href="orders.php" style="background: #e74c3c; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 6px; margin: 0.5rem; display: inline-block;">View Orders</a>
            <a href="product.php" style="background: #3498db; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 6px; margin: 0.5rem; display: inline-block;">Continue Shopping</a>
        </div>
    </div>
</div>

</body>
</html>