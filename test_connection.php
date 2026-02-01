<?php
echo "<h2>Database Connection Test</h2>";

// Test different connection methods
$hosts = ['localhost', '127.0.0.1', 'localhost:3306', '127.0.0.1:3306'];

foreach ($hosts as $host) {
    echo "<br>Testing connection to: $host<br>";
    $conn = @mysqli_connect($host, "root", "", "biba_shopping");
    
    if ($conn) {
        echo "✅ SUCCESS: Connected to $host<br>";
        mysqli_close($conn);
        break;
    } else {
        echo "❌ FAILED: " . mysqli_connect_error() . "<br>";
    }
}

// Test if MySQL service is running
echo "<br><h3>MySQL Service Check:</h3>";
echo "Run this in Command Prompt:<br>";
echo "<code>netstat -an | findstr 3306</code><br>";
echo "<br>If no output, MySQL is not running on port 3306<br>";
?>