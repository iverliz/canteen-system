<?php
/* categories-admin.php */

session_start();

require_once "../config/database.php";


/* CHECK ADMIN LOGIN */

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {

    header("Location: ../auth/admin_login.php");
    exit();

}


/* ADMIN INFORMATION */

$adminUsername =
    $_SESSION['admin_username'] ?? "Admin";

$adminRole =
    $_SESSION['admin_role'] ?? "Administrator";


$profileInitial =
    strtoupper(
        substr(
            trim($adminUsername),
            0,
            1
        )
    );


/* ADMIN ROLE DISPLAY */

$role = strtolower(
    trim(
        $_SESSION['admin_role'] ?? ''
    )
);


if (
    $role === 'canteen_manager' ||
    $role === 'manager' ||
    $role === 'admin' ||
    $role === 'canteen manager'
) {

    $displayRole = "Canteen Manager";

} elseif (
    $role === 'canteen_staff' ||
    $role === 'staff' ||
    $role === 'canteen staff'
) {

    $displayRole = "Canteen Staff";

} else {

    $displayRole =
        ucwords(
            str_replace(
                "_",
                " ",
                $role
            )
        );

}


/* AJAX DATABASE OPERATIONS */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $action =
        $_POST['action'] ?? '';


    /* ADD CATEGORY= */

    if ($action === 'add') {

        $title =
            trim(
                $_POST['category_title'] ?? ''
            );

        $description =
            trim(
                $_POST['category_description'] ?? ''
            );


        if ($title === '' || $description === '') {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Category title and description are required."
            ]);

            exit();

        }


        /* IMAGE */

        $imageData = null;


        if (
            isset($_FILES['category_picture']) &&
            $_FILES['category_picture']['error'] === UPLOAD_ERR_OK
        ) {

            $file =
                $_FILES['category_picture'];


            /* Maximum 2MB */

            if ($file['size'] > 2 * 1024 * 1024) {

                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Image must be smaller than 2MB."
                ]);

                exit();

            }


            /* Check actual MIME type */

            $finfo =
                finfo_open(FILEINFO_MIME_TYPE);

            $mime =
                finfo_file(
                    $finfo,
                    $file['tmp_name']
                );

            finfo_close($finfo);


            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ];


            if (!in_array($mime, $allowedTypes, true)) {

                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Only JPG, PNG, GIF, and WEBP images are allowed."
                ]);

                exit();

            }


            $imageData =
                file_get_contents(
                    $file['tmp_name']
                );

        }


        /* INSERT */

        $stmt =
            $conn->prepare(
                "INSERT INTO `food-category`
                (
                    category_picture,
                    category_title,
                    category_description
                )
                VALUES (?, ?, ?)"
            );


        if (!$stmt) {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Database error: " . $conn->error
            ]);

            exit();

        }


        $stmt->bind_param(
            "bss",
            $imageData,
            $title,
            $description
        );


        if ($imageData !== null) {

            $stmt->send_long_data(
                0,
                $imageData
            );

        }


        if ($stmt->execute()) {

            echo json_encode([
                "success" => true,
                "message" =>
                    "Category added successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Failed to save category: " .
                    $stmt->error
            ]);

        }


        $stmt->close();

        exit();

    }


    /* EDIT CATEGORY */

        if ($action === 'edit') {

            $categoryId =
                intval(
                    $_POST['category_id'] ?? 0
                );

            $title =
                trim(
                    $_POST['category_title'] ?? ''
                );

            $description =
                trim(
                    $_POST['category_description'] ?? ''
                );


            if (
                $categoryId <= 0 ||
                $title === '' ||
                $description === ''
            ) {

                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Invalid category information."
                ]);

                exit();

            }


            /*
            * CHECK IF A NEW IMAGE WAS UPLOADED
            */

            $hasNewImage =
                isset($_FILES['category_picture']) &&
                $_FILES['category_picture']['error'] === UPLOAD_ERR_OK;


            if ($hasNewImage) {

                $file =
                    $_FILES['category_picture'];


                /* MAXIMUM 2MB */

                if (
                    $file['size'] >
                    2 * 1024 * 1024
                ) {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Image must be smaller than 2MB."
                    ]);

                    exit();

                }


                /*
                * CHECK ACTUAL MIME TYPE
                */

                $finfo =
                    finfo_open(
                        FILEINFO_MIME_TYPE
                    );


                $mime =
                    finfo_file(
                        $finfo,
                        $file['tmp_name']
                    );


                finfo_close($finfo);


                $allowedTypes = [

                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp'

                ];


                if (
                    !in_array(
                        $mime,
                        $allowedTypes,
                        true
                    )
                ) {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Only JPG, PNG, GIF, and WEBP images are allowed."
                    ]);

                    exit();

                }


                /*
                * READ NEW IMAGE
                */

                $imageData =
                    file_get_contents(
                        $file['tmp_name']
                    );


                if ($imageData === false) {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Unable to read the uploaded image."
                    ]);

                    exit();

                }


                /*
                * UPDATE EVERYTHING INCLUDING IMAGE
                */

                $stmt =
                    $conn->prepare(
                        "UPDATE `food-category`
                        SET
                            category_picture = ?,
                            category_title = ?,
                            category_description = ?
                        WHERE category_id = ?"
                    );


                if (!$stmt) {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Database error: " .
                            $conn->error
                    ]);

                    exit();

                }


                /*
                * IMPORTANT:
                *
                * b = BLOB
                * s = STRING
                * s = STRING
                * i = INTEGER
                */

                $stmt->bind_param(
                    "bssi",
                    $imageData,
                    $title,
                    $description,
                    $categoryId
                );


                /*
                * Send the image as LONG DATA
                */

                $stmt->send_long_data(
                    0,
                    $imageData
                );

            } else {

                /*
                * NO NEW IMAGE
                *
                * Keep the existing image.
                */

                $stmt =
                    $conn->prepare(
                        "UPDATE `food-category`
                        SET
                            category_title = ?,
                            category_description = ?
                        WHERE category_id = ?"
                    );


                if (!$stmt) {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Database error: " .
                            $conn->error
                    ]);

                    exit();

                }


                $stmt->bind_param(
                    "ssi",
                    $title,
                    $description,
                    $categoryId
                );

            }


            /*
            * EXECUTE
            */

            if ($stmt->execute()) {

                if (
                    $stmt->affected_rows >= 0
                ) {

                    echo json_encode([
                        "success" => true,
                        "message" =>
                            "Category updated successfully."
                    ]);

                } else {

                    echo json_encode([
                        "success" => false,
                        "message" =>
                            "Category was not updated."
                    ]);

                }

            } else {

                echo json_encode([
                    "success" => false,
                    "message" =>
                        "Failed to update category: " .
                        $stmt->error
                ]);

            }


            $stmt->close();

            exit();

        }


    /* DELETE CATEGORY */

    if ($action === 'delete') {

        $categoryId =
            intval(
                $_POST['category_id'] ?? 0
            );


        if ($categoryId <= 0) {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Invalid category ID."
            ]);

            exit();

        }


        $stmt =
            $conn->prepare(
                "DELETE FROM `food-category`
                 WHERE category_id = ?"
            );


        if (!$stmt) {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Database error: " . $conn->error
            ]);

            exit();

        }


        $stmt->bind_param(
            "i",
            $categoryId
        );


        if ($stmt->execute()) {

            echo json_encode([
                "success" => true,
                "message" =>
                    "Category deleted successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" =>
                    "Failed to delete category: " .
                    $stmt->error
            ]);

        }


        $stmt->close();

        exit();

    }


    echo json_encode([
        "success" => false,
        "message" => "Invalid action."
    ]);

    exit();

}


