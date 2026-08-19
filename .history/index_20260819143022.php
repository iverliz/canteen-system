<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Canteen System</title>
    <link rel="stylesheet" href="assests/css/style.css">
    <link href="auth/">
</head>

<body>

    <div class="container">

        <!-- LEFT SIDE -->
        <div class="left">

            <div class="logo">
                ORDERFOOD
            </div>

            <h1>
                Order Your Food.<br>
                <span>Skip the Line.</span>
            </h1>

            <p>
                A simple and convenient food ordering and pickup
                management system designed for school canteen services.
            </p>

            <div class="buttons">

                <a href="auth/login.php" class="btn btn-login">
                    Login
                </a>

                <a href="auth/register.php" class="btn btn-register">
                    Register
                </a>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="right">

            <div>

                <div class="food-icon">
                    🍔
                </div>

                <h2>
                    Fast & Easy
                </h2>

                <p>
                    Browse the menu, place your order,
                    and pick up your food when it's ready.
                </p>

            </div>

        </div>

    </div>

</body>
</html>