/* =========================================================
   GLOBAL ADMIN DARK MODE
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const darkModeButton =
        document.getElementById("darkModeToggle");

    const root =
        document.documentElement;


    if (!darkModeButton) {
        return;
    }


    /* =====================================================
       LOAD SAVED MODE
    ===================================================== */

    const savedMode =
        localStorage.getItem("adminDarkMode");


    if (savedMode === "enabled") {

        root.classList.add("dark-mode");

        darkModeButton.textContent = "🌑";

    } else {

        root.classList.remove("dark-mode");

        darkModeButton.textContent = "☀️";

    }


    /* =====================================================
       BUTTON CLICK
    ===================================================== */

    darkModeButton.addEventListener(
        "click",
        function () {

            const darkModeEnabled =
                root.classList.toggle("dark-mode");


            /* =============================================
               DARK MODE ENABLED
            ============================================= */

            if (darkModeEnabled) {

                localStorage.setItem(
                    "adminDarkMode",
                    "enabled"
                );

                darkModeButton.textContent = "🌑";


            /* =============================================
               LIGHT MODE ENABLED
            ============================================= */

            } else {

                localStorage.setItem(
                    "adminDarkMode",
                    "disabled"
                );

                darkModeButton.textContent = "☀️";

            }

        }
    );

        /* =====================================================
       HOVER TOOLTIP CHANGE
    ===================================================== */
    darkModeButton.addEventListener("mouseenter", function () {
        if (darkModeButton.textContent === "🌑") {
            darkModeButton.title = "Toggle to light mode";
        } else {
            darkModeButton.title = "Toggle to dark mode";
        }
    });

    darkModeButton.addEventListener("mouseleave", function () {
        darkModeButton.title = "Toggle dark mode";
    });


});