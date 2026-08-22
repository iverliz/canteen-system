<!-- orders-admin.php -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Orders</title>

    <link
        rel="stylesheet"
        href="../assests\css/orders-admin.css"
    >

</head>


<body>


<div class="app-container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                🍴
            </div>

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>

        <!-- NAVIGATION -->

        <nav class="sidebar-menu">

            <a
                href="dashboard-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    ▣
                </span>

                <span>
                    Dashboard
                </span>

            </a>

            <a
                href="menu-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    🍔
                </span>

                <span>
                    Menu
                </span>

            </a>

            <a
                href="orders-admin.php"
                class="sidebar-link active"
            >

                <span class="menu-icon">
                    🛒
                </span>

                <span>
                    Orders
                </span>

            </a>

            <a
                href="categories-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    ☷
                </span>

                <span>
                    Categories
                </span>

            </a>

            <a
                href="user-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    👤
                </span>

                <span>
                    User
                </span>

            </a>

        </nav>


        <!-- SIDEBAR BOTTOM -->

        <div class="sidebar-bottom">

            <a
                href="../auth/log_out_admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    ↪
                </span>

                <span>
                    Logout
                </span>

            </a>

        </div>


    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">


            <div class="page-heading">

                <h1>
                    Orders
                </h1>

                <p>
                    Manage student food orders and pickup status.
                </p>

            </div>

            <!-- ADMIN PROFILE -->

            <div class="user-profile">


                <div class="profile-icon">
                    A
                </div>


                <div class="profile-info">

                    <strong>
                        Admin
                    </strong>

                    <span>
                        Administrator
                    </span>

                </div>


            </div>


        </header>



        <!-- ORDER SUMMARY -->

        <section class="order-summary">

            <div class="summary-card">

                <div class="summary-icon pending">
                    !
                </div>

                <div>

                    <span>
                        Pending
                    </span>

                    <strong id="pendingCount">
                        0
                    </strong>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon preparing">
                    ◷
                </div>

                <div>

                    <span>
                        Preparing
                    </span>

                    <strong id="preparingCount">
                        0
                    </strong>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon ready">
                    ✓
                </div>

                <div>

                    <span>
                        Ready
                    </span>

                    <strong id="readyCount">
                        0
                    </strong>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon completed">
                    ✓
                </div>

                <div>

                    <span>
                        Completed
                    </span>

                    <strong id="completedCount">
                        0
                    </strong>

                </div>

            </div>


        </section>


        <!-- ACTIVE ORDERS -->

        <section class="orders-card">

            <div class="section-header">

                <div>

                    <h2>
                        Current Orders
                    </h2>

                    <p>
                        Orders waiting to be prepared or picked up.
                    </p>

                </div>


                <div class="live-status">

                    <span class="live-dot"></span>

                    Live Orders

                </div>


            </div>

            <!-- ORDER TABLE -->

            <div class="table-wrapper">

                <table class="orders-table">

                    <thead>

                        <tr>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Total Amount
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody id="ordersTableBody">


                        <!--
                            DUMMY ORDERS WILL BE
                            INSERTED BY JAVASCRIPT
                        -->


                    </tbody>


                </table>


                <!-- EMPTY STATE -->

                <div
                    class="empty-orders"
                    id="emptyOrders"
                >

                    <div class="empty-icon">
                        🛒
                    </div>

                    <h3>
                        No active orders
                    </h3>

                    <p>
                        New student orders will appear here.
                    </p>

                </div>


            </div>


        </section>

        <!-- ORDER LOG -->

        <section class="orders-card order-log-card">

            <div class="section-header">

                <div>

                    <h2>
                        Order Log
                    </h2>

                    <p>
                        View completed orders and order history.
                    </p>

                </div>

                <!-- DATE SELECTOR -->

                <div class="date-filter">


                    <label for="logDate">
                        Date
                    </label>


                    <input
                        type="date"
                        id="logDate"
                    >

                </div>


            </div>

            <!-- ORDER LOG TABLE -->

            <div class="table-wrapper">

                <table class="orders-table log-table">

                    <thead>

                        <tr>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Total Amount
                            </th>

                            <th>
                                Completed Time
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="orderLogBody">

                    </tbody>

                </table>


                <!-- EMPTY LOG -->

                <div
                    class="empty-orders"
                    id="emptyLog"
                >

                    <div class="empty-icon">
                        📋
                    </div>

                    <h3>
                        No completed orders
                    </h3>

                    <p>
                        Completed orders for this date will appear here.
                    </p>

                </div>


            </div>


        </section>


    </main>


</div>


<!-- ORDER DETAILS MODAL -->

<div
    class="modal-overlay"
    id="orderModal"
>

    <div class="order-modal">


        <div class="modal-header">


            <div>

                <h2>
                    Order Details
                </h2>

                <p>
                    Review the student's order.
                </p>

            </div>


            <button
                class="close-button"
                onclick="closeOrderModal()"
            >
                ×
            </button>

        </div>

        <div id="orderDetails">

        </div>

        <button
            class="modal-close-action"
            onclick="closeOrderModal()"
        >
            Close
        </button>


    </div>


</div>


<script src="../assests\css/js/orders-admin.js"></script>

</body>

</html>