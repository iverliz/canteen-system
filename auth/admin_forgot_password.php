<?php
session_start();

// Add your admin password reset/database logic here later.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Forgot Password - Canteen System</title>

    <link rel="stylesheet" href="../assests/css/admin_forgot_password.css">
</head>

<body>

    <div class="admin-forgot-container">

        <!-- Decorative food images -->
        <img src="../assests/css/images/ramen.png" class="food food-ramen-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-top" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-center" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-left" alt="">
        <img src="../assests/css/images/pizza.png" class="food food-pizza-bottom" alt="">
        <img src="../assests/css/images/hotdog.png" class="food food-hotdog-bottom" alt="">
        <img src="../assests/css/images/ramen.png" class="food food-ramen-right" alt="">

        <div class="admin-forgot-box">

            <span class="admin-badge">ADMIN</span>

            <h1>Forgot<br>Password</h1>

            <form action="admin_forgot_password.php" method="POST">

                <!-- ID -->
                <div class="input-group">
                    <label for="staff_id">ID</label>

                    <input
                        type="text"
                        id="staff_id"
                        name="staff_id"
                        placeholder="Input ID"
                        required
                    >
                </div>

                <!-- New Password -->
                <div class="input-group password-group">
                    <label for="new_password">New Password</label>

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="New Password"
                        required
                    >
                </div>

                <!-- Buttons -->
                <div class="button-container">

                    <button
                        type="submit"
                        name="confirm"
                        class="confirm-btn"
                    >
                        <span>✓</span> Confirm
                    </button>

                    <a href="admin_login.php" class="back-btn">
                        <span>△</span> Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>