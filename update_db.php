<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Update for Payment System</h2>";

// Add payment_id column
$sql1 = "ALTER TABLE orders ADD COLUMN payment_id VARCHAR(255) NULL AFTER payment_method";
if (mysqli_query($conn, $sql1)) {
    echo "✓ payment_id column added successfully<br>";
} else {
    if (strpos(mysqli_error($conn), "Duplicate column name") !== false) {
        echo "✓ payment_id column already exists<br>";
    } else {
        echo "Error adding payment_id: " . mysqli_error($conn) . "<br>";
    }
}

// Add status column
$sql2 = "ALTER TABLE orders ADD COLUMN status ENUM('pending', 'paid', 'cancelled', 'delivered') DEFAULT 'pending' AFTER payment_id";
if (mysqli_query($conn, $sql2)) {
    echo "✓ status column added successfully<br>";
} else {
    if (strpos(mysqli_error($conn), "Duplicate column name") !== false) {
        echo "✓ status column already exists<br>";
    } else {
        echo "Error adding status: " . mysqli_error($conn) . "<br>";
    }
}

// Update existing orders
$sql3 = "UPDATE orders SET status = 'pending' WHERE status IS NULL OR status = ''";
if (mysqli_query($conn, $sql3)) {
    echo "✓ Existing orders updated with pending status<br>";
} else {
    echo "Error updating orders: " . mysqli_error($conn) . "<br>";
}

echo "<br><strong>Database update completed!</strong><br>";
echo "<a href='index.php'>Go to Home</a> | <a href='checkout.php'>Test Payment</a>";

mysqli_close($conn);
?>