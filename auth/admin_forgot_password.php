<?php
session_start();

/* DATABASE CONNECTION */

$host = "localhost";
$dbname = "canteen-system";
$dbuser = "root";
$dbpass = "";

$message = "";
$message_type = "";


/* PASSWORD RESET */

if (isset($_POST['confirm'])) {

    $staff_id = trim($_POST['staff_id'] ?? "");
    $old_password = $_POST['old_password'] ?? "";
    $new_password = $_POST['new_password'] ?? "";
    $repeat_password = $_POST['repeat_password'] ?? "";


    /* BASIC VALIDATION */

    if (
        empty($staff_id) ||
        empty($old_password) ||
        empty($new_password) ||
        empty($repeat_password)
    ) {

        $message = "Please complete all fields.";
        $message_type = "error";

    } elseif ($new_password !== $repeat_password) {

        $message = "New password and repeat password do not match.";
        $message_type = "error";

    } elseif (strlen($new_password) < 6) {

        $message = "New password must be at least 6 characters.";
        $message_type = "error";

    } else {

        try {

            /* CONNECT TO DATABASE */

            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $dbuser,
                $dbpass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );


            /* FIND ACCOUNT USING STAFF ID */

            $stmt = $pdo->prepare("
                SELECT *
                FROM admin_register
                WHERE staff_id = ?
                LIMIT 1
            ");

            $stmt->execute([$staff_id]);

            $admin = $stmt->fetch();


            /* CHECK IF ACCOUNT EXISTS */

            if (!$admin) {

                $message = "Staff ID was not found.";
                $message_type = "error";

            } else {

                /* CHECK OLD PASSWORD */

                if (!password_verify($old_password, $admin['password'])) {

                    $message = "Old password is incorrect.";
                    $message_type = "error";

                } else {

                    /* HASH NEW PASSWORD */

                    $hashed_password = password_hash(
                        $new_password,
                        PASSWORD_DEFAULT
                    );


                    /* UPDATE PASSWORD */

                    $update = $pdo->prepare("
                        UPDATE admin_register
                        SET password = ?
                        WHERE staff_id = ?
                    ");

                    $update->execute([
                        $hashed_password,
                        $staff_id
                    ]);


                    $message = "Password successfully changed. You can now log in.";
                    $message_type = "success";


                    /* CLEAR FORM VALUES */

                    $_POST = [];
                }
            }

        } catch (PDOException $e) {

            $message = "Database connection error. Please check your database settings.";
            $message_type = "error";
        }
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

    <title>OrderEATS - Reset Password</title>

    <link
        rel="stylesheet"
        href="../assests/css/admin_forgot_password.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="forgot-page">


    <!-- DECORATIVE FOOD IMAGES -->

    <img
        src="../assests/css/images/ramen.png"
        class="food food-ramen-top"
        alt=""
    >

    <img
        src="../assests/css/images/donut.png"
        class="food food-donut-top"
        alt=""
    >

    <img
        src="../assests/css/images/burger.png"
        class="food food-burger-top"
        alt=""
    >

    <img
        src="../assests/css/images/donut.png"
        class="food food-donut-center"
        alt=""
    >

    <img
        src="../assests/css/images/burger.png"
        class="food food-burger-left"
        alt=""
    >

    <img
        src="../assests/css/images/pizza.png"
        class="food food-pizza-bottom"
        alt=""
    >

    <img
        src="../assests/css/images/hotdog.png"
        class="food food-hotdog-bottom"
        alt=""
    >

    <img
        src="../assests/css/images/ramen.png"
        class="food food-ramen-right"
        alt=""
    >


    <!-- FORGOT PASSWORD CARD -->

    <div class="forgot-card">


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
            ADMIN ACCOUNT
        </div>


        <!-- TITLE -->

        <h1>
            Reset Password
        </h1>


        <p class="subtitle">
            Verify your account using your Staff ID
            and old password before creating a new password.
        </p>


        <!-- MESSAGE -->

        <?php if (!empty($message)): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <form
            action="admin_forgot_password.php"
            method="POST"
            id="resetPasswordForm"
        >


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
                    value="<?php echo htmlspecialchars($_POST['staff_id'] ?? ''); ?>"
                    required
                >

            </div>


            <!-- OLD PASSWORD -->

            <div class="input-group password-group">

                <label for="old_password">
                    Old Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="old_password"
                        name="old_password"
                        placeholder="Enter old password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="old_password"
                        aria-label="Show password"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <!-- NEW PASSWORD -->

            <div class="input-group password-group">

                <label for="new_password">
                    New Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="Enter new password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="new_password"
                        aria-label="Show password"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <!-- REPEAT NEW PASSWORD -->

            <div class="input-group password-group">

                <label for="repeat_password">
                    Repeat New Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="repeat_password"
                        name="repeat_password"
                        placeholder="Repeat new password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="repeat_password"
                        aria-label="Show password"
                    >
                        👁️
                    </button>

                </div>

                <small
                    id="password-match-message"
                    class="password-match-message"
                ></small>

            </div>


            <!-- BUTTONS -->

            <div class="button-container">

                <a
                    href="admin_login.php"
                    class="back-btn"
                >
                    ← Back
                </a>


                <button
                    type="submit"
                    name="confirm"
                    class="confirm-btn"
                >
                    Confirm
                </button>

            </div>


        </form>


        <!-- LOGIN LINK -->

        <div class="login-text">

            Remember your password?

            <a href="admin_login.php">
                Login here
            </a>

        </div>


    </div>


</div>


<script src="../assests\css/js/admin_forgot_password.js"></script>

</body>

</html>