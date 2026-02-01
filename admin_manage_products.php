<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $delete_query = "DELETE FROM products WHERE id = $product_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Product deleted successfully!');</script>";
    }
}

$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - BIBA Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">BIBA Admin</div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="admin_add_product.php" class="nav-link">Add Product</a></li>
            <li><a href="admin_manage_products.php" class="nav-link">Manage Products</a></li>
            <li><a href="admin_logout.php" class="nav-link" style="background: #e74c3c; padding: 0.5rem 1rem; border-radius: 5px;">Logout</a></li>
        </ul>
    </div>
</nav>

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">
    <h2>Manage Products</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <img src="<?= $row['image_url'] ?>" alt="Product" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                    <h3 style="margin-bottom: 0.5rem;"><?= $row['name'] ?></h3>
                    <p style="color: #e74c3c; font-weight: bold; margin-bottom: 0.5rem;">₹<?= $row['price'] ?></p>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;"><?= $row['category'] ?></p>
                    <p style="color: #999; font-size: 0.8rem; margin-bottom: 1rem;">ID: <?= $row['id'] ?></p>
                    
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete_product" style="background: #e74c3c; color: white; border: none; padding: 0.7rem 1rem; border-radius: 6px; cursor: pointer; width: 100%;">
                            Delete Product
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No products found!</p>
    <?php endif; ?>
</div>

</body>
</html>