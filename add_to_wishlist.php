<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product already in wishlist
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE product_id = $product_id");
    
    if (mysqli_num_rows($check) == 0) {
        // Add to wishlist
        mysqli_query($conn, "INSERT INTO wishlist (product_id) VALUES ($product_id)");
        echo "success";
    } else {
        echo "already_added";
    }
}
?>