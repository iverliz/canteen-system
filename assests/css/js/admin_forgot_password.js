document.addEventListener("DOMContentLoaded", function () {

    /* ==============================
       SHOW / HIDE PASSWORD
    ============================== */

    const passwordButtons =
        document.querySelectorAll(".password-toggle");

    passwordButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const targetId =
                button.getAttribute("data-target");

            const passwordInput =
                document.getElementById(targetId);

            if (!passwordInput) {
                return;
            }

            if (passwordInput.type === "password") {

                /* SHOW PASSWORD */

                passwordInput.type = "text";

                button.textContent = "✖️";

                button.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                /* HIDE PASSWORD */

                passwordInput.type = "password";

                button.textContent = "👁️";

                button.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        });

    });


    /* ==============================
       PASSWORD MATCH CHECK
    ============================== */

    const newPassword =
        document.getElementById("new_password");

    const repeatPassword =
        document.getElementById("repeat_password");

    const matchMessage =
        document.getElementById(
            "password-match-message"
        );


    function checkPasswordMatch() {

        if (
            !newPassword ||
            !repeatPassword ||
            !matchMessage
        ) {
            return;
        }


        if (
            repeatPassword.value === "" ||
            newPassword.value === ""
        ) {

            matchMessage.textContent = "";

            repeatPassword.classList.remove(
                "password-match",
                "password-no-match"
            );

            return;
        }


        if (
            newPassword.value ===
            repeatPassword.value
        ) {

            matchMessage.textContent =
                "✓ Passwords match.";

            matchMessage.className =
                "password-match-message match";

            repeatPassword.classList.remove(
                "password-no-match"
            );

            repeatPassword.classList.add(
                "password-match"
            );

        } else {

            matchMessage.textContent =
                "✖ Passwords do not match.";

            matchMessage.className =
                "password-match-message no-match";

            repeatPassword.classList.remove(
                "password-match"
            );

            repeatPassword.classList.add(
                "password-no-match"
            );

        }

    }


    if (newPassword && repeatPassword) {

        newPassword.addEventListener(
            "input",
            checkPasswordMatch
        );

        repeatPassword.addEventListener(
            "input",
            checkPasswordMatch
        );

    }


    /* ==============================
       AUTO HIDE SYSTEM MESSAGE
       AFTER 4 SECONDS
    ============================== */

    const systemMessage =
        document.querySelector(".message");


    if (systemMessage) {

        /*
         * Wait 4 seconds before starting
         * the fade-out animation.
         */

        setTimeout(function () {

            systemMessage.classList.add(
                "message-hide"
            );


            /*
             * Completely remove the message
             * after the 300ms CSS animation.
             */

            setTimeout(function () {

                if (systemMessage) {
                    systemMessage.remove();
                }

            }, 300);

        }, 4000);

    }


    /* ==============================
       FORM SUBMISSION CHECK
    ============================== */

    const form =
        document.getElementById(
            "resetPasswordForm"
        );


    if (form) {

        form.addEventListener(
            "submit",
            function (event) {

                if (
                    newPassword.value !==
                    repeatPassword.value
                ) {

                    event.preventDefault();

                    alert(
                        "The new password and repeat password do not match."
                    );

                    repeatPassword.focus();

                    return;
                }


                if (
                    newPassword.value.length < 6
                ) {

                    event.preventDefault();

                    alert(
                        "The new password must be at least 6 characters."
                    );

                    newPassword.focus();

                    return;
                }

            }
        );

    }

});