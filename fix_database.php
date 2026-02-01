<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

echo "<h2>Database Fix for Payment System</h2>";

// Check if columns exist and add them
$result = mysqli_query($conn, "DESCRIBE orders");
$columns = [];
while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row['Field'];
}

// Add payment_id column if not exists
if (!in_array('payment_id', $columns)) {
    $sql = "ALTER TABLE orders ADD COLUMN payment_id VARCHAR(255) NULL";
    if (mysqli_query($conn, $sql)) {
        echo "✅ payment_id column added<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✅ payment_id column already exists<br>";
}

// Add status column if not exists
if (!in_array('status', $columns)) {
    $sql = "ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'pending'";
    if (mysqli_query($conn, $sql)) {
        echo "✅ status column added<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✅ status column already exists<br>";
}

echo "<br><strong>✅ Database ready for payment system!</strong><br>";
echo "<a href='checkout.php' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Payment Now</a>";
?>