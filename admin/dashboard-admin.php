<?php
session_start();

require_once "../config/database.php";

/* =========================================================
   LOGIN SESSION
========================================================= */

if (
    !isset($_SESSION['admin_username']) ||
    !isset($_SESSION['admin_role'])
) {
    header("Location: ../auth/login.php");
    exit;
}

$loggedInName = $_SESSION['admin_username'];
$loggedInRole = $_SESSION['admin_role'];


/* =========================================================
   PROFILE INITIAL
========================================================= */

$profileInitial = strtoupper(
    substr(
        trim($loggedInName),
        0,
        1
    )
);


/* =========================================================
   FORMAT ROLE
========================================================= */

$role = strtolower(trim($loggedInRole));

switch ($role) {

    case 'canteen manager':
    case 'manager':
    case 'canteen_manager':

        $displayRole = 'Canteen Manager';

        break;


    case 'canteen staff':
    case 'staff':
    case 'canteen_staff':

        $displayRole = 'Canteen Staff';

        break;


    default:

        $displayRole = ucwords(
            str_replace(
                ['_', '-'],
                ' ',
                $role
            )
        );

        break;
}


/* =========================================================
   DASHBOARD AJAX ACTIONS
========================================================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action'])
) {

    header('Content-Type: application/json');

    $action = $_POST['action'];


    /* =====================================================
       ADD NOTE
    ===================================================== */

    if ($action === 'add_note') {

        $title = trim(
            $_POST['note_title'] ?? ''
        );

        $description = trim(
            $_POST['note_description'] ?? ''
        );


        if (
            $title === '' ||
            $description === ''
        ) {

            echo json_encode([
                'success' => false,
                'message' => 'Please complete all fields.'
            ]);

            exit;
        }


        $stmt = $conn->prepare("
            INSERT INTO dashboard_notes
            (
                note_title,
                note_description
            )
            VALUES (?, ?)
        ");


        $stmt->bind_param(
            "ss",
            $title,
            $description
        );


        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => 'Note added successfully.',
                'note' => [
                    'note_id' => $stmt->insert_id,
                    'note_title' => $title,
                    'note_description' => $description
                ]
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to add note.'
            ]);
        }


        $stmt->close();

        exit;
    }


    /* =====================================================
       DELETE NOTE
    ===================================================== */

    if ($action === 'delete_note') {

        $noteId = intval(
            $_POST['note_id'] ?? 0
        );


        if ($noteId <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid note.'
            ]);

            exit;
        }


        $stmt = $conn->prepare("
            DELETE FROM dashboard_notes
            WHERE note_id = ?
        ");


        $stmt->bind_param(
            "i",
            $noteId
        );


        if ($stmt->execute()) {

            echo json_encode([
                'success' => true,
                'message' => 'Note deleted successfully.'
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete note.'
            ]);
        }


        $stmt->close();

        exit;
    }
}


/* =========================================================
   GET NOTES
========================================================= */

$notes = [];

