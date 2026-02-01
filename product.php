<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $query = "SELECT * FROM products WHERE name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' OR category LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
} elseif ($category) {
    $query = "SELECT * FROM products WHERE category = '" . mysqli_real_escape_string($conn, $category) . "'";
} else {
    $query = "SELECT * FROM products";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - BIBA</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <style>
    .navbar {
        background: #654321;
        padding: 1.2rem 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo"><a href="index.php" style="color: white; text-decoration: none;">BIBA</a></div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="product.php" class="nav-link">Products</a></li>
            <li><a href="cart.php" class="nav-link">Cart</a></li>
            <li><a href="orders.php" class="nav-link">Orders</a></li>
            <li><a href="wishlist.php" class="nav-link">Wishlist</a></li>
            <li><a href="logout.php" class="nav-link" style="background: #e74c3c; color: white; padding: 0.5rem 1rem; border-radius: 5px;">Logout</a></li>
        </ul>
        <div style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: white; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 20px;">👤</span>
            <span style="font-size: 14px;"><?= $_SESSION['user_name'] ?></span>
        </div>
    </div>
</nav>

<div class="search-container" style="background: transparent; padding: 1rem 0; border-bottom: none;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
        <form method="GET" style="display: flex; gap: 0.5rem; justify-content: center; align-items: center;">
            <div style="position: relative; display: flex; align-items: center;">
                <input type="text" name="search" id="searchInput" placeholder="Search products..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" style="padding: 1rem 4rem 1rem 1rem; border: 2px solid #ddd; border-radius: 8px; width: 600px; font-size: 1.1rem;">
                <button type="button" id="voiceBtn" style="position: absolute; right: 50px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">🎤</button>
                <button type="button" id="cameraBtn" style="position: absolute; right: 15px; background: none; border: none; cursor: pointer; font-size: 1.2rem;">📷</button>
            </div>
            <button type="submit" style="background: #333; color: white; padding: 1rem 2rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">Search</button>
            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <a href="product.php" style="background: #e74c3c; color: white; padding: 0.7rem 1rem; border-radius: 6px; text-decoration: none;">Clear</a>
            <?php endif; ?>
        </form>
        
        <!-- Camera Modal -->
        <div id="cameraModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; justify-content: center; align-items: center;">
            <div style="background: white; padding: 2rem; border-radius: 12px; text-align: center;">
                <video id="video" width="400" height="300" autoplay style="border-radius: 8px;"></video>
                <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>
                <div style="margin-top: 1rem;">
                    <button id="captureBtn" style="background: #333; color: white; padding: 0.7rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; margin-right: 0.5rem;">Capture</button>
                    <button id="closeCameraBtn" style="background: #e74c3c; color: white; padding: 0.7rem 1.5rem; border: none; border-radius: 6px; cursor: pointer;">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="category-filter" style="background: transparent; padding: 1rem 0; border-bottom: none;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="product.php" style="background: #333; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">All Products</a>
            <a href="product.php?category=Fashion%20%26%20Apparel" style="background: #e74c3c; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Fashion & Apparel</a>
            <a href="product.php?category=Electronics" style="background: #3498db; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Electronics</a>
            <a href="product.php?category=Accessories%20%26%20Miscellaneous" style="background: #9b59b6; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Accessories & Miscellaneous</a>
            <a href="product.php?category=Toys%20%26%20Kids" style="background: #f39c12; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Toys & Kids</a>
            <a href="product.php?category=Fitness%20%26%20Sports" style="background: #27ae60; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Fitness & Sports</a>
            <a href="product.php?category=Home%20%26%20Kitchen" style="background: #e67e22; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Home & Kitchen</a>
            <a href="product.php?category=Beauty%20%26%20Personal%20Care" style="background: #e91e63; color: white; padding: 0.7rem 1.5rem; border-radius: 25px; text-decoration: none; font-size: 0.9rem;">Beauty & Personal Care</a>
        </div>
    </div>
</div>

<div class="products" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <div class="card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; overflow: hidden;">
            <?php 
            $imagePath = isset($row['image_url']) && !empty($row['image_url']) ? $row['image_url'] : 'uploads/image.jpg';
            echo '<img src="' . $imagePath . '" alt="Product Image" style="width: 250px; height: 250px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">';
            ?>
            <h3 style="color: #333; margin-bottom: 0.8rem; font-size: 1.2rem; font-weight: 600;"><?= $row['name'] ?></h3>
            <p style="color: #e74c3c; font-size: 1.3rem; font-weight: bold; margin-bottom: 0.5rem;">₹<?= $row['price'] ?></p>
            <p style="color: #7f8c8d; font-size: 0.9rem; background: #ecf0f1; padding: 0.3rem 0.8rem; border-radius: 15px; display: inline-block; margin-bottom: 1rem;"><?= $row['category'] ?></p>
            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                <button onclick="addToCart(<?= $row['id'] ?>)" style="background: #333; color: white; border: none; padding: 0.7rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.9rem;">Add to Cart</button>
                <button onclick="addToWishlist(<?= $row['id'] ?>)" style="background: #e74c3c; color: white; border: none; padding: 0.7rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.9rem;">Wishlist</button>
            </div>
        </div>
<?php
    }
} else {
    echo "<p style='text-align:center;'>No products found</p>";
}
?>

</div>

<script src="search.js"></script>

</body>
</html>