<!-- menu-admin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEATS - Menu</title>

    <link rel="stylesheet" href="../assests\css/menu-admin.css">
</head>

<body>

<div class="app-container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                🍴
            </div>

            <span><span style="color: #F9A825;">Order</span>EATS</span>

        </div>

        <nav class="sidebar-menu">

            <a href="dashboard-admin.php" class="sidebar-link">
                <span class="menu-icon">▣</span>
                <span>Dashboard</span>
            </a>

            <a href="#" class="sidebar-link active">
                <span class="menu-icon">🍔</span>
                <span>Menu</span>
            </a>

            <a href="orders-admin.php" class="sidebar-link">
                <span class="menu-icon">🛒</span>
                <span>Orders</span>
            </a>

            <a href="categories-admin.php" class="sidebar-link">
                <span class="menu-icon">☷</span>
                <span>Categories</span>
            </a>

            <a href="user-admin.php" class="sidebar-link">
                <span class="menu-icon">👤</span>
                <span>User</span>
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="#" class="sidebar-link">
                <span class="menu-icon">↪</span>
                <span>Logout</span>
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <!-- TOP HEADER -->

        <header class="top-header">

            <div class="page-heading">

                <h1>Menu</h1>

                <p>
                    Manage your canteen food menu
                </p>

            </div>

            <div class="user-profile">

                <div class="profile-icon">
                    A
                </div>

                <div class="profile-info">

                    <strong>Admin</strong>

                    <span>Administrator</span>

                </div>

            </div>

        </header>



        <!--  MENU SECTION -->

        <section class="menu-section">

            <!-- SECTION HEADER -->

            <div class="section-header">

                <div>

                    <h2>Food Menu</h2>

                    <p>
                        Add, edit, or remove food items from your canteen menu.
                    </p>

                </div>


            </div>



            <!-- SEARCH AND FILTER -->

            <div class="menu-tools">

                <div class="search-box">

                    <span>🔍</span>

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search food..."
                        oninput="searchFood()"
                    >

                </div>


                <select
                    id="categoryFilter"
                    onchange="filterFood()"
                >

                    <option value="all">
                        All Categories
                    </option>

                    <option value="meal">
                        Meals
                    </option>

                    <option value="snack">
                        Snacks
                    </option>

                    <option value="drink">
                        Drinks
                    </option>

                    <option value="dessert">
                        Desserts
                    </option>

                </select>

            </div>


            <!-- FOOD GRID -->

            <div
                class="food-grid"
                id="foodGrid"
            >

                <!-- ADD FOOD CARD -->

                <div
                    class="add-food-card"
                    onclick="openAddModal()"
                >

                    <div class="add-food-icon">
                        +
                    </div>

                    <h3>
                        Add Food
                    </h3>

                    <p>
                        Add a new food item to your menu
                    </p>

                </div>

            </div>

            <!-- NO RESULTS -->

            <div
                class="no-results"
                id="noResults"
            >

                <div>
                    🔍
                </div>

                <h3>No food items found</h3>

                <p>
                    Try another search or category.
                </p>

            </div>


        </section>

    </main>

</div>



<!-- ADD / EDIT MODAL -->

<div
    class="modal-overlay"
    id="menuModal"
>

    <div class="modal">

        <!-- MODAL HEADER -->

        <div class="modal-header">

            <div>

                <h2 id="modalTitle">
                    Add Menu Item
                </h2>

                <p id="modalDescription">
                    Add a new food item to your menu.
                </p>

            </div>


            <button
                class="close-button"
                onclick="closeModal()"
            >
                ×
            </button>

        </div>


        <!-- FORM -->

        <form
            id="menuForm"
            onsubmit="saveFood(event)"
        >

            <!-- FOOD NAME -->

            <div class="form-group">

                <label for="foodName">
                    Food Name
                </label>

                <input
                    type="text"
                    id="foodName"
                    placeholder="Enter food name"
                    maxlength="100"
                    required
                >

            </div>


            <!-- PRICE -->

            <div class="form-group">

                <label for="foodPrice">
                    Price
                </label>

                <div class="price-input">

                    <span>₱</span>

                    <input
                        type="number"
                        id="foodPrice"
                        placeholder="0.00"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>

            </div>


            <!-- CATEGORY -->

            <div class="form-group">

                <label for="foodCategory">
                    Category
                </label>

                <select
                    id="foodCategory"
                    required
                >

                    <option value="">
                        Select category
                    </option>

                    <option value="meal">
                        Meals
                    </option>

                    <option value="snack">
                        Snacks
                    </option>

                    <option value="drink">
                        Drinks
                    </option>

                    <option value="dessert">
                        Desserts
                    </option>

                </select>

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label for="foodDescription">
                    Description
                </label>

                <textarea
                    id="foodDescription"
                    placeholder="Enter food description"
                    maxlength="200"
                    rows="3"
                ></textarea>

            </div>


            <!-- IMAGE -->

            <div class="form-group">

                <label for="foodImage">
                    Food Picture
                </label>

                <input
                    type="file"
                    id="foodImage"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    onchange="previewImage(event)"
                >

                <small>
                    JPG, PNG, WEBP, or GIF
                </small>

            </div>


            <!-- IMAGE PREVIEW -->

            <div
                class="image-preview"
                id="imagePreview"
            >

                <span>
                    Image Preview
                </span>

            </div>


            <!-- BUTTONS -->

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeModal()"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="save-button"
                    id="saveButton"
                >
                    Add Menu Item
                </button>

            </div>


        </form>

    </div>

</div>


<!-- JAVASCRIPT -->

<script src="../assests\css/js/menu-admin.js"></script>

</body>
</html> 