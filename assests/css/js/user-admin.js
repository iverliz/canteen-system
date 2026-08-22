/* usere-admin.js */
let users = [

    {
        id: 1,

        name: "Canteen Manager",

        position: "Canteen Manager",

        active: true

    },


    {
        id: 2,

        name: "Canteen Staff",

        position: "Canteen Staff",

        active: true

    }

];


/* VARIABLES */

let deletingUserId = null;


/* INITIALIZE */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        renderUsers();

        setupEvents();

    }
);


/* RENDER USERS */

function renderUsers() {

    const tableBody =
        document.getElementById(
            "userTableBody"
        );


    tableBody.innerHTML = "";


    /* If no users exist. */

    if (
        users.length === 0
    ) {

        tableBody.innerHTML = `

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

        `;


        updateAccountCount();

        return;

    }


    /* Create rows. */

    users.forEach(
        user => {

            const row =
                createUserRow(
                    user
                );


            tableBody.appendChild(
                row
            );

        }
    );


    updateAccountCount();

}


/* CREATE USER ROW */

function createUserRow(
    user
) {

    const row =
        document.createElement(
            "tr"
        );


    const avatar =
        getInitials(
            user.name
        );


    const statusClass =
        user.active
            ? "status-active"
            : "status-inactive";


    const statusText =
        user.active
            ? "Active"
            : "Inactive";



    const toggleClass =
        user.active
            ? "deactivate-button"
            : "activate-button";


    const toggleText =
        user.active
            ? "Deactivate"
            : "Activate";


    row.innerHTML = `

        <!-- USER -->

        <td>

            <div class="user-cell">


                <div class="user-avatar">

                    ${avatar}

                </div>


                <span class="user-name">

                    ${escapeHTML(user.name)}

                </span>


            </div>

        </td>



        <!-- POSITION -->

        <td>

            <span class="position-badge">

                ${escapeHTML(user.position)}

            </span>

        </td>



        <!-- STATUS -->

        <td>

            <span
                class="status-badge ${statusClass}"
            >

                ${statusText}

            </span>

        </td>



        <!-- ACTION -->

        <td>

            <div class="action-buttons">


                <button
                    type="button"
                    class="status-toggle ${toggleClass}"
                    onclick="
                        toggleUserStatus(${user.id})
                    "
                >

                    ${toggleText}

                </button>


                <button
                    type="button"
                    class="delete-user-button"
                    onclick="
                        deleteUser(${user.id})
                    "
                >

                    Delete

                </button>


            </div>

        </td>

    `;


    return row;

}


/* TOGGLE USER STATUS */

function toggleUserStatus(
    userId
) {

    const user =
        users.find(
            item =>
                item.id ===
                userId
        );


    if (!user) {

        return;

    }


    /* Switch active/inactive.*/

    user.active =
        !user.active;


    /* Refresh table. */

    renderUsers();

}


/* DELETE USER*/

function deleteUser(
    userId
) {

    const user =
        users.find(
            item =>
                item.id ===
                userId
        );


    if (!user) {

        return;

    }


    deletingUserId =
        userId;


    document.getElementById(
        "deleteMessage"
    ).textContent =
        `Are you sure you want to delete the account "${user.name}"?`;


    document.getElementById(
        "deleteModal"
    ).classList.add(
        "show"
    );

}


/* CONFIRM DELETE */

function confirmDelete() {

    if (
        deletingUserId ===
        null
    ) {

        return;

    }


    users =
        users.filter(
            user =>
                user.id !==
                deletingUserId
        );



    renderUsers();



    closeDeleteModal();

}


/* CLOSE DELETE MODAL */

function closeDeleteModal() {

    document.getElementById(
        "deleteModal"
    ).classList.remove(
        "show"
    );


    deletingUserId =
        null;

}


/* SETUP EVENTS */

function setupEvents() {

    /* Cancel delete.*/

    document.getElementById(
        "cancelDelete"
    ).addEventListener(
        "click",
        closeDeleteModal
    );


    /* Confirm delete.*/

    document.getElementById(
        "confirmDelete"
    ).addEventListener(
        "click",
        confirmDelete
    );


    /*Click outside modal.  */

    document.getElementById(
        "deleteModal"
    ).addEventListener(
        "click",
        function(event) {

            if (
                event.target ===
                this
            ) {

                closeDeleteModal();

            }

        }
    );


    /*Escape key.*/

    document.addEventListener(
        "keydown",
        function(event) {

            if (
                event.key ===
                "Escape"
            ) {

                closeDeleteModal();

            }

        }
    );

}


/* ACCOUNT COUNT */

function updateAccountCount() {

    document.getElementById(
        "accountCount"
    ).textContent =
        users.length;

}


/* GET INITIALS */

function getInitials(
    name
) {

    const words =
        name
            .trim()
            .split(" ");


    if (
        words.length === 1
    ) {

        return words[0]
            .substring(0, 2)
            .toUpperCase();

    }


    return (
        words[0][0] +
        words[words.length - 1][0]
    ).toUpperCase();

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