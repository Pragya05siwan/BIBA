<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

// Razorpay configuration (Test keys - replace with your actual keys)
$razorpay_key_id = 'rzp_test_1234567890';
$razorpay_key_secret = 'your_secret_key_here';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = $_POST['payment_method'];
    
    // Get cart total
    $cart_query = "SELECT SUM(p.price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id";
    $cart_result = mysqli_query($conn, $cart_query);
    $total = mysqli_fetch_assoc($cart_result)['total'];
    
    if ($payment_method == 'Cash on Delivery') {
        // Process COD order directly
        processOrder($conn, $name, $email, $phone, $address, $total, $payment_method, 'pending');
    } else {
        // Store order details in session for payment processing
        $_SESSION['order_details'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'total' => $total,
            'payment_method' => $payment_method
        ];
        
        // Redirect to payment page
        header('Location: process_payment.php');
        exit();
    }
}

function processOrder($conn, $name, $email, $phone, $address, $total, $payment_method, $status) {
    // Insert order
    $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, status) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method', '$status')";
    mysqli_query($conn, $order_query);
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items
    $cart_items = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id");
    while ($item = mysqli_fetch_assoc($cart_items)) {
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
    }
    
    // Clear cart
    mysqli_query($conn, "DELETE FROM cart");
    
    echo "<script>alert('Order placed successfully!'); window.location.href='orders.php';</script>";
}
?>