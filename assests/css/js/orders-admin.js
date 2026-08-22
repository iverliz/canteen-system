/* orders-admin.js */
let activeOrders = [

    {
        id: 1,

        studentId: "2026-001",

        items: [
            {
                name: "Chicken Meal",
                quantity: 1,
                price: 85
            },
            {
                name: "Iced Tea",
                quantity: 1,
                price: 25
            }
        ],

        total: 110,

        status: "Pending"

    },


    {
        id: 2,

        studentId: "2026-014",

        items: [
            {
                name: "Burger",
                quantity: 2,
                price: 60
            }
        ],

        total: 120,

        status: "Pending"

    },


    {
        id: 3,

        studentId: "2026-027",

        items: [
            {
                name: "Spaghetti",
                quantity: 1,
                price: 70
            },
            {
                name: "Juice",
                quantity: 1,
                price: 30
            }
        ],

        total: 100,

        status: "Pending"

    }

];


/* COMPLETED ORDERS */

let completedOrders = [


    {
        id: 101,

        studentId: "2026-005",

        items: [
            {
                name: "Chicken Meal",
                quantity: 1,
                price: 85
            }
        ],

        total: 85,

        status: "Done",

        completedDate:
            getToday(),

        completedTime:
            "10:35 AM"

    }

];


/* STATUS ORDER */

const statusFlow = [

    "Pending",

    "Preparing",

    "Ready",

    "Done"

];


/* =========================
   INITIALIZE
========================= */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        /*
         * Set today's date.
         */

        document
            .getElementById("logDate")
            .value =
            getToday();


        renderOrders();

        renderOrderLog();

        updateSummary();

    }
);


/* RENDER ACTIVE ORDERS */

function renderOrders() {

    const tableBody =
        document.getElementById(
            "ordersTableBody"
        );


    const emptyState =
        document.getElementById(
            "emptyOrders"
        );


    tableBody.innerHTML = "";


    if (
        activeOrders.length === 0
    ) {

        emptyState.style.display =
            "block";

        return;

    }


    emptyState.style.display =
        "none";


    activeOrders.forEach(
        order => {

            const row =
                document.createElement(
                    "tr"
                );


            row.innerHTML = `

                <td>

                    <span class="student-id">
                        ${escapeHTML(order.studentId)}
                    </span>

                </td>


                <td>

                    <div class="order-items">

                        ${order.items
                            .map(
                                item => `
                                    <div class="order-item">

                                        <strong>
                                            ${item.quantity}×
                                        </strong>

                                        ${escapeHTML(item.name)}

                                    </div>
                                `
                            )
                            .join("")
                        }

                    </div>

                </td>


                <td>

                    <span class="order-total">

                        ₱${order.total.toFixed(2)}

                    </span>

                </td>


                <td>

                    <button
                        class="
                            status-button
                            ${getStatusClass(order.status)}
                        "
                        onclick="
                            changeOrderStatus(${order.id})
                        "
                    >

                        ${order.status}

                    </button>

                </td>

            `;


            

            row.addEventListener(
                "dblclick",
                function() {

                    openOrderModal(
                        order
                    );

                }
            );


            tableBody.appendChild(
                row
            );

        }
    );

}


/* CHANGE ORDER STATUS*/

function changeOrderStatus(
    orderId
) {

    const order =
        activeOrders.find(
            item =>
                item.id === orderId
        );


    if (!order) {
        return;
    }


    const currentIndex =
        statusFlow.indexOf(
            order.status
        );


    const nextIndex =
        currentIndex + 1;



    if (
        nextIndex >=
        statusFlow.length
    ) {

        return;

    }


    const nextStatus =
        statusFlow[nextIndex];


    order.status =
        nextStatus;



    if (
        nextStatus === "Done"
    ) {

        moveToOrderLog(
            order
        );

    }


    renderOrders();

    renderOrderLog();

    updateSummary();

}


/* MOVE TO ORDER LOG*/

function moveToOrderLog(
    order
) {

    const completedOrder = {

        ...order,

        completedDate:
            getToday(),

        completedTime:
            getCurrentTime(),

        status:
            "Done"

    };


    completedOrders.push(
        completedOrder
    );


    /* Remove from active orders. */

    activeOrders =
        activeOrders.filter(
            item =>
                item.id !== order.id
        );

}


/* UPDATE SUMMARY */

function updateSummary() {

    const pending =
        activeOrders.filter(
            order =>
                order.status ===
                "Pending"
        ).length;


    const preparing =
        activeOrders.filter(
            order =>
                order.status ===
                "Preparing"
        ).length;


    const ready =
        activeOrders.filter(
            order =>
                order.status ===
                "Ready"
        ).length;


    document.getElementById(
        "pendingCount"
    ).textContent =
        pending;


    document.getElementById(
        "preparingCount"
    ).textContent =
        preparing;


    document.getElementById(
        "readyCount"
    ).textContent =
        ready;


    document.getElementById(
        "completedCount"
    ).textContent =
        completedOrders.length;

}


