/* categories-admin.js */
const CATEGORY_STORAGE_KEY =
    "canteenCategories";


/* VARIABLES */

let categories =
    loadCategories();


let editingCategoryId =
    null;


let deletingCategoryId =
    null;


/* DEFAULT CATEGORIES */

function createDefaultCategories() {

    return [

        {
            id: generateId(),

            title: "Meals",

            description:
                "Complete and satisfying meals for students.",

            image: ""

        },


        {
            id: generateId(),

            title: "Snacks",

            description:
                "Quick and delicious snacks for break time.",

            image: ""

        },


        {
            id: generateId(),

            title: "Drinks",

            description:
                "Refreshing drinks and beverages.",

            image: ""

        }

    ];

}


/* INITIALIZE */

document.addEventListener(
    "DOMContentLoaded",
    function() {


        if (
            !localStorage.getItem(
                CATEGORY_STORAGE_KEY
            )
        ) {

            categories =
                createDefaultCategories();

            saveCategories();

        }


        renderCategories();

        setupEvents();

    }
);


/* LOAD CATEGORIES */

function loadCategories() {

    const saved =
        localStorage.getItem(
            CATEGORY_STORAGE_KEY
        );


    if (!saved) {

        return [];

    }


    try {

        return JSON.parse(saved);

    } catch (error) {

        console.error(
            "Could not load categories:",
            error
        );

        return [];

    }

}


/* SAVE CATEGORIES */

function saveCategories() {

    localStorage.setItem(
        CATEGORY_STORAGE_KEY,
        JSON.stringify(categories)
    );


    /* Notify other pages/tabs that categories changed. */

    window.dispatchEvent(
        new Event(
            "categoriesUpdated"
        )
    );

}


/* RENDER CATEGORIES */

function renderCategories() {

    const grid =
        document.getElementById(
            "categoryGrid"
        );


    const addCard =
        document.getElementById(
            "addCategoryCard"
        );


    /* Remove old category cards. */

    const oldCards =
        grid.querySelectorAll(
            ".category-item-card"
        );


    oldCards.forEach(
        card =>
            card.remove()
    );


    /* Create category cards. */

    categories.forEach(
        category => {

            const card =
                createCategoryCard(
                    category
                );


            /* Insert the category BEFORE the Add card. */

            grid.insertBefore(
                card,
                addCard
            );

        }
    );


    /* Update counter. */

    document.getElementById(
        "categoryCount"
    ).textContent =
        categories.length;

}


/* CREATE CATEGORY CARD */

function createCategoryCard(
    category
) {

    const card =
        document.createElement(
            "div"
        );


    card.className =
        "category-card category-item-card";


    let imageHTML;


    if (
        category.image &&
        category.image !== ""
    ) {

        imageHTML = `

            <img
                src="${category.image}"
                alt="${escapeHTML(category.title)}"
                class="category-image"
            >

        `;

    } else {

        imageHTML = `

            <div
                class="category-image-placeholder"
            >
                🍽️
            </div>

        `;

    }


    card.innerHTML = `

        ${imageHTML}


        <div class="category-content">


            <h3>
                ${escapeHTML(category.title)}
            </h3>


            <p>
                ${escapeHTML(category.description)}
            </p>


            <div class="category-actions">


                <button
                    type="button"
                    class="edit-button"
                    onclick="
                        editCategory('${category.id}')
                    "
                >
                    Edit
                </button>


                <button
                    type="button"
                    class="delete-card-button"
                    onclick="
                        deleteCategory('${category.id}')
                    "
                >
                    Delete
                </button>


            </div>


        </div>

    `;


    return card;

}


/* SETUP EVENTS */

