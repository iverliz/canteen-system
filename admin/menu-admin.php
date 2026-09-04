<?php
session_start();

require_once "../config/database.php";

/* LOGIN SESSION */

if (!isset($_SESSION['admin_username']) || !isset($_SESSION['admin_role'])) {
    header("Location: login.php");
    exit;
}

$loggedInName = $_SESSION['admin_username'];
$loggedInRole = $_SESSION['admin_role'];

/* PROFILE INITIAL */

$profileInitial = strtoupper(
    substr(
        trim($loggedInName),
        0,
        1
    )
);

/* FORMAT ROLE */

$role = strtolower(trim($loggedInRole));

switch ($role) {

    case 'canteen manager':
    case 'manager':
    case 'canteen_manager':
        $displayRole = 'Canteen Manager';
        break;

    case 'canteen staff':
    case 'staff':
    case 'canteen_staff':
        $displayRole = 'Canteen Staff';
        break;

    default:
        $displayRole = ucwords(
            str_replace(
                ['_', '-'],
                ' ',
                $role
            )
        );
        break;
}

/* DATABASE ACTIONS */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    header('Content-Type: application/json');

    $action = $_POST['action'];

    /* ADD FOOD */

    if ($action === 'add') {

        $name = trim($_POST['food_name'] ?? '');
        $price = $_POST['food_price'] ?? '';
        $category = trim($_POST['menu_food_category'] ?? '');
        $description = trim($_POST['food_description'] ?? '');

        if ($name === '' || $price === '' || $category === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Please complete all required fields.'
            ]);
            exit;
        }

        $imageData = null;

        if (
            isset($_FILES['food_picture']) &&
            $_FILES['food_picture']['error'] === UPLOAD_ERR_OK
        ) {

            if ($_FILES['food_picture']['size'] > 16 * 1024 * 1024) {

                echo json_encode([
                    'success' => false,
                    'message' => 'The image is too large. Maximum size is 16MB.'
                ]);

                exit;
            }

            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif'
            ];

            $fileType = mime_content_type(
                $_FILES['food_picture']['tmp_name']
            );

            if (!in_array($fileType, $allowedTypes)) {

                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid image type.'
                ]);

                exit;
            }

            $imageData = file_get_contents(
                $_FILES['food_picture']['tmp_name']
            );
        }

        if ($imageData !== null) {

            $stmt = $conn->prepare("
                INSERT INTO `food-menu`
                (
                    food_name,
                    food_price,
                    menu_food_category,
                    `food-description`,
                    food_picture,
                    availability
                )
                VALUES (?, ?, ?, ?, ?, 1)
            ");

            $null = NULL;

            $stmt->bind_param(
                "sdssb",
                $name,
                $price,
                $category,
                $description,
                $null
            );

            $stmt->send_long_data(4, $imageData);

        } else {

            $stmt = $conn->prepare("
                INSERT INTO `food-menu`
                (
                    food_name,
                    food_price,
                    menu_food_category,
                    `food-description`,
                    availability
                )
                VALUES (?, ?, ?, ?, 1)
            ");

            $stmt->bind_param(
                "sdss",
                $name,
                $price,
                $category,
                $description
            );
        }

        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => 'Menu item added successfully.'
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to add menu item.'
            ]);
        }

        $stmt->close();
        exit;
    }


    /* EDIT FOOD */

    if ($action === 'edit') {

        $foodId = intval($_POST['food_id'] ?? 0);
        $name = trim($_POST['food_name'] ?? '');
        $price = $_POST['food_price'] ?? '';
        $category = trim($_POST['menu_food_category'] ?? '');
        $description = trim($_POST['food_description'] ?? '');

        if ($foodId <= 0 || $name === '' || $price === '' || $category === '') {

            echo json_encode([
                'success' => false,
                'message' => 'Please complete all required fields.'
            ]);

            exit;
        }


        /* IF NEW IMAGE WAS UPLOADED */

        if (
            isset($_FILES['food_picture']) &&
            $_FILES['food_picture']['error'] === UPLOAD_ERR_OK
        ) {

            if ($_FILES['food_picture']['size'] > 16 * 1024 * 1024) {

                echo json_encode([
                    'success' => false,
                    'message' => 'The image is too large. Maximum size is 16MB.'
                ]);

                exit;
            }

            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif'
            ];

            $fileType = mime_content_type(
                $_FILES['food_picture']['tmp_name']
            );

            if (!in_array($fileType, $allowedTypes)) {

                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid image type.'
                ]);

                exit;
            }

            $imageData = file_get_contents(
                $_FILES['food_picture']['tmp_name']
            );

            $stmt = $conn->prepare("
                UPDATE `food-menu`
                SET
                    food_name = ?,
                    food_price = ?,
                    menu_food_category = ?,
                    `food-description` = ?,
                    food_picture = ?
                WHERE food_id = ?
            ");

            $null = NULL;

            $stmt->bind_param(
                "sdssbi",
                $name,
                $price,
                $category,
                $description,
                $null,
                $foodId
            );

            $stmt->send_long_data(4, $imageData);

        } else {

            /* UPDATE WITHOUT CHANGING IMAGE */

            $stmt = $conn->prepare("
                UPDATE `food-menu`
                SET
                    food_name = ?,
                    food_price = ?,
                    menu_food_category = ?,
                    `food-description` = ?
                WHERE food_id = ?
            ");

            $stmt->bind_param(
                "sdssi",
                $name,
                $price,
                $category,
                $description,
                $foodId
            );
        }


        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => 'Menu item updated successfully.'
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to update menu item.'
            ]);
        }

        $stmt->close();
        exit;
    }


    /* DELETE FOOD */

    if ($action === 'delete') {

        $foodId = intval($_POST['food_id'] ?? 0);

        if ($foodId <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid food item.'
            ]);

            exit;
        }

        $stmt = $conn->prepare("
            DELETE FROM `food-menu`
            WHERE food_id = ?
        ");

        $stmt->bind_param("i", $foodId);

        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => 'Menu item deleted successfully.'
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete menu item.'
            ]);
        }

        $stmt->close();
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    if ($action === 'availability') {

        $foodId = intval($_POST['food_id'] ?? 0);
        $availability = intval($_POST['availability'] ?? 0);

        if ($foodId <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid food item.'
            ]);

            exit;
        }

        $availability = $availability ? 1 : 0;

        $stmt = $conn->prepare("
            UPDATE `food-menu`
            SET availability = ?
            WHERE food_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $availability,
            $foodId
        );

        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => $availability
                    ? 'Food is now available.'
                    : 'Food is now unavailable.',
                'availability' => $availability
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to change availability.'
            ]);
        }

        $stmt->close();
        exit;
    }
}


