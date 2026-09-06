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

        darkModeButton.textContent = "☀️";

    } else {

        root.classList.remove("dark-mode");

        darkModeButton.textContent = "🌑";

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

                darkModeButton.textContent = "☀️";


            /* =============================================
               LIGHT MODE ENABLED
            ============================================= */

            } else {

                localStorage.setItem(
                    "adminDarkMode",
                    "disabled"
                );

                darkModeButton.textContent = "🌑";

            }

        }
    );

});