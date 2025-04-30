// Function to fetch schedules data via AJAX
function fetchSchedules() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../db/fetch_schedules.php', true); // PHP file to fetch schedules
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parse the JSON response and update the table
            var schedules = JSON.parse(xhr.responseText);
            var tableBody = document.querySelector('.schedule-table tbody');
            tableBody.innerHTML = ''; // Clear existing rows
            if (schedules.length > 0) {
                schedules.forEach(function(schedule) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${schedule.class_name}</td>
                        <td>${schedule.instructor_name}</td>
                        <td>${schedule.subject_title}</td>
                        <td>${schedule.room}</td>
                        <td>${schedule.timeStart} - ${schedule.timeEnd}</td>
                        <td>${schedule.days}</td>
                        <td>
                            <button onclick="editSchedule(${schedule.schedule_id}, '${schedule.class_name}', '${schedule.instructor_name}', '${schedule.subject_title}', '${schedule.room}', '${schedule.timeStart}', '${schedule.timeEnd}', '${schedule.days}', ${schedule.class_id}, ${schedule.instructor_id}, ${schedule.subject_id}, ${schedule.room_id})">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="deleteSchedule" onclick="deleteSchedule(${schedule.schedule_id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="7">No schedules available.</td></tr>';
            }
        }
    };
    xhr.send();
}

// Call the function to fetch schedules when the page loads
window.onload = fetchSchedules;

// Get modal element
const modal = document.getElementById("scheduleModal");
const editModal = document.getElementById("editScheduleModal");
const btn = document.getElementById("addScheduleBtn");
const span = document.getElementById("closeModal");
const spanEdit = document.getElementById("closeEditModal");

// Show the modal
btn.onclick = function() {
    modal.classList.add("show");
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add("show");
    }, 0);
}

// Close the modal
span.onclick = function() {
    modal.querySelector('.modal-content').classList.remove("show");
    modal.classList.remove("show");
}

// Close the edit modal
spanEdit.onclick = function() {
    editModal.querySelector('.modal-content').classList.remove("show");
    editModal.classList.remove("show");
}

// Close the modal when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target === modal) {
        span.onclick();
    }
    if (event.target === editModal) {
        spanEdit.onclick();
    }
}

function toggleMenu(menuId) {
    // Close all other menus first
    console.log(`Toggling menu: ${menuId}`);

    const menus = document.querySelectorAll('.menu-container');
    menus.forEach(menu => {
        if (menu.id !== menuId) {
            menu.style.display = 'none';
        }
    });

    // Toggle the target menu
    const menu = document.getElementById(menuId);
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function selectItem(inputType, item, id) {
    // Determine if we're editing or adding
    let inputId = inputType;
    let isEdit = false;

    // Check if the inputType contains 'edit' (e.g. 'editClass')
    if (inputType.startsWith("edit")) {
        isEdit = true;
        const baseType = inputType.replace("edit", "").toLowerCase(); // e.g. 'Class' => 'class'
        inputType = baseType; // Store the base type without 'edit' prefix for hidden field naming
    }

    // Build the actual input ID
    inputId = isEdit ? `edit${capitalizeFirstLetter(inputType)}` : inputType;
    
    // Set the value in the input field
    const inputElement = document.getElementById(inputId);
    if (inputElement) {
        inputElement.value = item;
    }

    // Store hidden ID value
    const formIndex = isEdit ? 1 : 0;
    let hiddenInput = document.forms[formIndex].querySelector(`input[name="${inputType}_id"]`);
    if (!hiddenInput) {
        hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = `${inputType}_id`;
        document.forms[formIndex].appendChild(hiddenInput);
    }
    hiddenInput.value = id;
    
    console.log(`Set ${inputType}_id to ${id}`);

    // If selecting class, filter subject list
    if (inputType === "class") {
        const selectedClass = item;
        const classParts = selectedClass.split(" ");
        const year = classParts[1]?.replace(/\D/g, "") || '';

        const subjectMenuId = isEdit ? 'edit-subject-menu' : 'subject-menu';
        const subjectMenuItems = document.querySelectorAll(`#${subjectMenuId} .menu-item`);

        subjectMenuItems.forEach(item => {
            const subjectYear = item.getAttribute("data-year");
            item.style.display = (subjectYear == year) ? "block" : "none";
        });
    }

    // Hide the dropdown
    const menuId = isEdit ? `edit-${inputType}-menu` : `${inputType}-menu`;
    const menu = document.getElementById(menuId);
    if (menu) {
        menu.style.display = "none";
    }
}

function capitalizeFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}


