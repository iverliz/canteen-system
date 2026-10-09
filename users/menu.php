<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$username = $_SESSION['username'] ?? 'User';

$categories = [];

$categoryResult = $conn->query("
    SELECT category_id, category_title, category_picture
    FROM `food-category`
    ORDER BY category_title ASC
");

if ($categoryResult) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = [
            'id' => $row['category_id'],
            'title' => $row['category_title'],
            'has_picture' => !empty($row['category_picture'])
        ];
    }
}

$foods = [];

$foodResult = $conn->query("
    SELECT
        food_id,
        food_name,
        food_price,
        menu_food_category,
        `food-description`,
        food_picture
    FROM `food-menu`
    WHERE availability = 1
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Menu</title>

    <link rel="stylesheet" href="../assests/css/menu_user.css">
</head>

<body>

<div class="menu-container">

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item">
                <span class="menu-icon">🏠</span>
                Dashboard
            </a>

            <a href="menu.php" class="menu-item active">
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
                <h1>MENU</h1>
                <p>Choose your favorites and add them to your order.</p>
            </div>

            <div class="search-box">

                <input
                    type="text"
                    placeholder="Search Food"
                    id="foodSearch"
                    aria-label="Search food"
                >

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

        <section class="category-section">

            <h2>Category</h2>

            <div class="category-list">

                <button type="button" class="category-button active" data-category="All" aria-pressed="true">
                    All
                </button>

                <?php foreach ($categories as $category): ?>

                    <button
                        type="button"
                        class="category-button"
                        data-category="<?= htmlspecialchars($category['title']) ?>"
                        aria-pressed="false"
                    >

                        <?php if ($category['has_picture']): ?>

                            <img
                                src="../admin/category-image.php?id=<?= (int)$category['id'] ?>"
                                alt=""
                                class="category-button-icon"
                                loading="lazy"
                            >

                        <?php endif; ?>

                        <?= htmlspecialchars($category['title']) ?>

                    </button>

                <?php endforeach; ?>

            </div>

        </section>

        <section class="food-section">

            <h2 class="section-title">Food Menu</h2>

            <div class="food-grid" id="foodGrid">

                <?php if (empty($foods)): ?>

                    <p class="no-food-message">
                        No food items available right now. Please check back later.
                    </p>

                <?php else: ?>

                    <?php foreach ($foods as $food): ?>

                        <div
                            class="food-card"
                            data-category="<?= htmlspecialchars($food['menu_food_category']) ?>"
                        >

                            <?php if ($food['food_picture']): ?>

                                <img
                                    src="<?= $food['food_picture'] ?>"
                                    alt="<?= htmlspecialchars($food['food_name']) ?>"
                                >

                            <?php else: ?>

                                <div class="food-placeholder">🍽️</div>

                            <?php endif; ?>

                            <div class="food-card-content">

                                <span class="food-category-tag">
                                    <?= htmlspecialchars($food['menu_food_category']) ?>
                                </span>

                                <h3 title="<?= htmlspecialchars($food['food_name']) ?>">
                                    <?= htmlspecialchars($food['food_name']) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars(
                                        $food['food-description'] ?: 'No description available.'
                                    ) ?>
                                </p>

                                <span
                                    class="food-price"
                                    data-price="<?= htmlspecialchars($food['food_price']) ?>"
                                >
                                    ₱<?= number_format($food['food_price'], 2) ?>
                                </span>

                                <button
                                    type="button"
                                    class="add-order-button"
                                    data-id="<?= (int)$food['food_id'] ?>"
                                >
                                    Add to Order
                                </button>

                            </div>

                        </div>

                    <?php endforeach; ?>

                    <p class="no-food-message" id="noResults" hidden>
                        No matching food found.
                    </p>

                <?php endif; ?>

            </div>

        </section>

    </main>

    <aside class="right-sidebar" id="cartPanel">

        <section class="my-order-box">

            <div class="order-box-header">
                <h2>My Order</h2>
                <button type="button" class="cart-close" id="cartClose" aria-label="Close my order">×</button>
            </div>

            <div class="my-order-content" id="myOrderContent"></div>

            <div class="order-total">
                <span>Total</span>
                <strong id="orderTotal">₱0.00</strong>
            </div>

            <div class="checkout-area">
                <button type="button" class="checkout-button" id="checkoutButton">
                    Checkout
                </button>
            </div>

        </section>

    </aside>

</div>

<button
    type="button"
    class="cart-fab"
    id="cartToggle"
    aria-label="Open my order"
    aria-controls="cartPanel"
    aria-expanded="false"
>
    <span>🛒</span>
    <span>My Order</span>
    <span class="cart-fab-count" id="cartCount">0</span>
</button>

<div class="cart-overlay" id="cartOverlay"></div>

<script src="../assests/css/js/menu_student.js"></script>

</body>

</html>