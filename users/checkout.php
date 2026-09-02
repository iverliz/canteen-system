<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}

require_once '../database/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['orders']) || !is_array($data['orders']) || count($data['orders']) === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid order data.']);
    exit();
}

$userId = $_SESSION['user_id'];
$total = 0;

foreach ($data['orders'] as $item) {
    $total += floatval($item['price']) * intval($item['quantity']);
}

$stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("id", $userId, $total);
$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();

$itemStmt = $conn->prepare(
    "INSERT INTO order_items (order_id, food_name, price, quantity) VALUES (?, ?, ?, ?)"
);

foreach ($data['orders'] as $item) {
    $foodName = htmlspecialchars($item['name']);
    $price = floatval($item['price']);
    $quantity = intval($item['quantity']);
    $itemStmt->bind_param("isdi", $orderId, $foodName, $price, $quantity);
    $itemStmt->execute();
}

$itemStmt->close();

echo json_encode(['success' => true, 'order_id' => $orderId]);