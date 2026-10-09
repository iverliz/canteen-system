<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'User';

require_once '../database/db_connect.php';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($orderId > 0) {
    $orderStmt = $conn->prepare(
        "SELECT id, total, status, created_at
         FROM orders
         WHERE id = ? AND user_id = ?
         LIMIT 1"
    );
    $orderStmt->bind_param("ii", $orderId, $_SESSION['user_id']);
} else {
    $orderStmt = $conn->prepare(
        "SELECT id, total, status, created_at
         FROM orders
         WHERE user_id = ?
         ORDER BY created_at DESC, id DESC
         LIMIT 1"
    );
    $orderStmt->bind_param("i", $_SESSION['user_id']);
}

$orderStmt->execute();
$order = $orderStmt->get_result()->fetch_assoc();
$orderStmt->close();

$items = [];
$itemCount = 0;

if ($order) {
    $itemsStmt = $conn->prepare(
        "SELECT food_name, price, quantity
         FROM order_items
         WHERE order_id = ?"
    );
    $itemsStmt->bind_param("i", $order['id']);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();

    while ($row = $itemsResult->fetch_assoc()) {
        $items[] = $row;
        $itemCount += (int)$row['quantity'];
    }

    $itemsStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Receipt - OrderEats</title>

    <link rel="stylesheet" href="../assests/css/receipt_user.css">
</head>

<body>

<div class="receipt-page">

    <div class="receipt-brand">
        Order<span>Eats</span>
    </div>

    <?php if (!$order): ?>

        <div class="receipt receipt-empty">

            <div class="receipt-check muted">?</div>

            <h1>Receipt not found</h1>
            <p>We couldn't find that order. It may not belong to your account.</p>

            <div class="receipt-actions">
                <a href="orders.php" class="receipt-button secondary">My Orders</a>
                <a href="menu.php" class="receipt-button success">Back to Menu</a>
            </div>

        </div>

    <?php else: ?>

        <?php $statusKey = strtolower($order['status']); ?>

        <div class="receipt">

            <div class="receipt-header">

                <div class="receipt-check">✓</div>

                <h1>Order Receipt</h1>
                <p>Thank you for your order, <?= htmlspecialchars($username) ?>!</p>

            </div>

            <div class="receipt-meta">

                <div>
                    <span>Receipt No.</span>
                    <strong>#<?= str_pad((string)$order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>
                        <span class="status-badge status-<?= htmlspecialchars($statusKey) ?>">
                            <?= ucfirst(htmlspecialchars($statusKey)) ?>
                        </span>
                    </strong>
                </div>

                <div>
                    <span>Date</span>
                    <strong><?= date('M d, Y g:i A', strtotime($order['created_at'])) ?></strong>
                </div>

                <div>
                    <span>Customer</span>
                    <strong><?= htmlspecialchars($username) ?></strong>
                </div>

            </div>

            <div class="receipt-items">

                <?php foreach ($items as $item): ?>

                    <div class="receipt-item">

                        <div class="receipt-item-info">
                            <strong><?= htmlspecialchars($item['food_name']) ?></strong>
                            <span><?= (int)$item['quantity'] ?> × ₱<?= number_format($item['price'], 2) ?></span>
                        </div>

                        <strong class="receipt-item-total">
                            ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </strong>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="receipt-totals">

                <div class="receipt-line">
                    <span>Total items</span>
                    <span><?= $itemCount ?></span>
                </div>

                <div class="receipt-line grand">
                    <span>Total</span>
                    <span>₱<?= number_format($order['total'], 2) ?></span>
                </div>

            </div>

            <div class="receipt-footer">
                <p>Please keep this receipt for your records.</p>
            </div>

        </div>

        <div class="receipt-actions">
            <button type="button" class="receipt-button primary" onclick="window.print()">Print Receipt</button>
            <a href="orders.php" class="receipt-button secondary">My Orders</a>
            <a href="menu.php" class="receipt-button success">Back to Menu</a>
        </div>

    <?php endif; ?>

</div>

</body>

</html>