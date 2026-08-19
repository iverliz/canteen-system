<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderEats Dashboard</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <div class="app-container">
        
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <h2>Order<span>Eats</span></h2>
            </div>
            <nav class="menu">
                <a href="#" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="#"><i class="fa-solid fa-utensils"></i> Menu</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            
            <!-- Top Navigation / Header -->
            <header class="top-header">
                <h1>DASHBOARD</h1>
                <div class="search-bar">
                    <input type="text" placeholder="Search Food">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="menu-toggle">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </header>

            <!-- Promo Banner -->
            <section class="banner">
                <div class="banner-text">
                    <h2>Craving Something <br><span class="highlight">Tasty?</span></h2>
                    <p class="price">$2.99</p>
                    <button class="btn-banner-order">Order <i class="fa-solid fa-cart-shopping"></i></button>
                </div>
                <div class="banner-image">
                    <!-- Replace with your food artwork/3D render -->
                    <img src="https://via.placeholder.com/350x200?text=Food+Banner+Art" alt="Food Banner">
                </div>
            </section>

            <!-- Popular Food Section -->
            <section class="popular-food">
                <h3>Popular Food</h3>
                <div class="food-grid">
                    
                    <?php 
                    // Array simulating food items
                    $foodItems = array_fill(0, 8, [
                        'title' => 'Fried Rice with drinks',
                        'rating' => 5,
                        'price' => '$5.99',
                        'image' => 'https://via.placeholder.com/120'
                    ]);

                    foreach ($foodItems as $item): 
                    ?>
                        <div class="food-card">
                            <div class="food-img">
                                <img src="<?php echo $item['image']; ?>" alt="Food Item">
                            </div>
                            <h4><?php echo $item['title']; ?></h4>
                            <div class="rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="card-footer">
                                <span class="card-price"><?php echo $item['price']; ?></span>
                                <button class="btn-card-order">ORDER</button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </section>
        </main>

        <!-- Right Side Panel -->
        <aside class="right-panel">
            
            <!-- Order History Block -->
            <div class="panel-card history-card">
                <h3>History Order</h3>
                <div class="history-list">
                    <div class="history-item">
                        <img src="https://via.placeholder.com/40" alt="Spaghetti">
                        <span class="item-name">Spagetti</span>
                        <span class="item-qty">x1</span>
                    </div>
                    <div class="history-item">
                        <img src="https://via.placeholder.com/40" alt="Spaghetti">
                        <span class="item-name">Spagetti</span>
                        <span class="item-qty">x1</span>
                    </div>
                    <div class="history-item">
                        <img src="https://via.placeholder.com/40" alt="Spaghetti">
                        <span class="item-name">Spagetti</span>
                        <span class="item-qty">x1</span>
                    </div>
                </div>
            </div>

            <!-- Current Cart / My Order Block -->
            <div class="panel-card order-card">
                <h3>My Order</h3>
                <div class="order-list">
                    
                    <div class="order-item">
                        <img src="https://via.placeholder.com/40" alt="Burger">
                        <div class="order-details">
                            <span class="item-name">Burger</span>
                            <span class="item-qty">x1</span>
                        </div>
                        <span class="item-price">$25</span>
                    </div>

                    <div class="order-item">
                        <img src="https://via.placeholder.com/40" alt="Spaghetti">
                        <div class="order-details">
                            <span class="item-name">Spaghetti</span>
                            <span class="item-qty">x1</span>
                        </div>
                        <span class="item-price">$25</span>
                    </div>

                    <div class="order-item">
                        <img src="https://via.placeholder.com/40" alt="Hotdog">
                        <div class="order-details">
                            <span class="item-name">Hotdog</span>
                            <span class="item-qty">x1</span>
                        </div>
                        <span class="item-price">$25</span>
                    </div>

                    <div class="order-item">
                        <img src="https://via.placeholder.com/40" alt="Pizza">
                        <div class="order-details">
                            <span class="item-name">Pizza</span>
                            <span class="item-qty">x1</span>
                        </div>
                        <span class="item-price">$25</span>
                    </div>

                    <div class="order-item">
                        <img src="https://via.placeholder.com/40" alt="Burger">
                        <div class="order-details">
                            <span class="item-name">Burger</span>
                            <span class="item-qty">x1</span>
                        </div>
                        <span class="item-price">$25</span>
                    </div>

                </div>

                <div class="order-footer">
                    <div class="total-row">
                        <span>Total:</span>
                        <span class="total-price">$123</span>
                    </div>
                    <button class="btn-checkout">CHECK OUT</button>
                </div>
            </div>

        </aside>

    </div>

</body>
</html>