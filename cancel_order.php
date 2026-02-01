<?php
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    // Update order status to cancelled
    $query = "UPDATE orders SET order_status = 'cancelled' WHERE id = $order_id";
    
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>