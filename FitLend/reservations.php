<?php
// reservations.php
include 'userheader.php';
require 'db.php';

$user_id = $_SESSION['user']['id'];
$reservations = $conn->query("SELECT reservations.*, equipment.name, users.username 
                              FROM reservations 
                              JOIN equipment ON reservations.equipment_id = equipment.id 
                              JOIN users ON reservations.user_id = users.id 
                              WHERE reservations.user_id = $user_id")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'];
    if ($action === 'return') {
        $reservation = $conn->query("SELECT * FROM reservations WHERE id = $reservation_id")->fetch_assoc();
        $end_date = new DateTime($reservation['end_date']);
        $current_date = new DateTime();
        
        if ($current_date > $end_date) {
            $late_days = $current_date->diff($end_date)->days;
            $late_fee = $late_days * 0.10 * $reservation['total_amount'];
            
            // Insert late fee into late_fees table
            $stmt = $conn->prepare("INSERT INTO late_fees (reservation_id, late_days, total_fee) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $reservation_id, $late_days, $late_fee);
            $stmt->execute();

            echo "<p>Dear " . htmlspecialchars($reservation['username']) . ", You will pay additional Ksh. " . number_format($late_fee, 2) . " for returning the equipment " . $late_days . " days late!</p>";
        }

        // Update reservation status to 'returned'
        $conn->query("UPDATE reservations SET status = 'returned' WHERE id = $reservation_id");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Fitness Equipment Lending</title>
    <link rel="stylesheet" href="css/reservations.css">
    <script>
        function confirmReturn(reservationId) {
            if (confirm("Are you sure you want to return the item?")) {
                document.getElementById('return-form-' + reservationId).submit();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <section class="reservations-list">
            <h2>My Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['number_of_equipment']); ?></td>
                            <td>Ksh. <?php echo number_format($reservation['total_amount'], 2); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($reservation['status'])); ?></td>
                            <td>
                                <?php if ($reservation['status'] == 'approved'): ?>
                                    <button onclick="location.href='review.php?reservation_id=<?php echo $reservation['id']; ?>'">Review</button>
                                <?php endif; ?>
                                <?php if ($reservation['status'] != 'returned'): ?>
                                    <form id="return-form-<?php echo $reservation['id']; ?>" method="post">
                                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                        <input type="hidden" name="action" value="return">
                                        <button type="button" onclick="confirmReturn(<?php echo $reservation['id']; ?>)">Return Item</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <?php include 'userfooter.php'; ?>
    </div>
</body>
</html>
