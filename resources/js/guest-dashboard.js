// resources/js/guest-dashboard.js - ADAPTADO A TU API ACTUAL

$(document).ready(function() {
    loadMyHistory();
    loadFacturasWidget();
});

// ===== FUNCIÓN EXISTENTE DE HISTORIAL =====
function loadMyHistory() {
    const url = BASE_URL + "/guest/readMyHistory"; // ✅ CORREGIDO

    $('#myHistoryTable').DataTable({
        ajax: {
            url: url,
            dataSrc: "transactions"
        },
        order: [[ 3, "desc" ]],
        dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>t<"mt-3"p>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel me-1"></i> Descargar Excel',
                className: 'btn btn-outline-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-outline-danger btn-sm'
            }
        ],
        columns: [
            {
                data: "description",
                render: d => `<span class="fw-bold text-dark">${d || 'Movimiento'}</span>`
            },
            {
                data: "type",
                className: "text-center",
                render: function(d) {
                    if (d === 'deposito') {
                        return '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Ingreso</span>';
                    } else {
                        return '<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Egreso</span>';
                    }
                }
            },
            {
                data: null,
                className: "text-end",
                render: function(row) {
                    const amount = $.fn.dataTable.render.number(',', '.', 2, '$ ').display(row.amount);
                    if(row.type === 'deposito') {
                        return `<span class="fw-bold text-success">+ ${amount}</span>`;
                    } else {
                        return `<span class="fw-bold text-danger">- ${amount}</span>`;
                    }
                }
            },
            {
                data: "create_at",
                className: "text-end text-muted small"
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
    });
}

// ===== FUNCIÓN ADAPTADA PARA TU API =====
function loadFacturasWidget() {
    const url = BASE_URL + "/guest/readMyFacturas";

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta API:', response); // Debug
            
            // ADAPTACIÓN: Normalizar datos de tu API al formato esperado
            const facturasNormalizadas = (response.facturas || []).map(f => ({
                pk_factura: f.id,
                numero_factura: `FAC-${f.id}`,
                concepto: 'Factura Pendiente', // Valor por defecto
                monto: f.ammount || f.amount || 0,
                estado: f.status || 'pendiente',
                fecha_vencimiento: f.dueDate || new Date().toISOString().split('T')[0],
                proveedor: 'N/A',
                categoria: 'General'
            }));
            
            console.log('Facturas normalizadas:', facturasNormalizadas);
            renderizarWidgetFacturas(facturasNormalizadas);
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar facturas:', {
                status: xhr.status,
                statusText: xhr.statusText,
                error: error,
                response: xhr.responseText
            });
            $('#widgetFacturasList').html(`
                <div class="no-facturas">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="mb-0">Error al cargar facturas</p>
                </div>
            `);
        }
    });
}

function renderizarWidgetFacturas(facturas) {
    console.log('Total facturas:', facturas.length);
    
    // Filtrar solo pendientes y vencidas
    const facturasPendientes = facturas.filter(f => 
        f.estado === 'pendiente' || f.estado === 'vencida'
    );

    // Calcular estadísticas
    const totalFacturas = facturasPendientes.length;
    const totalPendientes = facturas.filter(f => f.estado === 'pendiente').length;
    const montoPendiente = facturasPendientes.reduce((sum, f) => 
        sum + parseFloat(f.monto || 0), 0
    );

    // Actualizar estadísticas
    $('#widgetTotalFacturas').text(totalFacturas);
    $('#widgetFacturasPendientes').text(totalPendientes);
    $('#widgetMontoPendiente').text(`$${montoPendiente.toLocaleString('es-MX', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })}`);

    // Renderizar lista
    const container = $('#widgetFacturasList');

    if (facturasPendientes.length === 0) {
        container.html(`
            <div class="no-facturas">
                <i class="fas fa-check-circle"></i>
                <p class="mb-0">¡No tienes facturas pendientes!</p>
            </div>
        `);
        return;
    }

    // Ordenar por fecha de vencimiento
    facturasPendientes.sort((a, b) => 
        new Date(a.fecha_vencimiento) - new Date(b.fecha_vencimiento)
    );

    // Mostrar solo las primeras 5
    const facturasLimitadas = facturasPendientes.slice(0, 5);

    let html = '';
    facturasLimitadas.forEach(factura => {
        const claseTipo = obtenerClaseTipoWidget(factura);
        const badgeVencimiento = obtenerBadgeVencimiento(factura);

        html += `
            <div class="factura-item ${claseTipo}">
                <div class="factura-info">
                    <h6>${factura.concepto}</h6>
                    <p>
                        #${factura.numero_factura} • 
                        Vence: ${formatearFechaCorta(factura.fecha_vencimiento)}
                    </p>
                    ${badgeVencimiento}
                </div>
                <div class="factura-amount">
                    $${parseFloat(factura.monto).toLocaleString('es-MX', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}
                </div>
            </div>
        `;
    });

    if (facturasPendientes.length > 5) {
        html += `
            <div class="text-center mt-2">
                <small style="opacity: 0.8;">
                    +${facturasPendientes.length - 5} facturas más
                </small>
            </div>
        `;
    }

    container.html(html);
}

function obtenerClaseTipoWidget(factura) {
    if (factura.estado === 'vencida') {
        return 'factura-vencida';
    }

    const hoy = new Date();
    const vencimiento = new Date(factura.fecha_vencimiento);
    const diasRestantes = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));

    if (diasRestantes <= 5 && factura.estado === 'pendiente') {
        return 'factura-proxima';
    }

    return '';
}

function obtenerBadgeVencimiento(factura) {
    const hoy = new Date();
    const vencimiento = new Date(factura.fecha_vencimiento);
    const dias = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));

    if (dias < 0) {
        return `<span class="badge-vencimiento" style="background: rgba(255, 71, 87, 0.3);">
                    <i class="fas fa-exclamation-triangle"></i> Vencida hace ${Math.abs(dias)} días
                </span>`;
    } else if (dias === 0) {
        return `<span class="badge-vencimiento" style="background: rgba(255, 217, 61, 0.3);">
                    <i class="fas fa-clock"></i> Vence HOY
                </span>`;
    } else if (dias <= 5) {
        return `<span class="badge-vencimiento" style="background: rgba(255, 217, 61, 0.3);">
                    <i class="fas fa-clock"></i> Vence en ${dias} día${dias > 1 ? 's' : ''}
                </span>`;
    }

    return '';
}

function formatearFechaCorta(fecha) {
    const date = new Date(fecha);
    const options = { day: 'numeric', month: 'short' };
    return date.toLocaleDateString('es-MX', options);
}