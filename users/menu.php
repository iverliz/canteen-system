<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OrderEats Menu</title>

    <link rel="stylesheet" href="../assests/css/menu_user.css">

</head>


<body>


<div class="menu-container">


    <!-- =========================================
         LEFT SIDEBAR
    ========================================== -->

    <aside class="sidebar">

        <div class="logo">
            Order<span>Eats</span>
        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php" class="menu-item">
                Dashboard
            </a>

            <a href="menu.php" class="menu-item active">
                Menu
            </a>

        </nav>

    </aside>



    <!-- =========================================
         MAIN CONTENT
    ========================================== -->

    <main class="main-content">


        <!-- =========================================
             HEADER
        ========================================== -->

        <header class="top-header">

            <h1>MENU</h1>


            <!-- SEARCH -->

            <div class="search-box">

                <input
                    type="text"
                    placeholder="Search Food"
                    id="foodSearch"
                >

                <span class="search-icon">
                    ⌕
                </span>

            </div>



            <!-- =====================================
                 PROFILE
            ====================================== -->

            <div class="profile-container">

                <button
                    type="button"
                    class="profile-button"
                    id="profileBtn"
                >

                    <div class="profile-icon">
                        👤
                    </div>

                    <span class="profile-name"></span>

                    <span class="profile-arrow">
                        ⌄
                    </span>

                </button>


                <!-- PROFILE DROPDOWN -->

                <div
                    class="profile-dropdown"
                    id="profileDropdown"
                >

                    <div class="profile-info">

                        <div class="profile-large-icon">
                            👤
                        </div>

                        <div>

                            <strong></strong>

                            <small>
                                Student
                            </small>

                        </div>

                    </div>


                    <div class="dropdown-divider"></div>

                    <a href="../auth/logout.php" class="logout-button">
    🚪 Logout
</a>
                </div>

            </div>

        </header>



        <!-- =========================================
             CATEGORY
        ========================================== -->

        <section class="category-section">

            <h2>
                Category
            </h2>


            <div class="category-list">


                <!-- ALL -->

                <button
                    type="button"
                    class="category-button active"
                    data-category="All"
                >
                    All
                </button>


                <!-- MEALS -->

                <button
                    type="button"
                    class="category-button"
                    data-category="Meals"
                >
                    Meals
                </button>


                <!-- SNACKS -->

                <button
                    type="button"
                    class="category-button"
                    data-category="Snacks"
                >
                    Snacks
                </button>


                <!-- DRINKS -->

                <button
                    type="button"
                    class="category-button"
                    data-category="Drinks"
                >
                    Drinks
                </button>


                <!-- DESSERTS -->

                <button
                    type="button"
                    class="category-button"
                    data-category="Desserts"
                >
                    Desserts
                </button>


            </div>

        </section>



        <!-- =========================================
             FOOD MENU
        ========================================== -->

        <section class="food-section">

            <h2 class="section-title">
                Food Menu
            </h2>


            <div
                class="food-grid"
                id="foodGrid"
            >


                <!-- =================================
                     MEALS
                ================================== -->

                <div
                    class="food-card"
                    data-category="Meals"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="Hotdog"
                    >

                    <div class="food-card-content">

                        <h3>
                            Hotdog
                        </h3>

                        <p>
                            Delicious canteen hotdog
                        </p>

                        <span class="food-price">
                            ₱50.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>



                <div
                    class="food-card"
                    data-category="Meals"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="Burger"
                    >

                    <div class="food-card-content">

                        <h3>
                            Burger
                        </h3>

                        <p>
                            Classic school canteen burger
                        </p>

                        <span class="food-price">
                            ₱50.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>



                <!-- =================================
                     SNACKS
                ================================== -->

                <div
                    class="food-card"
                    data-category="Snacks"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="French Fries"
                    >

                    <div class="food-card-content">

                        <h3>
                            French Fries
                        </h3>

                        <p>
                            Crispy golden fries
                        </p>

                        <span class="food-price">
                            ₱30.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>



                <!-- =================================
                     DRINKS
                ================================== -->

                <div
                    class="food-card"
                    data-category="Drinks"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="Juice"
                    >

                    <div class="food-card-content">

                        <h3>
                            Juice
                        </h3>

                        <p>
                            Refreshing fruit juice
                        </p>

                        <span class="food-price">
                            ₱25.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>



                <!-- =================================
                     DESSERTS
                ================================== -->

                <div
                    class="food-card"
                    data-category="Desserts"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="Ice Cream"
                    >

                    <div class="food-card-content">

                        <h3>
                            Ice Cream
                        </h3>

                        <p>
                            Cold and creamy dessert
                        </p>

                        <span class="food-price">
                            ₱35.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>



                <div
                    class="food-card"
                    data-category="Desserts"
                >

                    <img
                        src="../assests/css/images/hotdog.png"
                        alt="Cake"
                    >

                    <div class="food-card-content">

                        <h3>
                            Cake
                        </h3>

                        <p>
                            Sweet chocolate cake
                        </p>

                        <span class="food-price">
                            ₱40.00
                        </span>

                        <button
                            type="button"
                            class="add-order-button"
                        >
                            Add to Order
                        </button>

                    </div>

                </div>


            </div>

        </section>


    </main>


    <aside class="right-sidebar">


        <section class="my-order-box">


            <h2>
                My Order
            </h2>


            <!-- FOOD ITEMS -->

            <div
                class="my-order-content"
                id="myOrderContent"
            >

            </div>



            <!-- TOTAL -->

            <div class="order-total">

                <span>
                    Total
                </span>

                <strong id="orderTotal">
                    ₱0.00
                </strong>

            </div>



            <!-- CHECKOUT -->

            <div class="checkout-area">

                <button
                    type="button"
                    class="checkout-button"
                    id="checkoutButton"
                >
                    Checkout
                </button>

            </div>


        </section>


    </aside>


