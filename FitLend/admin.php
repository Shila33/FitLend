<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle CRUD operations for equipment and users
    // Handle booking approvals
    $action = $_POST['action'];
    if ($action === 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $availableCount = $_POST['available_count'];
        $image = $_POST['image'];

        $stmt = $pdo->prepare("INSERT INTO equipment (name, description, category, available_count, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $category, $availableCount, $image]);
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $availableCount = $_POST['available_count'];
        $image = $_POST['image'];

        $stmt = $pdo->prepare("UPDATE equipment SET name = ?, description = ?, category = ?, available_count = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $category, $availableCount, $image, $id]);
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM equipment WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    // Fetch data for equipment and users
    $type = $_GET['type'];
    if ($type === 'equipment') {
        $stmt = $pdo->query("SELECT * FROM equipment");
        $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($equipment);
    } elseif ($type === 'users') {
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } elseif ($type === 'bookings') {
        $stmt = $pdo->query("SELECT * FROM bookings");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($bookings);
    }
}
?>
