/* admin_login.js */

document.addEventListener("DOMContentLoaded", function () {

    /* ==============================
       SHOW / HIDE PASSWORD
    ============================== */

    const passwordInput =
        document.getElementById("password");

    const togglePassword =
        document.getElementById("togglePassword");


    if (passwordInput && togglePassword) {

        togglePassword.addEventListener("click", function () {

            if (passwordInput.type === "password") {

                passwordInput.type = "text";

                togglePassword.textContent = "✖️";

                togglePassword.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                passwordInput.type = "password";

                togglePassword.textContent = "👁️";

                togglePassword.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        });

    }


    /* ==============================
       AUTO HIDE LOGIN MESSAGE
       AFTER 4 SECONDS
    ============================== */

    const loginError =
        document.getElementById("loginError");


    if (loginError) {

        setTimeout(function () {

            /* Fade out */

            loginError.style.opacity = "0";

            loginError.style.transform =
                "translateY(-5px)";


            /* Completely remove after animation */

            setTimeout(function () {

                loginError.style.display = "none";

            }, 350);

        }, 4000);

    }

});