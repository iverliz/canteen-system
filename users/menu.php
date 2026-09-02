<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect this page — redirect to login if not authenticated
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$username = $_SESSION['username'] ?? 'User';

/* GET CATEGORIES */

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

/* GET AVAILABLE FOOD ITEMS */

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


    <!-- =========================================
         LEFT SIDEBAR
    ========================================== -->

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item">
                Dashboard
            </a>

            <a href="menu.php" class="menu-item active">
                Menu
            </a>

            <a href="orders.php" class="menu-item">
                My Orders
            </a>

        </nav>

    </aside>



    <!-- =========================================
         MAIN CONTENT
    ========================================== -->

    <main class="main-content">


        <!-- =========================================
             HEADER
        ========================================== -->

        <header class="top-header">

            <h1>MENU</h1>


            <!-- SEARCH -->

            <div class="search-box">

                <input
                    type="text"
                    placeholder="Search Food"
                    id="foodSearch"
                >

                <span class="search-icon">
                    ⌕
                </span>

            </div>



            <!-- =====================================
                 PROFILE
            ====================================== -->

            <div class="profile-container">

                <button
                    type="button"
                    class="profile-button"
                    id="profileBtn"
                >

                    <div class="profile-icon">
                        👤
                    </div>

                    <span class="profile-name"><?= htmlspecialchars($username) ?></span>

                    <span class="profile-arrow">
                        ⌄
                    </span>

                </button>


                <!-- PROFILE DROPDOWN -->

                <div
                    class="profile-dropdown"
                    id="profileDropdown"
                >

                    <div class="profile-info">

                        <div class="profile-large-icon">
                            👤
                        </div>

                        <div>

                            <strong><?= htmlspecialchars($username) ?></strong>

                            <small>
                                Student
                            </small>

                        </div>

                    </div>


                    <div class="dropdown-divider"></div>

                    <a href="../auth/logout.php" class="logout-button">
                        🚪 Logout
                    </a>

                </div>

            </div>

        </header>



        <!-- =========================================
             CATEGORY
        ========================================== -->

        <section class="category-section">

            <h2>
                Category
            </h2>


            <div class="category-list">

                <button type="button" class="category-button active" data-category="All">
                    All
                </button>

                <?php foreach ($categories as $category): ?>

                    <button
                        type="button"
                        class="category-button"
                        data-category="<?= htmlspecialchars($category['title']) ?>"
                    >

                        <?php if ($category['has_picture']): ?>

                            <img
                                src="../admin/category-image.php?id=<?= (int)$category['id'] ?>"
                                alt="<?= htmlspecialchars($category['title']) ?>"
                                class="category-button-icon"
                            >

                        <?php endif; ?>

                        <?= htmlspecialchars($category['title']) ?>

                    </button>

                <?php endforeach; ?>

            </div>

        </section>



        <!-- =========================================
             FOOD MENU
        ========================================== -->

        <section class="food-section">

            <h2 class="section-title">
                Food Menu
            </h2>


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

                                <div class="food-placeholder">
                                    🍽️
                                </div>

                            <?php endif; ?>

                            <div class="food-card-content">

                                <h3>
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

                <?php endif; ?>

            </div>

        </section>


    </main>


    <aside class="right-sidebar">

        <section class="my-order-box">

            <h2>My Order</h2>

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



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    if (profileBtn && profileDropdown) {

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

    }

    const categoryButtons = document.querySelectorAll(".category-button");
    const foodCards = document.querySelectorAll(".food-card");

    categoryButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const selectedCategory = this.getAttribute("data-category");

            categoryButtons.forEach(function (btn) {
                btn.classList.remove("active");
            });

            this.classList.add("active");

            foodCards.forEach(function (card) {

                const foodCategory = card.getAttribute("data-category");

                if (selectedCategory === "All" || foodCategory === selectedCategory) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }

            });

        });

    });

    const foodSearch = document.getElementById("foodSearch");

    if (foodSearch) {

        foodSearch.addEventListener("input", function () {

            const searchText = this.value.toLowerCase().trim();

            foodCards.forEach(function (card) {

                const foodName = card.querySelector("h3").textContent.toLowerCase();

                if (foodName.includes(searchText)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }

            });

        });

    }

    const myOrderContent = document.getElementById("myOrderContent");
    const orderTotal = document.getElementById("orderTotal");
    const checkoutButton = document.getElementById("checkoutButton");

    let orders = [];

    const addOrderButtons = document.querySelectorAll(".add-order-button");

    addOrderButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const foodCard = this.closest(".food-card");
            const foodId = this.dataset.id;
            const foodName = foodCard.querySelector("h3").textContent.trim();
            const foodPriceEl = foodCard.querySelector(".food-price");
            const foodPrice = parseFloat(foodPriceEl.dataset.price);

            const existingItem = orders.find(function (item) {
                return item.id === foodId;
            });

            if (existingItem) {
                existingItem.quantity++;
            } else {
                orders.push({ id: foodId, name: foodName, price: foodPrice, quantity: 1 });
            }

            updateOrder();

            const originalText = this.textContent;
            this.textContent = "Added ✓";

            setTimeout(function () {
                button.textContent = originalText;
            }, 800);

        });

    });

    function updateOrder() {

        myOrderContent.innerHTML = "";
        let total = 0;

        if (orders.length === 0) {
            myOrderContent.innerHTML = `<div class="empty-order"><p>Your order is empty.</p></div>`;
        }

        orders.forEach(function (item, index) {

            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            const orderItem = document.createElement("div");
            orderItem.className = "order-item";

            orderItem.innerHTML = `
                <div class="order-item-info">
                    <strong>${item.name}</strong>
                    <span>₱${item.price.toFixed(2)}</span>
                </div>
                <div class="order-item-controls">
                    <button type="button" class="quantity-button decrease" data-index="${index}">−</button>
                    <span class="quantity">${item.quantity}</span>
                    <button type="button" class="quantity-button increase" data-index="${index}">+</button>
                    <button type="button" class="remove-button" data-index="${index}">×</button>
                </div>
                <div class="order-item-total">₱${itemTotal.toFixed(2)}</div>
            `;

            myOrderContent.appendChild(orderItem);

        });

        orderTotal.textContent = "₱" + total.toFixed(2);

        document.querySelectorAll(".decrease").forEach(function (button) {
            button.addEventListener("click", function () {
                const index = parseInt(this.dataset.index);
                if (orders[index].quantity > 1) {
                    orders[index].quantity--;
                } else {
                    orders.splice(index, 1);
                }
                updateOrder();
            });
        });

        document.querySelectorAll(".increase").forEach(function (button) {
            button.addEventListener("click", function () {
                const index = parseInt(this.dataset.index);
                orders[index].quantity++;
                updateOrder();
            });
        });

        document.querySelectorAll(".remove-button").forEach(function (button) {
            button.addEventListener("click", function () {
                const index = parseInt(this.dataset.index);
                orders.splice(index, 1);
                updateOrder();
            });
        });

    }


    if (checkoutButton) {

        checkoutButton.addEventListener("click", function () {

            if (orders.length === 0) {
                alert("Your order is empty.");
                return;
            }

            checkoutButton.disabled = true;
            checkoutButton.textContent = "Processing...";

            fetch("checkout.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ orders: orders })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {

                    if (data.success) {
                        orders = [];
                        updateOrder();
                        window.location.href = "dashboard.php";
                    } else {
                        alert(data.message || "Something went wrong.");
                        checkoutButton.disabled = false;
                        checkoutButton.textContent = "Checkout";
                    }

                })
                .catch(function () {
                    alert("Something went wrong. Please try again.");
                    checkoutButton.disabled = false;
                    checkoutButton.textContent = "Checkout";
                });

        });

    }

    updateOrder();

});

</script>

</body>

</html>