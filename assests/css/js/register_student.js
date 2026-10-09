 document.addEventListener("DOMContentLoaded", function () {

        const studentIdInput = document.getElementById("student_id");

        if (!studentIdInput) {
            return;
        }

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

    });