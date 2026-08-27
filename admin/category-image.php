<?php

require_once "../config/database.php";


/* =========================================
   GET CATEGORY ID
========================================= */

$categoryId =
    intval(
        $_GET['id'] ?? 0
    );


if ($categoryId <= 0) {

    http_response_code(404);
    exit();

}


/* =========================================
   GET IMAGE FROM DATABASE
========================================= */

$stmt =
    $conn->prepare(
        "SELECT category_picture
         FROM `food-category`
         WHERE category_id = ?
         LIMIT 1"
    );


$stmt->bind_param(
    "i",
    $categoryId
);


$stmt->execute();


$stmt->store_result();


if ($stmt->num_rows !== 1) {

    http_response_code(404);
    exit();

}


$stmt->bind_result(
    $image
);


$stmt->fetch();


$stmt->close();


/* =========================================
   CHECK IMAGE
========================================= */

if (
    empty($image)
) {

    http_response_code(404);
    exit();

}


/* =========================================
   DETERMINE IMAGE TYPE
========================================= */

$finfo =
    finfo_open(
        FILEINFO_MIME_TYPE
    );


$mime =
    finfo_buffer(
        $finfo,
        $image
    );


finfo_close($finfo);


/* =========================================
   ALLOWED IMAGE TYPES
========================================= */

$allowedTypes = [

    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'

];


if (
    !in_array(
        $mime,
        $allowedTypes,
        true
    )
) {

    http_response_code(404);
    exit();

}


/* =========================================
   OUTPUT IMAGE
========================================= */

header(
    "Content-Type: " . $mime
);

header(
    "Content-Length: " . strlen($image)
);

header(
    "Cache-Control: public, max-age=86400"
);


echo $image;

exit();

?>