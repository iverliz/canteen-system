<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db_connect.php';
require_once '../database/security_helpers.php';

$error = "";
$success = "";
$csrfToken = generate_csrf_token();

if (isset($_POST['confirm'])) {

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
    } elseif (is_ip_rate_limited()) {
        $error = "Too many attempts. Please wait a few minutes and try again.";
    } else {
        record_ip_attempt();

        $student_id  = trim($_POST['student_id']);
        $new_password = $_POST['new_password'];

        if ($student_id === '' || $new_password === '') {
            $error = "Please fill in all fields.";
        } elseif (strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE student_id = ?");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $hashedPassword, $user['id']);
                $update->execute();
                $update->close();

                $success = "Password updated successfully. You can now log in.";
            } else {
                // Same generic message either way — don't reveal whether the ID exists
                $success = "If that ID exists, the password has been updated.";
            }

            $stmt->close();
        }
    }

    $csrfToken = generate_csrf_token();
}
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

            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="success-message"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form action="forgot_password.php" method="POST" autocomplete="off">

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div class="input-group">
                    <label for="student_id">ID</label>

                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        placeholder="Input ID"
                        maxlength="50"
                        required
                    >
                </div>

                <div class="input-group password-group">
                    <label for="new_password">New Password</label>

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="New Password"
                        minlength="6"
                        required
                    >
                </div>

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