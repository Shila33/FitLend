<?php
require 'db.php';
$equipment_id = $_GET['id'];
$equipment = $conn->query("SELECT * FROM equipment WHERE id = $equipment_id")->fetch_assoc();
echo json_encode($equipment);
?>
