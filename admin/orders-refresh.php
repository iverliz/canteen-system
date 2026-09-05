<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================================================
   CHECK ADMIN LOGIN
========================================================= */

if (
    empty($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {

    http_response_code(401);

    header('Content-Type: application/json');

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized.'
    ]);

    exit();

}


/* =========================================================
   DATABASE
========================================================= */

require_once '../database/db_connect.php';


header('Content-Type: application/json');


/* =========================================================
   SELECTED LOG DATE
========================================================= */

$logDate =
    $_GET['log_date']
    ?? date('Y-m-d');


/* =========================================================
   SUMMARY COUNTS
========================================================= */

$counts = [
    'pending' => 0,
    'preparing' => 0,
    'ready' => 0,
    'completed' => 0,
    'cancelled' => 0
];


/* =========================================================
   ACTIVE ORDER COUNTS
========================================================= */

$countStmt = $conn->prepare("
    SELECT
        status,
        COUNT(*) AS total
    FROM orders
    WHERE status IN (
        'pending',
        'preparing',
        'ready'
    )
    GROUP BY status
");

$countStmt->execute();

$countResult =
    $countStmt->get_result();


while (
    $row =
    $countResult->fetch_assoc()
) {

    if (
        isset(
            $counts[
                $row['status']
            ]
        )
    ) {

        $counts[
            $row['status']
        ] =
            (int)$row['total'];

    }

}

$countStmt->close();


/* =========================================================
   COMPLETED FOR CURRENT DAY
========================================================= */

$today = date('Y-m-d');

$completedStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'completed'
    AND DATE(updated_at) = ?
");

$completedStmt->bind_param(
    "s",
    $today
);

$completedStmt->execute();

$completedResult =
    $completedStmt
        ->get_result()
        ->fetch_assoc();

$counts['completed'] =
    (int)(
        $completedResult['total']
        ?? 0
    );

$completedStmt->close();


/* =========================================================
   CANCELLED FOR SELECTED DATE
========================================================= */

$cancelledStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'cancelled'
    AND DATE(updated_at) = ?
");

$cancelledStmt->bind_param(
    "s",
    $logDate
);

$cancelledStmt->execute();

$cancelledResult =
    $cancelledStmt
        ->get_result()
        ->fetch_assoc();

$counts['cancelled'] =
    (int)(
        $cancelledResult['total']
        ?? 0
    );

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
    JOIN users u
        ON u.id = o.user_id
    WHERE o.status IN (
        'pending',
        'preparing',
        'ready'
    )
    ORDER BY o.created_at ASC
");

$activeStmt->execute();

$activeOrders =
    $activeStmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);

$activeStmt->close();


/* =========================================================
   ACTIVE ORDER ITEMS
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

    $itemStmt->bind_param(
        "i",
        $order['id']
    );

    $itemStmt->execute();

    $order['items'] =
        $itemStmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );

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
    JOIN users u
        ON u.id = o.user_id
    WHERE o.status IN (
        'completed',
        'cancelled'
    )
    AND DATE(o.updated_at) = ?
    ORDER BY o.updated_at DESC
");

$logStmt->bind_param(
    "s",
    $logDate
);

$logStmt->execute();

$logOrders =
    $logStmt
        ->get_result()
        ->fetch_all(
            MYSQLI_ASSOC
        );

$logStmt->close();


/* =========================================================
   ORDER LOG ITEMS
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

    $itemStmt->bind_param(
        "i",
        $log['id']
    );

    $itemStmt->execute();

    $log['items'] =
        $itemStmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );

    $itemStmt->close();

}

unset($log);


/* =========================================================
   RETURN JSON
========================================================= */

echo json_encode([
    'success' => true,

    'counts' => $counts,

    'activeOrders' =>
        $activeOrders,

    'logOrders' =>
        $logOrders,

    'logDate' =>
        $logDate,

    'today' =>
        $today
]);

exit();