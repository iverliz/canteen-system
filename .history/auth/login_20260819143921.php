<?php
session_start();

// Add your login/database PHP logic here later.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Canteen System</title>

    <link rel="stylesheet" href="assests/css/login.css">111111
</head>

<body>

    <div class="login-container">

        <!-- Decorative food images -->
        <img src="assets/images/hotdog.png" class="food food-hotdog" alt="">
        <img src="assets/images/donut.png" class="food food-donut" alt="">
        <img src="assets/images/burger.png" class="food food-burger" alt="">
        <img src="assets/images/pizza.png" class="food food-pizza" alt="">
        <img src="assets/images/noodles.png" class="food food-noodles" alt="">
        <img src="assets/images/cake.png" class="food food-cake" alt="">
        <img src="assets/images/drink.png" class="food food-drink" alt="">

        <div class="login-box">

            <h1>LOGIN</h1>

            <form action="login.php" method="POST">

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

                    <button type="submit" name="login" class="login-btn">
                        <span>✓</span> Log In
                    </button>

                    <a href="signup.php" class="signup-btn">
                        <span>□</span> SIGN UP
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>