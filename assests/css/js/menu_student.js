document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    if (profileBtn && profileDropdown) {

        profileBtn.addEventListener("click", function (event) {
            event.stopPropagation();
            profileDropdown.classList.toggle("show");
        });

        profileDropdown.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        document.addEventListener("click", function () {
            profileDropdown.classList.remove("show");
        });

    }

    const categoryButtons = document.querySelectorAll(".category-button");
    const foodCards = document.querySelectorAll(".food-card");
    const foodSearch = document.getElementById("foodSearch");
    const noResults = document.getElementById("noResults");

    let selectedCategory = "All";

    function applyFilters() {

        const text = foodSearch ? foodSearch.value.toLowerCase().trim() : "";
        let visible = 0;

        foodCards.forEach(function (card) {

            const matchesCategory =
                selectedCategory === "All" ||
                card.dataset.category === selectedCategory;

            const matchesText = card
                .querySelector("h3")
                .textContent.toLowerCase()
                .includes(text);

            const show = matchesCategory && matchesText;

            card.style.display = show ? "" : "none";

            if (show) {
                visible++;
            }

        });

        if (noResults) {
            noResults.hidden = visible !== 0;
        }

    }

    categoryButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            selectedCategory = this.dataset.category;

            categoryButtons.forEach(function (btn) {
                const active = btn === button;
                btn.classList.toggle("active", active);
                btn.setAttribute("aria-pressed", active ? "true" : "false");
            });

            applyFilters();

        });

    });

    if (foodSearch) {
        foodSearch.addEventListener("input", applyFilters);
    }

    const myOrderContent = document.getElementById("myOrderContent");
    const orderTotal = document.getElementById("orderTotal");
    const checkoutButton = document.getElementById("checkoutButton");
    const cartCount = document.getElementById("cartCount");
    const cartToggle = document.getElementById("cartToggle");
    const cartClose = document.getElementById("cartClose");
    const cartOverlay = document.getElementById("cartOverlay");

    let orders = [];

    function escapeHtml(value) {

        const map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;"
        };

        return String(value).replace(/[&<>"']/g, function (char) {
            return map[char];
        });

    }

    function setCartOpen(open) {
        document.body.classList.toggle("cart-open", open);
        cartToggle.setAttribute("aria-expanded", open ? "true" : "false");
    }

    cartToggle.addEventListener("click", function () {
        setCartOpen(true);
    });

    cartClose.addEventListener("click", function () {
        setCartOpen(false);
    });

    cartOverlay.addEventListener("click", function () {
        setCartOpen(false);
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            setCartOpen(false);
        }
    });

    document.querySelectorAll(".add-order-button").forEach(function (button) {

        button.addEventListener("click", function () {

            const foodCard = this.closest(".food-card");
            const foodId = this.dataset.id;
            const foodName = foodCard.querySelector("h3").textContent.trim();
            const foodPrice = parseFloat(foodCard.querySelector(".food-price").dataset.price);

            const existingItem = orders.find(function (item) {
                return item.id === foodId;
            });

            if (existingItem) {
                existingItem.quantity++;
            } else {
                orders.push({ id: foodId, name: foodName, price: foodPrice, quantity: 1 });
            }

            updateOrder();

            clearTimeout(button.addedTimer);
            button.textContent = "Added ✓";

            button.addedTimer = setTimeout(function () {
                button.textContent = "Add to Order";
            }, 800);

        });

    });

    function updateOrder() {

        myOrderContent.innerHTML = "";

        let total = 0;
        let count = 0;

        if (orders.length === 0) {
            myOrderContent.innerHTML = `<div class="empty-order"><p>Your order is empty.</p></div>`;
        }

        orders.forEach(function (item, index) {

            const itemTotal = item.price * item.quantity;

            total += itemTotal;
            count += item.quantity;

            const orderItem = document.createElement("div");
            orderItem.className = "order-item";

            orderItem.innerHTML = `
                <div class="order-item-info">
                    <strong>${escapeHtml(item.name)}</strong>
                    <span>₱${item.price.toFixed(2)}</span>
                </div>
                <div class="order-item-controls">
                    <button type="button" class="quantity-button decrease" data-index="${index}" aria-label="Decrease quantity">−</button>
                    <span class="quantity">${item.quantity}</span>
                    <button type="button" class="quantity-button increase" data-index="${index}" aria-label="Increase quantity">+</button>
                    <button type="button" class="remove-button" data-index="${index}" aria-label="Remove item">×</button>
                </div>
                <div class="order-item-total">₱${itemTotal.toFixed(2)}</div>
            `;

            myOrderContent.appendChild(orderItem);

        });

        orderTotal.textContent = "₱" + total.toFixed(2);

        cartCount.textContent = count;
        cartCount.classList.toggle("has-items", count > 0);

    }

    myOrderContent.addEventListener("click", function (event) {

        const button = event.target.closest("button[data-index]");

        if (!button) {
            return;
        }

        const index = parseInt(button.dataset.index, 10);

        if (!orders[index]) {
            return;
        }

        if (button.classList.contains("increase")) {
            orders[index].quantity++;
        } else if (button.classList.contains("decrease")) {
            if (orders[index].quantity > 1) {
                orders[index].quantity--;
            } else {
                orders.splice(index, 1);
            }
        } else if (button.classList.contains("remove-button")) {
            orders.splice(index, 1);
        }

        updateOrder();

    });

    checkoutButton.addEventListener("click", function () {

        if (orders.length === 0) {
            alert("Your order is empty.");
            return;
        }

        checkoutButton.disabled = true;
        checkoutButton.textContent = "Processing...";

        fetch("checkout.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ orders: orders })
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {

                if (data.success) {
                    orders = [];
                    updateOrder();
                    window.location.href = "receipt.php";
                } else {
                    alert(data.message || "Something went wrong.");
                    checkoutButton.disabled = false;
                    checkoutButton.textContent = "Checkout";
                }

            })
            .catch(function () {
                alert("Something went wrong. Please try again.");
                checkoutButton.disabled = false;
                checkoutButton.textContent = "Checkout";
            });

    });

    window.addEventListener("pageshow", function () {
        checkoutButton.disabled = false;
        checkoutButton.textContent = "Checkout";
    });

    updateOrder();

});