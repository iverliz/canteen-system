/* categories-admin.js */

let editingCategoryId = null;

let deletingCategoryId = null;


document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupEvents();

    }
);


/* SETUP EVENTS */

function setupEvents() {


    /* ADD CATEGORY */

    document
        .getElementById("addCategoryCard")
        .addEventListener(
            "click",
            openAddModal
        );


    /* CLOSE */

    document
        .getElementById("closeModal")
        .addEventListener(
            "click",
            closeCategoryModal
        );


    document
        .getElementById("cancelButton")
        .addEventListener(
            "click",
            closeCategoryModal
        );


    /* FORM */

    document
        .getElementById("categoryForm")
        .addEventListener(
            "submit",
            saveCategory
        );


    /* IMAGE */

    document
        .getElementById("categoryImage")
        .addEventListener(
            "change",
            previewImage
        );


    /* DELETE */

    document
        .getElementById("cancelDelete")
        .addEventListener(
            "click",
            closeDeleteModal
        );


    document
        .getElementById("confirmDelete")
        .addEventListener(
            "click",
            confirmCategoryDelete
        );


    /* CLICK OUTSIDE */

    document
        .getElementById("categoryModal")
        .addEventListener(
            "click",
            function (event) {

                if (
                    event.target === this
                ) {

                    closeCategoryModal();

                }

            }
        );


    document
        .getElementById("deleteModal")
        .addEventListener(
            "click",
            function (event) {

                if (
                    event.target === this
                ) {

                    closeDeleteModal();

                }

            }
        );


    /* ESCAPE */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape"
            ) {

                closeCategoryModal();

                closeDeleteModal();

            }

        }
    );

}


/* OPEN ADD MODAL */

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
    ).classList.add(
        "show"
    );

}


/* EDIT CATEGORY */

function editCategory(
    categoryId
) {

    editingCategoryId =
        categoryId;


    document.getElementById(
        "modalTitle"
    ).textContent =
        "Edit Category";


    /*
     * Find the category card
     * already rendered by PHP.
     */

    const card =
        document.querySelector(
            `.category-item-card[data-id="${categoryId}"]`
        );


    if (!card) {

        return;

    }


    const title =
        card.querySelector(
            ".category-content h3"
        ).textContent.trim();


    const description =
        card.querySelector(
            ".category-content p"
        ).textContent.trim();


    document.getElementById(
        "categoryTitle"
    ).value =
        title;


    document.getElementById(
        "categoryDescription"
    ).value =
        description;


    /* Existing image */

    const image =
        card.querySelector(
            ".category-image img"
        );


    if (
        image &&
        image.src
    ) {

        const preview =
            document.getElementById(
                "imagePreview"
            );


        const placeholder =
            document.getElementById(
                "uploadPlaceholder"
            );


        preview.src =
            image.src;


        preview.style.display =
            "block";


        placeholder.style.display =
            "none";

    } else {

        resetImagePreview();

    }


    document.getElementById(
        "categoryImage"
    ).value =
        "";


    document.getElementById(
        "categoryModal"
    ).classList.add(
        "show"
    );

}


/* SAVE CATEGORY */

async function saveCategory(
    event
) {

    event.preventDefault();


    const title =
        document.getElementById(
            "categoryTitle"
        ).value.trim();


    const description =
        document.getElementById(
            "categoryDescription"
        ).value.trim();


    const imageInput =
        document.getElementById(
            "categoryImage"
        );


    /* Validate */

    if (!title) {

        alert(
            "Please enter a category title."
        );

        return;

    }


    if (!description) {

        alert(
            "Please enter a category description."
        );

        return;

    }


    /* CREATE FORMDATA */

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


    if (
        editingCategoryId !== null
    ) {

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



    if (
        imageInput.files.length > 0
    ) {

        formData.append(
            "category_picture",
            imageInput.files[0]
        );

    }


    /* SAVE TO PHP */

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


        if (
            result.success
        ) {

            alert(
                result.message
            );


            window.location.reload();

        } else {

            alert(
                result.message
            );

        }

    } catch (error) {

        console.error(
            "Save category error:",
            error
        );


        alert(
            "Unable to connect to the server."
        );

    }

}


/* IMAGE PREVIEw */

function previewImage(
    event
) {

    const file =
        event.target.files[0];


    if (!file) {

        return;

    }


    /* Maximum 2MB */

    if (
        file.size >
        2 * 1024 * 1024
    ) {

        alert(
            "Please select an image smaller than 2MB."
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

        alert(
            "Only JPG, PNG, GIF, and WEBP images are allowed."
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


    reader.readAsDataURL(
        file
    );

}


/* RESET IMAGE PREVIEW */

function resetImagePreview() {

    const preview =
        document.getElementById(
            "imagePreview"
        );


    const placeholder =
        document.getElementById(
            "uploadPlaceholder"
        );


    preview.src =
        "";


    preview.style.display =
        "none";


    placeholder.style.display =
        "flex";


    document.getElementById(
        "categoryImage"
    ).value =
        "";

}


/* CLOSE CATEGORY MODAL */

function closeCategoryModal() {

    document.getElementById(
        "categoryModal"
    ).classList.remove(
        "show"
    );


    editingCategoryId =
        null;

}


/* DELETE CATEGORY */

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
    ).classList.add(
        "show"
    );

}


/* CONFIRM DELETE*/

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


        if (
            result.success
        ) {

            alert(
                result.message
            );


            window.location.reload();

        } else {

            alert(
                result.message
            );

        }

    } catch (error) {

        console.error(
            "Delete category error:",
            error
        );


        alert(
            "Unable to connect to the server."
        );

    }

}


/* CLOSE DELETE MODAL */

function closeDeleteModal() {

    document.getElementById(
        "deleteModal"
    ).classList.remove(
        "show"
    );


    deletingCategoryId =
        null;

}