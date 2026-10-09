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

    const chips = document.querySelectorAll(".filter-chip");
    const cards = document.querySelectorAll(".order-card");

    chips.forEach(function (chip) {

        chip.addEventListener("click", function () {

            const filter = this.dataset.filter;

            chips.forEach(function (other) {
                other.classList.remove("active");
                other.setAttribute("aria-pressed", "false");
            });

            this.classList.add("active");
            this.setAttribute("aria-pressed", "true");

            cards.forEach(function (card) {
                const show = (filter === "all") || (card.dataset.status === filter);
                card.style.display = show ? "" : "none";
            });

        });

    });

});