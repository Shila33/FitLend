<?php
// reserve_equipment.php
include 'userheader.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$equipment_id = $_GET['id'] ?? null;
if (!$equipment_id) {
    header('Location: equipment.php');
    exit();
}

$equipment = $conn->query("SELECT * FROM equipment WHERE id = $equipment_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $number_of_equipment = (int) $_POST['number_of_equipment'];
    $payment_method = $_POST['payment_method'];

    $date_diff = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    $total_amount = $equipment['daily_fee'] * $number_of_equipment * $date_diff;

    if ($number_of_equipment > $equipment['available_count']) {
        $error = "Cannot reserve more than available equipment.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, equipment_id, start_date, end_date, number_of_equipment, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iissids', $user_id, $equipment_id, $start_date, $end_date, $number_of_equipment, $total_amount, $payment_method);
        $stmt->execute();
        header('Location: reservations.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Equipment - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/reserve_equipment.css">
</head>
<body>
    <section class="reservation-form">
        <h2>Reserve <?php echo htmlspecialchars($equipment['name']); ?></h2>
        <form method="POST">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>
            
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>
            
            <label for="number_of_equipment">Number of Equipment</label>
            <input type="number" id="number_of_equipment" name="number_of_equipment" min="1" max="<?php echo $equipment['available_count']; ?>" required>
            
            <label for="payment_method">Payment Method</label>
            <div class="payment-methods">
                <label>
                    <input type="radio" name="payment_method" value="Mpesa" required>
                    <img src="images/mpesa.png" alt="Mpesa">
                </label>
                <label>
                    <input type="radio" name="payment_method" value="Card" required>
                    <img src="images/card.png" alt="Credit Card">
                </label>
            </div>
            
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <button type="submit">Reserve</button>
        </form>
    </section>
    <?php include 'userfooter.php'; ?>
</body>
</html>
