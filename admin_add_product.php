<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    $image_url = 'uploads/image.jpg'; // Default image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image_url = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_url);
    }
    
    $sql = "INSERT INTO products (name, price, category, image_url) VALUES ('$name', '$price', '$category', '$image_url')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - BIBA Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">BIBA Admin</div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="product.php" class="nav-link">View Products</a></li>
            <li><a href="admin_manage_products.php" class="nav-link">Manage Products</a></li>
            <li><a href="admin_logout.php" class="nav-link" style="background: #e74c3c; padding: 0.5rem 1rem; border-radius: 5px;">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="form-container">
    <h2>Add New Product</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Price *</label>
            <input type="number" name="price" required>
        </div>
        
        <div class="form-group">
            <label>Category *</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Fashion & Apparel">Fashion & Apparel</option>
                <option value="Electronics">Electronics</option>
                <option value="Accessories & Miscellaneous">Accessories & Miscellaneous</option>
                <option value="Toys & Kids">Toys & Kids</option>
                <option value="Fitness & Sports">Fitness & Sports</option>
                <option value="Home & Kitchen">Home & Kitchen</option>
                <option value="Beauty & Personal Care">Beauty & Personal Care</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" accept="image/*">
        </div>
        
        <button type="submit" name="add_product" class="submit-btn">Add Product</button>
    </form>
</div>

</body>
</html>