$notesResult = $conn->query("
    SELECT
        note_id,
        note_title,
        note_description,
        created_at
    FROM dashboard_notes
    ORDER BY created_at DESC
");


if ($notesResult) {

    while ($row = $notesResult->fetch_assoc()) {

        $notes[] = $row;
    }
}


/* =========================================================
   DEFAULT MONTH
========================================================= */

$currentMonth = date('Y-m');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEATS - Dashboard</title>


    <link
        rel="stylesheet"
        href="../assests/css/dashboard-admin.css"
    >


    <link
        rel="icon"
        type="image/x-icon"
        href="../assests/css/images/OrderEats_logo.png"
    >

</head>


<body>

<div class="app-container">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">

                <img
                    src="../assests/css/images/OrderEats_logo.png"
                    class="system-logo"
                >

            </div>


            <span>

                <span style="color: #F9A825;">
                    Order
                </span>EATS

            </span>

        </div>


        <nav class="sidebar-menu">


            <a
                href="dashboard-admin.php"
                class="sidebar-link active"
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
                class="sidebar-link"
            >

                <span class="menu-icon">
                    👤
                </span>

                <span>
                    User
                </span>

            </a>

        </nav>


        <!-- SIDEBAR LOGOUT -->

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



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>
                    Dashboard
                </h1>

                <p>
                    Overview of your canteen's activity
                </p>

            </div>


            <!-- ADMIN PROFILE -->

            <div class="user-profile">

                <div class="profile-icon">

                    <?= htmlspecialchars($profileInitial) ?>

                </div>


                <div class="profile-info">

                    <strong>
                        <?= htmlspecialchars($loggedInName) ?>
                    </strong>

                    <span>
                        <?= htmlspecialchars($displayRole) ?>
                    </span>

                </div>

            </div>

        </header>



        <!-- =================================================
             STATISTICS
        ================================================== -->

        <section class="stats-grid">


            <!-- COMPLETED ORDERS -->

            <div class="stat-card">

                <div class="stat-icon completed-icon">
                    ✓
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Completed Orders
                    </span>

                    <h2 id="completedOrders">
                        0
                    </h2>

                </div>

            </div>



            <!-- TOTAL SALES -->

            <div class="stat-card">

                <div class="stat-icon sales-icon">
                    ₱
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Total Sales
                    </span>

                    <h2 id="totalSales">
                        ₱0.00
                    </h2>

                </div>

            </div>



            <!-- PENDING ORDERS -->

            <div class="stat-card">

                <div class="stat-icon pending-icon">
                    !
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Pending Orders
                    </span>

                    <h2 id="pendingOrders">
                        0
                    </h2>

                </div>

            </div>

        </section>



        <!-- =================================================
             SALES REPORT
        ================================================== -->

        <section class="dashboard-card sales-report">


            <div class="card-header">

                <div>

                    <h2>
                        Sales Report
                    </h2>

                    <p>
                        Weekly sales performance
                    </p>

                </div>


                <!-- MONTH + WEEK SELECTOR -->

                <div class="report-filters">


                    <select
                        id="monthSelector"
                        class="report-select"
                    >

                        <?php

                        for ($i = 0; $i < 12; $i++) {

                            $monthValue = date(
                                'Y-m',
                                strtotime("-$i months")
                            );

                            $monthLabel = date(
                                'F Y',
                                strtotime($monthValue . '-01')
                            );

                            ?>

                            <option
                                value="<?= $monthValue ?>"
                                <?= $monthValue === $currentMonth ? 'selected' : '' ?>
                            >
                                <?= $monthLabel ?>
                            </option>

                            <?php

                        }

                        ?>

                    </select>


                    <select
                        id="weekSelector"
                        class="report-select"
                    >

                        <option value="1">
                            Week 1
                        </option>

                        <option value="2">
                            Week 2
                        </option>

                        <option value="3">
                            Week 3
                        </option>

                        <option value="4">
                            Week 4
                        </option>

                        <option value="5">
                            Week 5
                        </option>

                        <option value="6">
                            Week 6
                        </option>

                    </select>


                </div>

            </div>



            <!-- GRAPH -->

            <div class="chart-container">


                <div
                    class="y-axis"
                    id="yAxis"
                >

                    <!-- GENERATED BY JAVASCRIPT -->

                </div>


                <div class="chart-area">


                    <!-- GRID -->

                    <div
                        class="chart-grid"
                        id="chartGrid"
                    >

                        <!-- GENERATED BY JAVASCRIPT -->

                    </div>


                    <!-- BARS -->

                    <div
                        class="bars"
                        id="salesBars"
                    >

                        <!-- GENERATED BY JAVASCRIPT -->

                    </div>

                </div>

            </div>

        </section>



        <!-- =================================================
             NOTES
        ================================================== -->

        <section class="dashboard-card notes-card">


            <div class="card-header">

                <div>

                    <h2>
                        Notes & Reminders
                    </h2>

                    <p>
                        Keep important reminders for the canteen.
                    </p>

                </div>


                <button
                    type="button"
                    class="add-note-button"
                    onclick="openNoteModal()"
                >

                    <span>
                        +
                    </span>

                    Add Note

                </button>

            </div>



            <!-- NOTES LIST -->

            <div
                class="notes-list"
                id="notesList"
            >

                <?php if (empty($notes)): ?>

                    <div
                        class="empty-notes"
                        id="emptyNotes"
                    >

                        <div>
                            📝
                        </div>

                        <h3>
                            No notes yet
                        </h3>

                        <p>
                            Add a note or reminder for the canteen.
                        </p>

                    </div>

                <?php else: ?>


                    <?php foreach ($notes as $note): ?>

                        <div
                            class="note-item"
                            data-note-id="<?= (int)$note['note_id'] ?>"
                        >

                            <div class="note-icon">
                                !
                            </div>


                            <div class="note-content">

                                <h3>
                                    <?= htmlspecialchars(
                                        $note['note_title']
                                    ) ?>
                                </h3>


                                <p>
                                    <?= htmlspecialchars(
                                        $note['note_description']
                                    ) ?>
                                </p>

                            </div>


                            <button
                                type="button"
                                class="delete-note"
                                onclick="deleteNote(<?= (int)$note['note_id'] ?>)"
                                title="Delete note"
                            >
                                ×
                            </button>

                        </div>

                    <?php endforeach; ?>


                <?php endif; ?>

            </div>

        </section>


    </main>

</div>



<!-- =========================================================
     ADD NOTE MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="noteModal"
>

    <div class="note-modal">


        <div class="modal-header">

            <div>

                <h2>
                    Add Note
                </h2>

                <p>
                    Create a reminder for the canteen.
                </p>

            </div>


            <button
                type="button"
                class="close-button"
                onclick="closeNoteModal()"
            >
                ×
            </button>

        </div>


        <form
            id="noteForm"
            onsubmit="addNote(event)"
        >


            <div class="form-group">

                <label for="noteTitle">
                    Note Title
                </label>

                <input
                    type="text"
                    id="noteTitle"
                    placeholder="Enter note title"
                    maxlength="100"
                    required
                >

            </div>


            <div class="form-group">

                <label for="noteDescription">
                    Reminder
                </label>

                <textarea
                    id="noteDescription"
                    placeholder="Enter your reminder..."
                    rows="4"
                    maxlength="300"
                    required
                ></textarea>

            </div>


            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeNoteModal()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="save-button"
                >
                    Add Note
                </button>

            </div>


        </form>

    </div>

</div>



<!-- =========================================================
     DELETE NOTE CONFIRMATION MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="deleteNoteModal"
>

    <div class="delete-note-modal">


        <div class="delete-note-icon">
            🗑️
        </div>


        <h2>
            Delete Note?
        </h2>


        <p>
            Are you sure you want to delete this note?
            This action cannot be undone.
        </p>


        <div class="modal-buttons">


            <button
                type="button"
                class="cancel-button"
                onclick="closeDeleteNoteModal()"
            >
                Cancel
            </button>


            <button
                type="button"
                class="confirm-delete-button"
                id="confirmDeleteNoteButton"
            >
                Delete
            </button>


        </div>

    </div>

</div>



<!-- =========================================================
     LOGOUT CONFIRMATION MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="logoutModal"
>

    <div class="logout-modal">


        <div class="logout-icon">
            ↪
        </div>


        <h2>
            Logout?
        </h2>


        <p>
            Are you sure you want to log out of your account?
        </p>


        <div class="modal-buttons">


            <button
                type="button"
                class="cancel-button"
                onclick="closeLogoutModal()"
            >
                Cancel
            </button>


            <button
                type="button"
                class="confirm-logout-button"
                onclick="confirmLogout()"
            >
                Logout
            </button>


        </div>

    </div>

</div>



<script src="../assests/css/js/dashboard-admin.js"></script>

</body>

</html>