<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BIBA</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <style>
    .navbar {
        background: #654321 !important;
        padding: 1rem 0;
        position: relative;
    }
    .nav-link {
        color: white !important;
        text-decoration: none !important;
        padding: 0.5rem 1rem !important;
    }
    
    .logout-btn {
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        background: #e74c3c;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }
    
    .logout-btn:hover {
        background: #c0392b;
    }
    
    /* Portfolio Section */
    .portfolio-section {
        background: linear-gradient(135deg, #0c1e2e 0%, #1a3a4a 100%);
        min-height: 100vh;
        color: white;
        padding: 2rem 0;
    }
    
    .hero-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        padding: 0 2rem;
    }
    
    .hero-greeting {
        font-size: 1.2rem;
        color: #ccc;
        margin-bottom: 1rem;
    }
    
    .hero-name {
        font-size: 4rem;
        font-weight: bold;
        margin-bottom: 1rem;
        background: linear-gradient(45deg, #00ffff, #ff00ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .hero-title {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .typing-text {
        color: #00ffff;
        border-right: 2px solid #00ffff;
        animation: typing 2s infinite;
    }
    
    @keyframes typing {
        0%, 50% { border-color: #00ffff; }
        51%, 100% { border-color: transparent; }
    }
    
    .hero-description {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #ccc;
    }
    
    .profile-circle {
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: linear-gradient(45deg, #00ffff, #ff00ff, #00ff00);
        padding: 5px;
        animation: rotate 3s linear infinite;
        margin: 0 auto;
    }
    
    .profile-circle img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .hero-container {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .hero-name {
            font-size: 2.5rem;
        }
        .profile-circle {
            width: 300px;
            height: 300px;
        }
    }
    
    /* About Section */
    .about-section {
        padding: 5rem 2rem;
        background: rgba(26, 58, 74, 0.3);
    }
    
    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    
    .about-content h2 {
        font-size: 3rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .highlight {
        color: #00ffff;
    }
    
    .about-content p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        color: white;
    }
    
    /* Skills Section */
    .skills-section {
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #0c1e2e 0%, #1a3a4a 100%);
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }
    
    .skills-section h2 {
        font-size: 3rem;
        margin-bottom: 3rem;
        color: white;
    }
    
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .skill-item {
        background: linear-gradient(45deg, #00ffff, #0080ff);
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        color: white;
        transition: transform 0.3s;
    }
    
    .skill-item:hover {
        transform: scale(1.05);
    }
    
    /* Projects Section */
    .projects-section {
        padding: 5rem 2rem;
        background: rgba(26, 58, 74, 0.3);
    }
    
    .projects-section h2 {
        font-size: 3rem;
        margin-bottom: 3rem;
        color: white;
    }
    
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .project-card {
        background: rgba(12, 30, 46, 0.7);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid #ff00ff;
        transition: transform 0.3s;
    }
    
    .project-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(255, 0, 255, 0.3);
    }
    
    .project-card h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        color: #ff00ff;
    }
    
    .project-card p {
        color: #ccc;
    }
    
    .projects-note {
        font-size: 1.1rem;
        color: black;
        margin-bottom: 3rem;
    }
    
    .work-together {
        background: rgba(12, 30, 46, 0.7);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid #00ffff;
    }
    
    .work-together h3 {
        font-size: 1.5rem;
        color: #00ffff;
        margin-bottom: 1rem;
    }
    
    .work-together p {
        color: #ccc;
        font-size: 1.1rem;
    }
    
    /* Contact Section */
    .contact-section {
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #0c1e2e 0%, #1a3a4a 100%);
    }
    
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }
    
    .contact-info h2 {
        font-size: 3rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .contact-details p {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: #00ffff;
    }
    
    .contact-form {
        background: rgba(12, 30, 46, 0.7);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid #00ffff;
    }
    
    .contact-form h3 {
        color: white;
        margin-bottom: 1.5rem;
    }
    
    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1rem;
        background: rgba(26, 58, 74, 0.7);
        border: 1px solid #555;
        border-radius: 8px;
        color: white;
        font-size: 1rem;
    }
    
    .contact-form input::placeholder,
    .contact-form textarea::placeholder {
        color: #999;
    }
    
    .btn-submit {
        width: 100%;
        background: linear-gradient(45deg, #00ffff, #0080ff);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 25px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .btn-submit:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(0, 255, 255, 0.5);
    }
    
    @media (max-width: 768px) {
        .about-container,
        .contact-container {
            grid-template-columns: 1fr;
            text-align: center;
        }
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

<!-- Portfolio Hero Section -->
<section class="portfolio-section">
    <div class="hero-container">
        <div class="hero-content">
            <p class="hero-greeting">Hello, It's Me</p>
            <h1 class="hero-name">Pragya Kumari</h1>
            <p class="hero-title">And I'm a <span class="typing-text">Web Developer</span></p>
            <p class="hero-description">I am a passionate web developer and final-year BCA student, focused on creating clean, responsive, and user-friendly websites.</p>
            <p class="hero-description">This is my first website <span class="highlight">BIBA Shopping app</span> with <span class="highlight">user registration</span>, <span class="highlight">product management</span>, <span class="highlight">cart system</span>, <span class="highlight">wishlist</span>, <span class="highlight">order tracking</span>, and <span class="highlight">secure payment gateway integration</span>.</p>
        </div>
        <div class="hero-image">
            <div class="profile-circle">
                <img src="uploads/My image.jpeg" alt="Pragya Kumari">
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="about-container">
        <div class="about-image">
            <div class="profile-circle">
                <img src="uploads/My image.jpeg" alt="Pragya Kumari">
            </div>
        </div>
        <div class="about-content">
            <h2>About <span class="highlight">Me</span></h2>
            <p>I am a motivated web developer with strong knowledge of HTML, CSS, JavaScript, and PHP.</p>
            <p>My interest lies in frontend development and basic backend integration.</p>
            <p>I enjoy turning ideas into real websites and continuously learning new technologies to improve my skills.</p>
            <p>I believe a good website should not only look attractive but also be easy to use and accessible for everyone.</p>
        </div>
    </div>
</section>

<!-- Skills Section -->
<section class="skills-section">
    <div class="container">
        <h2>My <span class="highlight">Skills</span></h2>
        <div class="skills-grid">
            <div class="skill-item">HTML5</div>
            <div class="skill-item">CSS3</div>
            <div class="skill-item">JavaScript</div>
            <div class="skill-item">PHP</div>
            <div class="skill-item">MySQL</div>
            <div class="skill-item">Basic Git & GitHub</div>
            <div class="skill-item">Responsive Web Design</div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="projects-section">
    <div class="container">
        <h2>My <span class="highlight">Projects</span></h2>
        <div class="projects-grid">
            <div class="project-card">
                <h3>Shopping Website</h3>
                <p>HTML, CSS, JavaScript, PHP & MySQL</p>
            </div>
            <div class="project-card">
                <h3>Login & Registration System</h3>
                <p>PHP & MySQL</p>
            </div>
            <div class="project-card">
                <h3>Portfolio Website</h3>
                <p>HTML, CSS, JavaScript</p>
            </div>
        </div>
        <p class="projects-note">Each project helped me improve my problem-solving skills and understanding of real-world web development.</p>
        <div class="work-together">
            <h3>🤝 LET'S WORK TOGETHER</h3>
            <p>I am eager to start my career as a fresher web developer where I can apply my skills, learn from experienced professionals, and grow in the IT industry.</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact <span class="highlight">Me</span></h2>
            <div class="contact-details">
                <p>📧 pragyakumari0401@gmail.com</p>
                <p>📞 0123456789</p>
            </div>
        </div>
        <div class="contact-form">
            <h3>Send Me a Message</h3>
            <form action="mailto:pragyakumari0401@gmail.com" method="post" enctype="text/plain">
                <input type="email" name="email" placeholder="Enter Your Email" required>
                <input type="text" name="subject" placeholder="Enter Your Subject" required>
                <textarea name="message" placeholder="Enter Your Message" rows="5" required></textarea>
                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>
    </div>
</section>

<script>
const typingText = document.querySelector('.typing-text');
const words = ['Web Developer', 'Frontend Developer', 'BCA Student'];
let wordIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
    const currentWord = words[wordIndex];
    
    if (isDeleting) {
        typingText.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typingText.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
    }
    
    if (!isDeleting && charIndex === currentWord.length) {
        setTimeout(() => isDeleting = true, 1500);
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
    }
    
    const typingSpeed = isDeleting ? 100 : 150;
    setTimeout(typeEffect, typingSpeed);
}

setTimeout(typeEffect, 1000);
</script>

</body>
</html>
