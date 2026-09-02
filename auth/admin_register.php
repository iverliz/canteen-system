<?php


// Admin registration/database logic can be added here later.
// $_POST['role'] will be either "canteen_staff" or "manager".
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Sign Up - Canteen System</title>

    <link rel="stylesheet" href="../assests/css/admin_register.css">
</head>

<body>

    <div class="admin-signup-container">

        <!-- Decorative food images -->
        <img src="../assests/css/images/burger.png" class="food food-burger-top" alt="">
        <img src="../assests/css/images/pizza.png" class="food food-pizza-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-center" alt="">
        <img src="../assests/css/images/tacos.png" class="food food-tacos-center" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-bottom" alt="">
        <img src="../assests/css/images/ramen.png" class="food food-ramen-bottom" alt="">

        <div class="admin-signup-box">

            <span class="admin-badge">ADMIN</span>

            <h1>SIGN UP</h1>

            <form action="admin_register.php" method="POST">

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
                    <label for="staff_id">I.D</label>

                    <input
                        type="text"
                        id="staff_id"
                        name="staff_id"
                        placeholder="Input ID"
                        required
                    >
                </div>

                <!-- Role -->
                <div class="input-group">
                    <label for="role">Role</label>

                    <select
                        id="role"
                        name="role"
                        required
                    >
                        <option value="" disabled selected>Select role</option>
                        <option value="canteen_staff">Canteen Staff</option>
                        <option value="manager">Manager</option>
                    </select>
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

                    <a href="admin_login.php" class="back-btn">
                        <span>△</span> Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>