/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
//
// Scripts
//

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

// resources/js/scripts.js o al final de tus vistas
const MySwal = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success rounded-pill px-4 py-2 mx-2', // Verde
        cancelButton: 'btn btn-outline-secondary rounded-pill px-4 py-2 mx-2', // Gris
        popup: 'rounded-4 border-0 shadow-lg',
        title: 'fw-bold text-dark'
    },
    buttonsStyling: false
});
window.Swal = MySwal;
