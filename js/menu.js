// js/menu.js
document.addEventListener('DOMContentLoaded', () => {
    const nav = document.querySelector('header nav');
    const header = document.querySelector('header');

    // Creamos el botón hamburguesa
    const btnMenu = document.createElement('button');
    btnMenu.id = 'btn-hamburguesa';
    btnMenu.innerHTML = '☰';
    btnMenu.setAttribute('aria-label', 'Abrir menú');
    header.insertBefore(btnMenu, nav);

    // Al hacer clic abrimos/cerramos
    btnMenu.addEventListener('click', () => {
        nav.classList.toggle('nav-abierta');
        btnMenu.innerHTML = nav.classList.contains('nav-abierta') ? '✕' : '☰';
    });
});