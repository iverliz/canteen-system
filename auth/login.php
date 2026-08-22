<?php
session_start();

if (isset($_POST['login'])) {
    $_SESSION['logged_in'] = true;
    header("Location: ../users/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Canteen System</title>
    <link rel="stylesheet" href="../assests/css/login.css">
</head>

<body>

    <div class="login-container">

        <img src="../assests/css/images/donut.png" class="food food-donut" alt="donut">
<img src="../assests/css/images/hotdog.png" class="food food-hotdog" alt="hotdog">
<img src="../assests/css/images/burger.png" class="food food-burger" alt="burger">
<img src="../assests/css/images/tacos.png" class="food food-tacos" alt="tacos">
<img src="../assests/css/images/pizza.png" class="food food-pizza" alt="pizza">
<img src="../assests/css/images/pizza.png" class="food food-pizza-small" alt="pizza slice">
<img src="../assests/css/images/spagetti.png" class="food food-spagetti" alt="spagetti">
<img src="../assests/css/images/burger.png" class="food food-burger-bottom" alt="burger">
<img src="../assests/css/images/ramen.png" class="food food-ramen" alt="ramen">
<img src="../assests/css/images/donut.png" class="food food-drink" alt="donut">
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
                        <span>✓</span>
                        Log In
                    </button>

                    <a href="register.php" class="signup-btn">
                        <span>□</span>
                        SIGN UP
                    </a>
                </div>

            </form>

            <!-- Forgot Password -->
            <a href="forgot_password.php" class="forgot-password">
                Forgot Password?
            </a>

        </div>

    </div>
    

</body>

</html>