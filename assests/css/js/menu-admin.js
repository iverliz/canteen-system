/* MENU VARIABLES */

let editingCard = null;

/* PEN ADD MODAL */

function openAddModal() {

    editingCard = null;

    document.getElementById("modalTitle").textContent =
        "Add Menu Item";

    document.getElementById("modalDescription").textContent =
        "Add a new food item to your menu.";

    document.getElementById("saveButton").textContent =
        "Add Menu Item";

    document.getElementById("menuForm").reset();

    document.getElementById("imagePreview").innerHTML =
        "<span>Image Preview</span>";

    document.getElementById("menuModal").classList.add("show");

}


/* OPEN EDIT MODAL */

function openEditModal(button) {

    editingCard = button.closest(".food-card");

    const name =
        editingCard.querySelector("h3").textContent;

    const price =
        editingCard.querySelector(".price").textContent
            .replace("₱", "")
            .trim();

    const description =
        editingCard.querySelector(".food-description").textContent.trim();

    const category =
        editingCard.dataset.category;


    document.getElementById("modalTitle").textContent =
        "Edit Menu Item";

    document.getElementById("modalDescription").textContent =
        "Update the information of this food item.";

    document.getElementById("saveButton").textContent =
        "Save Changes";


    document.getElementById("foodName").value =
        name;

    document.getElementById("foodPrice").value =
        price;

    document.getElementById("foodCategory").value =
        category;

    document.getElementById("foodDescription").value =
        description;


    /* Show existing image */

    const image =
        editingCard.querySelector(".food-image img");

    if (image) {

        document.getElementById("imagePreview").innerHTML =
            `<img src="${image.src}" alt="Preview">`;

    } else {

        const placeholder =
            editingCard.querySelector(".food-placeholder");

        if (placeholder) {

            document.getElementById("imagePreview").innerHTML =
                `<div class="preview-placeholder">
                    ${placeholder.textContent}
                </div>`;

        }

    }


    document.getElementById("menuModal").classList.add("show");

}


/* CLOSE MODAL */

function closeModal() {

    document
        .getElementById("menuModal")
        .classList.remove("show");

    editingCard = null;

}


/* SAVE FOOD */

function saveFood(event) {

    event.preventDefault();


    const name =
        document
            .getElementById("foodName")
            .value
            .trim();


    const price =
        document
            .getElementById("foodPrice")
            .value;


    const category =
        document
            .getElementById("foodCategory")
            .value;


    const description =
        document
            .getElementById("foodDescription")
            .value
            .trim();


    if (!name || !price || !category) {

        alert(
            "Please complete all required fields."
        );

        return;

    }


    const categoryName =
        getCategoryName(category);


    /* EDIT EXISTING FOOD */

    if (editingCard) {

        editingCard.dataset.name =
            name;

        editingCard.dataset.category =
            category;


        editingCard.querySelector("h3").textContent =
            name;


        editingCard.querySelector(".price").textContent =
            "₱" +
            parseFloat(price).toFixed(2);


        editingCard.querySelector(
            ".food-description"
        ).textContent =
            description ||
            "No description available.";


        editingCard.querySelector(
            ".category-label"
        ).textContent =
            categoryName;


        updateImage(editingCard);


        showMessage(
            "Menu item updated successfully."
        );

    }


    /* ADD NEW FOOD */

    else {

        const card =
            createFoodCard(
                name,
                price,
                category,
                categoryName,
                description
            );


        const foodGrid =
            document.getElementById(
                "foodGrid"
            );


        const addCard =
            document.querySelector(
                ".add-food-card"
            );


        /*
         * Insert the new food
         * BEFORE the Add Food card.
         */

        foodGrid.insertBefore(
            card,
            addCard
        );


        updateImage(card);


        showMessage(
            "Menu item added successfully."
        );

    }


    closeModal();

    applyFilters();

}


/* CREATE FOOD CARD*/

function createFoodCard(
    name,
    price,
    category,
    categoryName,
    description
) {

    const card =
        document.createElement("div");


    card.className =
        "food-card";


    card.dataset.name =
        name;


    card.dataset.category =
        category;


    card.innerHTML = `

        <div class="food-image">

            <div class="food-placeholder">
                ${getCategoryIcon(category)}
            </div>

        </div>


        <div class="food-info">

            <div class="category-label">
                ${categoryName}
            </div>


            <h3>
                ${escapeHTML(name)}
            </h3>


            <p class="food-description">
                ${
                    escapeHTML(
                        description ||
                        "No description available."
                    )
                }
            </p>


            <div class="food-bottom">

                <span class="price">
                    ₱${parseFloat(price).toFixed(2)}
                </span>

            </div>


            <div class="food-actions">

                <button
                    class="edit-button"
                    onclick="openEditModal(this)"
                >
                    Edit
                </button>


                <button
                    class="delete-button"
                    onclick="deleteItem(this)"
                >
                    Delete
                </button>

            </div>

        </div>

    `;


    return card;

}


