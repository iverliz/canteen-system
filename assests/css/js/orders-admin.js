/* =========================================================
   orders-admin.js
   Order Management + Order Summary Modal
========================================================= */


/* =========================================================
   DOCUMENT READY
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    initializeOrderRows();

    initializeModal();

    initializeStatusSelects();

    initializeDateFilter();

});


/* =========================================================
   INITIALIZE ORDER ROWS
========================================================= */

function initializeOrderRows() {

    const orderRows =
        document.querySelectorAll(".order-row");


    orderRows.forEach(function (row) {

        row.addEventListener("click", function (event) {

            /*
             * If the user clicked the status dropdown,
             * do not open the order modal.
             */

            if (
                event.target.closest(".status-select")
            ) {
                return;
            }


            openOrderModal(row);

        });

    });

}


/* =========================================================
   INITIALIZE MODAL
========================================================= */

function initializeModal() {

    const modal =
        document.getElementById("orderModal");


    const closeButton =
        document.getElementById("closeOrderModal");


    const closeAction =
        document.getElementById("modalCloseAction");


    if (!modal) {
        return;
    }


    /*
     * X button
     */

    if (closeButton) {

        closeButton.addEventListener(
            "click",
            closeOrderModal
        );

    }


    /*
     * Close button at bottom
     */

    if (closeAction) {

        closeAction.addEventListener(
            "click",
            closeOrderModal
        );

    }


    /*
     * Click outside the modal box
     */

    modal.addEventListener(
        "click",
        function (event) {

            if (event.target === modal) {

                closeOrderModal();

            }

        }
    );

}


/* =========================================================
   OPEN ORDER MODAL
========================================================= */

function openOrderModal(row) {

    const modal =
        document.getElementById("orderModal");


    const details =
        document.getElementById("orderDetails");


    if (!modal || !details || !row) {

        console.error(
            "Order modal elements or row not found."
        );

        return;

    }


    /*
     * Get order information from the
     * row's data attributes.
     */

    const studentId =
        row.dataset.studentId || "N/A";


    const status =
        row.dataset.status || "N/A";


    const total =
        parseFloat(
            row.dataset.total || "0"
        );


    /*
     * Get order items.
     */

    let items = [];


    try {

        items = JSON.parse(
            row.dataset.items || "[]"
        );

    } catch (error) {

        console.error(
            "Unable to read order items:",
            error
        );

        items = [];

    }


    /*
     * Build status display.
     */

    const statusText =
        formatStatus(status);


    const statusClass =
        getModalStatusClass(status);


    /*
     * Build item list.
     */

    let itemsHTML = "";


    if (items.length > 0) {

        itemsHTML =
            items.map(function (item) {

                const quantity =
                    parseInt(
                        item.quantity || 0
                    );


                const price =
                    parseFloat(
                        item.price || 0
                    );


                const subtotal =
                    quantity * price;


                const foodName =
                    escapeHTML(
                        item.food_name || "Unknown Item"
                    );


                return `

                    <div class="detail-item">

                        <div class="detail-item-name">

                            <strong>
                                ${quantity}×
                            </strong>

                            <span>
                                ${foodName}
                            </span>

                        </div>


                        <div class="detail-item-prices">

                            <span class="item-price">
                                ₱${price.toFixed(2)} each
                            </span>

                            <strong>
                                ₱${subtotal.toFixed(2)}
                            </strong>

                        </div>

                    </div>

                `;

            }).join("");

    } else {

        itemsHTML = `

            <div class="no-items">

                No items found for this order.

            </div>

        `;

    }


    /*
     * Build complete modal.
     */

    details.innerHTML = `

        <!-- STUDENT ID -->

        <div class="detail-row">

            <span>
                Student ID
            </span>

            <strong>
                ${escapeHTML(studentId)}
            </strong>

        </div>


        <!-- ORDER ITEMS -->

        <div class="detail-items">

            <div class="detail-items-title">

                Order Items

            </div>

            ${itemsHTML}

        </div>


        <!-- OVERALL TOTAL -->

        <div class="detail-total">

            <span>
                Overall Total
            </span>

            <strong>
                ₱${total.toFixed(2)}
            </strong>

        </div>


        <!-- STATUS -->

        <div class="detail-row modal-status-row">

            <span>
                Status
            </span>

            <strong>

                <span class="modal-status ${statusClass}">

                    ${statusText}

                </span>

            </strong>

        </div>

    `;


    /*
     * Show modal.
     */

    modal.classList.add("show");


    /*
     * Prevent scrolling behind modal.
     */

    document.body.style.overflow =
        "hidden";

}


