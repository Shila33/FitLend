<?php
// index.php
include 'userheader.php';
require 'db.php';

$reviews = $conn->query("SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.id")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to Fitness <br>Equipment Lending</h1>
            <p>Rent the best fitness equipment for your home workouts.</p>
            <a href="equipment.php" class="explore-button">Explore</a>
        </div>
    </div>

    <!-- Main content -->
    <main>
        <!-- Your page content goes here -->
        <section class="simple-process">
            <h2>Simple Process</h2>
            <div class="process-columns">
                <div class="process-item">
                    <i class="fa fa-shopping-cart"></i>
                    <h3>Order Easily</h3>
                </div>
                <div class="process-item">
                    <i class="fa fa-money"></i>
                    <h3>Cheap Lend</h3>
                </div>
                <div class="process-item">
                    <i class="fa fa-truck"></i>
                    <h3>Free Delivery</h3>
                </div>
                <div class="process-item">
                    <i class="fa fa-headphones"></i>
                    <h3>Customer Care</h3>
                </div>
            </div>
        </section>

        <!-- Reviews section -->
        <section class="reviews">
            <h2>Customer Reviews</h2>
            <div class="reviews-slider">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <i class="fa fa-user-circle"></i>
                        <p><?php echo htmlspecialchars($review['username']); ?></p>
                        <p class="stars">
                            <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                <i class="fa fa-star"></i>
                            <?php endfor; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                                <i class="fa fa-star-o"></i>
                            <?php endfor; ?>
                        </p>
                        <blockquote><?php echo htmlspecialchars($review['comment']); ?></blockquote>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'userfooter.php'; ?>
    <script src="js/index.js"></script>
</body>
</html>
