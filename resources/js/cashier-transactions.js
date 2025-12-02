// resources/js/cashier-transactions.js

let historyTable;

$(document).ready(function() {
    loadHistory();
});

function loadHistory() {
    historyTable = $('#cashierHistoryTable').DataTable({
        ajax: {
            url: BASE_URL + "/cashier/readHistoryJson",
            dataSrc: "transactions"
        },
        order: [[ 5, "desc" ]],
        dom: 't<"d-flex justify-content-between align-items-center p-3"ip>',

        columns: [
            { data: "pk_transaction", render: d => `<span class="text-muted small ps-3">#${d}</span>` },
            { data: "fk_account", render: d => `<span class="fw-bold text-dark">${d}</span>` },
            { data: "person" },
            {
                data: "type",
                className: "text-center",
                render: d => d === 'deposito'
                    ? '<span class="badge-tx bg-soft-success">Depósito</span>'
                    : '<span class="badge-tx bg-soft-danger">Retiro</span>'
            },
            {
                data: null,
                className: "text-end",
                render: row => {
                    const color = row.type === 'deposito' ? 'text-deposit' : 'text-withdraw';
                    const sign = row.type === 'deposito' ? '+' : '-';
                    const amount = parseFloat(row.amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    return `<span class="${color} tx-amount">${sign} $${amount}</span>`;
                }
            },
            { data: "create_at", className: "text-end pe-4 text-muted small" }
        ],

        // ✅ AQUÍ ESTÁ LA MAGIA PARA LAS LÍNEAS DE COLOR
        createdRow: function(row, data, dataIndex) {
            if (data.type === 'deposito') {
                $(row).addClass('tx-deposit');
            } else {
                $(row).addClass('tx-withdraw');
            }
        },

        language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
    });
}

// --- FUNCIONES DEL MODAL (Se mantienen igual) ---

function openOperationModal() {
    $('#op_account_search').val('');
    $('#op_amount').val('');
    $('#account_feedback').html('').removeClass('text-success text-danger');
    new bootstrap.Modal(document.getElementById('operationModal')).show();
}

// Validación REAL de la cuenta
function searchAccountInfo() {
    const accountId = $('#op_account_search').val();
    const feedback = $('#account_feedback');

    // Limpiamos mensajes previos
    feedback.html('<span class="text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>').removeClass('text-success text-danger text-warning');

    if (!accountId) {
        feedback.html('<span class="text-danger">Ingrese un número.</span>');
        return;
    }

    $.ajax({
        type: "POST",
        url: BASE_URL + "/cashier/validateAccount", // Nuevo Endpoint
        data: { account_id: accountId },
        dataType: 'json',
        success: function(res) {
            if (res.status == 1) {
                // CUENTA ENCONTRADA
                if (res.state === 'active') {
                    // Activa y Lista
                    feedback.html(`<div class="alert alert-success py-1 px-2 mb-0 mt-2 d-flex align-items-center">
                                    <i class="fas fa-user-check me-2"></i>
                                    <div>
                                        <small class="d-block text-uppercase fw-bold" style="font-size:0.65rem">Titular</small>
                                        <span class="fw-bold">${res.owner}</span>
                                    </div>
                                   </div>`);
                } else {
                    // Cuenta Bloqueada (Advertencia)
                    feedback.html(`<div class="alert alert-warning py-1 px-2 mb-0 mt-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> 
                                    Cuenta BLOQUEADA (${res.owner})
                                   </div>`);
                }
            } else {
                // ERROR (No existe)
                feedback.html(`<span class="text-danger fw-bold"><i class="fas fa-times-circle me-1"></i> ${res.message}</span>`);
            }
        },
        error: function() {
            feedback.html('<span class="text-danger">Error de conexión.</span>');
        }
    });
}

function processOperation() {
    const account = $('#op_account_search').val();
    const type = $('input[name="op_type"]:checked').val();
    const amount = $('#op_amount').val();

    if(!account || amount <= 0) {
        Swal.fire({ icon: 'warning', title: 'Datos inválidos', text: 'Revise la cuenta y el monto.' });
        return;
    }

    $.ajax({
        type: "POST",
        url: BASE_URL + "/cashier/processTransaction",
        data: { pk_account: account, type: type, amount: amount },
        dataType: 'json',
        success: function(res) {
            if(res.status == 1) {
                const modalEl = document.getElementById('operationModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide();

                Swal.fire({ icon: 'success', title: 'Operación Exitosa', text: res.message, timer: 2000, showConfirmButton: false });
                historyTable.ajax.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            }
        },
        error: function() {
            Swal.fire('Error', 'Fallo de conexión con el servidor', 'error');
        }
    });
}