/* GET CATEGORIES FROM DATABASE */

$categories = [];


$sql = "
    SELECT
        category_id,
        category_title,
        category_description
    FROM `food-category`
    ORDER BY category_id DESC
";


$result =
    $conn->query($sql);


if ($result) {

    while ($row = $result->fetch_assoc()) {

        $categories[] = $row;

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

    <title>OrderEATS - Categories</title>

    <link
        rel="stylesheet"
        href="../assests\css/categories-admin.css"
    >

</head>


<body>

<div class="app-container">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                🍴
            </div>

            <span>
                <span style="color: #F9A825;">
                    Order</span><span style="color: #f97316;">EATS</span>
            </span>

        </div>


        <nav class="sidebar-menu">

            <a
                href="dashboard-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">▣</span>
                <span>Dashboard</span>
            </a>


            <a
                href="menu-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">🍔</span>
                <span>Menu</span>
            </a>


            <a
                href="orders-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">🛒</span>
                <span>Orders</span>
            </a>


            <a
                href="categories-admin.php"
                class="sidebar-link active"
            >
                <span class="menu-icon">☷</span>
                <span>Categories</span>
            </a>


            <a
                href="user-admin.php"
                class="sidebar-link"
            >
                <span class="menu-icon">👤</span>
                <span>User</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a
                href="../auth/log_out_admin.php"
                class="sidebar-link"
                id="logoutButton"
            >
                <span class="menu-icon">↪</span>
                <span>Logout</span>
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>
                    Categories
                </h1>

                <p>
                    Organize your food menu into categories.
                </p>

            </div>


            <div class="user-profile">

                <div class="profile-icon">

                    <?php
                    echo htmlspecialchars(
                        $profileInitial
                    );
                    ?>

                </div>


                <div class="profile-info">

                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $adminUsername
                        );
                        ?>

                    </strong>


                    <span>

                        <?php
                        echo htmlspecialchars(
                            $displayRole
                        );
                        ?>

                    </span>

                </div>

            </div>

        </header>


        <!-- CATEGORY SECTION -->

        <section class="category-section">


            <div class="section-header">

                <div>

                    <h2>
                        Food Categories
                    </h2>

                    <p>
                        Create and manage categories for your menu.
                    </p>

                </div>


                <div class="category-count">

                    <span id="categoryCount">
                        <?php
                        echo count($categories);
                        ?>
                    </span>

                    Categories

                </div>

            </div>


            <!-- CATEGORY GRID -->

            <div
                class="category-grid"
                id="categoryGrid"
            >


                <?php foreach ($categories as $category): ?>

                    <div
                        class="category-card category-item-card"
                        data-id="<?php
                            echo (int)$category['category_id'];
                        ?>"
                    >


                        <!-- IMAGE -->

                        <div class="category-image">

                            <img
                                src="category-image.php?id=<?php
                                    echo (int)$category['category_id'];
                                ?>&v=<?php echo time(); ?>"
                                alt="<?php
                                    echo htmlspecialchars(
                                        $category['category_title']
                                    );
                                ?>"
                                onerror="this.style.display='none';"
                            >  

                        </div>


                        <!-- CONTENT -->

                        <div class="category-content">

                            <h3>

                                <?php
                                echo htmlspecialchars(
                                    $category['category_title']
                                );
                                ?>

                            </h3>


                            <p>

                                <?php
                                echo htmlspecialchars(
                                    $category['category_description']
                                );
                                ?>

                            </p>


                            <div class="category-actions">


                                <button
                                    type="button"
                                    class="edit-button"
                                    onclick="editCategory(
                                        <?php
                                        echo (int)$category['category_id'];
                                        ?>
                                    )"
                                >
                                    Edit
                                </button>


                                <button
                                    type="button"
                                    class="delete-card-button"
                                    onclick="deleteCategory(
                                        <?php
                                        echo (int)$category['category_id'];
                                        ?>,
                                        '<?php
                                        echo htmlspecialchars(
                                            $category['category_title'],
                                            ENT_QUOTES
                                        );
                                        ?>'
                                    )"
                                >
                                    Delete
                                </button>


                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


                <!-- ADD CATEGORY -->

                <div
                    class="category-card add-category-card"
                    id="addCategoryCard"
                >

                    <div class="add-icon">
                        +
                    </div>

                    <h3>
                        Add Category
                    </h3>

                    <p>
                        Create a new food category
                    </p>

                </div>


            </div>

        </section>

    </main>