// Close all menus when clicking outside any text input or menu container
document.addEventListener('click', function(event) {
    const isInput = event.target.matches('input[type="text"]');
    const isMenuItem = event.target.closest('.menu-container');

    if (!isInput && !isMenuItem) {
        const menus = document.querySelectorAll('.menu-container');
        menus.forEach(menu => menu.style.display = 'none');
    }
});

// Function to handle editing a schedule
function editSchedule(scheduleId, className, instructorName, subjectTitle, roomName, timeStart, timeEnd, days, classId, instructorId, subjectId, roomId) {
    // Show the edit modal
    editModal.classList.add("show");
    setTimeout(() => {
        editModal.querySelector('.modal-content').classList.add("show");
    }, 0);
    
    // Set values in the form
    document.getElementById('editClass').value = className;
    document.getElementById('editInstructor').value = instructorName;
    document.getElementById('editSubject').value = subjectTitle;
    document.getElementById('editRoom').value = roomName;
    document.getElementById('editTimeStart').value = timeStart;
    document.getElementById('editTimeEnd').value = timeEnd;
    
    // Create or update hidden inputs for all IDs
    // Schedule ID
    let scheduleIdInput = document.forms[1].querySelector('input[name="schedule_id"]');
    if (!scheduleIdInput) {
        scheduleIdInput = document.createElement('input');
        scheduleIdInput.type = 'hidden';
        scheduleIdInput.name = 'schedule_id';
        document.forms[1].appendChild(scheduleIdInput);
    }
    scheduleIdInput.value = scheduleId;
    
    // Class ID
    let classIdInput = document.forms[1].querySelector('input[name="class_id"]');
    if (!classIdInput) {
        classIdInput = document.createElement('input');
        classIdInput.type = 'hidden';
        classIdInput.name = 'class_id';
        document.forms[1].appendChild(classIdInput);
    }
    classIdInput.value = classId;
    
    // Instructor ID
    let instructorIdInput = document.forms[1].querySelector('input[name="instructor_id"]');
    if (!instructorIdInput) {
        instructorIdInput = document.createElement('input');
        instructorIdInput.type = 'hidden';
        instructorIdInput.name = 'instructor_id';
        document.forms[1].appendChild(instructorIdInput);
    }
    instructorIdInput.value = instructorId;
    
    // Subject ID
    let subjectIdInput = document.forms[1].querySelector('input[name="subject_id"]');
    if (!subjectIdInput) {
        subjectIdInput = document.createElement('input');
        subjectIdInput.type = 'hidden';
        subjectIdInput.name = 'subject_id';
        document.forms[1].appendChild(subjectIdInput);
    }
    subjectIdInput.value = subjectId;
    
    // Room ID
    let roomIdInput = document.forms[1].querySelector('input[name="room_id"]');
    if (!roomIdInput) {
        roomIdInput = document.createElement('input');
        roomIdInput.type = 'hidden';
        roomIdInput.name = 'room_id';
        document.forms[1].appendChild(roomIdInput);
    }
    roomIdInput.value = roomId;
    
    // Set the day checkboxes
    const dayArray = days.split(',');
    const dayCheckboxes = document.forms[1].querySelectorAll('input[name="days"]');
    dayCheckboxes.forEach(checkbox => {
        checkbox.checked = dayArray.includes(checkbox.value);
    });
}

// Save the schedule using AJAX
document.querySelector('#scheduleModal .modal-buttons button[type="submit"]').addEventListener('click', function(e) {
    e.preventDefault();  // Prevent form submission

    const modal = document.getElementById("scheduleModal");
    modal.classList.remove("show");

    const form = document.querySelector('#scheduleModal form');
    
    const classId = form.querySelector('[name="class_id"]').value;
    const instructorId = form.querySelector('[name="instructor_id"]').value;
    const subjectId = form.querySelector('[name="subject_id"]').value;
    const roomId = form.querySelector('[name="room_id"]').value;
    const timeStart = document.getElementById('timeStart').value;
    const timeEnd = document.getElementById('timeEnd').value;

    // Get selected days
    const days = [];
    const dayCheckboxes = form.querySelectorAll('input[name="days"]:checked');
    dayCheckboxes.forEach(checkbox => days.push(checkbox.value));

    // Prepare data to send via AJAX
    const data = {
        class_id: classId,
        instructor_id: instructorId,
        subject_id: subjectId,
        room_id: roomId,
        time_start: timeStart,
        time_end: timeEnd,
        days: days.join(','),  // Join the days into a comma-separated string
    };

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../db/save_schedule.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);  // Log the response
            try {
                const response = JSON.parse(xhr.responseText);
                const alertContainer = document.querySelector('.alert-container');
                const responseMsg = document.getElementById('responseMsg');
                
                // Set the message
                responseMsg.textContent = response.message;
                
                // Show the alert container
                alertContainer.classList.add('show');
                
                // Hide the alert after 3 seconds (3000 ms)
                setTimeout(function() {
                    alertContainer.classList.remove('show');
                }, 3000);
    
                fetchSchedules(); 
                // Clear the form fields
                form.reset(); // Reset input fields
                dayCheckboxes.forEach(checkbox => checkbox.checked = false);
            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        }
    };
    
    const urlParams = new URLSearchParams(data).toString();
    xhr.send(urlParams);
});

