/* =========================================================
   orders-admin.js

   Features:
   - Order summary modal
   - Logout confirmation modal
   - Status updates
   - Automatic order refresh
   - Automatic order log refresh
   - Date filtering
   - Dynamic summary counts
   - New order notification
========================================================= */


/* =========================================================
   GLOBAL SETTINGS
========================================================= */

const AUTO_REFRESH_INTERVAL = 5000;

/*
 * Notification stays visible for 4 seconds.
 */
const NEW_ORDER_NOTIFICATION_DURATION = 4000;

let isUpdatingStatus = false;
let refreshTimer = null;

/*
 * Stores the IDs of orders already seen.
 *
 * This prevents the notification from appearing
 * every time the page refreshes.
 */
let knownOrderIds = new Set();

let firstRefreshCompleted = false;


/* =========================================================
   DOCUMENT READY
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    initializeModal();

    initializeDateFilter();

    initializeLogoutModal();

    initializeOrderRows();

    initializeStatusSelects();

    initializeNewOrderNotification();

    /*
     * Start automatic refreshing.
     */
    startAutoRefresh();

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
             * Do not open the order modal when
             * the status dropdown is clicked.
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
   INITIALIZE ORDER MODAL
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


    if (closeButton) {

        closeButton.addEventListener(
            "click",
            closeOrderModal
        );

    }


    if (closeAction) {

        closeAction.addEventListener(
            "click",
            closeOrderModal
        );

    }


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
        return;
    }


    const studentId =
        row.dataset.studentId || "N/A";


    const status =
        row.dataset.status || "N/A";


    const total =
        parseFloat(
            row.dataset.total || "0"
        );


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


    const statusText =
        formatStatus(status);


    const statusClass =
        getModalStatusClass(status);


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
                        item.food_name ||
                        "Unknown Item"
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


    details.innerHTML = `

        <div class="detail-row">

            <span>
                Student ID
            </span>

            <strong>
                ${escapeHTML(studentId)}
            </strong>

        </div>


        <div class="detail-items">

            <div class="detail-items-title">
                Order Items
            </div>

            ${itemsHTML}

        </div>


        <div class="detail-total">

            <span>
                Overall Total
            </span>

            <strong>
                ₱${total.toFixed(2)}
            </strong>

        </div>


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


    modal.classList.add("show");

    document.body.style.overflow = "hidden";

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

    restoreBodyScroll();

}


/* =========================================================
   RESTORE BODY SCROLL
========================================================= */

function restoreBodyScroll() {

    const orderModal =
        document.getElementById("orderModal");


    const logoutModal =
        document.getElementById("logoutModal");


    const orderOpen =
        orderModal &&
        orderModal.classList.contains("show");


    const logoutOpen =
        logoutModal &&
        logoutModal.classList.contains("show");


    if (!orderOpen && !logoutOpen) {

        document.body.style.overflow = "";

    }

}


/* =========================================================
   ESCAPE KEY
========================================================= */

document.addEventListener(
    "keydown",
    function (event) {

        if (event.key !== "Escape") {
            return;
        }


        closeOrderModal();

        closeLogoutModal();

        hideNewOrderNotification();

    }
);


/* =========================================================
   FORMAT STATUS
========================================================= */

function formatStatus(status) {

    switch (String(status).toLowerCase()) {

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

    switch (String(status).toLowerCase()) {

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
   INITIALIZE STATUS SELECTS
========================================================= */

function initializeStatusSelects() {

    const statusSelects =
        document.querySelectorAll(
            ".status-select"
        );


    statusSelects.forEach(function (select) {

        select.dataset.previousValue =
            select.value;


        select.addEventListener(
            "change",
            function () {

                const orderId =
                    this.dataset.orderId;


                const newStatus =
                    this.value;


                const previousStatus =
                    this.dataset.previousValue;


                updateOrderStatus(
                    orderId,
                    newStatus,
                    previousStatus,
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
    previousStatus,
    selectElement
) {

    isUpdatingStatus = true;


    if (selectElement) {

        selectElement.disabled = true;

    }


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

            if (selectElement) {

                selectElement.dataset.previousValue =
                    newStatus;

            }


            /*
             * Refresh immediately.
             *
             * If the order becomes completed or
             * cancelled, it will move to the log.
             */

            refreshOrders(true);

        } else {

            alert(
                data.message ||
                "Failed to update order status."
            );


            if (selectElement) {

                selectElement.value =
                    previousStatus ||
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


        if (selectElement) {

            selectElement.value =
                previousStatus ||
                "pending";

        }

    })

    .finally(function () {

        isUpdatingStatus = false;


        if (selectElement) {

            selectElement.disabled = false;

        }

    });

}


/* =========================================================
   DATE FILTER
========================================================= */

function initializeDateFilter() {

    const logDate =
        document.getElementById("logDate");


    if (!logDate) {
        return;
    }


    logDate.addEventListener(
        "change",
        function () {

            if (!this.value) {
                return;
            }


            /*
             * Only the Order Log changes.
             *
             * The Completed summary card
             * continues to show TODAY's
             * completed orders.
             */

            refreshOrders(true);

        }
    );

}


/* =========================================================
   AUTO REFRESH
========================================================= */

function startAutoRefresh() {

    /*
     * First refresh.
     */

    refreshOrders(false);


    /*
     * Refresh every 5 seconds.
     */

    refreshTimer =
        setInterval(
            function () {

                if (isUpdatingStatus) {
                    return;
                }


                /*
                 * Do not refresh while an order
                 * detail modal is open.
                 */

                const orderModal =
                    document.getElementById(
                        "orderModal"
                    );


                const logoutModal =
                    document.getElementById(
                        "logoutModal"
                    );


                if (
                    orderModal &&
                    orderModal.classList.contains("show")
                ) {

                    return;

                }


                if (
                    logoutModal &&
                    logoutModal.classList.contains("show")
                ) {

                    return;

                }


                refreshOrders(false);

            },
            AUTO_REFRESH_INTERVAL
        );

}


/* =========================================================
   REFRESH ORDERS
========================================================= */

function refreshOrders(showError) {

    const logDate =
        document.getElementById("logDate");


    const selectedDate =
        logDate
            ? logDate.value
            : "";


    let url =
        "orders-refresh.php";


    if (selectedDate) {

        url +=
            "?log_date=" +
            encodeURIComponent(
                selectedDate
            );

    }


    fetch(
        url,
        {
            method: "GET",
            cache: "no-store"
        }
    )

    .then(function (response) {

        if (!response.ok) {

            throw new Error(
                "Server returned " +
                response.status
            );

        }


        return response.json();

    })

    .then(function (data) {

        if (!data.success) {

            throw new Error(
                data.message ||
                "Unable to refresh orders."
            );

        }


        /*
         * Detect new orders BEFORE replacing
         * the table.
         */

        detectNewOrders(
            data.activeOrders || []
        );


        /*
         * Update summary cards.
         *
         * Completed is now ALWAYS today's
         * completed count.
         */

        updateSummaryCounts(
            data.counts
        );


        /*
         * Update current orders.
         */

        renderActiveOrders(
            data.activeOrders
        );


        /*
         * Update order log.
         */

        renderOrderLog(
            data.logOrders
        );


        /*
         * Mark this refresh as completed.
         */

        firstRefreshCompleted = true;

    })

    .catch(function (error) {

        console.error(
            "Automatic order refresh failed:",
            error
        );


        if (showError) {

            alert(
                "Unable to refresh orders. Please try again."
            );

        }

    });

}


/* =========================================================
   DETECT NEW ORDERS
========================================================= */

function detectNewOrders(orders) {

    if (!orders) {
        return;
    }


    const currentOrderIds =
        new Set(
            orders.map(function (order) {

                return String(order.id);

            })
        );


    /*
     * IMPORTANT:
     *
     * The first refresh should NOT trigger
     * the notification for orders that already
     * existed before the page was opened.
     */

    if (!firstRefreshCompleted) {

        knownOrderIds =
            currentOrderIds;

        return;

    }


    let newOrderFound = false;


    currentOrderIds.forEach(function (id) {

        if (!knownOrderIds.has(id)) {

            newOrderFound = true;

        }

    });


    /*
     * Update known orders.
     */

    knownOrderIds =
        currentOrderIds;


    /*
     * Show notification only if a new
     * order was detected.
     */

    if (newOrderFound) {

        showNewOrderNotification();

    }

}


/* =========================================================
   NEW ORDER NOTIFICATION
========================================================= */

let notificationTimer = null;


function initializeNewOrderNotification() {

    const closeButton =
        document.getElementById(
            "closeNewOrderNotification"
        );


    if (closeButton) {

        closeButton.addEventListener(
            "click",
            hideNewOrderNotification
        );

    }

}


/* =========================================================
   SHOW NEW ORDER NOTIFICATION
========================================================= */

function showNewOrderNotification() {

    const notification =
        document.getElementById(
            "newOrderNotification"
        );


    if (!notification) {
        return;
    }


    /*
     * Clear previous timer.
     */

    if (notificationTimer) {

        clearTimeout(
            notificationTimer
        );

    }


    /*
     * Show notification.
     */

    notification.classList.add("show");


    /*
     * Automatically hide after
     * 4 seconds.
     */

    notificationTimer =
        setTimeout(
            function () {

                hideNewOrderNotification();

            },
            NEW_ORDER_NOTIFICATION_DURATION
        );

}


/* =========================================================
   HIDE NEW ORDER NOTIFICATION
========================================================= */

function hideNewOrderNotification() {

    const notification =
        document.getElementById(
            "newOrderNotification"
        );


    if (!notification) {
        return;
    }


    notification.classList.remove("show");


    if (notificationTimer) {

        clearTimeout(
            notificationTimer
        );

        notificationTimer = null;

    }

}


/* =========================================================
   UPDATE SUMMARY COUNTS
========================================================= */

function updateSummaryCounts(counts) {

    if (!counts) {
        return;
    }


    const pending =
        document.getElementById(
            "pendingCount"
        );


    const preparing =
        document.getElementById(
            "preparingCount"
        );


    const ready =
        document.getElementById(
            "readyCount"
        );


    const completed =
        document.getElementById(
            "completedCount"
        );


    const cancelled =
        document.getElementById(
            "cancelledCount"
        );


    if (pending) {

        pending.textContent =
            counts.pending ?? 0;

    }


    if (preparing) {

        preparing.textContent =
            counts.preparing ?? 0;

    }


    if (ready) {

        ready.textContent =
            counts.ready ?? 0;

    }


    /*
     * IMPORTANT:
     *
     * This value comes from orders-refresh.php
     * and is calculated using TODAY,
     * not the selected Order Log date.
     */

    if (completed) {

        completed.textContent =
            counts.completed ?? 0;

    }


    if (cancelled) {

        cancelled.textContent =
            counts.cancelled ?? 0;

    }

}


/* =========================================================
   RENDER ACTIVE ORDERS
========================================================= */

function renderActiveOrders(orders) {

    const tbody =
        document.getElementById(
            "ordersTableBody"
        );


    const empty =
        document.getElementById(
            "emptyOrders"
        );


    if (!tbody) {
        return;
    }


    tbody.innerHTML = "";


    if (
        !orders ||
        orders.length === 0
    ) {

        if (empty) {

            empty.style.display =
                "block";

        }

        return;

    }


    if (empty) {

        empty.style.display =
            "none";

    }


    orders.forEach(function (order) {

        const row =
            document.createElement("tr");


        row.className =
            "order-row";


        row.dataset.orderId =
            order.id;


        row.dataset.studentId =
            order.student_id;


        row.dataset.status =
            order.status;


        row.dataset.total =
            order.total;


        row.dataset.items =
            JSON.stringify(
                order.items || []
            );


        /*
         * Order names.
         */

        let orderText = "";


        if (
            order.items &&
            order.items.length > 0
        ) {

            orderText =
                order.items
                    .map(function (item) {

                        return (
                            escapeHTML(
                                item.food_name
                            )
                            +
                            " ×"
                            +
                            item.quantity
                        );

                    })
                    .join(", ");

        } else {

            orderText =
                "No items";

        }


        const studentCell =
            document.createElement("td");


        studentCell.textContent =
            order.student_id || "N/A";


        const orderCell =
            document.createElement("td");


        orderCell.innerHTML =
            orderText;


        const totalCell =
            document.createElement("td");


        const total =
            parseFloat(
                order.total || 0
            );


        totalCell.textContent =
            "₱" +
            total.toFixed(2);


        const statusCell =
            document.createElement("td");


        const select =
            document.createElement("select");


        select.className =
            "status-select status-" +
            order.status;


        select.dataset.orderId =
            order.id;


        select.dataset.previousValue =
            order.status;


        const statuses = [
            ["pending", "Pending"],
            ["preparing", "Preparing"],
            ["ready", "Ready"],
            ["completed", "Completed"],
            ["cancelled", "Cancelled"]
        ];


        statuses.forEach(function (status) {

            const option =
                document.createElement("option");


            option.value =
                status[0];


            option.textContent =
                status[1];


            if (
                status[0] ===
                order.status
            ) {

                option.selected =
                    true;

            }


            select.appendChild(
                option
            );

        });


        statusCell.appendChild(
            select
        );


        row.appendChild(
            studentCell
        );


        row.appendChild(
            orderCell
        );


        row.appendChild(
            totalCell
        );


        row.appendChild(
            statusCell
        );


        tbody.appendChild(
            row
        );


        /*
         * Row click.
         */

        row.addEventListener(
            "click",
            function (event) {

                if (
                    event.target.closest(
                        ".status-select"
                    )
                ) {

                    return;

                }


                openOrderModal(row);

            }
        );


        /*
         * Status change.
         */

        select.addEventListener(
            "change",
            function () {

                updateOrderStatus(
                    order.id,
                    this.value,
                    this.dataset.previousValue,
                    this
                );

            }
        );

    });

}


/* =========================================================
   RENDER ORDER LOG
========================================================= */

function renderOrderLog(logOrders) {

    const tbody =
        document.getElementById(
            "orderLogBody"
        );


    const empty =
        document.getElementById(
            "emptyLog"
        );


    if (!tbody) {
        return;
    }


    tbody.innerHTML = "";


    if (
        !logOrders ||
        logOrders.length === 0
    ) {

        if (empty) {

            empty.style.display =
                "block";

        }

        return;

    }


    if (empty) {

        empty.style.display =
            "none";

    }


    logOrders.forEach(function (order) {

        const row =
            document.createElement("tr");


        row.className =
            "order-row";


        row.dataset.orderId =
            order.id;


        row.dataset.studentId =
            order.student_id;


        row.dataset.status =
            order.status;


        row.dataset.total =
            order.total;


        row.dataset.items =
            JSON.stringify(
                order.items || []
            );


        const studentCell =
            document.createElement("td");


        studentCell.textContent =
            order.student_id || "N/A";


        const orderCell =
            document.createElement("td");


        orderCell.textContent =
            "Order #" +
            order.id;


        const totalCell =
            document.createElement("td");


        totalCell.textContent =
            "₱" +
            parseFloat(
                order.total || 0
            ).toFixed(2);


        const timeCell =
            document.createElement("td");


        timeCell.textContent =
            formatTime(
                order.updated_at
            );


        const statusCell =
            document.createElement("td");


        const badge =
            document.createElement("span");


        badge.className =
            "status-badge status-" +
            order.status;


        badge.textContent =
            formatStatus(
                order.status
            );


        statusCell.appendChild(
            badge
        );


        row.appendChild(
            studentCell
        );


        row.appendChild(
            orderCell
        );


        row.appendChild(
            totalCell
        );


        row.appendChild(
            timeCell
        );


        row.appendChild(
            statusCell
        );


        tbody.appendChild(
            row
        );


        row.addEventListener(
            "click",
            function () {

                openOrderModal(row);

            }
        );

    });

}


/* =========================================================
   FORMAT TIME
========================================================= */

function formatTime(dateTime) {

    if (!dateTime) {
        return "N/A";
    }


    const date =
        new Date(
            dateTime.replace(
                " ",
                "T"
            )
        );


    if (isNaN(date.getTime())) {
        return "N/A";
    }


    return date.toLocaleTimeString(
        "en-US",
        {
            hour: "numeric",
            minute: "2-digit",
            hour12: true
        }
    );

}


/* =========================================================
   LOGOUT MODAL
========================================================= */

function initializeLogoutModal() {

    const logoutButton =
        document.getElementById(
            "logoutButton"
        );


    const logoutModal =
        document.getElementById(
            "logoutModal"
        );


    const cancelLogout =
        document.getElementById(
            "cancelLogout"
        );


    const confirmLogout =
        document.getElementById(
            "confirmLogout"
        );


    if (!logoutButton || !logoutModal) {
        return;
    }


    logoutButton.addEventListener(
        "click",
        function (event) {

            event.preventDefault();

            openLogoutModal();

        }
    );


    if (cancelLogout) {

        cancelLogout.addEventListener(
            "click",
            closeLogoutModal
        );

    }


    if (confirmLogout) {

        confirmLogout.addEventListener(
            "click",
            function () {

                window.location.href =
                    "../auth/log_out_admin.php";

            }
        );

    }


    logoutModal.addEventListener(
        "click",
        function (event) {

            if (
                event.target ===
                logoutModal
            ) {

                closeLogoutModal();

            }

        }
    );

}


/* =========================================================
   OPEN LOGOUT MODAL
========================================================= */

function openLogoutModal() {

    const modal =
        document.getElementById(
            "logoutModal"
        );


    if (!modal) {
        return;
    }


    modal.classList.add("show");


    document.body.style.overflow =
        "hidden";

}


/* =========================================================
   CLOSE LOGOUT MODAL
========================================================= */

function closeLogoutModal() {

    const modal =
        document.getElementById(
            "logoutModal"
        );


    if (!modal) {
        return;
    }


    modal.classList.remove("show");


    restoreBodyScroll();

}