/* GET CATEGORIES */

$categories = [];

$categoryResult = $conn->query("
    SELECT category_title
    FROM `food-category`
    ORDER BY category_title ASC
");

if ($categoryResult) {

    while ($row = $categoryResult->fetch_assoc()) {

        $categories[] = $row['category_title'];
    }
}


/* GET FOOD MENU */

$foods = [];

$foodResult = $conn->query("
    SELECT
        food_id,
        food_name,
        food_price,
        menu_food_category,
        `food-description`,
        food_picture,
        availability
    FROM `food-menu`
    ORDER BY food_id DESC
");

if ($foodResult) {

    while ($row = $foodResult->fetch_assoc()) {

        if (!empty($row['food_picture'])) {

            $row['food_picture'] =
                'data:image/jpeg;base64,' .
                base64_encode($row['food_picture']);

        } else {

            $row['food_picture'] = null;
        }

        $foods[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Menu</title>

    <link
        rel="stylesheet"
        href="../assests/css/menu-admin.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>

<body>

<div class="app-container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>
                <span style="color:#F9A825;">Order</span>EATS
            </span>

        </div>


        <nav class="sidebar-menu">

            <a
                href="dashboard-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">▣</span>
                <span>Dashboard</span>
            </a>


            <a
                href="#"
                class="sidebar-link active"
            >
                <span class="menu-icon">🍔</span>
                <span>Menu</span>
            </a>


            <a
                href="orders-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">🛒</span>
                <span>Orders</span>
            </a>


            <a
                href="categories-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">☷</span>
                <span>Categories</span>
            </a>


            <a
                href="user-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">👤</span>
                <span>User</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a
                href="#"
                class="sidebar-link"
                onclick="openLogoutModal(event)"
            >
                <span class="menu-icon">↪</span>
                <span>Logout</span>
            </a>

        </div>

    </aside>



    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- TOP HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>Menu</h1>

                <p>
                    Manage your canteen food menu
                </p>

            </div>


            <!-- USER PROFILE -->

            <div class="user-profile">

                <div class="profile-icon">
                    <?= htmlspecialchars($profileInitial) ?>
                </div>

                <div class="profile-info">

                    <strong>
                        <?= htmlspecialchars($loggedInName) ?>
                    </strong>

                    <span>
                        <?= htmlspecialchars($displayRole) ?>
                    </span>

                </div>

            </div>

        </header>



        <!-- MENU SECTION -->

        <section class="menu-section">


            <div class="section-header">

                <div>

                    <h2>
                        Food Menu
                    </h2>

                    <p>
                        Add, edit, or remove food items from your canteen menu.
                    </p>

                </div>

            </div>



            <!-- SEARCH AND FILTER -->

            <div class="menu-tools">

                <div class="search-box">

                    <span>🔍</span>

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search food..."
                        oninput="applyFilters()"
                    >

                </div>


                <select
                    id="categoryFilter"
                    onchange="applyFilters()"
                >

                    <option value="all">
                        All Categories
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= htmlspecialchars($category) ?>"
                        >
                            <?= htmlspecialchars($category) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>



            <!-- FOOD GRID -->

            <div
                class="food-grid"
                id="foodGrid"
            >


                <!-- ADD FOOD CARD -->

                <div
                    class="add-food-card"
                    onclick="openAddModal()"
                >

                    <div class="add-food-icon">
                        +
                    </div>

                    <h3>
                        Add Food
                    </h3>

                    <p>
                        Add a new food item to your menu
                    </p>

                </div>



                <!-- DATABASE FOOD -->

                <?php foreach ($foods as $food): ?>

                    <div
                        class="food-card <?= !$food['availability'] ? 'unavailable' : '' ?>"
                        data-id="<?= (int)$food['food_id'] ?>"
                        data-name="<?= htmlspecialchars(strtolower($food['food_name'])) ?>"
                        data-category="<?= htmlspecialchars($food['menu_food_category']) ?>"
                    >

                        <div class="food-image">

                            <?php if ($food['food_picture']): ?>

                                <img
                                    src="<?= $food['food_picture'] ?>"
                                    alt="<?= htmlspecialchars($food['food_name']) ?>"
                                >

                            <?php else: ?>

                                <div class="food-placeholder">
                                    🍽️
                                </div>

                            <?php endif; ?>


                            <?php if (!$food['availability']): ?>

                                <div class="unavailable-overlay">
                                    NOT AVAILABLE
                                </div>

                            <?php endif; ?>

                        </div>


                        <div class="food-info">

                            <div class="category-label">
                                <?= htmlspecialchars($food['menu_food_category']) ?>
                            </div>


                            <h3>
                                <?= htmlspecialchars($food['food_name']) ?>
                            </h3>


                            <p class="food-description">

                                <?= htmlspecialchars(
                                    $food['food-description'] ?: 'No description available.'
                                ) ?>

                            </p>


                            <div class="food-bottom">

                                <span class="price">
                                    ₱<?= number_format(
                                        $food['food_price'],
                                        2
                                    ) ?>
                                </span>

                            </div>


                            <!-- AVAILABILITY -->

                            <div class="availability-actions">

                                <button
                                    class="availability-button available-button <?= $food['availability'] ? 'selected' : '' ?>"
                                    onclick="changeAvailability(<?= (int)$food['food_id'] ?>, 1)"
                                    title="Available"
                                >
                                    ✅
                                </button>


                                <button
                                    class="availability-button unavailable-button <?= !$food['availability'] ? 'selected' : '' ?>"
                                    onclick="changeAvailability(<?= (int)$food['food_id'] ?>, 0)"
                                    title="Not Available"
                                >
                                    ❌
                                </button>

                            </div>


                            <!-- EDIT DELETE -->

                            <div class="food-actions">

                                <button
                                    class="edit-button"
                                    onclick="openEditModal(<?= (int)$food['food_id'] ?>)"
                                >
                                    Edit
                                </button>


                                <button
                                    class="delete-button"
                                    onclick="deleteItem(<?= (int)$food['food_id'] ?>)"
                                >
                                    Delete
                                </button>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            </div>



            <!-- NO RESULTS -->

            <div
                class="no-results"
                id="noResults"
            >

                <div>
                    🔍
                </div>

                <h3>
                    No food items found
                </h3>

                <p>
                    Try another search or category.
                </p>

            </div>


        </section>

    </main>

</div>



<!-- ADD / EDIT MODAL -->

<div
    class="modal-overlay"
    id="menuModal"
>

    <div class="modal">

        <div class="modal-header">

            <div>

                <h2 id="modalTitle">
                    Add Menu Item
                </h2>

                <p id="modalDescription">
                    Add a new food item to your menu.
                </p>

            </div>


            <button
                class="close-button"
                onclick="closeModal()"
            >
                ×
            </button>

        </div>



        <form
            id="menuForm"
            onsubmit="saveFood(event)"
        >

            <input
                type="hidden"
                id="foodId"
            >


            <!-- FOOD NAME -->

            <div class="form-group">

                <label>
                    Food Name
                </label>

                <input
                    type="text"
                    id="foodName"
                    placeholder="Enter food name"
                    maxlength="100"
                    required
                >

            </div>



            <!-- PRICE -->

            <div class="form-group">

                <label>
                    Price
                </label>

                <div class="price-input">

                    <span>₱</span>

                    <input
                        type="number"
                        id="foodPrice"
                        placeholder="0.00"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>

            </div>



            <!-- CATEGORY -->

            <div class="form-group">

                <label>
                    Category
                </label>

                <select
                    id="foodCategory"
                    required
                >

                    <option value="">
                        Select category
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= htmlspecialchars($category) ?>"
                        >
                            <?= htmlspecialchars($category) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>



            <!-- DESCRIPTION -->

            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea
                    id="foodDescription"
                    placeholder="Enter food description"
                    maxlength="200"
                    rows="3"
                ></textarea>

            </div>



            <!-- IMAGE -->

            <div class="form-group">

                <label>
                    Food Picture
                </label>

                <input
                    type="file"
                    id="foodImage"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    onchange="previewImage(event)"
                >

                <small>
                    JPG, PNG, WEBP, or GIF — maximum 16MB
                </small>

            </div>



            <!-- IMAGE PREVIEW -->

            <div
                class="image-preview"
                id="imagePreview"
            >

                <span>
                    Image Preview
                </span>

            </div>



            <!-- BUTTONS -->

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeModal()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="save-button"
                    id="saveButton"
                >
                    Add Menu Item
                </button>

            </div>

        </form>

    </div>

</div>



<!-- DELETE CONFIRMATION -->

<div
    class="modal-overlay"
    id="deleteModal"
>

    <div class="delete-modal">

        <div class="delete-icon">
            🗑️
        </div>

        <h2>
            Delete Food Item?
        </h2>

        <p id="deleteMessage">
            Are you sure you want to delete this food item?
        </p>

        <div class="delete-modal-buttons">

            <button
                type="button"
                class="cancel-button"
                onclick="closeDeleteModal()"
            >
                Cancel
            </button>

            <button
                type="button"
                class="confirm-delete-button"
                onclick="confirmDelete()"
            >
                Delete
            </button>

        </div>

    </div>  

</div>


<!-- LOGOUT CONFIRMATION MODAL -->

<div
    class="modal-overlay"
    id="logoutModal"
>

    <div class="logout-modal">

        <div class="logout-icon">
            ↪
        </div>

        <h2>
            Logout?
        </h2>

        <p>
            Are you sure you want to log out of your account?
        </p>

        <div class="logout-modal-buttons">

            <button
                type="button"
                class="cancel-button"
                onclick="closeLogoutModal()"
            >
                Cancel
            </button>

            <button
                type="button"
                class="confirm-logout-button"
                onclick="confirmLogout()"
            >
                Logout
            </button>

        </div>

    </div>

</div>



<script src="../assests\css/js/menu-admin.js"></script>

</body>

</html>