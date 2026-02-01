<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (!isset($_SESSION['order_details'])) {
    header('Location: checkout.php');
    exit();
}

$order_details = $_SESSION['order_details'];
$amount = $order_details['total'] * 100; // Convert to paise for Razorpay

// Razorpay configuration - Get these from https://razorpay.com/
$razorpay_key_id = 'rzp_test_YOUR_KEY_HERE'; // Replace with your actual test key
$razorpay_key_secret = 'YOUR_SECRET_KEY_HERE'; // Keep this secret
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - BIBA</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); min-height: 100vh;">

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">BIBA</div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="product.php" class="nav-link">Products</a></li>
            <li><a href="cart.php" class="nav-link">Cart</a></li>
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
        
        <?php if ($order_details['payment_method'] == 'UPI'): ?>
            <div style="margin: 2rem 0;">
                <h3>UPI Payment</h3>
                <button id="upi-pay-btn" style="background: #00D4AA; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer; margin: 0.5rem;">
                    Pay with UPI
                </button>
            </div>
        <?php elseif ($order_details['payment_method'] == 'Credit Card' || $order_details['payment_method'] == 'Debit Card'): ?>
            <div style="margin: 2rem 0;">
                <h3>Card Payment</h3>
                <button id="card-pay-btn" style="background: #3498db; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer; margin: 0.5rem;">
                    Pay with Card
                </button>
            </div>
        <?php elseif ($order_details['payment_method'] == 'Net Banking'): ?>
            <div style="margin: 2rem 0;">
                <h3>Net Banking</h3>
                <button id="netbanking-pay-btn" style="background: #e74c3c; color: white; padding: 1rem 2rem; border: none; border-radius: 6px; font-size: 1.1rem; cursor: pointer; margin: 0.5rem;">
                    Pay with Net Banking
                </button>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="checkout.php" style="color: #666; text-decoration: none;">← Back to Checkout</a>
        </div>
    </div>
</div>

<script>
var options = {
    "key": "<?= $razorpay_key_id ?>",
    "amount": "<?= $amount ?>",
    "currency": "INR",
    "name": "BIBA Shopping",
    "description": "Order Payment",
    "image": "https://your-logo-url.com/logo.png",
    "handler": function (response){
        // Payment successful
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'payment_success.php';
        
        var paymentId = document.createElement('input');
        paymentId.type = 'hidden';
        paymentId.name = 'razorpay_payment_id';
        paymentId.value = response.razorpay_payment_id;
        form.appendChild(paymentId);
        
        document.body.appendChild(form);
        form.submit();
    },
    "prefill": {
        "name": "<?= htmlspecialchars($order_details['name']) ?>",
        "email": "<?= htmlspecialchars($order_details['email']) ?>",
        "contact": "<?= htmlspecialchars($order_details['phone']) ?>"
    },
    "notes": {
        "address": "<?= htmlspecialchars($order_details['address']) ?>"
    },
    "theme": {
        "color": "#e74c3c"
    }
};

// UPI Payment
document.getElementById('upi-pay-btn')?.addEventListener('click', function(e){
    options.method = {
        "upi": true
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
});

// Card Payment
document.getElementById('card-pay-btn')?.addEventListener('click', function(e){
    options.method = {
        "card": true
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
});

// Net Banking Payment
document.getElementById('netbanking-pay-btn')?.addEventListener('click', function(e){
    options.method = {
        "netbanking": true
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
});
</script>

</body>
</html>