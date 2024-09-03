<?php
// adminheader.php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already started
}

if (!isset($_SESSION['admin'])) {
    header('Location: adminlogin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/adminheader.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <div class="profile">
            <i class="fa fa-user-circle"></i> Welcome, Admin
        </div>
        <a href="adminlogin.php" class="logout"><i class="fa fa-sign-out"></i>Signout</a>
    </header>
</body>
</html>
