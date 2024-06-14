<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect_to_login() {
    header('Location: login.php');
    exit;
}

$current_file = basename($_SERVER['PHP_SELF']);

$protected_pages = ['index.php', 'add_colors.php', 'edit_colors.php'];

if (in_array($current_file, $protected_pages) && !is_logged_in()) {
    redirect_to_login();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Color Tricks</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <i class="fas fa-palette"></i> 
            <p class="logo">Color Tricks</p>
        </div>
        <div class="palette-icon" onclick="toggleNav()">
            <i class="fas fa-palette"></i>
        </div>
        <div class="nav-links" id="navLinks">
            <div class="left-links">
                <a href="index.php">View Colors</a>
                <a href="add_colors.php">Add Colors</a>
            </div>
            <div class="right-links">
                <?php if (is_logged_in()): ?>
                    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">

<script>
function toggleNav() {
    var navLinks = document.getElementById("navLinks");
    if (navLinks.style.display === "block") {
        navLinks.style.display = "none";
    } else {
        navLinks.style.display = "block";
    }
}
</script>