</div>



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script>


/* =========================================
   PROFILE DROPDOWN
========================================= */

document.addEventListener("DOMContentLoaded", function () {


    const profileBtn =
        document.getElementById("profileBtn");


    const profileDropdown =
        document.getElementById("profileDropdown");


    if (profileBtn && profileDropdown) {


        profileBtn.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                profileDropdown.classList.toggle("show");

            }
        );


        profileDropdown.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

            }
        );


        document.addEventListener(
            "click",
            function () {

                profileDropdown.classList.remove("show");

            }
        );

    }



    /* =========================================
       CATEGORY FILTER
    ========================================== */

    const categoryButtons =
        document.querySelectorAll(".category-button");


    const foodCards =
        document.querySelectorAll(".food-card");


    categoryButtons.forEach(function (button) {


        button.addEventListener(
            "click",
            function () {


                /* Get selected category */

                const selectedCategory =
                    this.getAttribute("data-category");


                /* Remove active from every button */

                categoryButtons.forEach(
                    function (btn) {

                        btn.classList.remove("active");

                    }
                );


                /* Add active to clicked button */

                this.classList.add("active");


                /* Filter food */

                foodCards.forEach(
                    function (card) {


                        const foodCategory =
                            card.getAttribute("data-category");


                        /*
                            If All is selected,
                            show every food.
                        */

                        if (
                            selectedCategory === "All" ||
                            foodCategory === selectedCategory
                        ) {

                            card.style.display = "";

                        }


                        /*
                            Otherwise hide food
                            that does not belong
                            to selected category.
                        */

                        else {

                            card.style.display = "none";

                        }

                    }
                );

            }
        );

    });



    /* =========================================
       SEARCH FOOD
    ========================================== */

    const foodSearch =
        document.getElementById("foodSearch");


    if (foodSearch) {


        foodSearch.addEventListener(
            "input",
            function () {


                const searchText =
                    this.value.toLowerCase().trim();


                foodCards.forEach(
                    function (card) {


                        const foodName =
                            card
                            .querySelector("h3")
                            .textContent
                            .toLowerCase();


                        /*
                            Show matching food.
                        */

                        if (
                            foodName.includes(searchText)
                        ) {

                            card.style.display = "";

                        }


                        /*
                            Hide food that
                            doesn't match.
                        */

                        else {

                            card.style.display = "none";

                        }

                    }
                );

            }
        );

    }



    /* =========================================
       MY ORDER
    ========================================== */

    const myOrderContent =
        document.getElementById("myOrderContent");


    const orderTotal =
        document.getElementById("orderTotal");


    const checkoutButton =
        document.getElementById("checkoutButton");


    /*
        Store all ordered food here.
    */

    let orders = [];



    /* =========================================
       ADD TO ORDER
    ========================================== */

    const addOrderButtons =
        document.querySelectorAll(".add-order-button");


    addOrderButtons.forEach(function (button) {


        button.addEventListener(
            "click",
            function () {


                /*
                    Get the food card
                    containing the button.
                */

                const foodCard =
                    this.closest(".food-card");


                /*
                    Get food name.
                */

                const foodName =
                    foodCard
                    .querySelector("h3")
                    .textContent
                    .trim();


                /*
                    Get food price.
                    Example:
                    ₱50.00 → 50
                */

                const foodPriceText =
                    foodCard
                    .querySelector(".food-price")
                    .textContent
                    .trim();


                const foodPrice =
                    parseFloat(
                        foodPriceText
                        .replace("₱", "")
                        .replace(",", "")
                    );


                /*
                    Check if this food
                    is already in the order.
                */

                const existingItem =
                    orders.find(function (item) {

                        return item.name === foodName;

                    });


                /*
                    If already exists,
                    increase quantity.
                */

                if (existingItem) {

                    existingItem.quantity++;

                }


                /*
                    Otherwise create
                    a new order item.
                */

                else {

                    orders.push({

                        name: foodName,

                        price: foodPrice,

                        quantity: 1

                    });

                }


                /*
                    Refresh My Order.
                */

                updateOrder();


                /*
                    Temporary button feedback.
                */

                const originalText =
                    this.textContent;


                this.textContent =
                    "Added ✓";


                setTimeout(function () {

                    button.textContent =
                        originalText;

                }, 800);

            }
        );

    });



    /* =========================================
       UPDATE MY ORDER
    ========================================== */

    function updateOrder() {


        /*
            Clear current order display.
        */

        myOrderContent.innerHTML = "";


        let total = 0;



        /*
            If there are no orders.
        */

        if (orders.length === 0) {


            myOrderContent.innerHTML = `

                <div class="empty-order">

                    <p>Your order is empty.</p>

                </div>

            `;

        }



        /*
            Display every order item.
        */

        orders.forEach(function (item, index) {


            /*
                Calculate item total.
            */

            const itemTotal =
                item.price * item.quantity;


            /*
                Add to overall total.
            */

            total += itemTotal;



            /*
                Create order item.
            */

            const orderItem =
                document.createElement("div");


            orderItem.className =
                "order-item";



            orderItem.innerHTML = `

                <div class="order-item-info">

                    <strong>
                        ${item.name}
                    </strong>

                    <span>
                        ₱${item.price.toFixed(2)}
                    </span>

                </div>


                <div class="order-item-controls">

                    <button
                        type="button"
                        class="quantity-button decrease"
                        data-index="${index}"
                    >
                        −
                    </button>


                    <span class="quantity">
                        ${item.quantity}
                    </span>


                    <button
                        type="button"
                        class="quantity-button increase"
                        data-index="${index}"
                    >
                        +
                    </button>


                    <button
                        type="button"
                        class="remove-button"
                        data-index="${index}"
                    >
                        ×
                    </button>

                </div>


                <div class="order-item-total">

                    ₱${itemTotal.toFixed(2)}

                </div>

            `;



            myOrderContent.appendChild(orderItem);

        });



        /*
            Update total.
        */

        orderTotal.textContent =
            "₱" + total.toFixed(2);



        /* =====================================
           DECREASE QUANTITY
        ===================================== */

        const decreaseButtons =
            document.querySelectorAll(".decrease");


        decreaseButtons.forEach(function (button) {


            button.addEventListener(
                "click",
                function () {


                    const index =
                        parseInt(
                            this.dataset.index
                        );


                    /*
                        If quantity is greater
                        than 1, decrease it.
                    */

                    if (
                        orders[index].quantity > 1
                    ) {

                        orders[index].quantity--;

                    }


                    /*
                        If quantity is 1,
                        remove the item.
                    */

                    else {

                        orders.splice(index, 1);

                    }


                    updateOrder();

                }
            );

        });



        /* =====================================
           INCREASE QUANTITY
        ===================================== */

        const increaseButtons =
            document.querySelectorAll(".increase");


        increaseButtons.forEach(function (button) {


            button.addEventListener(
                "click",
                function () {


                    const index =
                        parseInt(
                            this.dataset.index
                        );


                    orders[index].quantity++;


                    updateOrder();

                }
            );

        });



        /* =====================================
           REMOVE ITEM
        ===================================== */

        const removeButtons =
            document.querySelectorAll(".remove-button");


        removeButtons.forEach(function (button) {


            button.addEventListener(
                "click",
                function () {


                    const index =
                        parseInt(
                            this.dataset.index
                        );


                    orders.splice(index, 1);


                    updateOrder();

                }
            );

        });

    }



    /* =========================================
       CHECKOUT
    ========================================= */

    /* =========================================
   CHECKOUT
========================================= */

if (checkoutButton) {

    checkoutButton.addEventListener(
        "click",
        function () {

            /*
                Don't allow empty checkout.
            */

            if (orders.length === 0) {

                alert("Your order is empty.");

                return;

            }


            /*
                Save the current order
                so checkout.php can read it.
            */

            localStorage.setItem(
                "orderEatsOrder",
                JSON.stringify(orders)
            );


            /*
                Go to receipt page.
            */

            window.location.href =
                "checkout.php";

        }
    );

}


    updateOrder();


});

</script>

</body>

</html>