/* RENDER ORDER LOG */

function renderOrderLog() {

    const tableBody =
        document.getElementById(
            "orderLogBody"
        );


    const emptyState =
        document.getElementById(
            "emptyLog"
        );


    const selectedDate =
        document.getElementById(
            "logDate"
        ).value;


    tableBody.innerHTML = "";


    const filteredOrders =
        completedOrders.filter(
            order =>
                order.completedDate ===
                selectedDate
        );


    if (
        filteredOrders.length === 0
    ) {

        emptyState.style.display =
            "block";

        return;

    }


    emptyState.style.display =
        "none";


    filteredOrders.forEach(
        order => {

            const row =
                document.createElement(
                    "tr"
                );


            row.innerHTML = `

                <td>

                    <span class="student-id">
                        ${escapeHTML(order.studentId)}
                    </span>

                </td>


                <td>

                    <div class="order-items">

                        ${order.items
                            .map(
                                item => `
                                    <div class="order-item">

                                        <strong>
                                            ${item.quantity}×
                                        </strong>

                                        ${escapeHTML(item.name)}

                                    </div>
                                `
                            )
                            .join("")
                        }

                    </div>

                </td>


                <td>

                    <span class="order-total">
                        ₱${order.total.toFixed(2)}
                    </span>

                </td>


                <td>

                    <span>
                        ${order.completedTime}
                    </span>

                </td>


                <td>

                    <span class="log-status">
                        Done
                    </span>

                </td>

            `;


            tableBody.appendChild(
                row
            );

        }
    );

}


/* DATE CHANGE */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        document
            .getElementById("logDate")
            .addEventListener(
                "change",
                function() {

                    renderOrderLog();

                }
            );

    }
);


/* ORDER MODAL */

function openOrderModal(
    order
) {

    const modal =
        document.getElementById(
            "orderModal"
        );


    const details =
        document.getElementById(
            "orderDetails"
        );


    details.innerHTML = `

        <div class="detail-row">

            <span>
                Student ID
            </span>

            <strong>
                ${escapeHTML(order.studentId)}
            </strong>

        </div>


        <div class="detail-row">

            <span>
                Status
            </span>

            <strong>
                ${order.status}
            </strong>

        </div>


        <div class="detail-row">

            <span>
                Total
            </span>

            <strong>
                ₱${order.total.toFixed(2)}
            </strong>

        </div>


        <div class="detail-items">

            ${order.items
                .map(
                    item => `

                        <div class="detail-item">

                            <span>
                                ${item.quantity}×
                                ${escapeHTML(item.name)}
                            </span>

                            <strong>
                                ₱${(
                                    item.price *
                                    item.quantity
                                ).toFixed(2)}
                            </strong>

                        </div>

                    `
                )
                .join("")
            }

        </div>

    `;


    modal.classList.add(
        "show"
    );

}


function closeOrderModal() {

    document
        .getElementById("orderModal")
        .classList.remove(
            "show"
        );

}


/* CLOSE MODAL OUTSIDE */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        document
            .getElementById("orderModal")
            .addEventListener(
                "click",
                function(event) {

                    if (
                        event.target ===
                        this
                    ) {

                        closeOrderModal();

                    }

                }
            );

    }
);


/* ESCAPE KEY */

document.addEventListener(
    "keydown",
    function(event) {

        if (
            event.key === "Escape"
        ) {

            closeOrderModal();

        }

    }
);


/* DATE */

function getToday() {

    const date =
        new Date();


    const year =
        date.getFullYear();


    const month =
        String(
            date.getMonth() + 1
        ).padStart(
            2,
            "0"
        );


    const day =
        String(
            date.getDate()
        ).padStart(
            2,
            "0"
        );


    return `${year}-${month}-${day}`;

}


/* TIME */

function getCurrentTime() {

    return new Date()
        .toLocaleTimeString(
            "en-US",
            {
                hour: "numeric",
                minute: "2-digit"
            }
        );

}


/* STATUS */

function getStatusClass(
    status
) {

    switch (status) {

        case "Pending":

            return "status-pending";


        case "Preparing":

            return "status-preparing";


        case "Ready":

            return "status-ready";


        default:

            return "";

    }

}


/* ESCAPE HTML */

function escapeHTML(
    text
) {

    const div =
        document.createElement(
            "div"
        );


    div.textContent =
        text;


    return div.innerHTML;

}