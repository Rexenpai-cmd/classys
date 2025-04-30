document.addEventListener('DOMContentLoaded', function () {
    const profileToggle = document.getElementById('profile-toggle');
    const profileMenu = document.getElementById('profile-menu');
    const updateToggle = document.getElementById('update');
    const updateMenu = document.getElementById('update-menu');
    const changeToggle = document.getElementById('change');
    const changeMenu = document.getElementById('change-menu');

    // Helper: Hide all menus
    function hideAllMenus() {
        profileMenu.classList.remove('show');
        updateMenu.classList.remove('show');
        changeMenu.classList.remove('show');
    }

    // Toggle profile menu and close others
    profileToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isAnyOpen =
            profileMenu.classList.contains('show') ||
            updateMenu.classList.contains('show') ||
            changeMenu.classList.contains('show');

        if (isAnyOpen) {
            hideAllMenus();
        } else {
            profileMenu.classList.add('show');
        }
    });

    // Show update menu from profile menu
    updateToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        profileMenu.classList.remove('show');
        updateMenu.classList.add('show');
        changeMenu.classList.remove('show');
    });

    // Show change menu from profile menu
    changeToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        profileMenu.classList.remove('show');
        updateMenu.classList.remove('show');
        changeMenu.classList.add('show');
    });
});
