const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');
const darkMode = document.querySelector('.dark-mode');
const lightIcon = darkMode.querySelector('span:nth-child(1)');
const darkIcon = darkMode.querySelector('span:nth-child(2)');

// Check if dark mode was previously enabled
if (sessionStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode-variables');
    lightIcon.classList.remove('active');  // Sun icon is NOT active when dark mode
    darkIcon.classList.add('active');      // Moon icon is active when dark mode
} else {
    lightIcon.classList.add('active');     // Sun icon is active in light mode
    darkIcon.classList.remove('active');  // Moon icon is NOT active in light mode
}

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    // Toggle dark mode
    document.body.classList.toggle('dark-mode-variables');
    
    // Toggle the active class between the sun and moon icons
    lightIcon.classList.toggle('active');  // Sun icon active in light mode
    darkIcon.classList.toggle('active');   // Moon icon active in dark mode

    // Save dark mode state in sessionStorage
    if (document.body.classList.contains('dark-mode-variables')) {
        sessionStorage.setItem('darkMode', 'enabled');
    } else {
        sessionStorage.setItem('darkMode', 'disabled');
    }
});
