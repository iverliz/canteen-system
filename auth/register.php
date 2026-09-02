<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db_connect.php';
require_once '../database/security_helpers.php';

$error = "";
$csrfToken = generate_csrf_token();

if (isset($_POST['register'])) {

    // --- Bot check #1: honeypot field ---
    if (!empty($_POST['website'])) {
        $error = "Something went wrong. Please try again.";
    }

    // --- Bot check #2: CSRF token ---
    elseif (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Your session expired. Please try again.";
    }

    // --- Bot check #3: IP-based rate limit ---
    elseif (is_ip_rate_limited()) {
        $error = "Too many attempts. Please wait a few minutes and try again.";
    }

    else {
        record_ip_attempt();

        $username   = trim($_POST['username']);
        $student_id = trim($_POST['student_id']);
        $password   = $_POST['password'];

        if ($username === '' || $student_id === '' || $password === '') {
            $error = "Please fill in all fields.";
        } elseif (strlen($username) > 50 || strlen($student_id) > 50) {
            $error = "Username or ID is too long.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {

            // Check if student ID already exists
            $check = $conn->prepare("SELECT id FROM users WHERE student_id = ?");
            $check->bind_param("s", $student_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "That I.D is already registered.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare(
                    "INSERT INTO users (username, student_id, password, role) VALUES (?, ?, ?, 'student')"
                );
                $stmt->bind_param("sss", $username, $student_id, $hashedPassword);

                if ($stmt->execute()) {
                    session_regenerate_id(true);

                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id']   = $stmt->insert_id;
                    $_SESSION['username']  = $username;
                    $_SESSION['role']      = 'student';

                    header("Location: ../users/dashboard.php");
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }

                $stmt->close();
            }

            $check->close();
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

    <title>Sign Up - Canteen System</title>

    <link rel="stylesheet" href="../assests/css/register.css">
</head>

<body>

    <div class="signup-container">

        <img src="../assests/css/images/burger.png" class="food food-burger-top" alt="">
        <img src="../assests/css/images/pizza.png" class="food food-pizza-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-top" alt="">
        <img src="../assests/css/images/donut.png" class="food food-donut-center" alt="">
        <img src="../assests/css/images/tacos.png" class="food food-center" alt="">
        <img src="../assests/css/images/burger.png" class="food food-burger-bottom" alt="">
        <img src="../assests/css/images/ramen.png" class="food food-noodles" alt="">

        <div class="signup-box">

            <h1>SIGN UP</h1>

            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="register.php" method="POST" autocomplete="off">

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div class="hp-field">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Username"
                        maxlength="50"
                        required
                    >
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
                        minlength="6"
                        required
                    >
                </div>

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