/* =========================================================
   CLOSE ORDER MODAL
========================================================= */

function closeOrderModal() {

    const modal =
        document.getElementById("orderModal");


    if (!modal) {
        return;
    }


    modal.classList.remove("show");


    /*
     * Restore page scrolling.
     */

    document.body.style.overflow =
        "";

}


/* =========================================================
   ESCAPE KEY
========================================================= */

document.addEventListener(
    "keydown",
    function (event) {

        if (event.key === "Escape") {

            closeOrderModal();

        }

    }
);


/* =========================================================
   FORMAT STATUS
========================================================= */

function formatStatus(status) {

    switch (status.toLowerCase()) {

        case "pending":
            return "Pending";

        case "preparing":
            return "Preparing";

        case "ready":
            return "Ready";

        case "completed":
            return "Completed";

        case "cancelled":
            return "Cancelled";

        default:
            return status;

    }

}


/* =========================================================
   MODAL STATUS CLASS
========================================================= */

function getModalStatusClass(status) {

    switch (status.toLowerCase()) {

        case "pending":
            return "modal-pending";

        case "preparing":
            return "modal-preparing";

        case "ready":
            return "modal-ready";

        case "completed":
            return "modal-completed";

        case "cancelled":
            return "modal-cancelled";

        default:
            return "";

    }

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHTML(text) {

    const div =
        document.createElement("div");


    div.textContent =
        text ?? "";


    return div.innerHTML;

}


/* =========================================================
   STATUS SELECT
========================================================= */

function initializeStatusSelects() {

    const statusSelects =
        document.querySelectorAll(
            ".status-select"
        );


    statusSelects.forEach(function (select) {

        select.addEventListener(
            "change",
            function () {

                const orderId =
                    this.dataset.orderId;


                const newStatus =
                    this.value;


                updateOrderStatus(
                    orderId,
                    newStatus,
                    this
                );

            }
        );

    });

}


/* =========================================================
   UPDATE ORDER STATUS
========================================================= */

function updateOrderStatus(
    orderId,
    newStatus,
    selectElement
) {

    fetch(
        "update_order_status.php",
        {
            method: "POST",

            headers: {
                "Content-Type":
                    "application/json"
            },

            body: JSON.stringify({

                order_id:
                    orderId,

                status:
                    newStatus

            })

        }
    )

    .then(function (response) {

        return response.json();

    })

    .then(function (data) {

        if (data.success) {

            /*
             * Reload the page so:
             * - summary counts update
             * - completed orders move to log
             * - table data stays synchronized
             */

            window.location.reload();

        } else {

            alert(
                data.message ||
                "Failed to update order status."
            );


            /*
             * Restore previous selection
             * if the update failed.
             */

            if (selectElement) {

                selectElement.value =
                    selectElement.dataset.previousValue ||
                    "pending";

            }

        }

    })

    .catch(function (error) {

        console.error(
            "Status update error:",
            error
        );


        alert(
            "Something went wrong. Please try again."
        );

    });

}


/* =========================================================
   DATE FILTER
========================================================= */

function initializeDateFilter() {

    const logDate =
        document.getElementById(
            "logDate"
        );


    if (!logDate) {
        return;
    }


    logDate.addEventListener(
        "change",
        function () {

            const selectedDate =
                this.value;


            if (!selectedDate) {
                return;
            }


            window.location.href =
                "orders-admin.php?log_date=" +
                encodeURIComponent(
                    selectedDate
                );

        }
    );

}