/* =========================================================
   dashboard-admin.js
========================================================= */


/* =========================================================
   VARIABLES
========================================================= */

let noteToDelete = null;

/* =========================================================
   GET RESPONSIVE DATE LABEL
========================================================= */

function getResponsiveDateLabel(label) {

    if (window.innerWidth <= 830) {

        return label.split(" ")[0];

    }

    return label;

}

/* =========================================================
   LOAD DASHBOARD STATISTICS
========================================================= */

async function loadDashboardStats() {

    try {

        const response = await fetch(
            "dashboard-data.php"
        );

        const data = await response.json();


        if (!data.success) {
            return;
        }


        document.getElementById(
            "completedOrders"
        ).textContent =
            data.completedOrders;


        document.getElementById(
            "pendingOrders"
        ).textContent =
            data.pendingOrders;


        document.getElementById(
            "totalSales"
        ).textContent =
            "₱" +
            Number(data.totalSales).toLocaleString(
                "en-PH",
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    } catch (error) {

        console.error(
            "Failed to load dashboard statistics:",
            error
        );

    }

}


/* =========================================================
   LOAD SALES REPORT
========================================================= */

async function loadSalesReport() {

    const month =
        document.getElementById(
            "monthSelector"
        ).value;


    const week =
        document.getElementById(
            "weekSelector"
        ).value;


    try {

        const response = await fetch(
            "sales-report.php?month=" +
            encodeURIComponent(month) +
            "&week=" +
            encodeURIComponent(week)
        );


        const data =
            await response.json();


        if (!data.success) {

            console.error(
                data.message
            );

            return;
        }


        renderSalesChart(
            data.days
        );


    } catch (error) {

        console.error(
            "Failed to load sales report:",
            error
        );

    }

}


/* =========================================================
   RENDER SALES CHART
========================================================= */

function renderSalesChart(days) {   

    const yAxis =
        document.getElementById(
            "yAxis"
        );


    const chartGrid =
        document.getElementById(
            "chartGrid"
        );


    const salesBars =
        document.getElementById(
            "salesBars"
        );


    yAxis.innerHTML = "";

    chartGrid.innerHTML = "";

    salesBars.innerHTML = "";


    /* -----------------------------------------------------
       FIND HIGHEST VALUE
    ----------------------------------------------------- */

    let highestValue = 0;


    days.forEach(
        day => {

            const sales =
                Number(day.sales);


            if (
                sales >
                highestValue
            ) {

                highestValue =
                    sales;

            }

        }
    );


    /* -----------------------------------------------------
       DYNAMIC Y-AXIS
    ----------------------------------------------------- */

    let maxValue;


    if (highestValue <= 0) {

        maxValue = 1000;

    } else {

        maxValue =
            Math.ceil(
                highestValue / 100
            ) * 100;


        /*
         * If sales are exactly 1000,
         * keep the maximum at 1000.
         */

        if (maxValue < 1000) {
            maxValue = 1000;
        }

    }


    const steps = 5;

    const stepValue =
        maxValue / steps;


    for (
        let i = steps;
        i >= 0;
        i--
    ) {

        const value =
            stepValue * i;


        const label =
            document.createElement(
                "span"
            );


        label.textContent =
            "₱" +
            Number(value).toLocaleString(
                "en-PH",
                {
                    maximumFractionDigits: 0
                }
            );


        yAxis.appendChild(
            label
        );


        const gridLine =
            document.createElement(
                "span"
            );


        chartGrid.appendChild(
            gridLine
        );

    }


    /* -----------------------------------------------------
       CREATE BARS
    ----------------------------------------------------- */

    days.forEach(
        day => {

            const column =
                document.createElement(
                    "div"
                );


            column.className =
                "bar-column";


            const bar =
                document.createElement(
                    "div"
                );


            bar.className =
                "bar";


            const sales =
                Number(day.sales);


            let height = 0;


            if (
                maxValue > 0
            ) {

                height =
                    (
                        sales /
                        maxValue
                    ) * 100;

            }


            /*
             * Do not make zero-sales
             * bars visible.
             */

            if (sales > 0) {

                height =
                    Math.max(
                        height,
                        2
                    );

            }


            bar.style.height =
                height + "%";


            bar.style.background =
                getBarColor(
                    day.dayIndex
                );


            const amount =
                document.createElement(
                    "span"
                );


            amount.textContent =
                "₱" +
                sales.toLocaleString(
                    "en-PH",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            bar.appendChild(
                amount
            );


            const dateLabel =
            document.createElement(
                "small"
            );


        dateLabel.textContent =
            getResponsiveDateLabel(
                day.label
            );


            column.appendChild(
                bar
            );


            column.appendChild(
                dateLabel
            );


            salesBars.appendChild(
                column
            );

        }
    );

}


/* =========================================================
   BAR COLORS
========================================================= */

function getBarColor(index) {

    const colors = [

        "#f97316",
        "#fb923c",
        "#f59e0b",
        "#eab308",
        "#84cc16",
        "#22c55e",
        "#14b8a6"

    ];


    return colors[
        index % colors.length
    ];

}


/* =========================================================
   OPEN NOTE MODAL
========================================================= */

function openNoteModal() {

    document
        .getElementById("noteModal")
        .classList.add("show");


    document
        .getElementById("noteForm")
        .reset();


    setTimeout(
        function() {

            document
                .getElementById("noteTitle")
                .focus();

        },
        100
    );

}


/* =========================================================
   CLOSE NOTE MODAL
========================================================= */

function closeNoteModal() {

    document
        .getElementById("noteModal")
        .classList.remove("show");

}


/* =========================================================
   ADD NOTE
========================================================= */

async function addNote(event) {

    event.preventDefault();


    const title =
        document
            .getElementById("noteTitle")
            .value
            .trim();


    const description =
        document
            .getElementById(
                "noteDescription"
            )
            .value
            .trim();


    if (
        !title ||
        !description
    ) {

        return;

    }


    const formData =
        new FormData();


    formData.append(
        "action",
        "add_note"
    );


    formData.append(
        "note_title",
        title
    );


    formData.append(
        "note_description",
        description
    );


    try {

        const response =
            await fetch(
                "dashboard-admin.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const data =
            await response.json();


        if (!data.success) {

            alert(
                data.message ||
                "Failed to add note."
            );

            return;
        }


        closeNoteModal();


        addNoteToList(
            data.note
        );


    } catch (error) {

        console.error(
            error
        );

        alert(
            "Something went wrong while adding the note."
        );

    }

}


/* =========================================================
   ADD NOTE TO UI
========================================================= */

function addNoteToList(note) {

    const noteList =
        document.getElementById(
            "notesList"
        );


    const emptyNotes =
        document.getElementById(
            "emptyNotes"
        );


    if (emptyNotes) {

        emptyNotes.remove();

    }


    const noteElement =
        document.createElement(
            "div"
        );


    noteElement.className =
        "note-item";


    noteElement.dataset.noteId =
        note.note_id;


    noteElement.innerHTML = `

        <div class="note-icon">
            !
        </div>

        <div class="note-content">

            <h3>
                ${escapeHTML(note.note_title)}
            </h3>

            <p>
                ${escapeHTML(note.note_description)}
            </p>

        </div>

        <button
            type="button"
            class="delete-note"
            onclick="deleteNote(${note.note_id})"
            title="Delete note"
        >
            ×
        </button>

    `;


    /*
     * Newest note appears first.
     */

    noteList.prepend(
        noteElement
    );

}


/* =========================================================
   DELETE NOTE - OPEN CONFIRMATION
========================================================= */

function deleteNote(noteId) {

    noteToDelete =
        noteId;


    document
        .getElementById(
            "deleteNoteModal"
        )
        .classList.add("show");

}


/* =========================================================
   CLOSE DELETE NOTE MODAL
========================================================= */

function closeDeleteNoteModal() {

    noteToDelete = null;


    document
        .getElementById(
            "deleteNoteModal"
        )
        .classList.remove("show");

}


/* =========================================================
   CONFIRM DELETE NOTE
========================================================= */

async function confirmDeleteNote() {

    if (!noteToDelete) {

        return;

    }


    const noteId =
        noteToDelete;


    const formData =
        new FormData();


    formData.append(
        "action",
        "delete_note"
    );


    formData.append(
        "note_id",
        noteId
    );


    try {

        const response =
            await fetch(
                "dashboard-admin.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const data =
            await response.json();


        if (!data.success) {

            alert(
                data.message ||
                "Failed to delete note."
            );

            return;
        }


        const note =
            document.querySelector(
                `.note-item[data-note-id="${noteId}"]`
            );


        if (note) {

            note.remove();

        }


        closeDeleteNoteModal();


        checkEmptyNotes();


    } catch (error) {

        console.error(
            error
        );

        alert(
            "Something went wrong while deleting the note."
        );

    }

}


/* =========================================================
   CHECK EMPTY NOTES
========================================================= */

function checkEmptyNotes() {

    const noteList =
        document.getElementById(
            "notesList"
        );


    const notes =
        noteList.querySelectorAll(
            ".note-item"
        );


    if (
        notes.length === 0
    ) {

        noteList.innerHTML = `

            <div
                class="empty-notes"
                id="emptyNotes"
            >

                <div>
                    📝
                </div>

                <h3>
                    No notes yet
                </h3>

                <p>
                    Add a note or reminder for the canteen.
                </p>

            </div>

        `;

    }

}


/* =========================================================
   LOGOUT MODAL
========================================================= */

function openLogoutModal(event) {

    if (event) {

        event.preventDefault();

    }


    document
        .getElementById(
            "logoutModal"
        )
        .classList.add("show");

}


function closeLogoutModal() {

    document
        .getElementById(
            "logoutModal"
        )
        .classList.remove("show");

}


function confirmLogout() {

    window.location.href =
        "../auth/log_out_admin.php";

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHTML(text) {

    const div =
        document.createElement(
            "div"
        );


    div.textContent =
        text;


    return div.innerHTML;

}


/* =========================================================
   MODAL CLICK OUTSIDE
========================================================= */

document.addEventListener(
    "click",
    function(event) {

        const noteModal =
            document.getElementById(
                "noteModal"
            );


        const deleteModal =
            document.getElementById(
                "deleteNoteModal"
            );


        const logoutModal =
            document.getElementById(
                "logoutModal"
            );


        if (
            event.target ===
            noteModal
        ) {

            closeNoteModal();

        }


        if (
            event.target ===
            deleteModal
        ) {

            closeDeleteNoteModal();

        }


        if (
            event.target ===
            logoutModal
        ) {

            closeLogoutModal();

        }

    }
);


/* =========================================================
   ESC KEY
========================================================= */

document.addEventListener(
    "keydown",
    function(event) {

        if (
            event.key !==
            "Escape"
        ) {

            return;

        }


        const noteModal =
            document.getElementById(
                "noteModal"
            );


        const deleteModal =
            document.getElementById(
                "deleteNoteModal"
            );


        const logoutModal =
            document.getElementById(
                "logoutModal"
            );


        if (
            noteModal.classList.contains(
                "show"
            )
        ) {

            closeNoteModal();

        }


        if (
            deleteModal.classList.contains(
                "show"
            )
        ) {

            closeDeleteNoteModal();

        }


        if (
            logoutModal.classList.contains(
                "show"
            )
        ) {

            closeLogoutModal();

        }

    }
);


/* =========================================================
   INITIALIZE
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function() {


        /* LOAD STATISTICS */

        loadDashboardStats();


        /* LOAD GRAPH */

        loadSalesReport();


        /* MONTH CHANGE */

        document
            .getElementById(
                "monthSelector"
            )
            .addEventListener(
                "change",
                loadSalesReport
            );


        /* WEEK CHANGE */

        document
            .getElementById(
                "weekSelector"
            )
            .addEventListener(
                "change",
                loadSalesReport
            );


        /* LOGOUT */

        document
            .getElementById(
                "logoutButton"
            )
            .addEventListener(
                "click",
                openLogoutModal
            );


        /* CONFIRM DELETE */

        document
            .getElementById(
                "confirmDeleteNoteButton"
            )
            .addEventListener(
                "click",
                confirmDeleteNote
            );

    }
);

/* =========================================================
   RESPONSIVE GRAPH LABEL
========================================================= */

window.addEventListener(
    "resize",
    function() {

        loadSalesReport();

    }
);