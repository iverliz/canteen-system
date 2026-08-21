<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Dashboard</title>

    <link rel="stylesheet" href="../assests/css/dashboard_user.css">
</head>

<body>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

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

});
</script>
<div class="dashboard-container">

    <!-- ================= LEFT SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item active">
                Dashboard
            </a>

            <a href="menu.php" class="menu-item">
                Menu
            </a>

        </nav>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">

        <!-- HEADER -->

        <header class="top-header">

            <h1>DASHBOARD</h1>

            <div class="search-box">
                <input type="text" placeholder="Search Food">
                <span class="search-icon">⌕</span>
            </div>


            <!-- ================= PROFILE ================= -->

            <div class="profile-container">

    <button type="button" class="profile-button" id="profileBtn">

        <div class="profile-icon">👤</div>

        <span class="profile-name"></span>

        <span class="profile-arrow">⌄</span>

    </button>


    <div class="profile-dropdown" id="profileDropdown">

        <div class="profile-info">

            <div class="profile-large-icon">👤</div>

            <div>
                <strong></strong>
                <small>Student</small>
            </div>

        </div>


        <div class="dropdown-divider"></div>
<a href="../auth/logout.php" class="logout-button">
    🚪 Logout
</a>
        </a>

    </div>

</div>
        </header>


        <!-- ================= PROMOTION BANNER ================= -->

        <section class="promo-banner">

    <div class="promo-content">

        <span class="promo-small">TODAY'S SPECIAL</span>

        <h2>Delicious Food<br>For Only ₱50!</h2>

        <p>
            Grab your favorite canteen meals at an affordable price.
        </p>

        <a href="menu.php" class="promo-button">
            Order Now
        </a>

    </div>

    <div class="promo-image">
        <img src="../assests/css/images/hotdog.png" alt="Hotdog">
    </div>

</section>


        <!-- ================= POPULAR FOOD ================= -->

        <section class="popular-section">

            <h2 class="section-title">
                Popular Food
            </h2>

        </section>

    </main>


    <!-- ================= RIGHT SIDEBAR ================= -->

    <aside class="right-sidebar">


        <!-- ================= HISTORY ORDER ================= -->

        <section class="history-box">

            <h2>History Order</h2>

            <!--
                Previous purchases will appear here later.

                Example:
                - Food image
                - Food name
                - Quantity
                - Date
            -->

            <div class="history-content">

            </div>

        </section>


        <!-- ================= MY ORDER ================= -->

       <!-- ================= MY ORDER ================= -->

<section class="my-order-box">

    <h2>My Order</h2>

    <!-- FOOD ORDERS WILL APPEAR HERE LATER -->

    <div class="my-order-content">
    </div>


    <!-- ORDER TOTAL -->

    <div class="order-total">

        <span>Total</span>

        <strong>₱0.00</strong>

    </div>


    <!-- CHECKOUT -->

    <div class="checkout-area">

        <button type="button" class="checkout-button">
            Checkout
        </button>

    </div>

</section>
</div>



</body>

</html>