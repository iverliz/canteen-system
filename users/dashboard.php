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

$historyStmt = $conn->prepare(
    "SELECT o.id, o.total, o.status, o.created_at, oi.food_name, oi.price, oi.quantity
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ? AND DATE(o.created_at) = CURDATE()
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

$todaySpent = 0;

foreach ($orderHistory as $order) {
    if (strtolower($order['status']) !== 'cancelled') {
        $todaySpent += $order['total'];
    }
}

$popularFoods = [];

$popularResult = $conn->query(
    "SELECT m.food_id, m.food_name, m.food_price, m.food_picture,
            (SELECT COALESCE(SUM(oi.quantity), 0)
             FROM order_items oi
             WHERE oi.food_name = m.food_name) AS sold
     FROM `food-menu` m
     WHERE m.availability = 1
     ORDER BY sold DESC, m.food_id DESC
     LIMIT 8"
);

if ($popularResult) {
    while ($row = $popularResult->fetch_assoc()) {
        $row['picture'] = !empty($row['food_picture'])
            ? 'data:image/jpeg;base64,' . base64_encode($row['food_picture'])
            : null;
        $popularFoods[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Dashboard</title>

    <link rel="stylesheet" href="../assests/css/dashboard_user.css">
    <link rel="stylesheet" href="../assests/css/dashboard_hero.css">
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item active">
                <span class="menu-icon">🏠</span>
                Dashboard
            </a>

            <a href="menu.php" class="menu-item">
                <span class="menu-icon">🍔</span>
                Menu
            </a>

            <a href="orders.php" class="menu-item">
                <span class="menu-icon">🧾</span>
                My Orders
            </a>

        </nav>

    </aside>


    <main class="main-content">

        <header class="top-header">

            <div class="page-heading">
                <h1>DASHBOARD</h1>
                <p>Welcome back, <?= htmlspecialchars($username) ?>! What would you like to eat today?</p>
            </div>

            <div class="search-box">
                <input type="text" id="foodSearch" placeholder="Search Food">
                <span class="search-icon">⌕</span>
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


        <section
            class="hero"
            id="hero"
            data-interval="6000"
            role="region"
            aria-roledescription="carousel"
            aria-label="Featured specials"
        >

            <div class="hero-track" id="heroTrack" aria-live="off">

                <div class="hero-slide theme-hotdog is-active" role="group" aria-roledescription="slide" aria-label="1 of 4">

                    <div class="hero-content">
                        <span class="hero-tag">Today's Special</span>
                        <h2>Delicious Food<br>For Only ₱50!</h2>
                        <p>Grab your favorite canteen meals at an affordable price.</p>
                        <a href="menu.php" class="hero-button">Order Now <span aria-hidden="true">→</span></a>
                    </div>

                    <div class="hero-visual">
                        <img src="../assests/css/images/hotdog.png" alt="Hotdog">
                    </div>

                </div>

                <div class="hero-slide theme-burger" role="group" aria-roledescription="slide" aria-label="2 of 4">

                    <div class="hero-content">
                        <span class="hero-tag">Canteen Favorite</span>
                        <h2>Juicy Burgers<br>Made Fresh Daily!</h2>
                        <p>Hot, filling and ready between classes.</p>
                        <a href="menu.php" class="hero-button">Order Now <span aria-hidden="true">→</span></a>
                    </div>

                    <div class="hero-visual">
                        <img src="../assests/css/images/burger.png" alt="Burger">
                    </div>

                </div>

                <div class="hero-slide theme-pizza" role="group" aria-roledescription="slide" aria-label="3 of 4">

                    <div class="hero-content">
                        <span class="hero-tag">Share &amp; Enjoy</span>
                        <h2>Cheesy Pizza<br>For Every Break!</h2>
                        <p>Grab a slice with your friends and skip the long lines.</p>
                        <a href="menu.php" class="hero-button">Order Now <span aria-hidden="true">→</span></a>
                    </div>

                    <div class="hero-visual">
                        <img src="../assests/css/images/pizza.png" alt="Pizza">
                    </div>

                </div>

                <div class="hero-slide theme-donut" role="group" aria-roledescription="slide" aria-label="4 of 4">

                    <div class="hero-content">
                        <span class="hero-tag">Sweet Treat</span>
                        <h2>Something Sweet<br>To Brighten Your Day!</h2>
                        <p>Desserts and snacks to finish your meal right.</p>
                        <a href="menu.php" class="hero-button">Order Now <span aria-hidden="true">→</span></a>
                    </div>

                    <div class="hero-visual">
                        <img src="../assests/css/images/donut.png" alt="Donut">
                    </div>

                </div>

            </div>

            <button type="button" class="hero-arrow prev" id="heroPrev" aria-label="Previous slide">‹</button>
            <button type="button" class="hero-arrow next" id="heroNext" aria-label="Next slide">›</button>

            <div class="hero-controls">
                <div class="hero-dots" id="heroDots"></div>
            </div>

            <button type="button" class="hero-toggle" id="heroToggle" aria-label="Pause automatic slideshow">Pause</button>

        </section>


        <section class="popular-section">

            <div class="section-header">
                <h2 class="section-title">Popular Food</h2>
                <a href="menu.php" class="section-link">View all</a>
            </div>

            <div class="popular-grid">

                <?php if (empty($popularFoods)): ?>

                    <p class="empty-note">No food is available right now. Please check back later.</p>

                <?php else: ?>

                    <?php foreach ($popularFoods as $food): ?>

                        <div class="popular-card" data-name="<?= htmlspecialchars(strtolower($food['food_name'])) ?>">

                            <div class="popular-card-image">

                                <?php if ($food['picture']): ?>
                                    <img src="<?= $food['picture'] ?>" alt="<?= htmlspecialchars($food['food_name']) ?>">
                                <?php else: ?>
                                    🍽️
                                <?php endif; ?>

                            </div>

                            <h3><?= htmlspecialchars($food['food_name']) ?></h3>

                            <div class="popular-card-meta">
                                <span class="popular-price">₱<?= number_format($food['food_price'], 2) ?></span>

                                <?php if ((int)$food['sold'] > 0): ?>
                                    <span class="popular-sold"><?= (int)$food['sold'] ?> ordered</span>
                                <?php endif; ?>
                            </div>

                            <a href="menu.php" class="popular-order">Order</a>

                        </div>

                    <?php endforeach; ?>

                    <p class="empty-note" id="searchEmpty" hidden>No matching food found.</p>

                <?php endif; ?>

            </div>

        </section>

    </main>


    <aside class="right-sidebar">

        <section class="history-box">

    <div class="box-header">
        <div>
            <h2>History Order</h2>
            <span class="history-day"><?= date('M d, Y') ?> · resets daily</span>
        </div>
        <span class="count-pill"><?= count($orderHistory) ?></span>
    </div>

    <div class="history-content">

        <?php if (empty($orderHistory)): ?>

            <div class="empty-history">
                <p>No orders today.</p>
                <a href="orders.php" class="history-link">View all my orders</a>
            </div>

        <?php else: ?>

            <?php foreach ($orderHistory as $orderId => $order): ?>

                <div class="history-order">

                    <div class="history-order-header">
                        <strong>Order #<?= $orderId ?></strong>
                        <span class="status-badge status-<?= htmlspecialchars(strtolower($order['status'])) ?>">
                            <?= ucfirst(htmlspecialchars(strtolower($order['status']))) ?>
                        </span>
                    </div>

                    <div class="history-order-date">
                        <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                    </div>

                    <?php foreach ($order['items'] as $item): ?>
                        <div class="history-item">
                            <div class="history-item-info">
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                                <span><?= (int)$item['quantity'] ?> × ₱<?= number_format($item['price'], 2) ?></span>
                            </div>
                            <span class="history-item-total">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="history-order-footer">
                        <a href="receipt.php?id=<?= (int)$orderId ?>" class="history-receipt">View receipt</a>
                        <span class="history-total">Total: ₱<?= number_format($order['total'], 2) ?></span>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <?php if (!empty($orderHistory)): ?>

        <div class="history-summary">
            <span>Spent today</span>
            <strong>₱<?= number_format($todaySpent, 2) ?></strong>
        </div>

    <?php endif; ?>

</section>

        <section class="cta-box">

            <h2>Feeling hungry?</h2>
            <p>Pick your meals from the menu and check out in a few taps.</p>

            <a href="menu.php" class="cta-button">Go to Menu</a>

        </section>

    </aside>

</div>



<script src="../assests/css/js/dashboard_student.js"></script>

</body>

</html>