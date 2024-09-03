<?php
// userheader.php
session_start();
require 'db.php';

$logged_in = isset($_SESSION['user']);
$username = $logged_in ? $_SESSION['user']['username'] : '';

function displayNavLink($link, $label) {
    echo "<li><a href='$link'>$label</a></li>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/userheader.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="equipment.php">Equipment</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <?php if ($logged_in): ?>
                    <li class="username">Hi, <?php echo htmlspecialchars($username); ?></li>
                    <li><a href="reservations.php">🎖️My Reservations</a></li>
                    <li><a href="logout.php">🏃Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
