<?php

session_start();

/* DATABASE CONNECTION */

$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "canteen-system";

$conn = new mysqli(
    $host,
    $db_user,
    $db_password,
    $db_name
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true ||
    !isset($_SESSION['admin_username']) ||
    !isset($_SESSION['admin_role'])
) {

    header("Location: ../auth/admin_login.php");
    exit();
}


/* LOGGED-IN USER */

$loggedInUsername = $_SESSION['admin_username'];
$loggedInRole = strtolower(trim($_SESSION['admin_role']));


/* POSITION DISPLAY */

function getPosition($role)
{
    $role = strtolower(trim($role));

    if ($role === "manager") {
        return "Canteen Manager";
    }

    if ($role === "canteen_staff") {
        return "Canteen Staff";
    }

    return ucfirst(
        str_replace("_", " ", $role)
    );
}


/* INITIALS */

function getInitials($name)
{
    $name = trim($name);

    if ($name === "") {
        return "?";
    }

    return strtoupper(
        substr($name, 0, 1)
    );
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    header("Content-Type: application/json");

    $action = $_POST["action"] ?? "";
    $username = trim($_POST["username"] ?? "");


    /* ONLY MANAGER CAN MANAGE ACCOUNTS */

    if ($loggedInRole !== "manager") {

        echo json_encode([
            "success" => false,
            "message" => "You do not have permission to manage user accounts."
        ]);

        exit();
    }


    /* DELETE USER */

    if ($action === "delete") {


        /* MANAGER CANNOT DELETE OWN ACCOUNT */

        if ($username === $loggedInUsername) {

            echo json_encode([
                "success" => false,
                "message" => "You cannot delete your own account."
            ]);

            exit();
        }


        /* CHECK TARGET ACCOUNT */

        $check = $conn->prepare(
            "SELECT id, username
             FROM admin_register
             WHERE username = ?
             LIMIT 1"
        );

        $check->bind_param(
            "s",
            $username
        );

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows === 0) {

            echo json_encode([
                "success" => false,
                "message" => "User account not found."
            ]);

            exit();
        }


        /* DELETE ACCOUNT */

        $delete = $conn->prepare(
            "DELETE FROM admin_register
             WHERE username = ?"
        );

        $delete->bind_param(
            "s",
            $username
        );


        if ($delete->execute()) {

            echo json_encode([
                "success" => true,
                "message" => "User account deleted successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Unable to delete the user account."
            ]);

        }

        exit();
    }


    /* TOGGLE STATUS */

    if ($action === "toggle_status") {


        /* MANAGER CANNOT CHANGE OWN STATUS */

        if ($username === $loggedInUsername) {

            echo json_encode([
                "success" => false,
                "message" => "You cannot change your own account status."
            ]);

            exit();
        }


        /* GET CURRENT STATUS */

        $check = $conn->prepare(
            "SELECT status
             FROM admin_register
             WHERE username = ?
             LIMIT 1"
        );

        $check->bind_param(
            "s",
            $username
        );

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows === 0) {

            echo json_encode([
                "success" => false,
                "message" => "User account not found."
            ]);

            exit();
        }


        $user = $result->fetch_assoc();

        $currentStatus = strtolower(
            trim($user["status"])
        );


        /* CHANGE STATUS */

        if (
            $currentStatus === "active" ||
            $currentStatus === "1"
        ) {

            $newStatus = "inactive";

        } else {

            $newStatus = "active";

        }


        $update = $conn->prepare(
            "UPDATE admin_register
             SET status = ?
             WHERE username = ?"
        );

        $update->bind_param(
            "ss",
            $newStatus,
            $username
        );


        if ($update->execute()) {

            echo json_encode([
                "success" => true,
                "message" => "Account status updated successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Unable to update account status."
            ]);

        }

        exit();
    }


    /* INVALID ACTION */

    echo json_encode([
        "success" => false,
        "message" => "Invalid action."
    ]);

    exit();
}


/* GET ALL ADMIN ACCOUNTS */

$users = [];

$query = "
    SELECT username, role, status
    FROM admin_register
    ORDER BY username ASC
";

$result = $conn->query($query);


