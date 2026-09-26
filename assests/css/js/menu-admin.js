/* MENU ADMIN JAVASCRIPT */

let editingFoodId = null;
let deletingFoodId = null;


/* OPEN ADD MODAL */

function openAddModal() {

    editingFoodId = null;

    document.getElementById("modalTitle").textContent =
        "Add Menu Item";

    document.getElementById("modalDescription").textContent =
        "Add a new food item to your menu.";

    document.getElementById("saveButton").textContent =
        "Add Menu Item";

    document.getElementById("menuForm").reset();

    document.getElementById("foodId").value = "";

    document.getElementById("imagePreview").innerHTML =
        "<span>Image Preview</span>";

    document.getElementById("menuModal")
        .classList.add("show");
}


/* OPEN EDIT MODAL */

function openEditModal(foodId) {

    const card =
        document.querySelector(
            `.food-card[data-id="${foodId}"]`
        );

    if (!card) {
        return;
    }

    editingFoodId = foodId;

    const name =
        card.querySelector("h3").textContent.trim();

    const price =
        card.querySelector(".price")
            .textContent
            .replace("₱", "")
            .trim();

    const category =
        card.dataset.category;

    const description =
        card.querySelector(".food-description")
            .textContent
            .trim();

    document.getElementById("modalTitle").textContent =
        "Edit Menu Item";

    document.getElementById("modalDescription").textContent =
        "Update the information of this food item.";

    document.getElementById("saveButton").textContent =
        "Save Changes";

    document.getElementById("foodId").value =
        foodId;

    document.getElementById("foodName").value =
        name;

    document.getElementById("foodPrice").value =
        price;

    document.getElementById("foodCategory").value =
        category;

    document.getElementById("foodDescription").value =
        description === "No description available."
            ? ""
            : description;


    const image =
        card.querySelector(".food-image img");

    if (image) {

        document.getElementById("imagePreview").innerHTML = `
            <img
                src="${image.src}"
                alt="Image Preview"
            >
        `;

    } else {

        document.getElementById("imagePreview").innerHTML =
            "<span>Image Preview</span>";
    }

    document.getElementById("foodImage").value = "";

    document.getElementById("menuModal")
        .classList.add("show");
}


/* CLOSE MODAL */

function closeModal() {

    document.getElementById("menuModal")
        .classList.remove("show");

    editingFoodId = null;
}


/* SAVE FOOD */

