<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$stmt = $conn->prepare("INSERT INTO visits (ip, city, device) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $data['ip'], $data['city'], $data['device']);
$stmt->execute();
$stmt->close();

$conn->close();
