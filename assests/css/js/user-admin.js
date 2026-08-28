/* user-admin.js */

let deletingUsername = null;


document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupEvents();

    }
);


/* TOGGLE USER STATUS */

function toggleUserStatus(username) {

    const formData = new FormData();

    formData.append(
        "action",
        "toggle_status"
    );

    formData.append(
        "username",
        username
    );


    fetch("user-admin.php", {

        method: "POST",

        body: formData

    })

    .then(response => response.json())

    .then(data => {

        if (data.success) {

            location.reload();

        } else {

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(
            "Error:",
            error
        );

        alert(
            "An error occurred while updating the account status."
        );

    });

}


/* DELETE USER */

function deleteUser(username) {

    deletingUsername = username;


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


/* CONFIRM DELETE */

function confirmDelete() {

    if (
        deletingUsername === null
    ) {

        return;

    }


    const formData = new FormData();

    formData.append(
        "action",
        "delete"
    );

    formData.append(
        "username",
        deletingUsername
    );


    fetch("user-admin.php", {

        method: "POST",

        body: formData

    })

    .then(response => response.json())

    .then(data => {

        if (data.success) {

            closeDeleteModal();

            location.reload();

        } else {

            alert(data.message);

            closeDeleteModal();

        }

    })

    .catch(error => {

        console.error(
            "Error:",
            error
        );

        alert(
            "An error occurred while deleting the account."
        );

        closeDeleteModal();

    });

}


/* CLOSE DELETE MODAL */

function closeDeleteModal() {

    document.getElementById(
        "deleteModal"
    ).classList.remove(
        "show"
    );


    deletingUsername = null;

}


/* ETUP EVENTS */

function setupEvents() {


    /* CANCEL DELETE */

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


    /* CONFIRM DELETE */

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


    /* CLICK OUTSIDE MODAL */

    const modal =
        document.getElementById(
            "deleteModal"
        );


    if (modal) {

        modal.addEventListener(
            "click",
            function (event) {

                if (
                    event.target ===
                    this
                ) {

                    closeDeleteModal();

                }

            }
        );

    }


    /* ESCAPE KEY */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key ===
                "Escape"
            ) {

                closeDeleteModal();

            }

        }
    );

}