<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db_connect.php';
require_once '../database/security_helpers.php';

$error = "";
$csrfToken = generate_csrf_token();

if (isset($_POST['login'])) {

    // --- Bot check #1: honeypot field ---
    if (!empty($_POST['website'])) {
        $error = "Incorrect I.D or password.";
    }

    // --- Bot check #2: CSRF token ---
    elseif (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
    }

    // --- Bot check #3: IP-based rate limit ---
    elseif (is_ip_rate_limited()) {
        $error = "Too many login attempts. Please wait a few minutes and try again.";
    }

    else {
        $student_id = trim($_POST['student_id']);
        $password = $_POST['password'];

        if ($student_id === '' || $password === '' || strlen($student_id) > 50) {
            $error = "Please enter a valid I.D and password.";
        } else {
            record_ip_attempt();

            $stmt = $conn->prepare(
                "SELECT id, username, password, role, failed_attempts, locked_until
                 FROM users WHERE student_id = ?"
            );
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                    $error = "This account is temporarily locked due to failed attempts. Try again later.";
                } elseif (password_verify($password, $user['password'])) {

                    $reset = $conn->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
                    $reset->bind_param("i", $user['id']);
                    $reset->execute();
                    $reset->close();

                    session_regenerate_id(true);

                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: ../users/dashboard.php");
                    exit();
                } else {
                    $attempts = $user['failed_attempts'] + 1;
                    $lockUntil = null;

                    if ($attempts >= 5) {
                        $lockUntil = date('Y-m-d H:i:s', time() + 600);
                    }

                    $update = $conn->prepare(
                        "UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?"
                    );
                    $update->bind_param("isi", $attempts, $lockUntil, $user['id']);
                    $update->execute();
                    $update->close();

                    $error = "Incorrect I.D or password.";
                }
            } else {
                $error = "Incorrect I.D or password.";
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

            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST" autocomplete="off">

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div class="hp-field">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="student_id">I.D</label>
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
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                </div>

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

            <a href="forgot_password.php" class="forgot-password">
                Forgot Password?
            </a>

        </div>

    </div>

</body>

</html>