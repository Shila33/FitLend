<?php
// userfooter.php
include 'db.php';

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    } else {
        // Check if the email is already subscribed
        $stmt = $conn->prepare("SELECT * FROM Subscriptions WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email is already subscribed
            $errorMessage = 'This email is already subscribed to the newsletter.';
        } else {
            // Insert the new subscription
            $stmt = $conn->prepare("INSERT INTO Subscriptions (email) VALUES (?)");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $successMessage = 'Thank you for subscribing to our newsletter!';
            } else {
                $errorMessage = 'Sorry, there was an error subscribing. Please try again later.';
            }
        }

        $stmt->close();
    }
}
?>

<footer>
<link rel="stylesheet" href="css/userfooter.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="footer-content">
        <div class="quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="equipment.php">Equipment</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="services">
            <h3>Services</h3>
            <ul>
                <li>Equipment Rental</li>
                <li>Fitness Guidance</li>
                <li>Consultancy</li>
                <li>Customer Support</li>
            </ul>
        </div>
        <div class="social-media">
            <h3>Follow Us</h3>
            <a href="https://www.linkedin.com" target="_blank" aria-label="LinkedIn">
                <i class="fa fa-linkedin"></i> LinkedIn
            </a>
            <a href="https://www.facebook.com" target="_blank" aria-label="Facebook">
                <i class="fa fa-facebook"></i> Facebook
            </a>
            <a href="https://www.twitter.com" target="_blank" aria-label="Twitter">
                <i class="fa fa-twitter"></i> Twitter
            </a>
            <a href="https://www.whatsapp.com" target="_blank" aria-label="WhatsApp">
                <i class="fa fa-whatsapp"></i> WhatsApp
            </a>
        </div>
        <div class="newsletter">
            <h3>Subscribe to Newsletter</h3>
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>
            <form method="post">
                <input type="email" name="email" placeholder="Enter your email..." required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        Fitness Equipment Lending © 2024 | Made with ❤ by Sheila
    </div>
</footer>