function setupEvents() {

    /* Add category card. */

    document
        .getElementById(
            "addCategoryCard"
        )
        .addEventListener(
            "click",
            openAddModal
        );


    /* Close modal. */

    document
        .getElementById(
            "closeModal"
        )
        .addEventListener(
            "click",
            closeCategoryModal
        );


    document
        .getElementById(
            "cancelButton"
        )
        .addEventListener(
            "click",
            closeCategoryModal
        );


    /* Form submit. */

    document
        .getElementById(
            "categoryForm"
        )
        .addEventListener(
            "submit",
            saveCategory
        );


    /* Image upload. */

    document
        .getElementById(
            "categoryImage"
        )
        .addEventListener(
            "change",
            previewImage
        );


    /* Delete buttons. */

    document
        .getElementById(
            "cancelDelete"
        )
        .addEventListener(
            "click",
            closeDeleteModal
        );


    document
        .getElementById(
            "confirmDelete"
        )
        .addEventListener(
            "click",
            confirmCategoryDelete
        );


    /* Close modal when clicking outside it. */

    document
        .getElementById(
            "categoryModal"
        )
        .addEventListener(
            "click",
            function(event) {

                if (
                    event.target ===
                    this
                ) {

                    closeCategoryModal();

                }

            }
        );


    document
        .getElementById(
            "deleteModal"
        )
        .addEventListener(
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


    /* Escape key. */

    document.addEventListener(
        "keydown",
        function(event) {

            if (
                event.key ===
                "Escape"
            ) {

                closeCategoryModal();

                closeDeleteModal();

            }

        }
    );

}


/* OPEN ADD MODAL */

function openAddModal() {

    editingCategoryId =
        null;


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

    const category =
        categories.find(
            item =>
                item.id ===
                categoryId
        );


    if (!category) {

        return;

    }


    editingCategoryId =
        categoryId;


    document.getElementById(
        "modalTitle"
    ).textContent =
        "Edit Category";


    document.getElementById(
        "categoryTitle"
    ).value =
        category.title;


    document.getElementById(
        "categoryDescription"
    ).value =
        category.description;


    /* Display existing image. */

    if (
        category.image
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
            category.image;


        preview.style.display =
            "block";


        placeholder.style.display =
            "none";

    } else {

        resetImagePreview();

    }


    document.getElementById(
        "categoryModal"
    ).classList.add(
        "show"
    );

}


/* SAVE CATEGORY */

function saveCategory(
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


    const image =
        document.getElementById(
            "imagePreview"
        ).src;


    /* Validate title. */

    if (!title) {

        alert(
            "Please enter a category title."
        );

        return;

    }

    let imageData = "";


    if (
        image &&
        image !==
        window.location.href
    ) {

        imageData =
            image;

    }



    if (
        editingCategoryId
    ) {

        const category =
            categories.find(
                item =>
                    item.id ===
                    editingCategoryId
            );


        if (category) {

            category.title =
                title;

            category.description =
                description;



            if (
                imageData &&
                imageData !==
                category.image
            ) {

                category.image =
                    imageData;

            }

        }

    }


    else {

        const newCategory = {

            id:
                generateId(),

            title:
                title,

            description:
                description,

            image:
                imageData

        };


        categories.push(
            newCategory
        );

    }



    saveCategories();




    renderCategories();



    closeCategoryModal();

}


/* PREVIEW IMAGE */

function previewImage(
    event
) {

    const file =
        event.target.files[0];


    if (!file) {

        return;

    }


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


    const reader =
        new FileReader();


    reader.onload =
        function(e) {

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


/* RESET IMAGE*/

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
    categoryId
) {

    const category =
        categories.find(
            item =>
                item.id ===
                categoryId
        );


    if (!category) {

        return;

    }


    deletingCategoryId =
        categoryId;


    document.getElementById(
        "deleteMessage"
    ).textContent =
        `Are you sure you want to delete "${category.title}"?`;


    document.getElementById(
        "deleteModal"
    ).classList.add(
        "show"
    );

}


/* CONFIRM DELETE */

function confirmCategoryDelete() {

    if (
        !deletingCategoryId
    ) {

        return;

    }


    categories =
        categories.filter(
            category =>
                category.id !==
                deletingCategoryId
        );


    saveCategories();


    renderCategories();


    closeDeleteModal();

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


/* GENERATE ID */

function generateId() {

    return Date.now().toString()
        +
        Math.random()
            .toString(36)
            .substring(2, 9);

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