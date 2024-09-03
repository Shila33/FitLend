<?php
// admindashboard.php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: adminlogin.php');
    exit();
}

require 'db.php';

// Fetching data from the database
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
$reservations = $conn->query("SELECT * FROM reservations")->fetch_all(MYSQLI_ASSOC);
$equipment = $conn->query("SELECT * FROM equipment")->fetch_all(MYSQLI_ASSOC);
$contacts = $conn->query("SELECT * FROM contacts")->fetch_all(MYSQLI_ASSOC);
$subscriptions = $conn->query("SELECT * FROM Subscriptions")->fetch_all(MYSQLI_ASSOC);
$payments = $conn->query("
    SELECT users.username, users.phone, equipment.name AS equipment_name, reservations.total_amount, 
           COALESCE(late_fees.total_fee, 0) AS total_fee, reservations.payment_method 
    FROM reservations 
    JOIN users ON reservations.user_id = users.id 
    JOIN equipment ON reservations.equipment_id = equipment.id 
    LEFT JOIN late_fees ON reservations.id = late_fees.reservation_id
")->fetch_all(MYSQLI_ASSOC);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);
        
        // Delete related records from reviews and other tables first
        $conn->query("DELETE FROM reviews WHERE user_id = $user_id");
        
        // Optionally delete related reservations if necessary
        $conn->query("DELETE FROM reservations WHERE user_id = $user_id");
        
        // Now delete the user
        $conn->query("DELETE FROM users WHERE id = $user_id");

    } elseif (isset($_POST['approve_reservation'])) {
        $reservation_id = intval($_POST['reservation_id']);
        $conn->query("UPDATE reservations SET status = 'approved' WHERE id = $reservation_id");
    } elseif (isset($_POST['delete_equipment'])) {
        $equipment_id = intval($_POST['equipment_id']);
        $conn->query("DELETE FROM equipment WHERE id = $equipment_id");
    } elseif (isset($_POST['update_equipment'])) {
        $equipment_id = intval($_POST['equipment_id']);
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        $available_count = intval($_POST['available_count']);
        $daily_fee = floatval($_POST['daily_fee']);
        $available = intval($_POST['available']);

        // Image upload handling
        if ($_FILES['image']['size'] > 0) {
            $image = basename($_FILES['image']['name']);
            $target_file = "images/" . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            $conn->query("UPDATE equipment SET image = '$image' WHERE id = $equipment_id");
        }

        // Update equipment information
        $conn->query("UPDATE equipment SET name = '$name', description = '$description', category = '$category', 
                      available_count = $available_count, daily_fee = $daily_fee, available = $available 
                      WHERE id = $equipment_id");
    } elseif (isset($_POST['add_equipment'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        $available_count = intval($_POST['available_count']);
        $daily_fee = floatval($_POST['daily_fee']);
        $available = intval($_POST['available']);

        // Image upload handling
        if ($_FILES['image']['size'] > 0) {
            $image = basename($_FILES['image']['name']);
            $target_file = "images/" . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            $image = NULL;
        }

        // Insert new equipment into the database
        $conn->query("INSERT INTO equipment (name, description, category, available_count, daily_fee, available, image)
                      VALUES ('$name', '$description', '$category', $available_count, $daily_fee, $available, '$image')");
    }

    header('Location: admindashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admindashboard.css">
    <link rel="stylesheet" href="css/adminheader.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    /* Back to Top Button Style */
    #backToTopBtn {
        display: none; 
        position: fixed; 
        bottom: 20px; 
        right: 30px;
        z-index: 99;
        font-size: 18px; 
        background-color: #e74c3c; 
        color: white; 
        border: none; 
        outline: none; 
        cursor: pointer; 
        padding: 15px; 
        border-radius: 50%; 
        transition: 0.3s; 
    }

    #backToTopBtn:hover {
        background-color: #000; /* Darker grey on hover */
    }
</style>

</head>
<body>
    <?php include 'adminheader.php'; ?>
    <div class="dashboard">
        <aside class="sidebar">
            <ul>
                <li><a href="#users"><i class="fa fa-users"></i> All Users</a></li>
                <li><a href="#reservations"><i class="fa fa-calendar"></i> Reservations</a></li>
                <li><a href="#payments"><i class="fa fa-money"></i> Payments</a></li>
                <li><a href="#equipment"><i class="fa fa-cogs"></i> Equipment</a></li>
                <li><a href="#contacts"><i class="fa fa-address-book"></i> Queries</a></li>
                <li><a href="#subscriptions"><i class="fa fa-envelope"></i> Subscriptions</a></li>
            </ul>
        </aside>
        <main class="content">
            <!-- Users Section -->
            <section id="users" class="content-section">
                <h2>Users</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user" class="btn-delete">Delete User</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Reservations Section -->
            <section id="reservations" class="content-section">
                <h2>Reservations</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Equipment ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['equipment_id']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                            <button type="submit" name="approve_reservation" 
                                            class="btn-<?php echo ($reservation['status'] == 'returned') ? 'returned' : 'approve'; ?>" 
                                            <?php echo ($reservation['status'] == 'returned') ? 'disabled' : ''; ?>>
                                            <?php echo ($reservation['status'] == 'returned') ? 'Returned' : 'Approve'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Payments Section -->
            <section id="payments" class="content-section">
                <h2>Payments</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Phone</th>
                                <th>Equipment</th>
                                <th>Total Amount</th>
                                <th>Late Fee</th>
                                <th>Final Charge</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['username']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['equipment_name']); ?></td>
                                    <td>Ksh. <?php echo number_format($payment['total_amount'], 2); ?></td>
                                    <td>Ksh. <?php echo number_format($payment['total_fee'], 2); ?></td>
                                    <td>Ksh. <?php echo number_format($payment['total_amount'] + $payment['total_fee'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Equipment Section -->
            <section id="equipment" class="content-section">
                <h2>Equipment</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Available Count</th>
                                <th>Daily Fee</th>
                                <th>Available</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equipment as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                    <td><?php echo htmlspecialchars($item['available_count']); ?></td>
                                    <td>Ksh. <?php echo number_format($item['daily_fee'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['available']) ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <?php if ($item['image']): ?>
                                            <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="Equipment Image" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="equipment_id" value="<?php echo $item['id']; ?>">
                                            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" placeholder="Name">
                                            <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($item['description']); ?></textarea>
                                            <input type="text" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" placeholder="Category">
                                            <input type="number" name="available_count" value="<?php echo htmlspecialchars($item['available_count']); ?>" placeholder="Available Count">
                                            <input type="number" name="daily_fee" value="<?php echo htmlspecialchars($item['daily_fee']); ?>" placeholder="Daily Fee">
                                            <input type="file" name="image">
                                            <select name="available">
                                                <option value="1" <?php echo $item['available'] ? 'selected' : ''; ?>>Yes</option>
                                                <option value="0" <?php echo !$item['available'] ? 'selected' : ''; ?>>No</option>
                                            </select>
                                            <button type="submit" name="update_equipment" class="btn-update">Update</button>
                                            <button type="submit" name="delete_equipment" class="btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="add-equipment-form">
                    <h3>Add New Equipment</h3>
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name="name" placeholder="Name" required>
                        <textarea name="description" placeholder="Description" required></textarea>
                        <input type="text" name="category" placeholder="Category" required>
                        <input type="number" name="available_count" placeholder="Available Count" required>
                        <input type="number" name="daily_fee" placeholder="Daily Fee" required>
                        <input type="file" name="image">
                        <select name="available" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <button type="submit" name="add_equipment" class="btn-add">Add Equipment</button>
                    </form>
                </div>
            </section>

            <!-- Contacts Section -->
            <section id="contacts" class="content-section">
                <h2>Queries</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($contact['id']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['message']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Subscriptions Section -->
            <section id="subscriptions" class="content-section">
                <h2>Subscriptions</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptions as $subscription): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($subscription['id']); ?></td>
                                    <td><?php echo htmlspecialchars($subscription['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <!-- Back to Top Button -->
<button onclick="scrollToTop()" id="backToTopBtn" title="Go to top">↑</button>
<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    var backToTopBtn = document.getElementById("backToTopBtn");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function scrollToTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
</script>

</body>
</html>
