<?php
session_start();

// Registration/database logic can be added here later.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign Up - Canteen System</title>

    <link rel="stylesheet" href="../assests/css/register.css">
</head>

<body>

    <div class="signup-container">

        <!-- Decorative food images -->
        <img src="../assests/css/images/burger.png" class="food food-burger-top" alt="">
        <img src="../assests/css/images/pizza.png" class="food food-pizza-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-center" alt="">
        <img src="../assests/css/images/tacos.png" class="food food-center" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-bottom" alt="">
        <img src="../assests/css/images/ramen.png" class="food food-noodles" alt="">

        <div class="signup-box">

            <h1>SIGN UP</h1>

            <form action="register.php" method="POST">

                <!-- Username -->
                <div class="input-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Username"
                        required
                    >
                </div>

                <!-- ID -->
                <div class="input-group">
                    <label for="student_id">I.D</label>

                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        placeholder="Input ID"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="input-group password-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                </div>

                <!-- Buttons -->
                <div class="button-container">

                    <button
                        type="submit"
                        name="register"
                        class="create-btn"
                    >
                        <span>✓</span> Create
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