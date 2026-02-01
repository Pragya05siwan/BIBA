<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

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
        $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, status) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method', 'pending')";
        if (!mysqli_query($conn, $order_query)) {
            // If status column doesn't exist, try without it
            $order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method) VALUES ('$name', '$email', '$phone', '$address', $total, '$payment_method')";
            mysqli_query($conn, $order_query);
        }
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items
        $cart_items = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id");
        while ($item = mysqli_fetch_assoc($cart_items)) {
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
        }
        
        // Clear cart
        mysqli_query($conn, "DELETE FROM cart");
        
        echo "<script>alert('Order placed successfully with Cash on Delivery!'); window.location.href='orders.php';</script>";
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
        
        // Choose payment system: demo or real
        $use_demo = true; // Set to false for real Razorpay
        
        if ($use_demo) {
            header('Location: working_payment.php'); // Demo payment
        } else {
            header('Location: process_payment.php'); // Real Razorpay
        }
        exit();
    }
}

$cart_query = "SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id";
$cart_result = mysqli_query($conn, $cart_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - BIBA</title>
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

<div style="max-width: 800px; margin: 2rem auto; padding: 0 2rem;">
    <h2>Checkout</h2>
    
    <form method="POST" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h3>Customer Information</h3>
        
        <div style="margin-bottom: 1rem;">
            <label>Name:</label><br>
            <input type="text" name="name" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>Email:</label><br>
            <input type="email" name="email" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>Phone:</label><br>
            <input type="text" name="phone" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>Address:</label><br>
            <textarea name="address" required style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px; height: 100px;"></textarea>
        </div>
        
        <h3>Payment Method</h3>
        <div style="margin-bottom: 1rem;">
            <label><input type="radio" name="payment_method" value="Cash on Delivery" checked onchange="togglePaymentDetails()"> Cash on Delivery</label><br>
            <label><input type="radio" name="payment_method" value="Credit Card" onchange="togglePaymentDetails()"> Credit Card</label><br>
            <label><input type="radio" name="payment_method" value="Debit Card" onchange="togglePaymentDetails()"> Debit Card</label><br>
            <label><input type="radio" name="payment_method" value="UPI" onchange="togglePaymentDetails()"> UPI</label><br>
            <label><input type="radio" name="payment_method" value="Net Banking" onchange="togglePaymentDetails()"> Net Banking</label>
        </div>
        
        <!-- Credit/Debit Card Details -->
        <div id="cardDetails" style="display: none; background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <h4>Card Details</h4>
            <div style="margin-bottom: 1rem;">
                <label>Card Number:</label><br>
                <input type="text" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <label>Expiry Date:</label><br>
                    <input type="text" name="expiry_date" placeholder="MM/YY" maxlength="5" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div style="flex: 1;">
                    <label>CVV:</label><br>
                    <input type="text" name="cvv" placeholder="123" maxlength="3" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Cardholder Name:</label><br>
                <input type="text" name="cardholder_name" placeholder="John Doe" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
            </div>
        </div>
        
        <!-- UPI Details -->
        <div id="upiDetails" style="display: none; background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <h4>UPI Details</h4>
            <div style="margin-bottom: 1rem;">
                <label>UPI ID:</label><br>
                <input type="text" name="upi_id" placeholder="yourname@paytm" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
            </div>
        </div>
        
        <!-- Net Banking Details -->
        <div id="bankDetails" style="display: none; background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <h4>Select Your Bank</h4>
            <select name="bank_name" style="width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 6px;">
                <option value="">Select Bank</option>
                <option value="SBI">State Bank of India</option>
                <option value="HDFC">HDFC Bank</option>
                <option value="ICICI">ICICI Bank</option>
                <option value="Axis">Axis Bank</option>
                <option value="PNB">Punjab National Bank</option>
            </select>
        </div>
        
        <h3>Order Summary</h3>
        <?php 
        $total = 0;
        while ($item = mysqli_fetch_assoc($cart_result)): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <p><?= $item['name'] ?> x <?= $item['quantity'] ?> = ₹<?= $subtotal ?></p>
        <?php endwhile; ?>
        
        <h4>Total: ₹<?= $total ?></h4>
        
        <button type="submit" style="background: #e74c3c; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer;">Place Order</button>
    </form>
</div>

<script>
function togglePaymentDetails() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    // Hide all payment details
    document.getElementById('cardDetails').style.display = 'none';
    document.getElementById('upiDetails').style.display = 'none';
    document.getElementById('bankDetails').style.display = 'none';
    
    // Show relevant payment details
    if (paymentMethod === 'Credit Card' || paymentMethod === 'Debit Card') {
        document.getElementById('cardDetails').style.display = 'block';
        // Make card fields required
        document.querySelector('input[name="card_number"]').required = true;
        document.querySelector('input[name="expiry_date"]').required = true;
        document.querySelector('input[name="cvv"]').required = true;
        document.querySelector('input[name="cardholder_name"]').required = true;
    } else if (paymentMethod === 'UPI') {
        document.getElementById('upiDetails').style.display = 'block';
        document.querySelector('input[name="upi_id"]').required = true;
    } else if (paymentMethod === 'Net Banking') {
        document.getElementById('bankDetails').style.display = 'block';
        document.querySelector('select[name="bank_name"]').required = true;
    } else {
        // Cash on Delivery - no additional fields required
        document.querySelector('input[name="card_number"]').required = false;
        document.querySelector('input[name="expiry_date"]').required = false;
        document.querySelector('input[name="cvv"]').required = false;
        document.querySelector('input[name="cardholder_name"]').required = false;
        document.querySelector('input[name="upi_id"]').required = false;
        document.querySelector('select[name="bank_name"]').required = false;
    }
}

// Format card number input
document.addEventListener('DOMContentLoaded', function() {
    const cardInput = document.querySelector('input[name="card_number"]');
    if (cardInput) {
        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    const expiryInput = document.querySelector('input[name="expiry_date"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0,2) + '/' + value.substring(2,4);
            }
            e.target.value = value;
        });
    }
});
</script>

</body>
</html>