/* UPDATE IMAGE */

function updateImage(card) {

    const file =
        document.getElementById("foodImage").files[0];


    if (!file) {
        return;
    }


    const reader =
        new FileReader();


    reader.onload = function(event) {

        card.querySelector(".food-image").innerHTML = `

            <img
                src="${event.target.result}"
                alt="Food Image"
            >

        `;

    };


    reader.readAsDataURL(file);

}


/* IMAGE PREVIEW */

function previewImage(event) {

    const file =
        event.target.files[0];


    if (!file) {

        document.getElementById("imagePreview").innerHTML =
            "<span>Image Preview</span>";

        return;

    }


    const reader =
        new FileReader();


    reader.onload = function(e) {

        document.getElementById("imagePreview").innerHTML = `

            <img
                src="${e.target.result}"
                alt="Image Preview"
            >

        `;

    };


    reader.readAsDataURL(file);

}


/* DELETE FOOD */

function deleteItem(button) {

    const card =
        button.closest(".food-card");


    const name =
        card.querySelector("h3").textContent;


    const confirmDelete =
        confirm(
            `Are you sure you want to delete "${name}"?`
        );


    if (!confirmDelete) {
        return;
    }


    card.remove();


    showMessage(
        "Menu item deleted successfully."
    );


    applyFilters();

}


/* SEARCH FOOD */

function searchFood() {

    applyFilters();

}


/* FILTER FOOD */

function filterFood() {

    applyFilters();

}


/* APPLY SEARCH + CATEGORY*/

function applyFilters() {

    const search =
        document
            .getElementById("searchInput")
            .value
            .toLowerCase()
            .trim();


    const category =
        document
            .getElementById("categoryFilter")
            .value;


    const cards =
        document.querySelectorAll(
            ".food-grid .food-card"
        );


    let visibleCount = 0;


    cards.forEach(card => {

        const name =
            card.dataset.name
                .toLowerCase();


        const cardCategory =
            card.dataset.category;


        const matchesSearch =
            name.includes(search);


        const matchesCategory =
            category === "all" ||
            cardCategory === category;


        if (
            matchesSearch &&
            matchesCategory
        ) {

            card.style.display =
                "";

            visibleCount++;

        } else {

            card.style.display =
                "none";

        }

    });


    const noResults =
        document.getElementById(
            "noResults"
        );


    /* show "No results" when the user selected a category. */

    const hasFilter =
        search !== "" ||
        category !== "all";


    if (
        visibleCount === 0 &&
        hasFilter
    ) {

        noResults.style.display =
            "block";

    } else {

        noResults.style.display =
            "none";

    }

}


/* CATEGORY NAME= */

function getCategoryName(category) {

    const categories = {

        meal: "Meals",

        snack: "Snacks",

        drink: "Drinks",

        dessert: "Desserts"

    };


    return categories[category] || "Other";

}


/* ATEGORY ICON */

function getCategoryIcon(category) {

    const icons = {

        meal: "🍗",

        snack: "🍟",

        drink: "🥤",

        dessert: "🍰"

    };


    return icons[category] || "🍽️";

}


/* MESSAGE */

function showMessage(message) {

    const existing =
        document.querySelector(".temporary-message");


    if (existing) {
        existing.remove();
    }


    const notification =
        document.createElement("div");


    notification.className =
        "temporary-message";


    notification.textContent =
        message;


    notification.style.position =
        "fixed";

    notification.style.top =
        "25px";

    notification.style.right =
        "25px";

    notification.style.background =
        "#d1e7dd";

    notification.style.color =
        "#0f5132";

    notification.style.padding =
        "13px 18px";

    notification.style.borderRadius =
        "8px";

    notification.style.fontSize =
        "14px";

    notification.style.fontWeight =
        "bold";

    notification.style.boxShadow =
        "0 5px 20px rgba(0,0,0,0.1)";

    notification.style.zIndex =
        "3000";


    document.body.appendChild(
        notification
    );


    setTimeout(() => {

        notification.remove();

    }, 2500);

}


/* ESCAPE HTML */

function escapeHTML(text) {

    const div =
        document.createElement("div");

    div.textContent =
        text;

    return div.innerHTML;

}


/* CLOSE WHEN CLICKING OUTSIDE */

document
    .getElementById("menuModal")
    .addEventListener("click", function(event) {

        if (event.target === this) {

            closeModal();

        }

    });


/* ESC KEY */

document.addEventListener(
    "keydown",
    function(event) {

        if (
            event.key === "Escape" &&
            document
                .getElementById("menuModal")
                .classList
                .contains("show")
        ) {

            closeModal();

        }

    }
);