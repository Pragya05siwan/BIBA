<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (!isset($_SESSION['order_details'])) {
    header('Location: checkout.php');
    exit();
}

$order_details = $_SESSION['order_details'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - BIBA</title>
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
        <h2>Complete Your Payment</h2>
        
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
            <h3>Order Summary</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($order_details['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order_details['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order_details['phone']) ?></p>
            <p><strong>Total Amount:</strong> ₹<?= $order_details['total'] ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order_details['payment_method']) ?></p>
        </div>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
            <strong>Demo Mode:</strong> This is a working demo payment system. Click below to simulate payment.
        </div>
        
        <div style="margin: 2rem 0;">
            <h3><?= $order_details['payment_method'] ?> Payment</h3>
            
            <?php if ($order_details['payment_method'] == 'UPI'): ?>
                <div style="background: #e8f5e8; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
                    <p>UPI ID: demo@paytm</p>
                    <p>Amount: ₹<?= $order_details['total'] ?></p>
                </div>
            <?php elseif ($order_details['payment_method'] == 'Credit Card' || $order_details['payment_method'] == 'Debit Card'): ?>
                <div style="background: #e3f2fd; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
                    <p>Card: **** **** **** 1234</p>
                    <p>Amount: ₹<?= $order_details['total'] ?></p>
                </div>
            <?php elseif ($order_details['payment_method'] == 'Net Banking'): ?>
                <div style="background: #fce4ec; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
                    <p>Bank: Demo Bank</p>
                    <p>Amount: ₹<?= $order_details['total'] ?></p>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="complete_payment.php">
                <button type="submit" name="payment_success" style="background: #27ae60; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer; margin: 0.5rem;">
                    ✓ Complete Payment
                </button>
                <br><br>
                <button type="submit" name="payment_failed" style="background: #e74c3c; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer;">
                    ✗ Simulate Payment Failure
                </button>
            </form>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="checkout.php" style="color: #666; text-decoration: none;">← Back to Checkout</a>
        </div>
    </div>
</div>

</body>
</html>