<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product already in cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE product_id = $product_id");
    
    if (mysqli_num_rows($check) > 0) {
        // Update quantity
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE product_id = $product_id");
    } else {
        // Add new item
        mysqli_query($conn, "INSERT INTO cart (product_id) VALUES ($product_id)");
    }
    
    echo "success";
}
?>