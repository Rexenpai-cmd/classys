document.addEventListener("DOMContentLoaded", function () {
    const addClassButton = document.getElementById("addClass");
    const addClassModal = document.getElementById("addClassModal");
    const cancelDelete = document.getElementById("cancelDelete");
    const addClassForm = document.getElementById("addClassForm");
    const responseMsg = document.getElementById("responseMsg");
    const alertContainer = document.querySelector('.alert-container');

    addClassButton.addEventListener("click", () => {
        addClassModal.classList.add("show");
    });

    cancelDelete.addEventListener("click", (e) => {
        e.preventDefault();
        addClassModal.classList.remove("show");
    });

    addClassForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const course = document.getElementById("course").value;
        const year = document.getElementById("year").value;
        const section = document.getElementById("section").value;

        console.log('Section:', section); // Debug log

        const payload = { course, year, section };
        console.log('Payload:', payload); // Debug log

        try {
            const response = await fetch('../db/save_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();
            responseMsg.textContent = result.message;
            alertContainer.classList.add('show'); // Show alert

            // Hide the alert after 5 seconds
            setTimeout(() => {
                alertContainer.classList.remove('show');
            }, 3000);

            if (response.ok) {
                addClassForm.reset();
                addClassModal.classList.remove("show");
            }
        } catch (error) {
            responseMsg.textContent = "Error saving class.";
            alertContainer.classList.add('show'); // Show alert on error

            // Hide the alert after 5 seconds
            setTimeout(() => {
                alertContainer.classList.remove('show');
            }, 3000);
        }
    });
});

// Show Add Room Modal
document.addEventListener("DOMContentLoaded", function () {
    const addRoomButton = document.getElementById("addRoom");
    const addRoomModal = document.getElementById("addRoomModal");
    const cancelAddRoom = document.getElementById("cancelAddRoom");

    addRoomButton.addEventListener("click", () => {
        addRoomModal.classList.add("show");
    });

    cancelAddRoom.addEventListener("click", (e) => {
        e.preventDefault(); // prevent form submission
        addRoomModal.classList.remove("show");
    });
});

// Show Edit Class Modal
document.addEventListener("DOMContentLoaded", function () {
    const editClassButton = document.getElementById("editClass");
    const editClassModal = document.getElementById("editClassModal");
    const cancelEditClass = document.getElementById("cancelEditClass");

    editClassButton.addEventListener("click", () => {
        editClassModal.classList.add("show");
    });

    cancelEditClass.addEventListener("click", (e) => {
        e.preventDefault(); // prevent form submission
        editClassModal.classList.remove("show");
    });
});

// Show Edit Room Modal
document.addEventListener("DOMContentLoaded", function () {
    const editRoomButton = document.getElementById("editRoom");
    const editRoomModal = document.getElementById("editRoomModal");
    const cancelEditRoom = document.getElementById("cancelEditRoom");

    editRoomButton.addEventListener("click", () => {
        editRoomModal.classList.add("show");
    });

    cancelEditRoom.addEventListener("click", (e) => {
        e.preventDefault(); // prevent form submission
        editRoomModal.classList.remove("show");
    });
});

// Show Delete Class Modal
document.addEventListener("DOMContentLoaded", function () {
    const deleteClassButton = document.getElementById("deleteClass");
    const deleteClassModal = document.getElementById("deleteClassModal");
    const cancelDeleteClass = document.getElementById("cancelDeleteClass");

    deleteClassButton.addEventListener("click", () => {
        deleteClassModal.classList.add("show");
    });

    cancelDeleteClass.addEventListener("click", (e) => {
        e.preventDefault(); // prevent form submission
        deleteClassModal.classList.remove("show");
    });
});

// Show Delete Room Modal
document.addEventListener("DOMContentLoaded", function () {
    const deleteRoomButton = document.getElementById("deleteRoom");
    const deleteRoomModal = document.getElementById("deleteRoomModal");
    const cancelDeleteRoom = document.getElementById("cancelDeleteRoom");

    deleteRoomButton.addEventListener("click", () => {
        deleteRoomModal.classList.add("show");
    });

    cancelDeleteRoom.addEventListener("click", (e) => {
        e.preventDefault(); // prevent form submission
        deleteRoomModal.classList.remove("show");
    });
});