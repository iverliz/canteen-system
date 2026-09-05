<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../auth/admin_login.php");
    exit();
}

require_once '../database/db_connect.php';

$logDate = $_GET['log_date'] ?? date('Y-m-d');

/* =========================================================
   GET LOGGED-IN ADMIN ROLE
========================================================= */

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
$adminRoleDisplay = 'Administrator';

$roleStmt = $conn->prepare("
    SELECT role
    FROM admin_register
    WHERE username = ?
    LIMIT 1
");

$roleStmt->bind_param("s", $adminUsername);
$roleStmt->execute();

$roleResult = $roleStmt->get_result();
$adminAccount = $roleResult->fetch_assoc();

if ($adminAccount) {

    if ($adminAccount['role'] === 'manager') {
        $adminRoleDisplay = 'Canteen Manager';

    } elseif ($adminAccount['role'] === 'canteen_staff') {
        $adminRoleDisplay = 'Canteen Staff';
    }
}

$roleStmt->close();


/* =========================================================
   INITIAL SUMMARY COUNTS
========================================================= */

$counts = [
    'pending' => 0,
    'preparing' => 0,
    'ready' => 0,
    'completed' => 0,
    'cancelled' => 0
];


/* Active order counts */

$countStmt = $conn->prepare("
    SELECT status, COUNT(*) AS total
    FROM orders
    WHERE status IN ('pending', 'preparing', 'ready')
    GROUP BY status
");

$countStmt->execute();

$countResult = $countStmt->get_result();

while ($row = $countResult->fetch_assoc()) {

    if (isset($counts[$row['status']])) {
        $counts[$row['status']] = (int)$row['total'];
    }

}

$countStmt->close();


/* =========================================================
   COMPLETED ORDERS FOR CURRENT DAY
========================================================= */

$today = date('Y-m-d');

$completedStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'completed'
    AND DATE(updated_at) = ?
");

$completedStmt->bind_param("s", $today);
$completedStmt->execute();

$completedResult = $completedStmt->get_result()->fetch_assoc();

$counts['completed'] = (int)($completedResult['total'] ?? 0);

$completedStmt->close();


/* =========================================================
   CANCELLED ORDERS FOR SELECTED DATE
========================================================= */

$cancelledStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'cancelled'
    AND DATE(updated_at) = ?
");

$cancelledStmt->bind_param("s", $logDate);
$cancelledStmt->execute();

$cancelledResult = $cancelledStmt->get_result()->fetch_assoc();

$counts['cancelled'] = (int)($cancelledResult['total'] ?? 0);

$cancelledStmt->close();


/* =========================================================
   ACTIVE ORDERS
========================================================= */

$activeStmt = $conn->prepare("
    SELECT
        o.id,
        o.total,
        o.status,
        o.created_at,
        u.student_id,
        u.username
    FROM orders o
    JOIN users u ON u.id = o.user_id
    WHERE o.status IN ('pending', 'preparing', 'ready')
    ORDER BY o.created_at ASC
");

$activeStmt->execute();

$activeOrders =
    $activeStmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);

$activeStmt->close();


/* =========================================================
   GET ITEMS FOR ACTIVE ORDERS
========================================================= */

foreach ($activeOrders as &$order) {

    $itemStmt = $conn->prepare("
        SELECT
            food_name,
            price,
            quantity
        FROM order_items
        WHERE order_id = ?
    ");

    $itemStmt->bind_param("i", $order['id']);
    $itemStmt->execute();

    $order['items'] =
        $itemStmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);

    $itemStmt->close();
}

unset($order);


/* =========================================================
   ORDER LOG
   COMPLETED + CANCELLED
========================================================= */

$logStmt = $conn->prepare("
    SELECT
        o.id,
        o.total,
        o.status,
        o.updated_at,
        u.student_id,
        u.username
    FROM orders o
    JOIN users u ON u.id = o.user_id
    WHERE o.status IN ('completed', 'cancelled')
    AND DATE(o.updated_at) = ?
    ORDER BY o.updated_at DESC
");

$logStmt->bind_param("s", $logDate);
$logStmt->execute();

$logOrders =
    $logStmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);

$logStmt->close();


/* =========================================================
   GET ITEMS FOR ORDER LOG
========================================================= */

foreach ($logOrders as &$log) {

    $itemStmt = $conn->prepare("
        SELECT
            food_name,
            price,
            quantity
        FROM order_items
        WHERE order_id = ?
    ");

    $itemStmt->bind_param("i", $log['id']);
    $itemStmt->execute();

    $log['items'] =
        $itemStmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);

    $itemStmt->close();
}

unset($log);

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
        rel="icon"
        type="image/x-icon"
        href="../assests/css/images/OrderEats_logo.png"
    >

</head>

<body>

    <!-- =========================================================
        NEW ORDER NOTIFICATION
    ========================================================= -->

    <div
        class="new-order-notification"
        id="newOrderNotification"
    >

        <div class="notification-icon">
            🛒
        </div>

        <div class="notification-content">

            <strong>
                New Order Received!
            </strong>

            <span>
                A new student order has been placed.
            </span>

        </div>

        <button
            type="button"
            class="notification-close"
            id="closeNewOrderNotification"
            aria-label="Close notification"
        >
            ×
        </button>

    </div>

<div class="app-container">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                <img
                    src="../assests/css/images/OrderEats_logo.png"
                    class="system-logo"
                >
            </div>

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>


        <nav class="sidebar-menu">

            <a href="dashboard-admin.php" class="sidebar-link">
                <span class="menu-icon">🟧</span>
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


        <!-- LOGOUT -->

        <div class="sidebar-bottom">

            <a href="#" class="sidebar-link" id="logoutButton">

                <span class="menu-icon">↪</span>

                <span>Logout</span>

            </a>

        </div>

    </aside>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>Orders</h1>

                <p>
                    Manage student food orders and pickup status.
                </p>

            </div>


            <div class="user-profile">

                <div class="profile-icon">

                    <?= htmlspecialchars(
                        strtoupper(
                            substr($adminUsername, 0, 1)
                        )
                    ) ?>

                </div>


                <div class="profile-info">

                    <strong>
                        <?= htmlspecialchars($adminUsername) ?>
                    </strong>

                    <span>
                        <?= htmlspecialchars($adminRoleDisplay) ?>
                    </span>

                </div>

            </div>

        </header>


        <!-- =================================================
             ORDER SUMMARY
        ================================================== -->

        <section class="order-summary">


            <!-- PENDING -->

            <div class="summary-card">

                <div class="summary-icon pending">
                    !
                </div>

                <div>

                    <span>
                        Pending
                    </span>

                    <strong id="pendingCount">
                        <?= $counts['pending'] ?>
                    </strong>

                </div>

            </div>


            <!-- PREPARING -->

            <div class="summary-card">

                <div class="summary-icon preparing">
                    ◷
                </div>

                <div>

                    <span>
                        Preparing
                    </span>

                    <strong id="preparingCount">
                        <?= $counts['preparing'] ?>
                    </strong>

                </div>

            </div>


            <!-- READY -->

            <div class="summary-card">

                <div class="summary-icon ready">
                    ✓
                </div>

                <div>

                    <span>
                        Ready
                    </span>

                    <strong id="readyCount">
                        <?= $counts['ready'] ?>
                    </strong>

                </div>

            </div>


            <!-- COMPLETED -->

            <div class="summary-card">

                <div class="summary-icon completed">
                    ✓
                </div>

                <div>

                    <span>
                        Completed
                    </span>

                    <strong id="completedCount">
                        <?= $counts['completed'] ?>
                    </strong>

                </div>

            </div>

            <!-- CANCELLED -->

            <div class="summary-card">

                <div class="summary-icon cancelled">
                    ×
                </div>

                <div>

                    <span>
                        Cancelled
                    </span>

                    <strong id="cancelledCount">
                        <?= $counts['cancelled'] ?>
                    </strong>

                </div>

            </div>

        </section>


        <!-- =================================================
             CURRENT ORDERS
        ================================================== -->

        <section class="orders-card">

            <div class="section-header">

                <div>

                    <h2>
                        Current Orders
                    </h2>

                    <p>
                        Orders waiting to be prepared or picked up.
                    </p>

                </div>


                <div class="live-status">

                    <span class="live-dot"></span>

                    Live Orders

                </div>

            </div>


            <!-- SCROLLABLE TABLE -->

            <div class="table-wrapper current-orders-wrapper">

                <table class="orders-table">

                    <thead>

                        <tr>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Total Amount
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="ordersTableBody">

                        <?php foreach ($activeOrders as $order): ?>

                            <tr
                                class="order-row"
                                data-order-id="<?= $order['id'] ?>"
                                data-student-id="<?= htmlspecialchars($order['student_id']) ?>"
                                data-status="<?= htmlspecialchars($order['status']) ?>"
                                data-total="<?= htmlspecialchars($order['total']) ?>"
                                data-items="<?= htmlspecialchars(
                                    json_encode($order['items']),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                                <td>
                                    <?= htmlspecialchars($order['student_id']) ?>
                                </td>


                                <td>

                                    <?php

                                    $itemNames = array_map(
                                        function ($i) {

                                            return
                                                htmlspecialchars(
                                                    $i['food_name']
                                                )
                                                . " ×"
                                                . $i['quantity'];

                                        },
                                        $order['items']
                                    );

                                    echo implode(', ', $itemNames);

                                    ?>

                                </td>


                                <td>

                                    ₱<?= number_format(
                                        $order['total'],
                                        2
                                    ) ?>

                                </td>


                                <td>

                                    <select
                                        class="status-select status-<?= htmlspecialchars($order['status']) ?>"
                                        data-order-id="<?= $order['id'] ?>"
                                    >

                                        <option
                                            value="pending"
                                            <?= $order['status'] === 'pending' ? 'selected' : '' ?>
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="preparing"
                                            <?= $order['status'] === 'preparing' ? 'selected' : '' ?>
                                        >
                                            Preparing
                                        </option>

                                        <option
                                            value="ready"
                                            <?= $order['status'] === 'ready' ? 'selected' : '' ?>
                                        >
                                            Ready
                                        </option>

                                        <option
                                            value="completed"
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="cancelled"
                                        >
                                            Cancelled
                                        </option>

                                    </select>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>


                <div
                    class="empty-orders"
                    id="emptyOrders"
                    style="<?= count($activeOrders) > 0 ? 'display:none;' : '' ?>"
                >

                    <div class="empty-icon">
                        🛒
                    </div>

                    <h3>
                        No active orders
                    </h3>

                    <p>
                        New student orders will appear here.
                    </p>

                </div>

            </div>

        </section>


        <!-- =================================================
             ORDER LOG
        ================================================== -->

        <section class="orders-card order-log-card">

            <div class="section-header">

                <div>

                    <h2>
                        Order Log
                    </h2>

                    <p>
                        View completed and cancelled orders.
                    </p>

                </div>


                <div class="date-filter">

                    <label for="logDate">
                        Date
                    </label>

                    <input
                        type="date"
                        id="logDate"
                        value="<?= htmlspecialchars($logDate) ?>"
                    >

                </div>

            </div>


            <!-- SCROLLABLE LOG TABLE -->

            <div class="table-wrapper order-log-wrapper">

                <table class="orders-table log-table">

                    <thead>

                        <tr>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Total Amount
                            </th>

                            <th>
                                Time
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="orderLogBody">

                        <?php foreach ($logOrders as $log): ?>

                            <tr
                                class="order-row"
                                data-order-id="<?= $log['id'] ?>"
                                data-student-id="<?= htmlspecialchars($log['student_id']) ?>"
                                data-status="<?= htmlspecialchars($log['status']) ?>"
                                data-total="<?= htmlspecialchars($log['total']) ?>"
                                data-items="<?= htmlspecialchars(
                                    json_encode($log['items']),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                                <td>
                                    <?= htmlspecialchars($log['student_id']) ?>
                                </td>

                                <td>
                                    Order #<?= $log['id'] ?>
                                </td>

                                <td>
                                    ₱<?= number_format(
                                        $log['total'],
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <?= date(
                                        'g:i A',
                                        strtotime($log['updated_at'])
                                    ) ?>
                                </td>

                                <td>

                                    <span
                                        class="status-badge status-<?= htmlspecialchars($log['status']) ?>"
                                    >
                                        <?= ucfirst($log['status']) ?>
                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>


                <div
                    class="empty-orders"
                    id="emptyLog"
                    style="<?= count($logOrders) > 0 ? 'display:none;' : '' ?>"
                >

                    <div class="empty-icon">
                        📋
                    </div>

                    <h3>
                        No orders for this date
                    </h3>

                    <p>
                        Completed or cancelled orders for this date will appear here.
                    </p>

                </div>

            </div>

        </section>

    </main>

</div>


<!-- =========================================================
     ORDER SUMMARY MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="orderModal"
>

    <div
        class="order-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalOrderTitle"
    >

        <div class="modal-header">

            <div>

                <h2 id="modalOrderTitle">
                    Order Summary
                </h2>

                <p>
                    Details of the student's order
                </p>

            </div>


            <button
                type="button"
                class="close-button"
                id="closeOrderModal"
                aria-label="Close"
            >
                ×
            </button>

        </div>


        <div id="orderDetails">

        </div>


        <button
            type="button"
            class="modal-close-action"
            id="modalCloseAction"
        >
            Close
        </button>

    </div>

</div>


<!-- =========================================================
     LOGOUT CONFIRMATION MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="logoutModal"
>

    <div
        class="logout-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="logoutTitle"
    >

        <div class="logout-icon">
            ↪
        </div>


        <h2 id="logoutTitle">
            Confirm Logout
        </h2>


        <p>
            Are you sure you want to log out of your admin account?
        </p>


        <div class="logout-actions">

            <button
                type="button"
                class="logout-cancel"
                id="cancelLogout"
            >
                Cancel
            </button>


            <button
                type="button"
                class="logout-confirm"
                id="confirmLogout"
            >
                Log Out
            </button>

        </div>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="../assests\css/js/orders-admin.js"></script>

</body>

</html>