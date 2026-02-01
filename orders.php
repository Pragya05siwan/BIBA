<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

$query = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - BIBA</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .navbar {
        background: #654321 !important;
        padding: 1rem 0 !important;
        box-shadow: none !important;
        position: static !important;
    }
    .nav-link {
        color: white !important;
        text-decoration: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0 !important;
        transition: none !important;
        transform: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .nav-link:hover {
        background: transparent !important;
        transform: none !important;
        box-shadow: none !important;
    }
    .nav-link::before {
        display: none !important;
    }
    </style>
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

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">
    <h2>All Orders</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3>Order #<?= $order['id'] ?></h3>
                    <div>
                        <span style="background: <?= $order['order_status'] == 'cancelled' ? '#e74c3c' : '#27ae60' ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">
                            <?= ucfirst($order['order_status']) ?>
                        </span>
                        <?php if ($order['order_status'] != 'cancelled'): ?>
                            <button onclick="cancelOrder(<?= $order['id'] ?>)" style="background: #e74c3c; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; margin-left: 0.5rem;">Cancel Order</button>
                        <?php endif; ?>
                    </div>
                </div>
                <p><strong>Customer:</strong> <?= $order['customer_name'] ?></p>
                <p><strong>Email:</strong> <?= $order['customer_email'] ?></p>
                <p><strong>Phone:</strong> <?= $order['customer_phone'] ?></p>
                <p><strong>Address:</strong> <?= $order['customer_address'] ?></p>
                <p><strong>Total:</strong> ₹<?= $order['total_amount'] ?></p>
                <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?></p>
                <?php if (isset($order['payment_id']) && $order['payment_id']): ?>
                    <p><strong>Payment ID:</strong> <?= $order['payment_id'] ?></p>
                <?php endif; ?>
                <?php if (isset($order['status'])): ?>
                    <p><strong>Payment Status:</strong> 
                        <span style="color: <?= $order['status'] == 'paid' ? '#27ae60' : ($order['status'] == 'pending' ? '#f39c12' : '#e74c3c') ?>; font-weight: bold;">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </p>
                <?php endif; ?>
                <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
                
                <h4>Items:</h4>
                <?php
                $items_query = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = {$order['id']}";
                $items_result = mysqli_query($conn, $items_query);
                while ($item = mysqli_fetch_assoc($items_result)):
                ?>
                    <p>- <?= $item['name'] ?> x <?= $item['quantity'] ?> = ₹<?= $item['price'] * $item['quantity'] ?></p>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No orders found!</p>
    <?php endif; ?>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        fetch('cancel_order.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'order_id=' + orderId
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Order cancelled successfully!');
                location.reload();
            } else {
                alert('Error cancelling order!');
            }
        });
    }
}
</script>

</body>
</html>