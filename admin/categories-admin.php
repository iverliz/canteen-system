<!-- categories-admin.php -->

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


        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                🍴
            </div>

            <span>
                <span style="color: #F9A825;">Order</span>EATS
            </span>

        </div>

        <!-- NAVIGATION -->

        <nav class="sidebar-menu">

            <a
                href="dashboard-admin.php"
                class="sidebar-link"
            >

                <span class="menu-icon">
                    ▣
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
                class="sidebar-link active"
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


        <!-- SIDEBAR BOTTOM -->

        <div class="sidebar-bottom">

            <a
                href="../auth/log_out_admin.php"
                class="sidebar-link"
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
                    Categories
                </h1>

                <p>
                    Organize your food menu into categories.
                </p>

            </div>


            <!-- ADMIN PROFILE -->

            <div class="user-profile">

                <div class="profile-icon">
                    A
                </div>

                <div class="profile-info">

                    <strong>
                        Admin
                    </strong>

                    <span>
                        Administrator
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
                        0
                    </span>

                    Categories

                </div>

            </div>


            <!-- CATEGORY GRID -->

            <div
                class="category-grid"
                id="categoryGrid"
            >


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


<!-- ADD / EDIT CATEGORY MODAL -->

<div
    class="modal-overlay"
    id="categoryModal"
>

    <div class="category-modal">

        <!-- MODAL HEADER -->

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


        <!-- FORM -->

        <form id="categoryForm">


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

                        <span>
                            📷
                        </span>

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
                        accept="image/*"
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


<script src="../assests\css/js/categories-admin.js"></script>

</body>

</html>