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

        const course = document.getElementById("addCourse").value;
        const year = document.getElementById("addYear").value;
        const section = document.getElementById("addSection").value;

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

// Show Add Room Modal and save the room details
document.addEventListener("DOMContentLoaded", function () {
    const addRoomButton = document.getElementById("addRoom");
    const addRoomModal = document.getElementById("addRoomModal");
    const cancelAddRoom = document.getElementById("cancelAddRoom");
    const addRoomForm = document.getElementById("addRoomForm");
    const responseMsg = document.getElementById("responseMsg");
    const alertContainer = document.querySelector('.alert-container');

    addRoomButton.addEventListener("click", () => {
        addRoomModal.classList.add("show");
    });

    cancelAddRoom.addEventListener("click", (e) => {
        e.preventDefault();
        addRoomModal.classList.remove("show");
    });

    addRoomForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const room = document.getElementById("room").value;

        console.log('Room:', room); // Debug log

        const payload = { room };
        console.log('Payload:', payload); // Debug log

        try {
            const response = await fetch('../db/save_room.php', {
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
                addRoomForm.reset();
                addRoomModal.classList.remove("show");
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

// Show Edit Modal
document.addEventListener("DOMContentLoaded", function () {
    const editClassButtons = document.querySelectorAll(".editClass");
    const editClassModal = document.getElementById("editClassModal");
    const cancelEditClass = document.getElementById("cancelEditClass");

    const courseInput = editClassModal.querySelector("#editCourse");
    const yearInput = editClassModal.querySelector("#editYear");
    const sectionInput = editClassModal.querySelector("#editSection");    

    const idInput = editClassModal.querySelector("#classId");

    editClassButtons.forEach(button => {
        button.addEventListener("click", () => {
            const id = button.getAttribute("data-id");
            const course = button.getAttribute("data-course");
            const year = button.getAttribute("data-year");
            const section = button.getAttribute("data-section");

            idInput.value = id;
            courseInput.value = course;
            yearInput.value = year;
            sectionInput.value = section;

            editClassModal.classList.add("show");
        });
    });

    cancelEditClass.addEventListener("click", (e) => {
        e.preventDefault();
        editClassModal.classList.remove("show");
    });
});


// Save updated Class
document.addEventListener("DOMContentLoaded", function () {
    const confirmEditClass = document.getElementById("confirmEditClass");
    const editClassModal = document.getElementById("editClassModal");

    const idInput = document.getElementById("classId");
    const courseInput = document.getElementById("editCourse");
    const yearInput = document.getElementById("editYear");
    const sectionInput = document.getElementById("editSection");

    confirmEditClass.addEventListener("click", function (e) {
        e.preventDefault(); // Stop form from reloading page

        const formData = new FormData();
        formData.append("classId", idInput.value);
        formData.append("course", courseInput.value);
        formData.append("year", yearInput.value);
        formData.append("section", sectionInput.value);

        fetch("../db/edit_class.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const alertContainer = document.querySelector('.alert-container');
            const responseMsg = document.querySelector('#responseMsg');
            
            if (data.success) {
                responseMsg.textContent = data.message; // Success message
            } else {
                responseMsg.textContent = data.message; // Error message
            }

            alertContainer.classList.add('show'); // Show alert

            // Hide the alert after 3 seconds
            setTimeout(() => {
                alertContainer.classList.remove('show');
            }, 3000);

            // Close the modal if successful
            if (data.success) {
                editClassModal.classList.remove("show"); // Close the modal
            }
        })
        .catch(err => {
            alert("Request failed: " + err);
        });
    });
});

// Show Edit Room Modal and Save the updated room
document.addEventListener("DOMContentLoaded", function () {
    const editRoomButtons = document.querySelectorAll(".editRoom"); // All edit buttons
    const editRoomModal = document.getElementById("editRoomModal");
    const cancelEditRoom = document.getElementById("cancelEditRoom");
    const editRoomInput = document.getElementById("editRoom");
    const alertContainer = document.querySelector(".alert-container");
    const responseMsg = document.getElementById("responseMsg");

    // Open the modal and populate the input field with the room's details
    editRoomButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            const roomName = e.target.closest('button').getAttribute("data-room"); // Get room name
            const roomId = e.target.closest('button').getAttribute("data-id"); // Get room ID

            // Set the input field with the current room's name
            editRoomInput.value = roomName;

            // You can use roomId for further processing like updating the room on the server
            // For now, we just store it in the modal
            editRoomModal.setAttribute('data-room-id', roomId);

            // Show the modal
            editRoomModal.classList.add("show");
        });
    });

    // Close the modal
    cancelEditRoom.addEventListener("click", (e) => {
        e.preventDefault();
        editRoomModal.classList.remove("show");
    });

    // Save the updated room data via AJAX
    document.getElementById("confirmEditRoom").addEventListener("click", () => {
        const roomId = editRoomModal.getAttribute('data-room-id');
        const updatedRoomName = editRoomInput.value;

        // Make sure the input is not empty
        if (updatedRoomName.trim() === "") {
            alert("Please enter a valid room name.");
            return;
        }

        // Prepare the data to be sent
        const data = new FormData();
        data.append("id", roomId);
        data.append("room", updatedRoomName);

        // Make the AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "edit_room.php", true);

        // Handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                // Show the response message in the alert container
                responseMsg.textContent = response.message;
                
                if (response.success) {
                    // Successfully updated room, update the UI
                    const row = document.querySelector(`button[data-id="${roomId}"]`).closest('tr');
                    row.querySelector('td').textContent = updatedRoomName;

                    // Show the alert container with success message
                    alertContainer.style.display = "block";
                    alertContainer.classList.add("success");

                    // Hide the modal after saving
                    editRoomModal.classList.remove("show");
                } else {
                    // Show the alert container with error message
                    alertContainer.style.display = "block";
                    alertContainer.classList.add("error");
                }
            } else {
                alert("An error occurred while updating the room. Please try again.");
            }
        };

        // Send the request
        xhr.send(data);
    });
});


