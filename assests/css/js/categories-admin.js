/* categories-admin.js */

let editingCategoryId = null;
let deletingCategoryId = null;


/* ================================
   PAGE LOAD
================================ */

document.addEventListener("DOMContentLoaded", function () {

    setupEvents();

});


/* ================================
   SETUP EVENTS
================================ */

function setupEvents() {

    /* ADD CATEGORY */

    document
        .getElementById("addCategoryCard")
        .addEventListener("click", openAddModal);


    /* CLOSE CATEGORY MODAL */

    document
        .getElementById("closeModal")
        .addEventListener("click", closeCategoryModal);


    document
        .getElementById("cancelButton")
        .addEventListener("click", closeCategoryModal);


    /* FORM */

    document
        .getElementById("categoryForm")
        .addEventListener("submit", saveCategory);


    /* IMAGE */

    document
        .getElementById("categoryImage")
        .addEventListener("change", previewImage);


    /* DELETE */

    document
        .getElementById("cancelDelete")
        .addEventListener("click", closeDeleteModal);


    document
        .getElementById("confirmDelete")
        .addEventListener("click", confirmCategoryDelete);


    /* LOGOUT */

    const logoutButton =
        document.getElementById("logoutButton");

    if (logoutButton) {

        logoutButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                openLogoutModal(
                    logoutButton.href
                );

            }
        );

    }


    const cancelLogout =
        document.getElementById("cancelLogout");

    if (cancelLogout) {

        cancelLogout.addEventListener(
            "click",
            closeLogoutModal
        );

    }


    const confirmLogout =
        document.getElementById("confirmLogout");

    if (confirmLogout) {

        confirmLogout.addEventListener(
            "click",
            function () {

                const logoutUrl =
                    document.getElementById(
                        "logoutButton"
                    ).href;

                window.location.href =
                    logoutUrl;

            }
        );

    }


    /* CLICK OUTSIDE CATEGORY MODAL */

    document
        .getElementById("categoryModal")
        .addEventListener(
            "click",
            function (event) {

                if (event.target === this) {

                    closeCategoryModal();

                }

            }
        );


    /* CLICK OUTSIDE DELETE MODAL */

    document
        .getElementById("deleteModal")
        .addEventListener(
            "click",
            function (event) {

                if (event.target === this) {

                    closeDeleteModal();

                }

            }
        );


    /* CLICK OUTSIDE LOGOUT MODAL */

    const logoutModal =
        document.getElementById("logoutModal");

    if (logoutModal) {

        logoutModal.addEventListener(
            "click",
            function (event) {

                if (event.target === this) {

                    closeLogoutModal();

                }

            }
        );

    }


    /* ESCAPE */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                closeCategoryModal();
                closeDeleteModal();
                closeLogoutModal();

            }

        }
    );

}


/* ================================
   NOTIFICATION
================================ */

function showNotification(
    message,
    type = "success"
) {

    const notification =
        document.getElementById(
            "categoryNotification"
        );

    if (!notification) {
        return;
    }


    const messageElement =
        document.getElementById(
            "notificationMessage"
        );


    const icon =
        document.getElementById(
            "notificationIcon"
        );


    messageElement.textContent =
        message;


    notification.className =
        "category-notification " + type;


    if (type === "success") {

        icon.textContent = "✓";

    } else {

        icon.textContent = "!";

    }


    notification.classList.add("show");


    clearTimeout(
        window.categoryNotificationTimer
    );


    window.categoryNotificationTimer =
        setTimeout(
            function () {

                notification.classList.remove(
                    "show"
                );

            },
            3000
        );

}


/* ================================
   OPEN ADD MODAL
================================ */

function openAddModal() {

    editingCategoryId = null;


    document.getElementById(
        "modalTitle"
    ).textContent =
        "Add Category";


    document.getElementById(
        "categoryForm"
    ).reset();


    resetImagePreview();


    document.getElementById(
        "categoryModal"
    ).classList.add("show");

}


/* ================================
   EDIT CATEGORY
================================ */

function editCategory(categoryId) {

    editingCategoryId =
        categoryId;


    document.getElementById(
        "modalTitle"
    ).textContent =
        "Edit Category";


    const card =
        document.querySelector(
            `.category-item-card[data-id="${categoryId}"]`
        );


    if (!card) {

        return;

    }


    const title =
        card
            .querySelector(
                ".category-content h3"
            )
            .textContent
            .trim();


    const description =
        card
            .querySelector(
                ".category-content p"
            )
            .textContent
            .trim();


    document.getElementById(
        "categoryTitle"
    ).value =
        title;


    document.getElementById(
        "categoryDescription"
    ).value =
        description;


    /* EXISTING IMAGE */

    const image =
        card.querySelector(
            ".category-image img"
        );


    const preview =
        document.getElementById(
            "imagePreview"
        );


    const placeholder =
        document.getElementById(
            "uploadPlaceholder"
        );


    if (
        image &&
        image.src &&
        image.style.display !== "none"
    ) {

        /*
         * Add timestamp to prevent browser cache
         * from showing the old image.
         */

        preview.src =
            image.src +
            (
                image.src.includes("?")
                    ? "&"
                    : "?"
            ) +
            "edit=" +
            Date.now();


        preview.style.display =
            "block";


        placeholder.style.display =
            "none";

    } else {

        resetImagePreview();

    }


    /*
     * Important:
     * Empty the file input because the existing
     * database image is NOT a new upload.
     */

    document.getElementById(
        "categoryImage"
    ).value =
        "";


    document.getElementById(
        "categoryModal"
    ).classList.add("show");

}


