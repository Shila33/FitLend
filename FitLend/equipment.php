<?php
// equipment.php
include 'userheader.php';
require 'db.php';

$equipment = $conn->query("SELECT * FROM equipment")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/equipment.css">
</head>
<body>
    <div class="content-wrapper">
        <section class="equipment-list">
            <h2>Our Equipment</h2>
            <div class="equipment-grid">
                <?php foreach ($equipment as $item): ?>
                    <div class="equipment-card">
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p>Available: <?php echo htmlspecialchars($item['available_count']); ?></p>
                        <p>Status: <?php echo $item['available'] ? 'Available' : 'Not Available'; ?></p>
                        <p>Daily Fee: Ksh. <?php echo number_format($item['daily_fee'], 2); ?></p>
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="reserve_equipment.php?id=<?php echo $item['id']; ?>" class="reserve-button">Reserve</a>
                        <?php else: ?>
                            <a href="login.php" class="reserve-button">Login to Reserve</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    <?php include 'userfooter.php'; ?>
</body>
</html>
