<?php
// contact.php
include 'userheader.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $email, $phone, $message);
    $stmt->execute();

    $success = 'Your message has been sent successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <section class="contact-form">
        <h2>Contact Us</h2>
        <form method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Sheila" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="youremail@example.com" required>

            <label for="phone">Mobile</label>
            <input type="tel" id="phone" name="phone" minlength="10" maxlength="10" placeholder="07123456789" required>
            
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
            
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <button type="submit">Send</button>
        </form>
    </section>
    <?php include 'userfooter.php'; ?>
</body>
</html>
