/* admin_register.js */


/* =========================================
   PASSWORD SHOW / HIDE
========================================= */

function setupPasswordToggle(inputId, buttonId) {

    const passwordInput =
        document.getElementById(inputId);

    const toggleButton =
        document.getElementById(buttonId);


    if (!passwordInput || !toggleButton) {
        return;
    }


    toggleButton.addEventListener(
        "click",
        function () {

            if (passwordInput.type === "password") {

                passwordInput.type = "text";

                toggleButton.textContent = "✖️";

                toggleButton.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                passwordInput.type = "password";

                toggleButton.textContent = "👁️";

                toggleButton.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        }
    );

}


/* =========================================
   PASSWORD MATCH CHECKING
========================================= */

function checkPasswordMatch() {

    const password =
        document.getElementById("password");

    const repeatPassword =
        document.getElementById("repeat_password");

    const message =
        document.getElementById(
            "passwordMatchMessage"
        );


    if (
        !password ||
        !repeatPassword ||
        !message
    ) {
        return;
    }


    const passwordValue =
        password.value;

    const repeatPasswordValue =
        repeatPassword.value;


    /* NOTHING ENTERED */

    if (repeatPasswordValue.length === 0) {

        message.textContent = "";

        message.className =
            "password-match-message";

        repeatPassword.classList.remove(
            "password-valid",
            "password-invalid"
        );

        return;
    }


    /* PASSWORD MATCH */

    if (
        passwordValue ===
        repeatPasswordValue
    ) {

        message.textContent =
            "✓ Passwords match.";

        message.className =
            "password-match-message match";

        repeatPassword.classList.remove(
            "password-invalid"
        );

        repeatPassword.classList.add(
            "password-valid"
        );

    }


    /* PASSWORD DOES NOT MATCH */

    else {

        message.textContent =
            "✖ Passwords do not match.";

        message.className =
            "password-match-message no-match";

        repeatPassword.classList.remove(
            "password-valid"
        );

        repeatPassword.classList.add(
            "password-invalid"
        );

    }

}


/* =========================================
   FORM VALIDATION
========================================= */

function validateRegistrationForm(event) {

    const password =
        document.getElementById("password");

    const repeatPassword =
        document.getElementById("repeat_password");


    if (
        password.value !==
        repeatPassword.value
    ) {

        event.preventDefault();

        checkPasswordMatch();

        repeatPassword.focus();

        return false;

    }

    return true;

}


/* =========================================
   HIDE PHP MESSAGE AFTER 4 SECONDS
========================================= */

function setupMessageTimeout() {

    const message =
        document.getElementById("formMessage");


    if (!message) {
        return;
    }


    setTimeout(
        function () {

            message.classList.add(
                "hide-message"
            );


            /* Completely remove after animation */

            setTimeout(
                function () {

                    if (message) {
                        message.remove();
                    }

                },
                400
            );

        },
        4000
    );

}


/* =========================================
   PAGE INITIALIZATION
========================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* PASSWORD TOGGLE */

        setupPasswordToggle(
            "password",
            "passwordToggle"
        );


        /* REPEAT PASSWORD TOGGLE */

        setupPasswordToggle(
            "repeat_password",
            "repeatPasswordToggle"
        );


        /* PASSWORD MATCH WHILE TYPING */

        const password =
            document.getElementById("password");

        const repeatPassword =
            document.getElementById(
                "repeat_password"
            );


        if (password) {

            password.addEventListener(
                "input",
                checkPasswordMatch
            );

        }


        if (repeatPassword) {

            repeatPassword.addEventListener(
                "input",
                checkPasswordMatch
            );

        }


        /* FORM VALIDATION */

        const form =
            document.getElementById(
                "registerForm"
            );


        if (form) {

            form.addEventListener(
                "submit",
                validateRegistrationForm
            );

        }


        /* AUTO HIDE MESSAGE */

        setupMessageTimeout();

    }
);