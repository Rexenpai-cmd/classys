// Get modal element
const modal = document.getElementById("subjectModal");
const btn = document.getElementById("addSubjectBtn");
const span = document.getElementById("closeModal");

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

// Close the modal when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target === modal) {
        span.onclick();
    }
}

function toggleMenu(menuId) {
    // Close all other menus first
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

function selectItem(inputId, item) {
    document.getElementById(inputId).value = item;
    document.querySelector(`#${inputId} + .menu-container`).style.display = 'none';
}

// Close all menus when clicking outside any text input or menu container
window.onclick = function (event) {
    const isInput = event.target.matches('input[type="text"]');
    const isMenuItem = event.target.closest('.menu-container');

    if (!isInput && !isMenuItem) {
        const menus = document.querySelectorAll('.menu-container');
        menus.forEach(menu => menu.style.display = 'none');
    }
};

document.getElementById("subjectForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent normal form submission

    const form = e.target;
    const formData = new FormData(form);
    const alertBox = document.querySelector(".alert-container");
    const responseMsg = document.getElementById("responseMsg");

    fetch("../db/save_subject.php", {
        method: "POST",
        body: formData,
    })
    .then(res => res.text())
    .then(data => {
        responseMsg.innerHTML = data;
        alertBox.classList.add("show"); // Slide down
        setTimeout(() => {
            alertBox.classList.remove("show"); // Slide up after 3s
        }, 3000);
        form.reset(); // Optionally clear the form

        modal.querySelector('.modal-content').classList.remove("show");
        modal.classList.remove("show");
    })
    .catch(err => {
        responseMsg.innerHTML = "Error saving subject.";
        console.error("AJAX error:", err);
        alertBox.classList.add("show"); // Slide down
        setTimeout(() => {
            alertBox.classList.remove("show"); // Slide up after 3s
        }, 3000);
    });
});

// Load subjects and handle pagination
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const initialPage = urlParams.get('page') ? parseInt(urlParams.get('page')) : 1;
    loadSubjects(initialPage);

    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const page = this.getAttribute('href').split('page=')[1];
            loadSubjects(page);
            history.pushState(null, '', '?page=' + page);

            // Update active class
            document.querySelectorAll('.pagination .page-item').forEach(item => {
                item.classList.remove('active');
            });
            this.parentElement.classList.add('active');
        });
    });
});

function loadSubjects(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '?page=' + page + '&ajax=1', true);
    xhr.onload = function () {
        if (this.status === 200) {
            const subjects = JSON.parse(this.responseText);
            const tableBody = document.getElementById('subjectsTableBody');
            tableBody.innerHTML = '';

            subjects.forEach(subject => {
                const row = `<tr>
                    <td>${subject.section_code}</td>
                    <td>${subject.subject_code}</td>
                    <td>${subject.subject_title}</td>
                    <td>${subject.year}</td>
                    <td>${subject.unit}</td>
                    <td class="actions-container">
                        <button onclick="openEditModal('${subject.id}', '${subject.section_code}', '${subject.subject_code}', '${subject.subject_title}', '${subject.year}', '${subject.unit}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="openDeleteModal('${subject.id}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            // Update the results display
            const start = ((page - 1) * itemsPerPage) + 1;
            const end = Math.min(start + itemsPerPage - 1, totalItems);
            document.querySelector('.pages h3').innerHTML = `Results: ${start} - ${end} of ${totalItems}`;
        }
    };
    xhr.send();
}
// Show the modal for adding a subject
btn.onclick = function() {
    modal.classList.add("show");
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.add("show");
    }, 0);
}

// Close the add modal
span.onclick = function() {
    modal.querySelector('.modal-content').classList.remove("show");
    modal.classList.remove("show");
};

function openEditModal(id, sectionCode, subjectCode, subjectTitle, year, unit) {
    const editModal = document.getElementById("editSubjectModal");

    // Set values in the form
    document.getElementById("editid").value = id;
    document.getElementById("editsection").value = sectionCode;
    document.getElementById("editsubject").value = subjectCode;
    document.getElementById("edittitle").value = subjectTitle;
    document.getElementById("edityear").value = year;
    document.getElementById("editunit").value = unit;

    // Show the modal
    editModal.classList.add("show");
    setTimeout(() => {
        editModal.querySelector('.modal-content').classList.add("show");
    }, 0);
}

// Save Updated Subject
document.getElementById("editSubjectForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const alertBox = document.querySelector(".alert-container");
    const responseMsg = document.getElementById("responseMsg");

    fetch("../db/edit_subject.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        responseMsg.innerHTML = data;
        alertBox.classList.add("show");
        setTimeout(() => alertBox.classList.remove("show"), 3000);

        // Close and reset the modal
        const editModal = document.getElementById("editSubjectModal");
        editModal.querySelector('.modal-content').classList.remove("show");
        editModal.classList.remove("show");
        form.reset();

        // Reload subjects
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') ? parseInt(urlParams.get('page')) : 1;
        loadSubjects(page);
    })
    .catch(err => {
        responseMsg.innerHTML = "Error updating subject.";
        console.error("Edit AJAX error:", err);
        alertBox.classList.add("show");
        setTimeout(() => alertBox.classList.remove("show"), 3000);
    });
});

// Close the Edit Modal
document.getElementById("closeEditModal").onclick = function () {
    const editModal = document.getElementById("editSubjectModal");
    editModal.querySelector('.modal-content').classList.remove("show");
    editModal.classList.remove("show");
    document.getElementById("editSubjectForm").reset(); // optional: reset form
};

// Clear form when the edit modal is close
document.getElementById("closeEditModal").onclick = function () {
    const editModal = document.getElementById("editSubjectModal");
    editModal.querySelector('.modal-content').classList.remove("show");
    editModal.classList.remove("show");
    document.getElementById("editSubjectForm").reset(); // Optional clear
};

// Open Delete Modal
let subjectIdToDelete;

function openDeleteModal(subjectId) {
    subjectIdToDelete = subjectId;
    const deleteModal = document.getElementById("deleteModal");
    deleteModal.classList.add("show");
    setTimeout(() => {
        deleteModal.querySelector('.deleteModalContent').classList.add("show");
    }, 0);
}

document.getElementById("confirmDelete").onclick = function() {
    deleteSubject(subjectIdToDelete);
    closeDeleteModal();
};

document.getElementById("cancelDelete").onclick = function() {
    closeDeleteModal();
};

function deleteSubject(subjectId) {
    fetch(`../db/delete_subject.php?id=${subjectId}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // Get the response text
        } else {
            throw new Error("Error deleting subject.");
        }
    })
    .then(data => {
        // Show success message
        const alertBox = document.querySelector(".alert-container");
        const responseMsg = document.getElementById("responseMsg");
        responseMsg.innerHTML = data; // Set the message from PHP
        alertBox.classList.add("show"); // Show the alert box
        
        setTimeout(() => {
            alertBox.classList.remove("show"); // Hide after 3 seconds
        }, 3000);

        loadSubjects(); // Reload subjects after deletion
        closeDeleteModal(); // Close the modal
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

function closeDeleteModal() {
    console.log("Closing modal...");
    const deleteModal = document.getElementById("deleteModal");
    const modalContent = deleteModal.querySelector(".deleteModalContent");

    modalContent.classList.remove("show");
    deleteModal.classList.remove("show");
    subjectIdToDelete = null;
}