if ($result) {

    while ($row = $result->fetch_assoc()) {

        $users[] = $row;

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

    <title>
        OrderEATS - User Management
    </title>

    <link
        rel="stylesheet"
        href="../assests/css/user-admin.css"
    >

    <link
        rel="icon" type="image/x-icon"
        href="../assests\css/images/OrderEats_logo.png"
    >

</head>


<body>


<div class="app-container">


    <!-- SIDEBAR -->

    <aside class="sidebar">


        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                <img src="../assests\css/images/OrderEats_logo.png" class="system-logo">
            </div>

            <span>

                <span style="color: #F9A825;">
                    Order</span><span style="color: #f97316;">EATS</span>

            </span>

        </div>


        <!-- NAVIGATION -->

        <nav class="sidebar-menu">


            <a
                href="dashboard-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    🟧
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="menu-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    🍔
                </span>

                <span>
                    Menu
                </span>

            </a>


            <a
                href="orders-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    🛒
                </span>

                <span>
                    Orders
                </span>

            </a>


            <a
                href="categories-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    ☷
                </span>

                <span>
                    Categories
                </span>

            </a>


            <a
                href="user-admin.php"
                class="sidebar-link active"
            >

                <span class="menu-icon">
                    👤
                </span>

                <span>
                    User
                </span>

            </a>


        </nav>


        <!-- SIDEBAR BOTTOM -->

        <div class="sidebar-bottom">

            <a
                href="#"
                class="sidebar-link"
                id="logoutButton"
            >
                <span class="menu-icon">
                    ↪
                </span>
                <span>
                    Logout
                </span>
            </a>

        </div>


    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">


            <div class="page-heading">

                <h1>
                    User Management
                </h1>

                <p>
                    Manage accounts that can access the admin page.
                </p>

            </div>


            <!-- LOGGED-IN USER PROFILE -->

            <div class="user-profile">


                <div class="profile-icon">

                    <?= htmlspecialchars(
                        getInitials($loggedInUsername)
                    ) ?>

                </div>


                <div class="profile-info">

                    <strong>

                        <?= htmlspecialchars(
                            $loggedInUsername
                        ) ?>

                    </strong>


                    <span>

                        <?= htmlspecialchars(
                            getPosition($loggedInRole)
                        ) ?>

                    </span>

                </div>


            </div>


        </header>


        <!-- USER SECTION -->

        <section class="user-section">


            <!-- SECTION HEADER -->

            <div class="section-header">


                <div>

                    <h2>
                        Admin Accounts
                    </h2>

                    <p>
                        View and manage users who have access to the admin system.
                    </p>

                </div>


                <div class="account-count">

                    <span id="accountCount">

                        <?= count($users) ?>

                    </span>

                    Accounts

                </div>


            </div>


            <!-- USER TABLE -->

            <div class="table-container">


                <table class="user-table">


                    <thead>

                        <tr>

                            <th>
                                User
                            </th>

                            <th>
                                Position
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="userTableBody">


                    <?php if (count($users) === 0): ?>


                        <tr class="empty-row">

                            <td colspan="4">

                                <div class="empty-icon">
                                    👤
                                </div>

                                <strong>
                                    No user accounts
                                </strong>

                                <span>
                                    There are currently no admin accounts.
                                </span>

                            </td>

                        </tr>


                    <?php else: ?>


                        <?php foreach ($users as $user): ?>


                            <?php

                            $username = $user["username"];

                            $role = strtolower(
                                trim($user["role"])
                            );

                            $status = strtolower(
                                trim($user["status"])
                            );


                            $isActive =
                                (
                                    $status === "active" ||
                                    $status === "1"
                                );


                            $isCurrentUser =
                                (
                                    $username ===
                                    $loggedInUsername
                                );


                            $position =
                                getPosition($role);


                            $initials =
                                getInitials($username);

                            ?>


                            <tr>


                                <!-- USER -->

                                <td>

                                    <div class="user-cell">


                                        <div class="user-avatar">

                                            <?= htmlspecialchars(
                                                $initials
                                            ) ?>

                                        </div>


                                        <span class="user-name">

                                            <?= htmlspecialchars(
                                                $username
                                            ) ?>

                                        </span>


                                    </div>

                                </td>


                                <!-- POSITION -->

                                <td>

                                    <span class="position-badge">

                                        <?= htmlspecialchars(
                                            $position
                                        ) ?>

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="status-badge
                                        <?= $isActive
                                            ? 'status-active'
                                            : 'status-inactive'
                                        ?>"
                                    >

                                        <?= $isActive
                                            ? "Active"
                                            : "Inactive"
                                        ?>

                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <div class="action-buttons">


                                    <?php if ($loggedInRole === "manager"): ?>


                                        <?php if (!$isCurrentUser): ?>


                                            <button
                                                type="button"
                                                class="status-toggle
                                                <?= $isActive
                                                    ? 'deactivate-button'
                                                    : 'activate-button'
                                                ?>"
                                                onclick="toggleUserStatus(
                                                    '<?= htmlspecialchars(
                                                        $username,
                                                        ENT_QUOTES
                                                    ) ?>'
                                                )"
                                            >

                                                <?= $isActive
                                                    ? "⛔"
                                                    : "🟢"
                                                ?>

                                            </button>


                                            <button
                                                type="button"
                                                class="delete-user-button"
                                                onclick="deleteUser(
                                                    '<?= htmlspecialchars(
                                                        $username,
                                                        ENT_QUOTES
                                                    ) ?>'
                                                )"
                                            >

                                                ❌

                                            </button>


                                        <?php else: ?>


                                            <span class="current-user-label">
                                                Your Account
                                            </span>


                                        <?php endif; ?>


                                    <?php else: ?>


                                        <span class="no-action-label">
                                            No Permission
                                        </span>


                                    <?php endif; ?>


                                    </div>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </section>


    </main>


</div>


<!-- DELETE MODAL -->

<div
    class="modal-overlay"
    id="deleteModal"
>

    <div class="delete-modal">


        <div class="delete-icon">
            !
        </div>


        <h2>
            Delete Account?
        </h2>


        <p id="deleteMessage">
            Are you sure you want to delete this account?
        </p>


        <div class="delete-actions">


            <button
                type="button"
                class="cancel-button"
                id="cancelDelete"
            >
                Cancel
            </button>


            <button
                type="button"
                class="delete-button"
                id="confirmDelete"
            >
                Delete
            </button>


        </div>


    </div>

</div>

<!-- LOGOUT MODAL -->
<div
    class="modal-overlay"
    id="logoutModal"
>
    <div class="logout-modal">

        <div class="logout-icon">
            ↪
        </div>

        <h2>
            Log Out?
        </h2>

        <p>
            Are you sure you want to log out of your account?
        </p>

        <div class="logout-actions">

            <button
                type="button"
                class="logout-cancel-button"
                id="cancelLogout"
            >
                Cancel
            </button>

            <button
                type="button"
                class="logout-confirm-button"
                id="confirmLogout"
            >
                Log Out
            </button>

        </div>

    </div>
</div>  


<script src="../assests\css/js/user-admin.js"></script>

</body>

</html>