const drawer = document.getElementById('drawer');
const overlay = document.getElementById('overlay');
const passwordPopup = document.getElementById('passwordPopup');

function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
}

function openPasswordPopup() {
    closeDrawer();
    passwordPopup.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closePasswordPopup() {
    passwordPopup.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
}

function closeAll() {
    closeDrawer();
    closePasswordPopup();
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAll();
});