async function saveFood(event) {

    event.preventDefault();

    const name =
        document.getElementById("foodName")
            .value
            .trim();

    const price =
        document.getElementById("foodPrice")
            .value;

    const category =
        document.getElementById("foodCategory")
            .value;

    const description =
        document.getElementById("foodDescription")
            .value
            .trim();

    const image =
        document.getElementById("foodImage")
            .files[0];


    if (!name || !price || !category) {

        showMessage(
            "Please complete all required fields.",
            "error"
        );

        return;
    }


    const formData =
        new FormData();

    formData.append(
        "action",
        editingFoodId ? "edit" : "add"
    );

    formData.append(
        "food_name",
        name
    );

    formData.append(
        "food_price",
        price
    );

    formData.append(
        "menu_food_category",
        category
    );

    formData.append(
        "food_description",
        description
    );


    if (editingFoodId) {

        formData.append(
            "food_id",
            editingFoodId
        );
    }


    if (image) {

        formData.append(
            "food_picture",
            image
        );
    }


    const saveButton =
        document.getElementById("saveButton");

    saveButton.disabled = true;
    saveButton.textContent = "Saving...";


    try {

        const response =
            await fetch("menu-admin.php", {
                method: "POST",
                body: formData
            });


        const result =
            await response.json();


        if (result.success) {

            showMessage(
                result.message
            );

            closeModal();

            setTimeout(() => {
                location.reload();
            }, 500);

        } else {

            showMessage(
                result.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "An error occurred while saving the food item.",
            "error"
        );

    } finally {

        saveButton.disabled = false;

        saveButton.textContent =
            editingFoodId
                ? "Save Changes"
                : "Add Menu Item";
    }
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


    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp",
        "image/gif"
    ];


    if (!allowedTypes.includes(file.type)) {

        showMessage(
            "Please select a JPG, PNG, WEBP, or GIF image.",
            "error"
        );

        event.target.value = "";

        return;
    }


    if (file.size > 16 * 1024 * 1024) {

        showMessage(
            "The image is too large. Maximum size is 16MB.",
            "error"
        );

        event.target.value = "";

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


/* CHANGE AVAILABILITY */

async function changeAvailability(
    foodId,
    availability
) {

    const formData =
        new FormData();

    formData.append(
        "action",
        "availability"
    );

    formData.append(
        "food_id",
        foodId
    );

    formData.append(
        "availability",
        availability
    );


    try {

        const response =
            await fetch("menu-admin.php", {
                method: "POST",
                body: formData
            });


        const result =
            await response.json();


        if (result.success) {

            const card =
                document.querySelector(
                    `.food-card[data-id="${foodId}"]`
                );


            if (card) {

                if (availability === 1) {

                    card.classList.remove(
                        "unavailable"
                    );

                } else {

                    card.classList.add(
                        "unavailable"
                    );
                }


                const availableButton =
                    card.querySelector(
                        ".available-button"
                    );

                const unavailableButton =
                    card.querySelector(
                        ".unavailable-button"
                    );


                availableButton.classList.toggle(
                    "selected",
                    availability === 1
                );

                unavailableButton.classList.toggle(
                    "selected",
                    availability === 0
                );


                const imageContainer =
                    card.querySelector(
                        ".food-image"
                    );


                const existingOverlay =
                    imageContainer.querySelector(
                        ".unavailable-overlay"
                    );


                if (availability === 0) {

                    if (!existingOverlay) {

                        const overlay =
                            document.createElement("div");

                        overlay.className =
                            "unavailable-overlay";

                        overlay.textContent =
                            "NOT AVAILABLE";

                        imageContainer.appendChild(
                            overlay
                        );
                    }

                } else {

                    if (existingOverlay) {
                        existingOverlay.remove();
                    }
                }
            }


            showMessage(
                result.message
            );

        } else {

            showMessage(
                result.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Unable to change food availability.",
            "error"
        );
    }
}


/* DELETE FOOD */

function deleteItem(foodId) {

    const card =
        document.querySelector(
            `.food-card[data-id="${foodId}"]`
        );

    if (!card) {
        return;
    }


    const name =
        card.querySelector("h3")
            .textContent
            .trim();


    deletingFoodId =
        foodId;


    document.getElementById(
        "deleteMessage"
    ).textContent =
        `Are you sure you want to delete "${name}"?`;


    document.getElementById(
        "deleteModal"
    ).classList.add("show");
}


/* CLOSE DELETE MODAL */

function closeDeleteModal() {

    deletingFoodId = null;

    document.getElementById(
        "deleteModal"
    ).classList.remove("show");
}


/* CONFIRM DELETE */

async function confirmDelete() {

    if (!deletingFoodId) {
        return;
    }


    const formData =
        new FormData();

    formData.append(
        "action",
        "delete"
    );

    formData.append(
        "food_id",
        deletingFoodId
    );


    try {

        const response =
            await fetch("menu-admin.php", {
                method: "POST",
                body: formData
            });


        const result =
            await response.json();


        if (result.success) {

            const card =
                document.querySelector(
                    `.food-card[data-id="${deletingFoodId}"]`
                );


            if (card) {
                card.remove();
            }


            closeDeleteModal();


            showMessage(
                result.message
            );


            applyFilters();

        } else {

            showMessage(
                result.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Unable to delete the food item.",
            "error"
        );
    }
}


/* SEARCH + CATEGORY FILTER */

function applyFilters() {

    const search =
        document.getElementById("searchInput")
            .value
            .toLowerCase()
            .trim();


    const category =
        document.getElementById("categoryFilter")
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

            card.style.display = "";

            visibleCount++;

        } else {

            card.style.display = "none";
        }
    });


    const noResults =
        document.getElementById(
            "noResults"
        );


    if (visibleCount === 0) {

        noResults.style.display =
            "block";

    } else {

        noResults.style.display =
            "none";
    }
}


/* MESSAGE */

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
        document.createElement("div");


    notification.className =
        "temporary-message";


    notification.textContent =
        message;


    notification.classList.add(
        type === "error"
            ? "message-error"
            : "message-success"
    );


    document.body.appendChild(
        notification
    );


    setTimeout(() => {

        notification.remove();

    }, 2500);
}


/* CLOSE MODALS WHEN CLICKING OUTSIDE */

document.addEventListener(
    "click",
    function(event) {

        const menuModal =
            document.getElementById(
                "menuModal"
            );

        const deleteModal =
            document.getElementById(
                "deleteModal"
            );

        const logoutModal =
            document.getElementById(
                "logoutModal"
            );


        if (
            event.target === menuModal
        ) {

            closeModal();
        }


        if (
            event.target === deleteModal
        ) {

            closeDeleteModal();
        }


        if (
            event.target === logoutModal
        ) {

            closeLogoutModal();
        }
    }
);


/* ESC KEY */

document.addEventListener(
    "keydown",
    function(event) {

        if (event.key !== "Escape") {
            return;
        }


        const menuModal =
            document.getElementById(
                "menuModal"
            );

        const deleteModal =
            document.getElementById(
                "deleteModal"
            );

        const logoutModal =
            document.getElementById(
                "logoutModal"
            );


        if (
            menuModal.classList.contains("show")
        ) {

            closeModal();
        }


        if (
            deleteModal.classList.contains("show")
        ) {

            closeDeleteModal();
        }


        if (
            logoutModal.classList.contains("show")
        ) {

            closeLogoutModal();
        }
    }
);


/* LOGOUT CONFIRMATION */

function openLogoutModal(event) {

    event.preventDefault();

    document.getElementById("logoutModal")
        .classList.add("show");
}


/* CLOSE LOGOUT MODAL */

function closeLogoutModal() {

    document.getElementById("logoutModal")
        .classList.remove("show");
}


/* CONFIRM LOGOUT */

function confirmLogout() {

    window.location.href =
        "../auth/log_out_admin.php";
}

/* =========================================================
   BACK TO TOP BUTTON
   ========================================================= */

const backToTopButton =
    document.getElementById("backToTopButton");


/* SHOW / HIDE BUTTON WHEN SCROLLING */

window.addEventListener("scroll", function () {

    if (window.scrollY > 300) {

        backToTopButton.classList.add("show");

    } else {

        backToTopButton.classList.remove("show");
    }

});


/* SCROLL TO TOP */

backToTopButton.addEventListener("click", function () {

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

});