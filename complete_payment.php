<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (!isset($_SESSION['order_details'])) {
    header('Location: checkout.php');
    exit();
}

$order_details = $_SESSION['order_details'];

if (isset($_POST['payment_success'])) {
    // Simulate successful payment
    $payment_id = 'demo_' . time() . '_' . rand(1000, 9999);
    
    // Insert order
    $name = mysqli_real_escape_string($conn, $order_details['name']);
    $email = mysqli_real_escape_string($conn, $order_details['email']);
    $phone = mysqli_real_escape_string($conn, $order_details['phone']);
    $address = mysqli_real_escape_string($conn, $order_details['address']);
    $total = $order_details['total'];
    $payment_method = $order_details['payment_method'];
    
    $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, payment_id, status) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method', '$payment_id', 'paid')";
    mysqli_query($conn, $order_query);
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items
    $cart_items = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id");
    while ($item = mysqli_fetch_assoc($cart_items)) {
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
    }
    
    // Clear cart and session
    mysqli_query($conn, "DELETE FROM cart");
    unset($_SESSION['order_details']);
    
    // Redirect to success page
    header('Location: payment_success.php?demo=1&order_id=' . $order_id . '&payment_id=' . $payment_id);
    exit();
    
} elseif (isset($_POST['payment_failed'])) {
    // Simulate payment failure
    unset($_SESSION['order_details']);
    echo "<script>alert('Payment Failed! Please try again.'); window.location.href='checkout.php';</script>";
}
?>