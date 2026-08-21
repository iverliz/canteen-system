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
    <link href="auth/register.php" rel="stylesheet">

</head>

<body>

    <div class="login-container">


<img src="../assests/images/hotdog.png" class="food food-hotdog" alt="">
<img src="../assests/images/donut.png" class="food food-donut" alt="">
<img src="../assests/images/burger.png" class="food food-burger" alt="">
<img src="../assests/images/pizza.png" class="food food-pizza" alt="">
<img src="../assests/images/ramen.png" class="food food-ramen" alt="">
<img src="../assests/images/spagetti.png" class="food food-spagetti" alt="">
<img src="../assests/images/tacos.png" class="food food-tacos" alt="">
        <div class="login-box">


        
            <h1>LOGIN</h1>


            <form action="login.php" method="POST">

                <!-- Username -->

                <div class="input-group">

                    <label for="username">
                        Username
                    </label>

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

                    <label for="password">
                        Password
                    </label>

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
                        name="login"
                        class="login-btn"
                    >

                        <span>✓</span>

                        Log In

                    </button>


                    <a
                        href="register.php"
                        class="signup-btn"
                    >

                        <span>□</span>

                        SIGN UP

                    </a>

                </div>

            </form>


            <!-- Forgot Password -->

            <a
                href="forgot_password.php"
                class="forgot-password"
            >
                Forgot Password?
            </a>

        </div>

    </div>

</body>

</html>