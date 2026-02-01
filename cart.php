<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

$query = "SELECT c.*, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - BIBA</title>
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
    <h2>Shopping Cart</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <?php 
            $total = 0;
            while ($row = mysqli_fetch_assoc($result)): 
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
            ?>
                <div class="card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 300px;">
                    <img src="<?= $row['image_url'] ?>" alt="Product" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                    <h3><?= $row['name'] ?></h3>
                    <p>Price: ₹<?= $row['price'] ?></p>
                    <p>Quantity: <?= $row['quantity'] ?></p>
                    <p><strong>Subtotal: ₹<?= $subtotal ?></strong></p>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div style="margin-top: 2rem; text-align: center;">
            <h3>Total: ₹<?= $total ?></h3>
            <button onclick="checkout()" style="background: #e74c3c; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer;">Checkout</button>
        </div>
    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>
</div>

<script>
function checkout() {
    window.location.href = 'checkout.php';
}
</script>

</body>
</html>