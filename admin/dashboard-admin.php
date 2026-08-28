<!-- dashboard-admin.php -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Dashboard</title>

    <link
        rel="stylesheet"
        href="../assests\css/dashboard-admin.css"
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

        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>


        <!-- NAVIGATION -->

        <nav class="sidebar-menu">

            <a
                href="dashboard-admin.php"
                class="sidebar-link active"
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
                class="sidebar-link"
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



    <!--MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>
                    Dashboard
                </h1>

                <p>
                    Overview of your canteen's activity
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


        <!-- STATISTICS -->

        <section class="stats-grid">


            <!-- COMPLETED ORDERS -->

            <div class="stat-card">

                <div class="stat-icon completed-icon">
                    ✓
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Completed Orders
                    </span>

                    <h2 id="completedOrders">
                        0
                    </h2>

                </div>

            </div>


            <!-- TOTAL SALES -->

            <div class="stat-card">

                <div class="stat-icon sales-icon">
                    ₱
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Total Sales
                    </span>

                    <h2 id="totalSales">
                        ₱0.00
                    </h2>

                </div>

            </div>


            <!-- PENDING ORDERS -->

            <div class="stat-card">

                <div class="stat-icon pending-icon">
                    !
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Pending Orders
                    </span>

                    <h2 id="pendingOrders">
                        0
                    </h2>

                </div>


            </div>

        </section>


        <!-- SALES REPORT -->

        <section class="dashboard-card sales-report">

            <div class="card-header">

                <div>

                    <h2>
                        Sales Report
                    </h2>

                    <p>
                        Weekly sales performance
                    </p>

                </div>

                <div class="report-label">
                    This Week
                </div>

            </div>


            <!-- GRAPH -->

            <div class="chart-container">

                <div class="y-axis">

                    <span>
                        ₱1,000
                    </span>

                    <span>
                        ₱800
                    </span>

                    <span>
                        ₱600
                    </span>

                    <span>
                        ₱400
                    </span>

                    <span>
                        ₱200
                    </span>

                    <span>
                        ₱0
                    </span>

                </div>


                <div class="chart-area">


                    <!-- GRID -->

                    <div class="chart-grid">

                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>

                    </div>


                    <!-- BARS -->

                    <div class="bars">

                        <!-- MONDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-mon"
                                style="height: 45%;"
                            >

                                <span>
                                    ₱450
                                </span>

                            </div>

                            <small>
                                Mon
                            </small>

                        </div>


                        <!-- TUESDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-tue"
                                style="height: 65%;"
                            >

                                <span>
                                    ₱650
                                </span>

                            </div>

                            <small>
                                Tue
                            </small>

                        </div>


                        <!-- WEDNESDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-wed"
                                style="height: 55%;"
                            >

                                <span>
                                    ₱550
                                </span>

                            </div>

                            <small>
                                Wed
                            </small>

                        </div>


                        <!-- THURSDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-thu"
                                style="height: 80%;"
                            >

                                <span>
                                    ₱800
                                </span>

                            </div>

                            <small>
                                Thu
                            </small>

                        </div>


                        <!-- FRIDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-fri"
                                style="height: 95%;"
                            >

                                <span>
                                    ₱950
                                </span>

                            </div>

                            <small>
                                Fri
                            </small>

                        </div>


                        <!-- SATURDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-sat"
                                style="height: 70%;"
                            >

                                <span>
                                    ₱700
                                </span>

                            </div>

                            <small>
                                Sat
                            </small>

                        </div>


                        <!-- SUNDAY -->

                        <div class="bar-column">

                            <div
                                class="bar bar-sun"
                                style="height: 35%;"
                            >

                                <span>
                                    ₱350
                                </span>

                            </div>

                            <small>
                                Sun
                            </small>

                        </div>


                    </div>


                </div>

            </div>

        </section>


        <!-- NOTES -->

        <section class="dashboard-card notes-card">

            <div class="card-header">

                <div>

                    <h2>
                        Notes & Reminders
                    </h2>

                    <p>
                        Keep important reminders for the canteen.
                    </p>

                </div>

                <button
                    class="add-note-button"
                    onclick="openNoteModal()"
                >

                    <span>
                        +
                    </span>

                    Add Note

                </button>

            </div>


            <!-- NOTES LIST -->

            <div
                class="notes-list"
                id="notesList"
            >

                <!-- DUMMY NOTE -->

                <div class="note-item">

                    <div class="note-icon">
                        !
                    </div>

                    <div class="note-content">

                        <h3>
                            Check food stock
                        </h3>

                        <p>
                            Make sure all ingredients
                            are available before opening.
                        </p>

                    </div>

                    <button
                        class="delete-note"
                        onclick="deleteNote(this)"
                    >
                        ×
                    </button>

                </div>


                <div class="note-item">

                    <div class="note-icon">
                        !
                    </div>


                    <div class="note-content">

                        <h3>
                            Prepare tomorrow's menu
                        </h3>

                        <p>
                            Review the available food
                            items for tomorrow.
                        </p>

                    </div>


                    <button
                        class="delete-note"
                        onclick="deleteNote(this)"
                    >
                        ×
                    </button>


                </div>


            </div>


        </section>


    </main>


</div>



<!-- ADD NOTE MODAL -->

<div
    class="modal-overlay"
    id="noteModal"
>

    <div class="note-modal">

        <div class="modal-header">


            <div>

                <h2>
                    Add Note
                </h2>

                <p>
                    Create a reminder for the canteen.
                </p>

            </div>


            <button
                class="close-button"
                onclick="closeNoteModal()"
            >
                ×
            </button>

        </div>


        <form
            id="noteForm"
            onsubmit="addNote(event)"
        >

            <div class="form-group">

                <label for="noteTitle">
                    Note Title
                </label>

                <input
                    type="text"
                    id="noteTitle"
                    placeholder="Enter note title"
                    maxlength="100"
                    required
                >

            </div>


            <div class="form-group">

                <label for="noteDescription">
                    Reminder
                </label>

                <textarea
                    id="noteDescription"
                    placeholder="Enter your reminder..."
                    rows="4"
                    maxlength="300"
                    required
                ></textarea>

            </div>


            <div class="modal-buttons">


                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeNoteModal()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="save-button"
                >
                    Add Note
                </button>


            </div>


        </form>


    </div>


</div>


<script src="../assests\css/js/dashboard-admin.js"></script>

</body>

</html>