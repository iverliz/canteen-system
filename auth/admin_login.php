<!-- admin_login.php -->
<?php
session_start();

if (isset($_POST['login'])) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: ../admin/dashboard-admin.php");
    exit();
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

    <title>OrderEATS - Admin Login</title>

    <link
        rel="stylesheet"
        href="../assests\css/admin_login.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="login-page">


    <!-- DECORATIVE FOOD IMAGES -->

    <img
        src="../assests/css/images/donut.png"
        class="food food-donut-top"
        alt=""
    >

    <img
        src="../assests/css/images/hotdog.png"
        class="food food-hotdog-top"
        alt=""
    >

    <img
        src="../assests/css/images/burger.png"
        class="food food-burger-top"
        alt=""
    >

    <img
        src="../assests/css/images/tacos.png"
        class="food food-tacos-right"
        alt=""
    >

    <img
        src="../assests/css/images/pizza.png"
        class="food food-pizza-left"
        alt=""
    >

    <img
        src="../assests/css/images/burger.png"
        class="food food-burger-bottom"
        alt=""
    >

    <img
        src="../assests/css/images/ramen.png"
        class="food food-ramen-bottom"
        alt=""
    >



    <!-- LOGIN CARD -->

    <div class="login-card">


        <!-- LOGO -->

        <div class="logo">

            <div class="logo-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>
                <a href="../admin/index_admin.php" >
                <span style="color: #F9A825;">Order</span><span style="color: #F97316;">EATS</span>
                </a>
            </span>

        </div>



        <!-- ADMIN BADGE -->

        <div class="admin-badge">
            ADMIN LOGIN
        </div>



        <!-- TITLE -->

        <h1>
            Welcome Back
        </h1>


        <p class="subtitle">
        
            Sign in to access the OrderEATS
            canteen administration system.
        </p>

        <?php if (!empty($error)): ?>

            <div
                class="login-error"
                id="loginError"
            >
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>



        <!-- LOGIN FORM -->

        <form
            action="admin_login.php"
            method="POST"
        >


            <!-- USERNAME -->

            <div class="input-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter username"
                    required
                >

            </div>



            <!-- PASSWORD -->

            <div class="input-group password-group">

                <label for="password">
                    Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        class="show-password"
                        aria-label="Show password"
                    >
                        👁️
                    </button>

                </div>

            </div>



            <!-- BUTTONS -->

            <div class="button-container">


                <a
                    href="admin_register.php"
                    class="signup-btn"
                >
                    Create Account
                </a>


                <button
                    type="submit"
                    name="login"
                    class="login-btn"
                >
                    Log In
                </button>


            </div>


        </form>



        <!-- FORGOT PASSWORD -->

        <a
            href="admin_forgot_password.php"
            class="forgot-password"
        >
            Forgot Password?
        </a>


    </div>


</div>

<script src="../assests\css/js/admin_login.js"></script>

</body>

</html>