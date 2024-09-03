<?php
include 'userheader.php';
require 'db.php';

// Validate reservation ID
if (!isset($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
    echo "Invalid reservation ID.";
    exit;
}

$reservation_id = (int)$_GET['reservation_id'];
$user_id = $_SESSION['user']['id'];

// Fetch equipment_id based on reservation_id
$reservation_query = $conn->prepare("SELECT equipment_id FROM reservations WHERE id = ? AND user_id = ?");
$reservation_query->bind_param("ii", $reservation_id, $user_id);
$reservation_query->execute();
$reservation_result = $reservation_query->get_result();

if ($reservation_result->num_rows === 0) {
    echo "Reservation not found or does not belong to you.";
    exit;
}

$reservation = $reservation_result->fetch_assoc();
$equipment_id = $reservation['equipment_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating value.";
        exit;
    }

    // Insert review into the database
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, equipment_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $equipment_id, $rating, $comment);

    if ($stmt->execute()) {
        echo "<p>Your review has been submitted!</p>";
    } else {
        echo "<p>Failed to submit your review. Please try again later.</p>";
    }
    exit;
}

// Fetch equipment details
$equipment_query = $conn->prepare("SELECT name FROM equipment WHERE id = ?");
$equipment_query->bind_param("i", $equipment_id);
$equipment_query->execute();
$equipment_result = $equipment_query->get_result();

if ($equipment_result->num_rows === 0) {
    echo "Equipment not found.";
    exit;
}

$equipment = $equipment_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Equipment - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/review.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function rateEquipment(rating) {
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById('star-' + i);
                star.style.color = i <= rating ? '#FFD700' : '#DCDCDC';
            }
            document.getElementById('rating').value = rating;
        }

        function validateForm() {
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value;
            if (!rating || !comment) {
                alert('Please fill out all fields.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="content">
        <section class="review-form">
            <h2>Review Equipment: <?php echo htmlspecialchars($equipment['name']); ?></h2>
            <form method="post" onsubmit="return validateForm()">
                <div class="rating-section">
                    <p>Rate this equipment on a scale of 1-5</p>
                    <div class="stars">
                        <i id="star-1" class="fa fa-star" onclick="rateEquipment(1)"></i>
                        <i id="star-2" class="fa fa-star" onclick="rateEquipment(2)"></i>
                        <i id="star-3" class="fa fa-star" onclick="rateEquipment(3)"></i>
                        <i id="star-4" class="fa fa-star" onclick="rateEquipment(4)"></i>
                        <i id="star-5" class="fa fa-star" onclick="rateEquipment(5)"></i>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="">
                </div>
                <div class="comment-section">
                    <label for="comment">Write your comment</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>
                <button type="submit">Submit Review</button>
            </form>
        </section>
    </div>
    <?php include 'userfooter.php'; ?>
</body>
</html>
