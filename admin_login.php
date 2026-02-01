<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username == '9572841394' && $password == 'pragya9065') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_add_product.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - BIBA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #FFE5F1 0%, #E8F4FD 50%, #FFF2E8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Floating hearts */
        .heart {
            position: absolute;
            color: #FF69B4;
            font-size: 25px;
            animation: float-hearts 6s infinite linear;
        }
        
        .heart:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
        }
        
        .heart:nth-child(2) {
            left: 30%;
            animation-delay: 2s;
        }
        
        .heart:nth-child(3) {
            left: 70%;
            animation-delay: 4s;
        }
        
        .heart:nth-child(4) {
            left: 90%;
            animation-delay: 1s;
        }
        
        @keyframes float-hearts {
            0% {
                bottom: -50px;
                transform: translateX(0px) rotate(0deg);
                opacity: 1;
            }
            100% {
                bottom: 100vh;
                transform: translateX(-100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Twinkling stars */
        .star {
            position: absolute;
            color: #FFD700;
            font-size: 20px;
            animation: twinkle 2s ease-in-out infinite;
        }
        
        .star:nth-child(5) {
            top: 15%;
            left: 20%;
            animation-delay: 0s;
        }
        
        .star:nth-child(6) {
            top: 25%;
            right: 15%;
            animation-delay: 1s;
        }
        
        .star:nth-child(7) {
            bottom: 30%;
            left: 15%;
            animation-delay: 2s;
        }
        
        .star:nth-child(8) {
            top: 60%;
            right: 25%;
            animation-delay: 0.5s;
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.3); }
        }
        
        /* Login container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            animation: bounce-in 1s ease-out;
            border: 3px solid #FFB6C1;
            position: relative;
        }
        
        @keyframes bounce-in {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(-100px);
            }
            50% {
                opacity: 1;
                transform: scale(1.05) translateY(0);
            }
            70% {
                transform: scale(0.95);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #FF69B4, #FFB6C1, #87CEEB, #FFE4E1);
            border-radius: 25px;
            z-index: -1;
            animation: rainbow-border 3s linear infinite;
        }
        
        @keyframes rainbow-border {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }
        
        .logo {
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(135deg, #FF69B4, #87CEEB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            animation: glow-pulse 2s ease-in-out infinite;
        }
        
        @keyframes glow-pulse {
            0%, 100% { filter: drop-shadow(0 0 10px rgba(255, 105, 180, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(135, 206, 235, 0.8)); }
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.2rem;
            animation: fade-in 2s ease-in;
        }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1.2rem;
            border: 2px solid #FFB6C1;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FF69B4;
            box-shadow: 0 0 0 4px rgba(255, 105, 180, 0.2);
            transform: scale(1.02);
            background: white;
        }
        
        .login-btn {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.4);
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .error-message {
            background: linear-gradient(135deg, #FFB6C1, #FFC0CB);
            color: #8B0000;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            animation: shake 0.5s ease-in-out;
            border: 2px solid #FF69B4;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        
        /* Cute floating bubbles */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            animation: float-bubble 8s infinite linear;
        }
        
        .bubble:nth-child(9) {
            width: 30px;
            height: 30px;
            left: 5%;
            animation-delay: 0s;
        }
        
        .bubble:nth-child(10) {
            width: 20px;
            height: 20px;
            left: 85%;
            animation-delay: 3s;
        }
        
        .bubble:nth-child(11) {
            width: 25px;
            height: 25px;
            left: 50%;
            animation-delay: 6s;
        }
        
        @keyframes float-bubble {
            0% {
                bottom: -50px;
                opacity: 0.7;
            }
            50% {
                opacity: 1;
            }
            100% {
                bottom: 100vh;
                opacity: 0;
            }
        }
    </style>
</head>
<body>

<!-- Floating hearts -->
<div class="heart">💖</div>
<div class="heart">💕</div>
<div class="heart">💗</div>
<div class="heart">💝</div>

<!-- Twinkling stars -->
<div class="star">⭐</div>
<div class="star">✨</div>
<div class="star">🌟</div>
<div class="star">💫</div>

<!-- Floating bubbles -->
<div class="bubble"></div>
<div class="bubble"></div>
<div class="bubble"></div>

<!-- Login form -->
<div class="login-container">
    <div class="logo">BIBA</div>
    <div class="subtitle">✨ Admin Portal ✨</div>
    
    <?php if (isset($error)): ?>
        <div class="error-message">❌ <?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>👤 Username</label>
            <input type="text" name="username" class="form-input" required placeholder="Enter your username">
        </div>
        
        <div class="form-group">
            <label>🔒 Password</label>
            <input type="password" name="password" class="form-input" required placeholder="Enter your password">
        </div>
        
        <button type="submit" name="login" class="login-btn">
            🚀 Login to Dashboard
        </button>
    </form>
</div>

</body>
</html>