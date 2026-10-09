
    document.addEventListener("DOMContentLoaded", function () {

        const studentIdInput = document.getElementById("student_id");
        const passwordInput = document.getElementById("password");

        if (studentIdInput) {

            const blockedCharacters = /\D/g;

            studentIdInput.addEventListener("input", function () {

                const original = studentIdInput.value;
                const cleaned = original.replace(blockedCharacters, "");

                if (cleaned === original) {
                    return;
                }

                const removed = original.length - cleaned.length;
                const caret = Math.max(0, studentIdInput.selectionStart - removed);

                studentIdInput.value = cleaned;
                studentIdInput.setSelectionRange(caret, caret);

            });

        }

        if (passwordInput) {

            const box = document.createElement("div");
            box.style.position = "relative";

            passwordInput.parentNode.insertBefore(box, passwordInput);
            box.appendChild(passwordInput);

            passwordInput.style.display = "block";
            passwordInput.style.paddingRight = "64px";

            const toggle = document.createElement("button");
            toggle.type = "button";
            toggle.textContent = "Show";
            toggle.setAttribute("aria-label", "Show password");
            toggle.style.cssText =
                "position:absolute;top:0;bottom:0;right:12px;border:none;" +
                "background:none;font-size:12px;font-weight:600;color:#6b5a3a;cursor:pointer;";

            box.appendChild(toggle);

            toggle.addEventListener("click", function () {

                const show = passwordInput.type === "password";

                passwordInput.type = show ? "text" : "password";
                toggle.textContent = show ? "Hide" : "Show";
                toggle.setAttribute("aria-label", show ? "Hide password" : "Show password");

            });

        }

    });
