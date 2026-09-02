<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../auth/admin_login.php");
    exit();
}

require_once '../database/db_connect.php';

// --- Counts for the summary cards ---
$counts = ['pending' => 0, 'preparing' => 0, 'ready' => 0, 'completed' => 0];

$countResult = $conn->query(
    "SELECT status, COUNT(*) AS total FROM orders GROUP BY status"
);
while ($row = $countResult->fetch_assoc()) {
    if (isset($counts[$row['status']])) {
        $counts[$row['status']] = (int)$row['total'];
    }
}

// --- Active orders: pending, preparing, ready ---
$activeStmt = $conn->prepare(
    "SELECT o.id, o.total, o.status, o.created_at, u.student_id, u.username
     FROM orders o
     JOIN users u ON u.id = o.user_id
     WHERE o.status IN ('pending', 'preparing', 'ready')
     ORDER BY o.created_at ASC"
);
$activeStmt->execute();
$activeOrders = $activeStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$activeStmt->close();

// Pull items for each active order
foreach ($activeOrders as &$order) {
    $itemStmt = $conn->prepare(
        "SELECT food_name, price, quantity FROM order_items WHERE order_id = ?"
    );
    $itemStmt->bind_param("i", $order['id']);
    $itemStmt->execute();
    $order['items'] = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $itemStmt->close();
}
unset($order);

// --- Order log: completed orders, filterable by date ---
$logDate = $_GET['log_date'] ?? date('Y-m-d');

$logStmt = $conn->prepare(
    "SELECT o.id, o.total, o.status, o.updated_at, u.student_id, u.username
     FROM orders o
     JOIN users u ON u.id = o.user_id
     WHERE o.status = 'completed'
       AND DATE(o.updated_at) = ?
     ORDER BY o.updated_at DESC"
);
$logStmt->bind_param("s", $logDate);
$logStmt->execute();
$logOrders = $logStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$logStmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Orders</title>

    <link
        rel="stylesheet"
        href="../assests/css/orders-admin.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests/css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="app-container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                <img src="../assests/css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>

        <!-- NAVIGATION -->

        <nav class="sidebar-menu">

            <a href="dashboard-admin.php" class="sidebar-link">
                <span class="menu-icon">▣</span>
                <span>Dashboard</span>
            </a>

            <a href="menu-admin.php" class="sidebar-link">
                <span class="menu-icon">🍔</span>
                <span>Menu</span>
            </a>

            <a href="orders-admin.php" class="sidebar-link active">
                <span class="menu-icon">🛒</span>
                <span>Orders</span>
            </a>

            <a href="categories-admin.php" class="sidebar-link">
                <span class="menu-icon">☷</span>
                <span>Categories</span>
            </a>

            <a href="user-admin.php" class="sidebar-link">
                <span class="menu-icon">👤</span>
                <span>User</span>
            </a>

        </nav>


        <!-- SIDEBAR BOTTOM -->

        <div class="sidebar-bottom">

            <a href="../auth/log_out_admin.php" class="sidebar-link">
                <span class="menu-icon">↪</span>
                <span>Logout</span>
            </a>

        </div>


    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div class="page-heading">
                <h1>Orders</h1>
                <p>Manage student food orders and pickup status.</p>
            </div>

            <div class="user-profile">
                <div class="profile-icon">A</div>
                <div class="profile-info">
                    <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></strong>
                    <span>Administrator</span>
                </div>
            </div>

        </header>


        <!-- ORDER SUMMARY -->

        <section class="order-summary">

            <div class="summary-card">
                <div class="summary-icon pending">!</div>
                <div>
                    <span>Pending</span>
                    <strong><?= $counts['pending'] ?></strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon preparing">◷</div>
                <div>
                    <span>Preparing</span>
                    <strong><?= $counts['preparing'] ?></strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon ready">✓</div>
                <div>
                    <span>Ready</span>
                    <strong><?= $counts['ready'] ?></strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon completed">✓</div>
                <div>
                    <span>Completed</span>
                    <strong><?= $counts['completed'] ?></strong>
                </div>
            </div>

        </section>


        <!-- ACTIVE ORDERS -->

        <section class="orders-card">

            <div class="section-header">

                <div>
                    <h2>Current Orders</h2>
                    <p>Orders waiting to be prepared or picked up.</p>
                </div>

                <div class="live-status">
                    <span class="live-dot"></span>
                    Live Orders
                </div>

            </div>

            <div class="table-wrapper">

                <table class="orders-table">

                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Order</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="ordersTableBody">

                        <?php foreach ($activeOrders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['student_id']) ?></td>
                                <td>
                                    <?php
                                        $itemNames = array_map(function ($i) {
                                            return htmlspecialchars($i['food_name']) . " ×" . $i['quantity'];
                                        }, $order['items']);
                                        echo implode(', ', $itemNames);
                                    ?>
                                </td>
                                <td>₱<?= number_format($order['total'], 2) ?></td>
                                <td>
                                    <select
                                        class="status-select status-<?= htmlspecialchars($order['status']) ?>"
                                        data-order-id="<?= $order['id'] ?>"
                                    >
                                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                                        <option value="ready" <?= $order['status'] === 'ready' ? 'selected' : '' ?>>Ready</option>
                                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>


                </table>

                <div class="empty-orders" id="emptyOrders" style="<?= count($activeOrders) > 0 ? 'display:none;' : '' ?>">
                    <div class="empty-icon">🛒</div>
                    <h3>No active orders</h3>
                    <p>New student orders will appear here.</p>
                </div>

            </div>

        </section>

        <!-- ORDER LOG -->

        <section class="orders-card order-log-card">

            <div class="section-header">

                <div>
                    <h2>Order Log</h2>
                    <p>View completed orders and order history.</p>
                </div>

                <div class="date-filter">
                    <label for="logDate">Date</label>
                    <input type="date" id="logDate" value="<?= htmlspecialchars($logDate) ?>">
                </div>

            </div>

            <div class="table-wrapper">

                <table class="orders-table log-table">

                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Order</th>
                            <th>Total Amount</th>
                            <th>Completed Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="orderLogBody">

                        <?php foreach ($logOrders as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['student_id']) ?></td>
                                <td>Order #<?= $log['id'] ?></td>
                                <td>₱<?= number_format($log['total'], 2) ?></td>
                                <td><?= date('g:i A', strtotime($log['updated_at'])) ?></td>
                                <td><span class="status-badge status-<?= htmlspecialchars($log['status']) ?>"><?= ucfirst($log['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

                <div class="empty-orders" id="emptyLog" style="<?= count($logOrders) > 0 ? 'display:none;' : '' ?>">
                    <div class="empty-icon">📋</div>
                    <h3>No completed orders</h3>
                    <p>Completed orders for this date will appear here.</p>
                </div>

            </div>

        </section>


    </main>


</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // Update order status when the dropdown changes
    document.querySelectorAll(".status-select").forEach(function (select) {

        select.addEventListener("change", function () {

            const orderId = this.dataset.orderId;
            const newStatus = this.value;

            fetch("update_order_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        // Reload so the summary counts and log/active split refresh
                        window.location.reload();
                    } else {
                        alert(data.message || "Failed to update status.");
                    }
                })
                .catch(function () {
                    alert("Something went wrong. Please try again.");
                });

        });

    });

    // Reload the page with the selected log date
    const logDateInput = document.getElementById("logDate");

    if (logDateInput) {
        logDateInput.addEventListener("change", function () {
            window.location.href = "orders-admin.php?log_date=" + this.value;
        });
    }

});
</script>

</body>

</html>