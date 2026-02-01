<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "biba_shopping");

if (isset($_POST['login'])) {
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM registration WHERE mobile = '$mobile'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_mobile'] = $user['mobile'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Mobile number not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - BIBA</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        background-image: url('uploads/BIBA.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }
    .form-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login to BIBA</h2>
    
    <?php if (isset($error)): ?>
        <div style="background: red; color: white; padding: 10px; margin: 10px 0;">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" name="login" class="submit-btn">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Don't have account? <a href="register.php">Create Account</a>
    </p>
</div>

</body>
</html>