/* ================================
   SAVE CATEGORY
================================ */

async function saveCategory(event) {

    event.preventDefault();


    const title =
        document
            .getElementById("categoryTitle")
            .value
            .trim();


    const description =
        document
            .getElementById("categoryDescription")
            .value
            .trim();


    const imageInput =
        document.getElementById(
            "categoryImage"
        );


    /* VALIDATION */

    if (!title) {

        showNotification(
            "Please enter a category title.",
            "error"
        );

        return;

    }


    if (!description) {

        showNotification(
            "Please enter a category description.",
            "error"
        );

        return;

    }


    const formData =
        new FormData();


    formData.append(
        "category_title",
        title
    );


    formData.append(
        "category_description",
        description
    );


    if (editingCategoryId !== null) {

        formData.append(
            "category_id",
            editingCategoryId
        );

        formData.append(
            "action",
            "edit"
        );

    } else {

        formData.append(
            "action",
            "add"
        );

    }


    /*
     * NEW IMAGE
     *
     * This is important.
     * Only append category_picture when
     * the user actually selected a new file.
     */

    if (
        imageInput.files &&
        imageInput.files.length > 0
    ) {

        formData.append(
            "category_picture",
            imageInput.files[0]
        );

    }


    try {

        const response =
            await fetch(
                "categories-admin.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const result =
            await response.json();


        if (result.success) {

            closeCategoryModal();


            showNotification(
                result.message,
                "success"
            );


            /*
             * Give the notification a moment
             * before refreshing the page.
             */

            setTimeout(
                function () {

                    window.location.reload();

                },
                500
            );


        } else {

            showNotification(
                result.message,
                "error"
            );

        }

    } catch (error) {

        showNotification(
            "Unable to connect to the server.",
            "error"
        );

    }

}


/* ================================
   IMAGE PREVIEW
================================ */

function previewImage(event) {

    const file =
        event.target.files[0];


    if (!file) {

        return;

    }


    /* MAXIMUM 2MB */

    if (
        file.size >
        2 * 1024 * 1024
    ) {

        showNotification(
            "Please select an image smaller than 2MB.",
            "error"
        );


        event.target.value =
            "";


        return;

    }


    const allowedTypes = [

        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp"

    ];


    if (
        !allowedTypes.includes(
            file.type
        )
    ) {

        showNotification(
            "Only JPG, PNG, GIF, and WEBP images are allowed.",
            "error"
        );


        event.target.value =
            "";


        return;

    }


    const reader =
        new FileReader();


    reader.onload =
        function (e) {

            const preview =
                document.getElementById(
                    "imagePreview"
                );


            const placeholder =
                document.getElementById(
                    "uploadPlaceholder"
                );


            preview.src =
                e.target.result;


            preview.style.display =
                "block";


            placeholder.style.display =
                "none";

        };


    reader.readAsDataURL(file);

}


/* ================================
   RESET IMAGE PREVIEW
================================ */

function resetImagePreview() {

    const preview =
        document.getElementById(
            "imagePreview"
        );


    const placeholder =
        document.getElementById(
            "uploadPlaceholder"
        );


    preview.src = "";


    preview.style.display =
        "none";


    placeholder.style.display =
        "flex";


    document.getElementById(
        "categoryImage"
    ).value = "";

}


/* ================================
   CLOSE CATEGORY MODAL
================================ */

function closeCategoryModal() {

    document.getElementById(
        "categoryModal"
    ).classList.remove("show");


    editingCategoryId =
        null;

}


/* ================================
   DELETE CATEGORY
================================ */

function deleteCategory(
    categoryId,
    categoryTitle
) {

    deletingCategoryId =
        categoryId;


    document.getElementById(
        "deleteMessage"
    ).textContent =
        `Are you sure you want to delete "${categoryTitle}"?`;


    document.getElementById(
        "deleteModal"
    ).classList.add("show");

}


/* ================================
   CONFIRM DELETE
================================ */

async function confirmCategoryDelete() {

    if (
        deletingCategoryId === null
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
        "category_id",
        deletingCategoryId
    );


    try {

        const response =
            await fetch(
                "categories-admin.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const result =
            await response.json();


        if (result.success) {

            closeDeleteModal();


            showNotification(
                result.message,
                "success"
            );


            setTimeout(
                function () {

                    window.location.reload();

                },
                500
            );


        } else {

            showNotification(
                result.message,
                "error"
            );

        }

    } catch (error) {

        showNotification(
            "Unable to connect to the server.",
            "error"
        );

    }

}


/* ================================
   CLOSE DELETE MODAL
================================ */

function closeDeleteModal() {

    document.getElementById(
        "deleteModal"
    ).classList.remove("show");


    deletingCategoryId =
        null;

}


/* ================================
   LOGOUT MODAL
================================ */

function openLogoutModal() {

    document.getElementById(
        "logoutModal"
    ).classList.add("show");

}


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