</div>


<!-- ADD / EDIT MODAL -->

<div
    class="modal-overlay"
    id="categoryModal"
>

    <div class="category-modal">


        <div class="modal-header">

            <div>

                <h2 id="modalTitle">
                    Add Category
                </h2>

                <p>
                    Add a category for your food menu.
                </p>

            </div>


            <button
                type="button"
                class="close-button"
                id="closeModal"
            >
                ×
            </button>

        </div>


        <form
            id="categoryForm"
            enctype="multipart/form-data"
        >


            <!-- PICTURE -->

            <div class="form-group">

                <label>
                    Category Picture
                </label>


                <div
                    class="image-upload"
                    id="imageUpload"
                >

                    <img
                        src=""
                        id="imagePreview"
                        alt="Category Preview"
                    >


                    <div
                        class="upload-placeholder"
                        id="uploadPlaceholder"
                    >

                        <span>📷</span>

                        <strong>
                            Add Picture
                        </strong>

                        <small>
                            Click to upload
                        </small>

                    </div>


                    <input
                        type="file"
                        id="categoryImage"
                        name="category_picture"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                    >

                </div>

            </div>


            <!-- TITLE -->

            <div class="form-group">

                <label for="categoryTitle">
                    Category Title
                </label>


                <input
                    type="text"
                    id="categoryTitle"
                    name="category_title"
                    placeholder="e.g. Meals"
                    required
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label for="categoryDescription">
                    Description
                </label>


                <textarea
                    id="categoryDescription"
                    name="category_description"
                    rows="4"
                    placeholder="Describe this food category..."
                    required
                ></textarea>

            </div>


            <!-- BUTTONS -->

            <div class="form-actions">

                <button
                    type="button"
                    class="cancel-button"
                    id="cancelButton"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="save-button"
                >
                    Save Category
                </button>

            </div>

        </form>

    </div>

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
            Delete Category?
        </h2>


        <p id="deleteMessage">
            Are you sure you want to delete this category?
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

<!-- LOGOUT CONFIRMATION MODAL -->

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

        <div class="logout-actions">

            <button
                type="button"
                class="cancel-button"
                id="cancelLogout"
            >
                Cancel
            </button>

            <button
                type="button"
                class="logout-confirm-button"
                id="confirmLogout"
            >
                Yes, Logout
            </button>

        </div>

    </div>

</div>

<!-- CATEGORY NOTIFICATION -->

<div
    class="category-notification"
    id="categoryNotification"
>

    <div
        class="notification-icon"
        id="notificationIcon"
    >
        ✓
    </div>

    <div
        class="notification-message"
        id="notificationMessage"
    >
        Category added successfully.
    </div>

</div>


<script src="../assests\css/js/categories-admin.js"></script>

</body>

</html>