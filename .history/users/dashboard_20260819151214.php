<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Dashboard</title>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

<div class="app-container">

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">

        <div class="logo">
            <h2>Order<span>Eats</span></h2>
        </div>

        <nav class="menu">

            <a href="#" class="active">
                <i class="fa-solid fa-gauge"></i>
                Dashboard
            </a>

            <a href="menu.php">
                <i class="fa-solid fa-utensils"></i>
                Menu
            </a>

        </nav>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- TOP HEADER -->
        <header class="top-header">

            <h1>DASHBOARD</h1>

            <div class="search-bar">
                <input
                    type="text"
                    placeholder="Search Food"
                >

                <button type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <div class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>

        </header>


        <!-- ================= PROMO BANNER ================= -->
        <section class="banner">

            <div class="banner-text">

                <h2>
                    Craving Something
                    <br>
                    <span class="highlight">Tasty?</span>
                </h2>

                <p class="price">₱99</p>

                <button type="button" class="btn-banner-order">
                    Order
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>

            </div>

            <div class="banner-image">

                <img
                    src="https://via.placeholder.com/350x200?text=Food+Banner+Art"
                    alt="Food Banner"
                >

            </div>

        </section>


        <!-- ================= POPULAR FOOD ================= -->
        <section class="popular-food">

            <h3>Popular Food</h3>

            <div class="food-grid">

                <?php

                $foodItems = [

                    [
                        'title' => 'Fried Rice with Drinks',
                        'rating' => 5,
                        'price' => '₱99',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Burger',
                        'rating' => 5,
                        'price' => '₱85',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Spaghetti',
                        'rating' => 5,
                        'price' => '₱75',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Hotdog',
                        'rating' => 5,
                        'price' => '₱60',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Pizza',
                        'rating' => 5,
                        'price' => '₱120',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Chicken Meal',
                        'rating' => 5,
                        'price' => '₱110',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Fries',
                        'rating' => 5,
                        'price' => '₱50',
                        'image' => 'https://via.placeholder.com/120'
                    ],

                    [
                        'title' => 'Sandwich',
                        'rating' => 5,
                        'price' => '₱70',
                        'image' => 'https://via.placeholder.com/120'
                    ]

                ];

                foreach ($foodItems as $item):
                ?>

                    <div class="food-card">

                        <div class="food-img">

                            <img
                                src="<?php echo htmlspecialchars($item['image']); ?>"
                                alt="<?php echo htmlspecialchars($item['title']); ?>"
                            >

                        </div>

                        <h4>
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h4>

                        <div class="rating">

                            <?php for ($i = 0; $i < $item['rating']; $i++): ?>

                                <i class="fa-solid fa-star"></i>

                            <?php endfor; ?>

                        </div>

                        <div class="card-footer">

                            <span class="card-price">
                                <?php echo htmlspecialchars($item['price']); ?>
                            </span>

                            <button
                                type="button"
                                class="btn-card-order"
                            >
                                ORDER
                            </button>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>

    </main>


    <!-- ================= RIGHT PANEL ================= -->
    <aside class="right-panel">


        <!-- ORDER HISTORY -->
        <div class="panel-card history-card">

            <h3>Order History</h3>

            <div class="history-list">

                <div class="history-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Spaghetti"
                    >

                    <span class="item-name">
                        Spaghetti
                    </span>

                    <span class="item-qty">
                        x1
                    </span>

                </div>


                <div class="history-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Burger"
                    >

                    <span class="item-name">
                        Burger
                    </span>

                    <span class="item-qty">
                        x1
                    </span>

                </div>


                <div class="history-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Pizza"
                    >

                    <span class="item-name">
                        Pizza
                    </span>

                    <span class="item-qty">
                        x1
                    </span>

                </div>

            </div>

        </div>


        <!-- MY ORDER -->
        <div class="panel-card order-card">

            <h3>My Order</h3>

            <div class="order-list">


                <!-- BURGER -->
                <div class="order-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Burger"
                    >

                    <div class="order-details">

                        <span class="item-name">
                            Burger
                        </span>

                        <span class="item-qty">
                            x1
                        </span>

                    </div>

                    <span class="item-price">
                        ₱25
                    </span>

                </div>


                <!-- SPAGHETTI -->
                <div class="order-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Spaghetti"
                    >

                    <div class="order-details">

                        <span class="item-name">
                            Spaghetti
                        </span>

                        <span class="item-qty">
                            x1
                        </span>

                    </div>

                    <span class="item-price">
                        ₱25
                    </span>

                </div>


                <!-- HOTDOG -->
                <div class="order-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Hotdog"
                    >

                    <div class="order-details">

                        <span class="item-name">
                            Hotdog
                        </span>

                        <span class="item-qty">
                            x1
                        </span>

                    </div>

                    <span class="item-price">
                        ₱25
                    </span>

                </div>


                <!-- PIZZA -->
                <div class="order-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Pizza"
                    >

                    <div class="order-details">

                        <span class="item-name">
                            Pizza
                        </span>

                        <span class="item-qty">
                            x1
                        </span>

                    </div>

                    <span class="item-price">
                        ₱25
                    </span>

                </div>


                <!-- BURGER -->
                <div class="order-item">

                    <img
                        src="https://via.placeholder.com/40"
                        alt="Burger"
                    >

                    <div class="order-details">

                        <span class="item-name">
                            Burger
                        </span>

                        <span class="item-qty">
                            x1
                        </span>

                    </div>

                    <span class="item-price">
                        ₱25
                    </span>

                </div>

            </div>


            <!-- ORDER FOOTER -->
            <div class="order-footer">

                <div class="total-row">

                    <span>Total:</span>

                    <span class="total-price">
                        ₱125
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-checkout"
                >
                    CHECK OUT
                </button>

            </div>

        </div>

    </aside>

</div>

</body>
</html>