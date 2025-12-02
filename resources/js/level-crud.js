let tableLevels;

$(document).ready(function() {
    loadTable();
});

function loadTable() {
    if ($.fn.DataTable.isDataTable('#tableLevels')) {
        tableLevels.ajax.reload();
        return;
    }

    tableLevels = $('#tableLevels').DataTable({
        ajax: {
            url: BASE_URL + "/level/read",
            dataSrc: "levels"
        },
        dom: '<"mb-2"f>t<"mt-3"p>', // Diseño minimalista
        columns: [
            {
                data: "pk_level",
                width: "10%",
                render: function(data) {
                    // ID discreto
                    return `<span class="text-muted fw-bold">#${data}</span>`;
                }
            },
            {
                data: "level",
                render: function(data) {
                    // Nombre del rol resaltado
                    return `<span class="fs-6 fw-bold text-dark"><i class="fas fa-user-tag text-primary me-2 opacity-50"></i>${data}</span>`;
                }
            },
            {
                data: null,
                className: "text-end", // Botones a la derecha
                width: "30%",
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-light text-primary border me-2" onclick="editLevel(${row.pk_level}, '${row.level}')">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-light text-danger border" onclick="deleteLevel(${row.pk_level})">
                            <i class="fas fa-trash"></i> Borrar
                        </button>
                    `;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
    });
}

// --- FUNCIONES DEL MODAL (Igual que antes, pero necesarias) ---

function openModal() {
    $('#formMode').val('create');
    $('#level_name').val('');
    $('#modalTitle').text('Crear Nuevo Rol');
    $('#pk_level').val('').prop('disabled', false);

    // Obtener ID sugerido
    $.ajax({
        url: BASE_URL + "/level/nextId",
        type: "GET",
        dataType: "json",
        success: function (response) {
            $('#pk_level').val(response.nextId);
            new bootstrap.Modal(document.getElementById('levelModal')).show();
        },
        error: function() {
            // Si falla, abre el modal vacío
            new bootstrap.Modal(document.getElementById('levelModal')).show();
        }
    });
}

function editLevel(id, name) {
    $('#formMode').val('update');
    $('#pk_level').val(id).prop('disabled', true);
    $('#level_name').val(name);
    $('#modalTitle').text('Editar Rol');

    new bootstrap.Modal(document.getElementById('levelModal')).show();
}

function saveLevel() {
    const mode = $('#formMode').val();
    const id = $('#pk_level').val();
    const name = $('#level_name').val();

    if(!id || !name) {
        Swal.fire('Ups', 'Por favor llena todos los campos.', 'warning');
        return;
    }

    const url = mode === 'create'
        ? BASE_URL + "/level/create"
        : BASE_URL + "/level/update";

    $.ajax({
        type: "POST",
        url: url,
        data: { pk_level: id, level_name: name },
        dataType: 'json',
        success: function(res) {
            // Cerrar modal
            const modalEl = document.getElementById('levelModal');
            bootstrap.Modal.getInstance(modalEl).hide();

            if(res.status == 1) {
                Swal.fire({icon: 'success', title: 'Guardado', showConfirmButton: false, timer: 1200});
                tableLevels.ajax.reload();
            } else {
                Swal.fire('Error', 'No se pudo guardar.', 'error');
            }
        }
    });
}

function deleteLevel(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás eliminar roles que tengan usuarios asignados.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: BASE_URL + "/level/delete",
                data: { pk_level: id },
                dataType: 'json',
                success: function(res) {
                    if(res.status == 1) {
                        Swal.fire('Listo', 'Rol eliminado.', 'success');
                        tableLevels.ajax.reload();
                    } else {
                        Swal.fire('Error', 'No se puede eliminar (quizás está en uso).', 'error');
                    }
                }
            });
        }
    });
}