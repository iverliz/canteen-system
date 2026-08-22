/* dashboard-admin.js */
const dashboardData = {

    completedOrders: 0,
    totalSales: 0,
    pendingOrders: 0

};


/* LOAD DASHBOARD DATA */

function loadDashboard() {

    document.getElementById(
        "completedOrders"
    ).textContent =
        dashboardData.completedOrders;


    document.getElementById(
        "totalSales"
    ).textContent =
        "₱" +
        dashboardData.totalSales.toFixed(2);


    document.getElementById(
        "pendingOrders"
    ).textContent =
        dashboardData.pendingOrders;

}


/* OPEN NOTE MODAL*/

function openNoteModal() {

    document
        .getElementById("noteModal")
        .classList.add("show");


    document
        .getElementById("noteForm")
        .reset();

}


/* CLOSE NOTE MODAL */

function closeNoteModal() {

    document
        .getElementById("noteModal")
        .classList.remove("show");

}


/* ADD NOTE */

function addNote(event) {

    event.preventDefault();


    const title =
        document
            .getElementById("noteTitle")
            .value
            .trim();


    const description =
        document
            .getElementById("noteDescription")
            .value
            .trim();


    if (!title || !description) {

        return;

    }


    const noteList =
        document.getElementById(
            "notesList"
        );


    const note =
        document.createElement("div");


    note.className =
        "note-item";


    note.innerHTML = `

        <div class="note-icon">
            !
        </div>


        <div class="note-content">

            <h3>
                ${escapeHTML(title)}
            </h3>

            <p>
                ${escapeHTML(description)}
            </p>

        </div>


        <button
            class="delete-note"
            onclick="deleteNote(this)"
        >
            ×
        </button>

    `;


    noteList.appendChild(note);


    closeNoteModal();

}


/* DELETE NOTE */

function deleteNote(button) {

    const note =
        button.closest(".note-item");


    if (!note) {
        return;
    }


    const confirmed =
        confirm(
            "Are you sure you want to delete this note?"
        );


    if (!confirmed) {
        return;
    }


    note.remove();

}


/* ESCAPE HTML */

function escapeHTML(text) {

    const div =
        document.createElement("div");


    div.textContent =
        text;


    return div.innerHTML;

}


/* CLICK OUTSIDE MODAL */

document
    .getElementById("noteModal")
    .addEventListener(
        "click",
        function(event) {

            if (
                event.target === this
            ) {

                closeNoteModal();

            }

        }
    );


/* ESC KEY */

document.addEventListener(
    "keydown",
    function(event) {

        if (
            event.key === "Escape"
        ) {

            closeNoteModal();

        }

    }
);


/* INITIALIZE */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        loadDashboard();

    }
);