// Show Delete Class Modal
document.addEventListener("DOMContentLoaded", function () {
    // Get references to modal and buttons
    const deleteClassButtons = document.querySelectorAll(".deleteClass"); // All delete buttons
    const deleteClassModal = document.getElementById("deleteClassModal");
    const confirmDeleteClass = document.getElementById("confirmDeleteClass");
    const cancelAddRoomClass = document.getElementById("cancelDeleteClass");
    const classIdInput = document.getElementById("classIdInput"); // The hidden field to store class ID

    // Debugging: log to make sure the page is loaded and the elements are found
    console.log('Page loaded');
    
    // Show the Delete Class Modal and store the class ID
    deleteClassButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            const classId = button.closest('tr').querySelector('.editClass').getAttribute('data-id');
            console.log("Class ID: ", classId); // Debugging line
            classIdInput.value = classId; // Store the class ID in the hidden input
            deleteClassModal.classList.add("show"); // Show the modal
        });
    });

    // Close the Delete Class Modal
    cancelDeleteClass.addEventListener("click", (e) => {
        e.preventDefault();
        deleteClassModal.classList.remove("show");
    });

    // Confirm Delete Class
    confirmDeleteClass.addEventListener("click", (e) => {
        e.preventDefault();
        
        const classId = classIdInput.value; // Get the class ID from the hidden input
        
        // Send delete request to the server
        fetch('../db/delete_class.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: classId })
        })
        .then(response => response.json())
        .then(data => {
            const alertContainer = document.querySelector('.alert-container');
            const responseMsg = document.querySelector('#responseMsg');

            // Display response message
            if (data.success) {
                responseMsg.textContent = 'Class deleted successfully!';
            } else {
                responseMsg.textContent = 'Error deleting class.';
            }

            // Show the alert and hide it after 3 seconds
            alertContainer.classList.add('show');
            setTimeout(() => {
                alertContainer.classList.remove('show');
            }, 3000);

            // Hide the modal
            deleteClassModal.classList.remove("show");

            // Optionally, remove the deleted row from the table (refresh is another option)
            const rowToDelete = document.querySelector(`[data-id="${classId}"]`).closest('tr');
            if (rowToDelete) {
                rowToDelete.remove();
            }
        })
        .catch(err => {
            console.error("Error deleting class:", err);
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const deleteRoomButtons = document.querySelectorAll(".deleteRoom");
    const deleteRoomModal = document.getElementById("deleteRoomModal");
    const cancelDeleteRoom = document.getElementById("cancelDeleteRoom");
    const confirmDeleteRoom = document.getElementById("confirmDeleteRoom");
    const alertContainer = document.querySelector(".alert-container");
    const responseMsg = document.getElementById("responseMsg");

    let roomIdToDelete = null; // Variable to store the room ID to delete

    // Open the modal and store the room ID to delete
    deleteRoomButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            roomIdToDelete = e.target.closest('button').getAttribute("data-id");
            deleteRoomModal.classList.add("show");
        });
    });

    // Close the modal
    cancelDeleteRoom.addEventListener("click", (e) => {
        e.preventDefault();
        deleteRoomModal.classList.remove("show");
    });

    // Confirm the delete action
    confirmDeleteRoom.addEventListener("click", () => {
        if (!roomIdToDelete) {
            alert("No room selected for deletion.");
            return;
        }

        // Prepare the data to send with the AJAX request
        const data = new FormData();
        data.append("id", roomIdToDelete);

        // Make the AJAX request to delete the room
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../db/delete_room.php", true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                // Show the response message in the alert container
                responseMsg.textContent = response.message;

                // Show the alert container with success or error message
                alertContainer.classList.add("show"); // Add the "show" class to display the alert container

                // Add success or error class based on the response
                if (response.success) {
                    alertContainer.classList.add("success");
                    alertContainer.classList.remove("error");

                    // Successfully deleted room, remove the row from the table
                    const row = document.querySelector(`button[data-id="${roomIdToDelete}"]`).closest('tr');
                    row.remove();
                    
                    // Close the modal after deletion
                    deleteRoomModal.classList.remove("show");
                } else {
                    alertContainer.classList.add("error");
                    alertContainer.classList.remove("success");
                }

                // Hide the alert container after 3 seconds
                setTimeout(() => {
                    alertContainer.classList.remove("show"); // Remove the "show" class to hide the alert container
                }, 3000);
            } else {
                alert("An error occurred while deleting the room. Please try again.");
            }
        };

        // Send the request
        xhr.send(data);
    });
});
