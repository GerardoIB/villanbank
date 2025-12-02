// resources/js/admin-accounts.js

$(document).ready(function() {
    loadAccountsTable();
});

function loadAccountsTable() {
    const url = BASE_URL + "/admin/readAccountsJson";

    if ($.fn.DataTable.isDataTable('#adminAccountsTable')) {
        $('#adminAccountsTable').DataTable().ajax.reload();
        return;
    }

    $('#adminAccountsTable').DataTable({
        ajax: {
            url: url,
            dataSrc: "accounts"
        },
        // --- AQUÍ ESTÁ LA MAGIA ---
        // 'B' activa los Botones. Los ponemos a la izquierda y el buscador 'f' a la derecha.
        dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>t<"mt-3"p>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel me-2"></i>Excel',
                className: 'btn btn-success btn-sm', // Botón Verde
                title: 'Reporte_Cuentas_Lumina',
                exportOptions: { columns: [0, 1, 2, 3] } // No exportamos la columna "Acciones"
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf me-2"></i>PDF',
                className: 'btn btn-danger btn-sm', // Botón Rojo
                title: 'Reporte de Capital - Lumina Financial',
                exportOptions: { columns: [0, 1, 2, 3] },
                customize: function (doc) {
                    // Centrar la tabla en el PDF
                    doc.content[1].table.widths = [ '20%', '40%', '20%', '20%' ];
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-2"></i>Imprimir',
                className: 'btn btn-secondary btn-sm', // Botón Gris
                exportOptions: { columns: [0, 1, 2, 3] }
            }
        ],
        // ---------------------------
        columns: [
            {
                data: "pk_account",
                width: "15%",
                render: function(data) {
                    return `<span class="badge bg-light text-secondary border">#${data}</span>`;
                }
            },
            {
                data: null,
                render: function(row) {
                    return `
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark">${row.person}</span>
                            <span class="small text-muted">${row.first_name} ${row.last_name}</span>
                        </div>
                    `;
                }
            },
            {
                data: "balance",
                className: "text-end",
                render: function(data) {
                    const amount = $.fn.dataTable.render.number(',', '.', 2, '$ ').display(data);
                    return `<span class="fw-bold text-success" style="font-family: monospace; font-size: 1.1em;">${amount}</span>`;
                }
            },
            {
                data: "state",
                className: "text-center",
                width: "10%",
                render: function(data) {
                    if (data === 'active') {
                        return '<span class="badge bg-success bg-opacity-10 text-success border border-success px-3">Activa</span>';
                    } else {
                        return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3">Bloqueada</span>';
                    }
                }
            },
            {
                data: null,
                className: "text-center",
                width: "25%",
                render: function(data, type, row) {
                    // Botones de acción limpios
                    let btnStatus = '';
                    if (row.state === 'active') {
                        btnStatus = `
                            <button class="btn btn-sm btn-light text-warning border me-2" onclick="toggleAccount(${row.pk_account})">
                                <i class="fas fa-ban"></i> Bloquear
                            </button>
                        `;
                    } else {
                        btnStatus = `
                            <button class="btn btn-sm btn-light text-success border me-2" onclick="toggleAccount(${row.pk_account})">
                                <i class="fas fa-check"></i> Activar
                            </button>
                        `;
                    }
                    const btnDelete = `
                        <button class="btn btn-sm btn-light text-danger border" onclick="deleteAccount(${row.pk_account})">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    `;
                    return btnStatus + btnDelete;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
    });
}

function toggleAccount(id) {
    Swal.fire({
        title: '¿Cambiar Estado?',
        text: "Confirmar cambio de estado de la cuenta.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Actualizado', 'Estado modificado correctamente.', 'success');
            // $('#adminAccountsTable').DataTable().ajax.reload();
        }
    });
}

function deleteAccount(id) {
    Swal.fire({
        title: '¿Eliminar Cuenta?',
        text: "Esta acción borrará los fondos y el historial. Es irreversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: BASE_URL + "/admin/deleteAccount",
                data: { pk_account: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 1) {
                        Swal.fire('Eliminado', res.message, 'success');
                        $('#adminAccountsTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error de conexión.', 'error');
                }
            });
        }
    });
}