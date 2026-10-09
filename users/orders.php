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

$ordersStmt = $conn->prepare(
    "SELECT o.id, o.total, o.status, o.created_at, oi.food_name, oi.price, oi.quantity
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ?
     ORDER BY o.created_at DESC, o.id DESC"
);
$ordersStmt->bind_param("i", $_SESSION['user_id']);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();

$myOrders = [];
while ($row = $ordersResult->fetch_assoc()) {
    $myOrders[$row['id']]['status'] = $row['status'];
    $myOrders[$row['id']]['created_at'] = $row['created_at'];
    $myOrders[$row['id']]['total'] = $row['total'];
    $myOrders[$row['id']]['items'][] = [
        'name'     => $row['food_name'],
        'price'    => $row['price'],
        'quantity' => $row['quantity'],
    ];
}
$ordersStmt->close();

$statusCounts = [
    'pending'   => 0,
    'preparing' => 0,
    'ready'     => 0,
    'completed' => 0,
    'cancelled' => 0,
];
$activeOrders = 0;
$totalSpent = 0;

foreach ($myOrders as $order) {
    $statusKey = strtolower($order['status']);

    if (isset($statusCounts[$statusKey])) {
        $statusCounts[$statusKey]++;
    }

    if (in_array($statusKey, ['pending', 'preparing', 'ready'], true)) {
        $activeOrders++;
    }

    if ($statusKey !== 'cancelled') {
        $totalSpent += $order['total'];
    }
}

$totalOrders = count($myOrders);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Orders - OrderEats</title>

    <link rel="stylesheet" href="../assests/css/orders_user.css">
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item">
                <span class="menu-icon">🏠</span>
                Dashboard
            </a>

            <a href="menu.php" class="menu-item">
                <span class="menu-icon">🍔</span>
                Menu
            </a>

            <a href="orders.php" class="menu-item active">
                <span class="menu-icon">🧾</span>
                My Orders
            </a>

        </nav>

    </aside>

    <main class="main-content">

        <header class="top-header">

            <div class="page-heading">
                <h1>MY ORDERS</h1>
                <p>Track your orders and review your order history.</p>
            </div>

            <div class="profile-container">

                <button type="button" class="profile-button" id="profileBtn">
                    <div class="profile-icon">👤</div>
                    <span class="profile-name"><?= htmlspecialchars($username) ?></span>
                    <span class="profile-arrow">⌄</span>
                </button>

                <div class="profile-dropdown" id="profileDropdown">

                    <div class="profile-info">
                        <div class="profile-large-icon">👤</div>
                        <div>
                            <strong><?= htmlspecialchars($username) ?></strong>
                            <small>Student</small>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <a href="../auth/logout.php" class="logout-button">
                        🚪 Logout
                    </a>

                </div>

            </div>

        </header>

        <?php if (empty($myOrders)): ?>

            <section class="orders-section">

                <div class="empty-orders">
                    <div class="empty-icon">🧾</div>
                    <p>You haven't placed any orders yet.</p>
                    <a href="menu.php" class="promo-button">Order Now</a>
                </div>

            </section>

        <?php else: ?>

            <section class="orders-summary">

                <div class="stat-card">
                    <div class="stat-icon">🧾</div>
                    <div>
                        <span class="stat-label">Total Orders</span>
                        <strong class="stat-value"><?= $totalOrders ?></strong>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div>
                        <span class="stat-label">Active Orders</span>
                        <strong class="stat-value"><?= $activeOrders ?></strong>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div>
                        <span class="stat-label">Total Spent</span>
                        <strong class="stat-value">₱<?= number_format($totalSpent, 2) ?></strong>
                    </div>
                </div>

            </section>

            <section class="orders-section">

                <div class="order-filters">

                    <button type="button" class="filter-chip active" data-filter="all" aria-pressed="true">
                        All <span class="filter-count"><?= $totalOrders ?></span>
                    </button>

                    <?php foreach ($statusCounts as $statusName => $count): ?>

                        <?php if ($count > 0): ?>

                            <button type="button" class="filter-chip" data-filter="<?= $statusName ?>" aria-pressed="false">
                                <?= ucfirst($statusName) ?> <span class="filter-count"><?= $count ?></span>
                            </button>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

                <div class="orders-grid">

                    <?php foreach ($myOrders as $orderId => $order): ?>

                        <?php $statusKey = strtolower($order['status']); ?>

                        <div class="order-card" data-status="<?= htmlspecialchars($statusKey) ?>">

                            <div class="order-card-header">

                                <div>
                                    <strong>Order #<?= $orderId ?></strong>
                                    <span class="order-date">
                                        <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                                    </span>
                                </div>

                                <span class="status-badge status-<?= htmlspecialchars($statusKey) ?>">
                                    <?= ucfirst(htmlspecialchars($statusKey)) ?>
                                </span>

                            </div>

                            <div class="order-card-items">

                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-card-item">
                                        <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                                        <span>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <div class="order-card-footer">
                                <span>Total</span>
                                <strong>₱<?= number_format($order['total'], 2) ?></strong>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        <?php endif; ?>

    </main>

</div>

<script src="../assests/css/js/order_student.js"></script>

</body>

</html>