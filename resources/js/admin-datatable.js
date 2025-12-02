// resources/js/admin-datatable.js
let tableUsers;

$(document).ready(function() {
    myTable();
});

function myTable() {
    var url = BASE_URL + "/admin/read";

    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function (response) {
            if (!response.users) { return; }

            window.users = response.users;
            window.levels = response.levels;

            // KPI Total (contador simple)
            $('#totalUsersCount').text(window.users.length);

            if ($.fn.DataTable.isDataTable('#myTableUsers')) {
                tableUsers.clear().rows.add(window.users).draw();
            } else {
                tableUsers = new DataTable('#myTableUsers', {
                    data: window.users,
                    responsive: true,
                    // Diseño simple de la tabla
                    dom: '<"row mb-3"<"col-md-6"f>>t<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                    columns: [
                        { data: "pk_user" },
                        { data: "pk_phone" }, // Teléfono
                        { title: "Nombre", data: "person" },
                        { title: "Paterno", data: "first_name" },
                        { title: "Materno", data: "last_name" },
                        { title: "Rol", data: "level" }, // Mostramos el nombre del rol directamente
                        {
                            title: "Acciones",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                // BOTONES SIMPLES Y CLAROS
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-primary edit-btn" 
                                            data-pk_user="${row.pk_user}"
                                            data-phone="${row.pk_phone}"
                                            data-name="${row.person}"
                                            data-fname="${row.first_name}"
                                            data-lname="${row.last_name}">
                                            Editar
                                        </button>
                                        
                                        <button class="btn btn-secondary lock-btn" data-pk_user="${row.pk_user}">
                                            Bloquear
                                        </button>
                                        
                                        <button class="btn btn-danger delete-btn" data-pk_user="${row.pk_user}">
                                            Borrar
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
                });
            }
            actionTable();
        }
    });
}

// --- LÓGICA DE LOS BOTONES ---
function actionTable() {

    // Botón Editar
    $('#myTableUsers tbody').on('click', '.edit-btn', function () {
        const d = $(this).data();
        $('#original_phone').val(d.phone);
        $('#edit_phone').val(d.phone);
        $('#edit_person').val(d.name);
        $('#edit_first_name').val(d.fname);
        $('#edit_last_name').val(d.lname);

        // Abrir el modal de Bootstrap
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    });

    // Botón Bloquear
    $('#myTableUsers tbody').on('click', '.lock-btn', function () {
        sendLockUser($(this).data('pk_user'));
    });

    // Botón Borrar
    $('#myTableUsers tbody').on('click', '.delete-btn', function () {
        sendDeleteUser($(this).data('pk_user'));
    });
}

// --- GUARDAR EDICIÓN ---
function saveEditedUser() {
    var formData = {
        'original_phone': $('#original_phone').val(),
        'new_phone': $('#edit_phone').val(),
        'person': $('#edit_person').val(),
        'first_name': $('#edit_first_name').val(),
        'last_name': $('#edit_last_name').val()
    };

    $.ajax({
        type: "POST",
        url: BASE_URL + "/admin/updateUser",
        data: formData,
        dataType: 'json',
        success: function (res) {
            if (res.status == 1) {
                // Cerrar modal y recargar
                const modalEl = document.getElementById('editUserModal');
                bootstrap.Modal.getInstance(modalEl).hide();
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');

                Swal.fire({ icon: 'success', title: 'Guardado', showConfirmButton: false, timer: 1500 });
                myTable(); // Recargar tabla
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }
    });
}

// --- FUNCIONES AUXILIARES (Bloquear/Borrar) ---
function sendLockUser(pk) {
    Swal.fire({
        title: '¿Bloquear usuario?',
        text: 'No podrá acceder al sistema',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, bloquear',
        cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) ajaxPost(BASE_URL + "/admin/toggleLock", { 'pkUser': pk });
    });
}

function sendDeleteUser(pk) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: "Esta acción no se puede deshacer.",
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#d33'
    }).then((r) => {
        if (r.isConfirmed) ajaxPost(BASE_URL + "/admin/deleteUser", { 'pkUser': pk });
    });
}

function ajaxPost(url, data) {
    $.ajax({
        type: "POST", url: url, data: data, dataType: 'json',
        success: function(r) {
            if(r.status == 1) {
                Swal.fire({ icon: 'success', title: 'Listo', showConfirmButton: false, timer: 1000 });
                myTable();
            } else {
                Swal.fire('Error', r.message, 'error');
            }
        }
    });
}