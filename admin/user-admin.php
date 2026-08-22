<!-- user-admin.php -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - User Management</title>

    <link
        rel="stylesheet"
        href="../assests\css/user-admin.css"
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
                class="sidebar-link active"
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
                href="#"
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
                    User Management
                </h1>

                <p>
                    Manage accounts that can access the admin page.
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


        <!-- USER SECTION -->

        <section class="user-section">

            <!-- SECTION HEADER -->

            <div class="section-header">

                <div>

                    <h2>
                        Admin Accounts
                    </h2>

                    <p>
                        View and manage users who have access to the admin system.
                    </p>

                </div>


                <div class="account-count">

                    <span id="accountCount">
                        0
                    </span>

                    Accounts

                </div>

            </div>


            <!-- USER TABLE -->

            <div class="table-container">


                <table class="user-table">


                    <thead>

                        <tr>

                            <th>
                                User
                            </th>

                            <th>
                                Position
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="userTableBody">

                        <!--
                            User accounts are
                            inserted using JavaScript.
                        -->

                    </tbody>


                </table>


            </div>


        </section>


    </main>


</div>



<!-- DELETE MODAL -->

<div
    class="modal-overlay"
    id="deleteModal"
>

    <div class="delete-modal">


        <div class="delete-icon">
            !
        </div>


        <h2>
            Delete Account?
        </h2>


        <p id="deleteMessage">
            Are you sure you want to delete this account?
        </p>


        <div class="delete-actions">

            <button
                type="button"
                class="cancel-button"
                id="cancelDelete"
            >
                Cancel
            </button>

            <button
                type="button"
                class="delete-button"
                id="confirmDelete"
            >
                Delete
            </button>

        </div>

    </div>

</div>


<script src="../assests\css/js/user-admin.js"></script>

</body>

</html>