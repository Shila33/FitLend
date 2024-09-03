<!-- header.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['username']);
$username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';
?>
<style>
            header {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header nav {
            display: flex;
            gap: 2rem;
        }

        header nav a {
            color: #fff;
            text-decoration: none;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        header .auth-links {
            margin-left: auto;
        }

        header .auth-links a, .auth-links span {
            color: #fff;
            text-decoration: none;
            margin-left: 1rem;
        }

        .auth-links span {
            margin-right: 1rem;
        }

    </style>
<header>
    <div class="logo">FitLend</div>
    <nav>
        <a href="/index.php">Home</a>
        <a href="/equipment.php">Listings</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
    </nav>
    <div class="auth-links">
        <?php if ($is_logged_in): ?>
            <span>Hi, <?php echo $username; ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</header>
