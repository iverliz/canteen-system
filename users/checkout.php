<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OrderEats Receipt</title>

   <link rel="stylesheet" href="../assests/css/check_out.css">

</head>


<body>


<div class="receipt-page">


    <!-- =========================================
         RECEIPT
    ========================================== -->

    <div class="receipt">


        <!-- =====================================
             LOGO
        ====================================== -->

        <div class="receipt-header">

            <div class="logo">
                Order<span>Eats</span>
            </div>

            <p>
                SCHOOL CANTEEN
            </p>

            <h1>
                ORDER RECEIPT
            </h1>

        </div>



        <!-- =====================================
             ORDER INFORMATION
        ====================================== -->

        <div class="receipt-info">

            <div>

                <span>
                    Order #
                </span>

                <strong id="orderNumber">
                    000001
                </strong>

            </div>


            <div>

                <span>
                    Date
                </span>

                <strong id="orderDate">
                </strong>

            </div>

        </div>



        <div class="dashed-line"></div>



        <!-- =====================================
             STUDENT INFORMATION
        ====================================== -->

        <div class="student-info">

            <div>

                <span>
                    Student Name
                </span>

                <strong id="studentNameDisplay">
                    John Iverson Burgos
                </strong>

            </div>


            <div>

                <span>
                    Student ID
                </span>

                <strong id="studentIdDisplay">
                    2023-00001
                </strong>

            </div>

        </div>



        <div class="dashed-line"></div>



        <!-- =====================================
             ORDER ITEMS
        ====================================== -->

        <div class="items-header">

            <span>
                ITEM
            </span>

            <span>
                QTY
            </span>

            <span>
                PRICE
            </span>

        </div>


        <div
            id="receiptItems"
            class="receipt-items"
        >

        </div>



        <div class="dashed-line"></div>



        <!-- =====================================
             TOTAL
        ====================================== -->

        <div class="total-row">

            <span>
                TOTAL
            </span>

            <strong id="receiptTotal">
                ₱0.00
            </strong>

        </div>



        <!-- =====================================
             PICKUP INFORMATION
        ====================================== -->

        <div class="receipt-details">

            <div>

                <span>
                    Pickup Time
                </span>

                <strong>
                    12:00 PM
                </strong>

            </div>


            <div>

                <span>
                    Payment
                </span>

                <strong>
                    Cash
                </strong>

            </div>

        </div>



        <div class="dashed-line"></div>



        <!-- =====================================
             FOOTER
        ====================================== -->

        <div class="receipt-footer">

            <h3>
                THANK YOU!
            </h3>

            <p>
                Please wait for your order
                to be ready.
            </p>

            <small>
                OrderEats Canteen System
            </small>

        </div>



        <!-- =====================================
             BUTTONS
        ====================================== -->

        <div class="receipt-actions">

            <button
                type="button"
                id="placeOrderButton"
                class="place-order-button"
            >
                Place Order
            </button>


            <a
                href="menu.php"
                class="back-menu-button"
            >
                Back to Menu
            </a>

        </div>


    </div>


</div>



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =====================================
           GET ORDER
        ===================================== */

        const savedOrder =
            localStorage.getItem(
                "orderEatsOrder"
            );


        const receiptItems =
            document.getElementById(
                "receiptItems"
            );


        const receiptTotal =
            document.getElementById(
                "receiptTotal"
            );


        const placeOrderButton =
            document.getElementById(
                "placeOrderButton"
            );



        /* =====================================
           CHECK ORDER
        ===================================== */

        if (!savedOrder) {

            receiptItems.innerHTML = `

                <p class="empty-message">
                    No order found.
                </p>

            `;

            placeOrderButton.disabled = true;

            return;

        }



        /* =====================================
           CONVERT ORDER DATA
        ===================================== */

        const orders =
            JSON.parse(savedOrder);



        /* =====================================
           GENERATE ORDER NUMBER
        ===================================== */

        const orderNumber =
            Math.floor(
                100000 +
                Math.random() * 900000
            );


        document.getElementById(
            "orderNumber"
        ).textContent =
            orderNumber;



        /* =====================================
           CURRENT DATE
        ===================================== */

        const currentDate =
            new Date();


        document.getElementById(
            "orderDate"
        ).textContent =
            currentDate.toLocaleDateString(
                "en-PH",
                {
                    year: "numeric",
                    month: "short",
                    day: "numeric"
                }
            );



        /* =====================================
           DISPLAY ORDER ITEMS
        ===================================== */

        let total = 0;


        orders.forEach(
            function (item) {


                const itemTotal =
                    item.price *
                    item.quantity;


                total += itemTotal;



                const itemElement =
                    document.createElement(
                        "div"
                    );


                itemElement.className =
                    "receipt-item";


                itemElement.innerHTML = `

                    <span class="item-name">
                        ${item.name}
                    </span>

                    <span>
                        ${item.quantity}
                    </span>

                    <span>
                        ₱${itemTotal.toFixed(2)}
                    </span>

                `;


                receiptItems.appendChild(
                    itemElement
                );

            }
        );



        /* =====================================
           DISPLAY TOTAL
        ===================================== */

        receiptTotal.textContent =
            "₱" + total.toFixed(2);



        /* =====================================
           PLACE ORDER
        ===================================== */

        placeOrderButton.addEventListener(
            "click",
            function () {


                /*
                    Example student information
                    for demo purposes.
                */

                const finalOrder = {

                    orderNumber:
                        orderNumber,

                    studentName:
                        "John Iverson Burgos",

                    studentId:
                        "2023-00001",

                    pickupTime:
                        "12:00 PM",

                    payment:
                        "Cash",

                    items:
                        orders,

                    total:
                        total,

                    date:
                        currentDate.toISOString()

                };



                /* =================================
                   SAVE COMPLETED ORDER
                ================================== */

                localStorage.setItem(
                    "orderEatsCompletedOrder",
                    JSON.stringify(
                        finalOrder
                    )
                );



                /* =================================
                   CLEAR CART
                ================================== */

                localStorage.removeItem(
                    "orderEatsOrder"
                );



                /* =================================
                   SUCCESS MESSAGE
                ================================== */

                alert(
                    "Order #" +
                    orderNumber +
                    " has been placed successfully!"
                );



                /* =================================
                   RETURN TO DASHBOARD
                ================================== */

                window.location.href =
                    "dashboard.php";

            }
        );

    }

);

</script>


</body>

</html>