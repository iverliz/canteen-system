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
   GET SELECTED MONTH AND WEEK
========================================================= */

$month =
    $_GET['month'] ?? date('Y-m');


$week =
    intval(
        $_GET['week'] ?? 1
    );


/* =========================================================
   VALIDATE MONTH
========================================================= */

if (
    !preg_match(
        '/^\d{4}-\d{2}$/',
        $month
    )
) {

    $month =
        date('Y-m');

}


$year =
    intval(
        substr(
            $month,
            0,
            4
        )
    );


$monthNumber =
    intval(
        substr(
            $month,
            5,
            2
        )
    );


if (
    $monthNumber < 1 ||
    $monthNumber > 12
) {

    $monthNumber =
        intval(
            date('m')
        );

    $year =
        intval(
            date('Y')
        );

}


if (
    $week < 1 ||
    $week > 6
) {

    $week = 1;

}


/* =========================================================
   FIRST DAY OF SELECTED MONTH
========================================================= */

$firstDay =
    new DateTime(
        sprintf(
            '%04d-%02d-01',
            $year,
            $monthNumber
        )
    );


/* =========================================================
   CALCULATE WEEK START
   Week 1 = first Monday/Sunday block containing month start
========================================================= */

$firstDayOfWeek =
    intval(
        $firstDay->format('N')
    );


/*
 * Move to Monday of the first calendar week.
 */

$firstWeekMonday =
    clone $firstDay;


$firstWeekMonday->modify(
    '-' .
    ($firstDayOfWeek - 1) .
    ' days'
);


/*
 * Selected week's Monday.
 */

$weekStart =
    clone $firstWeekMonday;


$weekStart->modify(
    '+' .
    (($week - 1) * 7) .
    ' days'
);


/*
 * Sunday.
 */

$weekEnd =
    clone $weekStart;


$weekEnd->modify(
    '+6 days'
);


/* =========================================================
   CREATE 7 DAYS
========================================================= */

$days = [];


for (
    $i = 0;
    $i < 7;
    $i++
) {

    $date =
        clone $weekStart;


    $date->modify(
        '+' . $i . ' days'
    );


    $dateString =
        $date->format(
            'Y-m-d'
        );


    $days[$dateString] = [

        'date' =>
            $dateString,

        'dayIndex' =>
            $i,

        'day' =>
            $date->format('D'),

        'label' =>
            $date->format('D') .
            ' (' .
            $date->format('M j') .
            ')',

        'sales' =>
            0

    ];

}


/* =========================================================
   GET COMPLETED SALES
========================================================= */

$startDate =
    $weekStart->format(
        'Y-m-d'
    );


$endDate =
    $weekEnd->format(
        'Y-m-d'
    );


$stmt = $conn->prepare("
    SELECT
        DATE(created_at) AS order_date,
        COALESCE(
            SUM(total),
            0
        ) AS daily_sales
    FROM orders
    WHERE status = 'completed'
    AND DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY order_date ASC
");


$stmt->bind_param(
    "ss",
    $startDate,
    $endDate
);


$stmt->execute();


$result =
    $stmt->get_result();


while (
    $row =
        $result->fetch_assoc()
) {

    $orderDate =
        $row['order_date'];


    if (
        isset(
            $days[$orderDate]
        )
    ) {

        $days[$orderDate]['sales'] =
            (float)$row['daily_sales'];

    }

}


$stmt->close();


/* =========================================================
   RESPONSE
========================================================= */

echo json_encode([

    'success' => true,

    'month' =>
        $month,

    'week' =>
        $week,

    'startDate' =>
        $startDate,

    'endDate' =>
        $endDate,

    'days' =>
        array_values($days)

]);

?>