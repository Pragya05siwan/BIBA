<?php
session_start();
// If already logged in, redirect to home
if (isset($_SESSION['user_logged_in'])) {
    header('Location: index.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "biba_shopping");
if (!$conn) {
    die("Connection failed");
}

if (isset($_POST['submit'])) {

    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);

    // ✅ PASSWORD HASHING 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

    $sql = "INSERT INTO registration (name, email, password, mobile)
            VALUES ('$name', '$email', '$password', '$mobile')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Account Created Successfully');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - BIBA</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .navbar {
        background: #654321 !important;
        padding: 1rem 0 !important;
        box-shadow: none !important;
        position: static !important;
    }
    .nav-link {
        color: white !important;
        text-decoration: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0 !important;
        transition: none !important;
        transform: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .nav-link:hover {
        background: transparent !important;
        transform: none !important;
        box-shadow: none !important;
    }
    .nav-link::before {
        display: none !important;
    }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="index.php" style="color:white;text-decoration:none;">BIBA</a>
        </div>
        <ul class="nav-menu">
            <li><a href="login.php" class="nav-link">Login</a></li>
        </ul>
    </div>
</nav>

<div class="form-container">
    <h2>Create Account</h2>

    <form method="POST">

        <!-- DO NOT TAKE registration_no -->
        
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Mobile *</label>
            <input type="text" name="mobile" required>
        </div>

        <button type="submit" name="submit" class="submit-btn">
            Create Account
        </button>

    </form>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
