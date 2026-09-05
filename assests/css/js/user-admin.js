/* =========================================================
   USER ADMIN JAVASCRIPT
   ========================================================= */

let deletingUsername = null;


/* =========================================================
   DOM READY
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupEvents();
        setupStatusBadges();

    }
);


/* =========================================================
   STATUS BADGES
   ========================================================= */

function setupStatusBadges() {

    const statusBadges =
        document.querySelectorAll(
            ".status-clickable"
        );


    statusBadges.forEach(function (badge) {

        badge.addEventListener(
            "click",
            function () {

                const username =
                    this.dataset.username;

                if (!username) {
                    return;
                }

                toggleUserStatus(username);

            }
        );

    });

}


/* =========================================================
   TOGGLE USER STATUS
   ========================================================= */

function toggleUserStatus(username) {

    const formData =
        new FormData();


    formData.append(
        "action",
        "toggle_status"
    );


    formData.append(
        "username",
        username
    );


    fetch(
        "user-admin.php",
        {
            method: "POST",
            body: formData
        }
    )

    .then(function (response) {

        return response.json();

    })

    .then(function (data) {

        if (data.success) {

            showMessage(
                data.message
            );

            setTimeout(
                function () {

                    location.reload();

                },
                700
            );

        } else {

            showMessage(
                data.message,
                "error"
            );

        }

    })

    .catch(function (error) {

        console.error(
            "Error:",
            error
        );

        showMessage(
            "An error occurred while updating the account status.",
            "error"
        );

    });

}


/* =========================================================
   DELETE USER
   ========================================================= */

function deleteUser(username) {

    deletingUsername =
        username;


    document.getElementById(
        "deleteMessage"
    ).textContent =
        `Are you sure you want to delete the account "${username}"?`;


    document.getElementById(
        "deleteModal"
    ).classList.add(
        "show"
    );

}


/* =========================================================
   CONFIRM DELETE
   ========================================================= */

function confirmDelete() {

    if (
        deletingUsername === null
    ) {

        return;

    }


    const formData =
        new FormData();


    formData.append(
        "action",
        "delete"
    );


    formData.append(
        "username",
        deletingUsername
    );


    const confirmButton =
        document.getElementById(
            "confirmDelete"
        );


    confirmButton.disabled =
        true;

    confirmButton.textContent =
        "Deleting...";


    fetch(
        "user-admin.php",
        {
            method: "POST",
            body: formData
        }
    )

    .then(function (response) {

        return response.json();

    })

    .then(function (data) {

        if (data.success) {

            closeDeleteModal();

            showMessage(
                data.message
            );

            setTimeout(
                function () {

                    location.reload();

                },
                400
            );

        } else {

            showMessage(
                data.message,
                "error"
            );

            closeDeleteModal();

        }

    })

    .catch(function (error) {

        console.error(
            "Error:",
            error
        );

        showMessage(
            "An error occurred while deleting the account.",
            "error"
        );

        closeDeleteModal();

    })

    .finally(function () {

        confirmButton.disabled =
            false;

        confirmButton.textContent =
            "Delete";

    });

}


/* =========================================================
   CLOSE DELETE MODAL
   ========================================================= */

function closeDeleteModal() {

    const modal =
        document.getElementById(
            "deleteModal"
        );


    if (modal) {

        modal.classList.remove(
            "show"
        );

    }


    deletingUsername =
        null;

}


/* =========================================================
   OPEN LOGOUT MODAL
   ========================================================= */

function openLogoutModal() {

    const modal =
        document.getElementById(
            "logoutModal"
        );


    if (modal) {

        modal.classList.add(
            "show"
        );

    }

}


/* =========================================================
   CLOSE LOGOUT MODAL
   ========================================================= */

function closeLogoutModal() {

    const modal =
        document.getElementById(
            "logoutModal"
        );


    if (modal) {

        modal.classList.remove(
            "show"
        );

    }

}


/* =========================================================
   SHOW MESSAGE
   ========================================================= */

function showMessage(
    message,
    type = "success"
) {

    const existing =
        document.querySelector(
            ".temporary-message"
        );


    if (existing) {

        existing.remove();

    }


    const notification =
        document.createElement(
            "div"
        );


    notification.className =
        "temporary-message";


    notification.textContent =
        message;


    if (type === "error") {

        notification.classList.add(
            "message-error"
        );

    } else {

        notification.classList.add(
            "message-success"
        );

    }


    document.body.appendChild(
        notification
    );


    setTimeout(
        function () {

            if (
                notification &&
                notification.parentNode
            ) {

                notification.remove();

            }

        },
        2500
    );

}


/* =========================================================
   SETUP EVENTS
   ========================================================= */

function setupEvents() {


    /* -----------------------------------------------------
       CANCEL DELETE
       ----------------------------------------------------- */

    const cancelButton =
        document.getElementById(
            "cancelDelete"
        );


    if (cancelButton) {

        cancelButton.addEventListener(
            "click",
            closeDeleteModal
        );

    }


    /* -----------------------------------------------------
       CONFIRM DELETE
       ----------------------------------------------------- */

    const confirmButton =
        document.getElementById(
            "confirmDelete"
        );


    if (confirmButton) {

        confirmButton.addEventListener(
            "click",
            confirmDelete
        );

    }


    /* -----------------------------------------------------
       CLICK OUTSIDE DELETE MODAL
       ----------------------------------------------------- */

    const deleteModal =
        document.getElementById(
            "deleteModal"
        );


    if (deleteModal) {

        deleteModal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    deleteModal
                ) {

                    closeDeleteModal();

                }

            }
        );

    }


    /* -----------------------------------------------------
       LOGOUT BUTTON
       ----------------------------------------------------- */

    const logoutButton =
        document.getElementById(
            "logoutButton"
        );


    if (logoutButton) {

        logoutButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                openLogoutModal();

            }
        );

    }


    /* -----------------------------------------------------
       CANCEL LOGOUT
       ----------------------------------------------------- */

    const cancelLogout =
        document.getElementById(
            "cancelLogout"
        );


    if (cancelLogout) {

        cancelLogout.addEventListener(
            "click",
            closeLogoutModal
        );

    }


    /* -----------------------------------------------------
       CONFIRM LOGOUT
       ----------------------------------------------------- */

    const confirmLogout =
        document.getElementById(
            "confirmLogout"
        );


    if (confirmLogout) {

        confirmLogout.addEventListener(
            "click",
            function () {

                window.location.href =
                    "../auth/log_out_admin.php";

            }
        );

    }


    /* -----------------------------------------------------
       CLICK OUTSIDE LOGOUT MODAL
       ----------------------------------------------------- */

    const logoutModal =
        document.getElementById(
            "logoutModal"
        );


    if (logoutModal) {

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


    /* -----------------------------------------------------
       ESCAPE KEY
       ----------------------------------------------------- */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key !==
                "Escape"
            ) {

                return;

            }


            closeDeleteModal();
            closeLogoutModal();

        }
    );

}