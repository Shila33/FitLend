<?php
// register.php
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $username, $email, $password, $phone);
    
    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        $error = 'Registration failed. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <section class="register-form">
        <h2>Register</h2>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <button type="submit"><b>Register</b></button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </section>
</body>
</html>
