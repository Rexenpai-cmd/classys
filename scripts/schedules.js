// Get modal element
const modal = document.getElementById("scheduleModal");
const btn = document.getElementById("addScheduleBtn");
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
