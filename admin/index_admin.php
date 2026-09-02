<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Admin Portal</title>

    <link
        rel="stylesheet"
        href="../assests\css/index_admin.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="container">


    <!-- LEFT SIDE -->

    <div class="left">


        <!-- LOGO -->

        <div class="logo">

            <div class="logo-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>  

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>



        <!-- MAIN TITLE -->

        <div class="content">


            <div class="admin-label">
                ADMIN PORTAL
            </div>


            <h1>

                Manage Your
                <br>

                <span>Canteen.</span>

            </h1>


            <p>

                A centralized administration system for managing
                school canteen menus, orders, categories, users,
                and daily operations.

            </p>


            <!-- BUTTONS -->

            <div class="buttons">


                <a
                    href="../auth/admin_login.php"
                    class="btn btn-login"
                >

                    Login

                </a>


                <a
                    href="../auth/admin_register.php"
                    class="btn btn-register"
                >

                    Register

                </a>


            </div>


        </div>



        <!-- FEATURES -->

        <div class="features">


            <div class="feature">

                <div class="feature-icon">
                    🍔
                </div>

                <div>

                    <strong>
                        Menu Management
                    </strong>

                    <span>
                        Manage food items
                    </span>

                </div>

            </div>



            <div class="feature">

                <div class="feature-icon">
                    🛒
                </div>

                <div>

                    <strong>
                        Order Management
                    </strong>

                    <span>
                        Track student orders
                    </span>

                </div>

            </div>


        </div>


    </div>



    <!-- RIGHT SIDE -->

    <div class="right">


        <div class="right-content">


            <!-- ADMIN ICON -->

            <div class="admin-icon">

                <div class="icon-circle">

                    <div class="icon">
                        <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
                    </div>

                </div>

            </div>



            <div class="right-label">
                ORDER EATS
            </div>


            <h2>

                Canteen
                <br>

                <span>Administration</span>

            </h2>


            <p>

                Manage your school canteen
                operations from one place.

            </p>



            <!-- ADMIN FEATURES -->

            <div class="admin-features">


                <div class="admin-feature">

                    <span class="check">
                        ✓
                    </span>

                    Manage Menu & Categories

                </div>


                <div class="admin-feature">

                    <span class="check">
                        ✓
                    </span>

                    Monitor Student Orders

                </div>


                <div class="admin-feature">

                    <span class="check">
                        ✓
                    </span>

                    Manage Staff Accounts

                </div>


                <div class="admin-feature">

                    <span class="check">
                        ✓
                    </span>

                    View Sales & Reports

                </div>


            </div>


        </div>


        <!-- DECORATIVE SHAPES -->

        <div class="circle circle-one"></div>

        <div class="circle circle-two"></div>

        <div class="circle circle-three"></div>


    </div>


</div>


</body>

</html>