// Update the schedule using AJAX
// Update the schedule using AJAX
document.querySelector('#editScheduleModal .modal-buttons button[type="submit"]').addEventListener('click', function(e) {
    e.preventDefault();  // Prevent form submission

    const modal = document.getElementById("editScheduleModal");
    modal.querySelector('.modal-content').classList.remove("show");
    setTimeout(() => {
        modal.classList.remove("show");
    }, 300);

    const form = document.querySelector('#editScheduleModal form');
    
    // Get the IDs from the hidden inputs
    const scheduleId = form.querySelector('input[name="schedule_id"]').value;
    const classId = form.querySelector('input[name="class_id"]').value;
    const instructorId = form.querySelector('input[name="instructor_id"]').value;
    const subjectId = form.querySelector('input[name="subject_id"]').value;
    const roomId = form.querySelector('input[name="room_id"]').value;
    const timeStart = document.getElementById('editTimeStart').value;
    const timeEnd = document.getElementById('editTimeEnd').value;

    // Get selected days
    const days = [];
    const dayCheckboxes = form.querySelectorAll('input[name="days"]:checked');
    dayCheckboxes.forEach(checkbox => days.push(checkbox.value));

    // Prepare data to send via AJAX
    const data = {
        schedule_id: scheduleId,
        class_id: classId,
        instructor_id: instructorId,
        subject_id: subjectId,
        room_id: roomId,
        time_start: timeStart,
        time_end: timeEnd,
        days: days.join(','),  // Join the days into a comma-separated string
    };

    console.log("Submitting schedule update with data:", data);

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../db/update_schedule.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText);  // Log the response
            try {
                const response = JSON.parse(xhr.responseText);
                const alertContainer = document.querySelector('.alert-container');
                const responseMsg = document.getElementById('responseMsg');
                
                // Set the message
                responseMsg.textContent = response.message;
                
                // Show the alert container
                alertContainer.classList.add('show');
                
                // Hide the alert after 3 seconds (3000 ms)
                setTimeout(function() {
                    alertContainer.classList.remove('show');
                }, 3000);
    
                fetchSchedules(); 
                // Clear the form fields
                form.reset(); // Reset input fields
                dayCheckboxes.forEach(checkbox => checkbox.checked = false);
            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        }
    };
    
    const urlParams = new URLSearchParams(data).toString();
    xhr.send(urlParams);
});


// Delete Schedule
let scheduleToDelete = null; // Store the schedule ID to delete

function deleteSchedule(scheduleId) {
    scheduleToDelete = scheduleId; // Save the ID for deletion
    const deleteModal = document.getElementById("deleteModal");
    deleteModal.classList.add("show");
}

document.getElementById("cancelDelete").onclick = function() {
    document.getElementById("deleteModal").classList.remove("show");
    scheduleToDelete = null;
};

document.getElementById("confirmDelete").onclick = function() {
    if (scheduleToDelete !== null) {

        // close delete modal
        document.getElementById("deleteModal").classList.remove("show");
        
        // Perform AJAX delete request here
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../db/delete_schedule.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    const alertContainer = document.querySelector('.alert-container');
                    const responseMsg = document.getElementById('responseMsg');
        
                    // Set the message
                    responseMsg.textContent = response.message;
        
                    // Show the alert container
                    alertContainer.classList.add('show');
        
                    // Hide the alert after 3 seconds
                    setTimeout(function () {
                        alertContainer.classList.remove('show');
                    }, 3000);
        
                    // Refresh table
                    fetchSchedules();
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            }
        };        
        xhr.send("schedule_id=" + encodeURIComponent(scheduleToDelete));
    }
};
