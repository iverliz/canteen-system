<?php

session_start();

require_once "../config/database.php";


/* =========================================================
   LOGIN CHECK
========================================================= */

if (
    !isset($_SESSION['admin_username']) ||
    !isset($_SESSION['admin_role'])
) {

    header("Content-Type: application/json");

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized.'
    ]);

    exit;
}


header("Content-Type: application/json");


/* =========================================================
   TODAY
========================================================= */

$today = date('Y-m-d');


/* =========================================================
   COMPLETED ORDERS TODAY
   Same basis as orders-admin.php
========================================================= */

$completedOrders = 0;


$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'completed'
    AND DATE(updated_at) = ?
");


$stmt->bind_param(
    "s",
    $today
);


$stmt->execute();


$result =
    $stmt->get_result();


if ($row = $result->fetch_assoc()) {

    $completedOrders =
        (int)$row['total'];

}


$stmt->close();


/* =========================================================
   PENDING ORDERS
   Same basis as orders-admin.php
========================================================= */

$pendingOrders = 0;


$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'pending'
");


if ($result) {

    $row =
        $result->fetch_assoc();


    $pendingOrders =
        (int)$row['total'];

}


/* =========================================================
   TOTAL SALES TODAY
   ONLY COMPLETED ORDERS
   Uses created_at as requested
========================================================= */

$totalSales = 0;


$stmt = $conn->prepare("
    SELECT COALESCE(
        SUM(total),
        0
    ) AS total_sales
    FROM orders
    WHERE status = 'completed'
    AND DATE(created_at) = ?
");


$stmt->bind_param(
    "s",
    $today
);


$stmt->execute();


$result =
    $stmt->get_result();


if ($row = $result->fetch_assoc()) {

    $totalSales =
        (float)$row['total_sales'];

}


$stmt->close();


/* =========================================================
   RESPONSE
========================================================= */

echo json_encode([

    'success' => true,

    'completedOrders' =>
        $completedOrders,

    'pendingOrders' =>
        $pendingOrders,

    'totalSales' =>
        $totalSales

]);

?>