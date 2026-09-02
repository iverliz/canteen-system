<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect this page — redirect to login if not authenticated
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'User';

require_once '../database/db_connect.php';

$historyStmt = $conn->prepare(
    "SELECT o.id, o.total, o.status, o.created_at, oi.food_name, oi.price, oi.quantity
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ?
     ORDER BY o.created_at DESC, o.id DESC"
);
$historyStmt->bind_param("i", $_SESSION['user_id']);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();

$orderHistory = [];
while ($row = $historyResult->fetch_assoc()) {
    $orderHistory[$row['id']]['status'] = $row['status'];
    $orderHistory[$row['id']]['created_at'] = $row['created_at'];
    $orderHistory[$row['id']]['total'] = $row['total'];
    $orderHistory[$row['id']]['items'][] = [
        'name'     => $row['food_name'],
        'price'    => $row['price'],
        'quantity' => $row['quantity'],
    ];
}
$historyStmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Dashboard</title>

    <link rel="stylesheet" href="../assests/css/dashboard_user.css">
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

            <a href="dashboard.php" class="menu-item active">
                Dashboard
            </a>

            <a href="menu.php" class="menu-item">
                Menu
            </a>

             <a href="orders.php" class="menu-item">
                My Orders
            </a>

        </nav>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">

        <!-- HEADER -->

        <header class="top-header">

            <h1>DASHBOARD</h1>

            <div class="search-box">
                <input type="text" placeholder="Search Food">
                <span class="search-icon">⌕</span>
            </div>


            <!-- ================= PROFILE ================= -->

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


        <!-- ================= PROMOTION BANNER ================= -->

        <section class="promo-banner">

            <div class="promo-content">

                <span class="promo-small">TODAY'S SPECIAL</span>

                <h2>Delicious Food<br>For Only ₱50!</h2>

                <p>
                    Grab your favorite canteen meals at an affordable price.
                </p>

                <a href="menu.php" class="promo-button">
                    Order Now
                </a>

            </div>

            <div class="promo-image">
                <img src="../assests/css/images/hotdog.png" alt="Hotdog">
            </div>

        </section>


        <!-- ================= POPULAR FOOD ================= -->

        <section class="popular-section">

            <h2 class="section-title">
                Popular Food
            </h2>

        </section>

    </main>


    <!-- ================= RIGHT SIDEBAR ================= -->

    <aside class="right-sidebar">

        <!-- ================= HISTORY ORDER ================= -->

        <section class="history-box">

    <h2>History Order</h2>

    <div class="history-content">

        <?php if (empty($orderHistory)): ?>

            <p class="empty-history">No orders yet.</p>

        <?php else: ?>

            <?php foreach ($orderHistory as $orderId => $order): ?>

                <div class="history-order">

                    <div class="history-order-header">
                        <strong>Order #<?= $orderId ?></strong>
                        <span class="status-badge status-<?= htmlspecialchars($order['status']) ?>">
                            <?= ucfirst(htmlspecialchars($order['status'])) ?>
                        </span>
                    </div>

                    <div class="history-order-date">
                        <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                    </div>

                    <?php foreach ($order['items'] as $item): ?>
                        <div class="history-item">
                            <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                            <span>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="history-order-total">
                        Total: ₱<?= number_format($order['total'], 2) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</section>

        <!-- ================= MY ORDER ================= -->

        <section class="my-order-box">

            <h2>My Order</h2>

            <div class="my-order-content">
            </div>


            <!-- ORDER TOTAL -->

            <div class="order-total">

                <span>Total</span>

                <strong>₱0.00</strong>

            </div>


            <!-- CHECKOUT -->

            <div class="checkout-area">

                <button type="button" class="checkout-button">
                    Checkout
                </button>

            </div>

        </section>

    </aside>

</div>

</body>

</html>