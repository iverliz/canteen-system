<?php
session_start();

require_once "../config/database.php";

$message = "";
$message_type = "";

$username = "";
$staff_id = "";
$role = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username'] ?? "");
    $staff_id = trim($_POST['staff_id'] ?? "");
    $role = $_POST['role'] ?? "";
    $password = $_POST['password'] ?? "";
    $repeat_password = $_POST['repeat_password'] ?? "";

    /* VALIDATE INPUT */

    if (
        empty($username) ||
        empty($staff_id) ||
        empty($role) ||
        empty($password) ||
        empty($repeat_password)
    ) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif ($password !== $repeat_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        /* CHECK IF STAFF ID ALREADY EXISTS */

        $check = $conn->prepare(
            "SELECT id FROM admin_register WHERE staff_id = ?"
        );

        $check->bind_param(
            "s",
            $staff_id
        );

        $check->execute();

        $check->store_result();


        if ($check->num_rows > 0) {

            $message = "This Staff ID is already registered.";
            $message_type = "error";

        } else {

            /* HASH PASSWORD */

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            /* INSERT ADMIN ACCOUNT */

            $stmt = $conn->prepare(
                "INSERT INTO admin_register
                (username, staff_id, role, password)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $username,
                $staff_id,
                $role,
                $hashed_password
            );


            if ($stmt->execute()) {

                $message = "Admin account created successfully!";
                $message_type = "success";

                /* Clear form values after successful registration */
                $username = "";
                $staff_id = "";
                $role = "";

            } else {

                $message = "Registration failed. Please try again.";
                $message_type = "error";

            }

            $stmt->close();
        }

        $check->close();
    }
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

    <title>OrderEATS - Admin Registration</title>

    <link
        rel="stylesheet"
        href="../assests/css/admin_register.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="register-page">


    <!-- DECORATIVE FOOD IMAGES -->

    <img
        src="../assests/css/images/burger.png"
        class="food food-burger-top"
        alt=""
    >

    <img
        src="../assests/css/images/pizza.png"
        class="food food-pizza-top"
        alt=""
    >

    <img
        src="../assests/css/images/donut.png"
        class="food food-donut-top"
        alt=""
    >

    <img
        src="../assests/css/images/donut.png"
        class="food food-donut-center"
        alt=""
    >

    <img
        src="../assests/css/images/tacos.png"
        class="food food-tacos-center"
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


    <!-- REGISTRATION CARD -->

    <div class="register-card">


        <!-- LOGO -->

        <div class="logo">

            <div class="logo-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>
                <a href="../admin/index_admin.php">
                    <span style="color: #F9A825;">Order</span><span style="color: #F97316;">EATS</span>
                </a>
            </span>

        </div>


        <!-- ADMIN BADGE -->

        <div class="admin-badge">
            ADMIN REGISTRATION
        </div>


        <!-- TITLE -->

        <h1>
            Create Account
        </h1>


        <p class="subtitle">
            Create an administrator account to manage
            the school canteen system.
        </p>


        <!-- FORM MESSAGE -->

        <?php if (!empty($message)): ?>

            <div
                class="form-message <?php echo $message_type; ?>"
                id="formMessage"
            >

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <form
            action="admin_register.php"
            method="POST"
            id="registerForm"
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
                    value="<?php echo htmlspecialchars($username); ?>"
                    required
                >

            </div>


            <!-- STAFF ID -->

            <div class="input-group">

                <label for="staff_id">
                    Staff ID
                </label>

                <input
                    type="text"
                    id="staff_id"
                    name="staff_id"
                    placeholder="Enter staff ID"
                    value="<?php echo htmlspecialchars($staff_id); ?>"
                    required
                >

            </div>


            <!-- ROLE -->

            <div class="input-group">

                <label for="role">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    required
                >

                    <option
                        value=""
                        disabled
                        <?php echo empty($role) ? "selected" : ""; ?>
                    >
                        Select role
                    </option>

                    <option
                        value="canteen_staff"
                        <?php echo $role === "canteen_staff" ? "selected" : ""; ?>
                    >
                        Canteen Staff
                    </option>

                    <option
                        value="manager"
                        <?php echo $role === "manager" ? "selected" : ""; ?>
                    >
                        Manager
                    </option>

                </select>

            </div>


            <!-- PASSWORD -->

            <div class="input-group">

                <label for="password">
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Show password"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <!-- REPEAT PASSWORD -->

            <div class="input-group repeat-password-group">

                <label for="repeat_password">
                    Repeat Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="repeat_password"
                        name="repeat_password"
                        placeholder="Repeat password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="repeatPasswordToggle"
                        aria-label="Show repeat password"
                    >
                        👁️
                    </button>

                </div>


                <!-- PASSWORD MATCH MESSAGE -->

                <div
                    class="password-match-message"
                    id="passwordMatchMessage"
                ></div>

            </div>


            <!-- BUTTONS -->

            <div class="button-container">


                <a
                    href="../admin/index_admin.php"
                    class="back-btn"
                >
                    ← Back
                </a>


                <button
                    type="submit"
                    name="register"
                    class="create-btn"
                    id="createAccountButton"
                >
                    Create Account
                </button>


            </div>


        </form>


        <!-- LOGIN LINK -->

        <div class="login-text">

            Already have an account?

            <a href="admin_login.php">
                Login here
            </a>

        </div>


    </div>


</div>


<script src="../assests\css/js/admin_register.js"></script>

</body>

</html>