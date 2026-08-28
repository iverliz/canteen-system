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
       MESSAGE FUNCTION
    ============================== */

    function showMessage(messageText, messageType) {

        /*
         * Check if a message already exists.
         */

        let message =
            document.querySelector(".message");


        /*
         * If there is no message element,
         * create one.
         */

        if (!message) {

            message =
                document.createElement("div");

            message.className = "message";

            /*
             * Insert the message before
             * the form.
             */

            const form =
                document.getElementById(
                    "resetPasswordForm"
                );

            form.parentNode.insertBefore(
                message,
                form
            );
        }


        /*
         * Set the message type.
         */

        message.className =
            "message " + messageType;


        /*
         * Set the message text.
         */

        message.textContent =
            messageText;


        /*
         * Make sure the message is visible.
         */

        message.classList.remove(
            "message-hide"
        );

        message.style.display = "block";

        message.style.opacity = "1";

        message.style.transform =
            "translateY(0)";


        /*
         * Automatically hide after
         * 4 seconds.
         */

        setTimeout(function () {

            message.classList.add(
                "message-hide"
            );


            /*
             * Remove after the
             * 300ms animation.
             */

            setTimeout(function () {

                if (message) {
                    message.remove();
                }

            }, 300);

        }, 4000);

    }


    /* ==============================
       AUTO HIDE PHP SYSTEM MESSAGE
       AFTER 4 SECONDS
    ============================== */

    const systemMessage =
        document.querySelector(".message");


    if (systemMessage) {

        setTimeout(function () {

            systemMessage.classList.add(
                "message-hide"
            );


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

                /*
                 * PASSWORDS DO NOT MATCH
                 */

                if (
                    newPassword.value !==
                    repeatPassword.value
                ) {

                    event.preventDefault();


                    showMessage(
                        "The new password and repeat password do not match.",
                        "error"
                    );


                    repeatPassword.focus();

                    return;
                }


                /*
                 * PASSWORD TOO SHORT
                 */

                if (
                    newPassword.value.length < 6
                ) {

                    event.preventDefault();


                    showMessage(
                        "The new password must be at least 6 characters.",
                        "error"
                    );


                    newPassword.focus();

                    return;
                }

            }
        );

    }

});