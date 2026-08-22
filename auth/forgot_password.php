<?php
session_start();

// Add your password reset/database logic here later.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password - Canteen System</title>

    <link rel="stylesheet" href="../assests/css/forgot_password.css">
</head>

<body>

    <div class="forgot-container">

        <!-- Decorative food images -->
        <img src="../assests/css/images/ramen.png" class="food food-noodles-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-top" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-center" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-left" alt="">
        <img src="../assests/css/images/pizza.png" class="food food-pizza-bottom" alt="">
        <img src="../assests/css/images/hotdog.png" class="food food-hotdog-bottom" alt="">
        <img src="../assests/css/images/ramen.png" class="food food-noodles-right" alt="">

        <div class="forgot-box">

            <h1>Forgot<br>Password</h1>

            <form action="forgot_password.php" method="POST">

                <!-- ID -->
                <div class="input-group">
                    <label for="student_id">ID</label>

                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
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

                    <a href="login.php" class="back-btn">
                        <span>△</span> Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>