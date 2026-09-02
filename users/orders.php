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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    profileBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        profileDropdown.classList.toggle("show");
    });

    profileDropdown.addEventListener("click", function (event) {
        event.stopPropagation();
    });

    document.addEventListener("click", function () {
        profileDropdown.classList.remove("show");
    });

});
</script>

<div class="dashboard-container">

    <!-- ================= LEFT SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item">
                Dashboard
            </a>

            <a href="menu.php" class="menu-item">
                Menu
            </a>

            <a href="orders.php" class="menu-item active">
                My Orders
            </a>

        </nav>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">

        <header class="top-header">

            <h1>MY ORDERS</h1>

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


        <!-- ================= ORDER LIST ================= -->

        <section class="orders-section">

            <?php if (empty($myOrders)): ?>

                <div class="empty-orders">
                    <p>You haven't placed any orders yet.</p>
                    <a href="menu.php" class="promo-button">Order Now</a>
                </div>

            <?php else: ?>

                <?php foreach ($myOrders as $orderId => $order): ?>

                    <div class="order-card">

                        <div class="order-card-header">

                            <div>
                                <strong>Order #<?= $orderId ?></strong>
                                <span class="order-date">
                                    <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                                </span>
                            </div>

                            <span class="status-badge status-<?= htmlspecialchars($order['status']) ?>">
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>
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
                            <strong>Total: ₱<?= number_format($order['total'], 2) ?></strong>
                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>

</div>

</body>

</html>