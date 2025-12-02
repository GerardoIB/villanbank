// resources/js/guest-facturas.js - ADAPTADO A TU API

let facturasData = [];
let facturaSeleccionada = null;

$(document).ready(function() {
    cargarFacturas();
    inicializarEventos();
});

function inicializarEventos() {
    // Filtro por estado
    $('#filtroEstado').on('change', function() {
        filtrarFacturas();
    });

    // Búsqueda en tiempo real
    $('#buscarFactura').on('keyup', function() {
        filtrarFacturas();
    });
}

function cargarFacturas() {
    const url = BASE_URL + "/guest/readMyFacturas";

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta API:', response);
            
            // ADAPTACIÓN: Normalizar datos de tu API
            facturasData = (response.facturas || []).map(f => ({
                pk_factura: f.id,
                numero_factura: `FAC-${f.id}`,
                concepto: f.concept || 'Factura Pendiente',
                descripcion: f.description || '',
                monto: parseFloat(f.ammount || f.amount || 0),
                fecha_emision: f.issueDate || new Date().toISOString().split('T')[0],
                fecha_vencimiento: f.dueDate || new Date().toISOString().split('T')[0],
                estado: f.status || 'pendiente',
                proveedor: f.provider || 'N/A',
                categoria: f.category || 'General'
            }));
            
            console.log('Facturas normalizadas:', facturasData);
            
            renderizarFacturas(facturasData);
            actualizarEstadisticas(facturasData);
            inicializarTabla(facturasData);
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar facturas:', error);
            $('#facturasContainer').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar las facturas. Por favor, intenta más tarde.
                    <br><small>Error: ${xhr.status} - ${error}</small>
                </div>
            `);
        }
    });
}

function renderizarFacturas(facturas) {
    const container = $('#facturasContainer');
    
    if (facturas.length === 0) {
        container.html(`
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No tienes facturas registradas</p>
            </div>
        `);
        return;
    }

    let html = '';
    
    facturas.forEach(factura => {
        const claseTipo = obtenerClaseTipo(factura);
        const badgeEstado = obtenerBadgeEstado(factura.estado);
        const diasVencimiento = calcularDiasVencimiento(factura.fecha_vencimiento);
        
        html += `
            <div class="factura-card ${claseTipo}">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                    <div class="col-md-5">
                        <h6 class="mb-1 fw-bold">Factura #${factura.numero_factura}</h6>
                        <p class="text-muted mb-1 small">${factura.concepto}</p>
                        <span class="badge bg-light text-dark small">
                            <i class="far fa-calendar-alt me-1"></i>
                            Vence: ${formatearFecha(factura.fecha_vencimiento)}
                        </span>
                        ${diasVencimiento}
                    </div>
                    <div class="col-md-3 text-center">
                        <p class="text-muted small mb-1">Monto</p>
                        <div class="factura-amount">
                            $${factura.monto.toLocaleString('es-MX', {minimumFractionDigits: 2})}
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        ${badgeEstado}
                        <div class="mt-2">
                            ${factura.estado === 'pendiente' || factura.estado === 'vencida' ? 
                                `<button class="btn btn-success btn-sm" onclick="abrirModalPago(${factura.pk_factura})">
                                    <i class="fas fa-credit-card me-1"></i>Pagar
                                </button>` : 
                                `<button class="btn btn-outline-secondary btn-sm" onclick="verDetalle(${factura.pk_factura})">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </button>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.html(html);
}

function obtenerClaseTipo(factura) {
    if (factura.estado === 'vencida') return 'vencida';
    
    const hoy = new Date();
    const vencimiento = new Date(factura.fecha_vencimiento);
    const diasRestantes = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));
    
    if (diasRestantes <= 5 && factura.estado === 'pendiente') return 'proxima';
    return 'normal';
}

function obtenerBadgeEstado(estado) {
    const badges = {
        'pendiente': '<span class="badge bg-warning text-dark">Pendiente</span>',
        'pagado': '<span class="badge bg-success">Pagada</span>',
        'vencida': '<span class="badge bg-danger">Vencida</span>',
        'cancelada': '<span class="badge bg-secondary">Cancelada</span>'
    };
    return badges[estado] || '<span class="badge bg-secondary">Desconocido</span>';
}

function calcularDiasVencimiento(fechaVencimiento) {
    const hoy = new Date();
    const vencimiento = new Date(fechaVencimiento);
    const dias = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));
    
    if (dias < 0) {
        return `<span class="badge bg-danger mt-1">Vencida hace ${Math.abs(dias)} días</span>`;
    } else if (dias <= 5) {
        return `<span class="badge bg-warning text-dark mt-1">Vence en ${dias} días</span>`;
    }
    return '';
}

function formatearFecha(fecha) {
    const date = new Date(fecha);
    return date.toLocaleDateString('es-MX', { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric' 
    });
}

function actualizarEstadisticas(facturas) {
    const total = facturas.length;
    const pendientes = facturas.filter(f => f.estado === 'pendiente').length;
    const vencidas = facturas.filter(f => f.estado === 'vencida').length;
    const montoTotal = facturas
        .filter(f => f.estado !== 'pagada')
        .reduce((sum, f) => sum + parseFloat(f.monto), 0);

    $('#totalFacturas').text(total);
    $('#totalPendientes').text(pendientes);
    $('#totalVencidas').text(vencidas);
    $('#totalMonto').text(`$${montoTotal.toLocaleString('es-MX', {minimumFractionDigits: 2})}`);
}

function filtrarFacturas() {
    const estado = $('#filtroEstado').val();
    const busqueda = $('#buscarFactura').val().toLowerCase();

    let facturasFiltradas = facturasData;

    if (estado) {
        facturasFiltradas = facturasFiltradas.filter(f => f.estado === estado);
    }

    if (busqueda) {
        facturasFiltradas = facturasFiltradas.filter(f => 
            f.numero_factura.toLowerCase().includes(busqueda) ||
            f.concepto.toLowerCase().includes(busqueda)
        );
    }

    renderizarFacturas(facturasFiltradas);
}

function inicializarTabla(facturas) {
    if ($.fn.DataTable.isDataTable('#facturasTable')) {
        $('#facturasTable').DataTable().destroy();
    }

    $('#facturasTable').DataTable({
        data: facturas,
        order: [[ 3, "asc" ]],
        dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>t<"mt-3"p>',
        columns: [
            { 
                data: "numero_factura",
                render: d => `<strong>${d}</strong>`
            },
            { data: "concepto" },
            {
                data: "monto",
                className: "text-end",
                render: d => `<strong>$${parseFloat(d).toLocaleString('es-MX', {minimumFractionDigits: 2})}</strong>`
            },
            {
                data: "fecha_vencimiento",
                className: "text-center",
                render: d => formatearFecha(d)
            },
            {
                data: "estado",
                className: "text-center",
                render: d => obtenerBadgeEstado(d)
            },
            {
                data: null,
                className: "text-center",
                render: function(row) {
                    if (row.estado === 'pendiente' || row.estado === 'vencida') {
                        return `<button class="btn btn-success btn-sm" onclick="abrirModalPago(${row.pk_factura})">
                                    <i class="fas fa-credit-card"></i>
                                </button>`;
                    }
                    return `<button class="btn btn-outline-secondary btn-sm" onclick="verDetalle(${row.pk_factura})">
                                <i class="fas fa-eye"></i>
                            </button>`;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json" }
    });
}

function abrirModalPago(idFactura) {
    const factura = facturasData.find(f => f.pk_factura == idFactura);
    
    if (!factura) return;

    facturaSeleccionada = factura;
    
    $('#modalFacturaNum').text(factura.numero_factura);
    $('#modalFacturaConcepto').text(factura.concepto);
    $('#modalMonto').val(`$${parseFloat(factura.monto).toLocaleString('es-MX', {minimumFractionDigits: 2})}`);
    
    const modal = new bootstrap.Modal(document.getElementById('modalPagar'));
    modal.show();
}

function confirmarPago() {
    if (!facturaSeleccionada) return;

    Swal.fire({
        title: '¿Confirmar Pago?',
        text: `Se pagará la factura #${facturaSeleccionada.numero_factura} por $${parseFloat(facturaSeleccionada.monto).toFixed(2)}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, pagar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            procesarPago(facturaSeleccionada.pk_factura);
        }
    });
}

function procesarPago(idFactura) {
    const metodoPago = $('#metodoPago').val();
    
    $.ajax({
        url: BASE_URL + '/guest/pagarFactura',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            idFactura: idFactura,
            metodoPago: metodoPago
        }),
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pago Exitoso',
                    text: response.message || 'La factura ha sido pagada correctamente',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    $('#modalPagar').modal('hide');
                    cargarFacturas(); // Recargar facturas
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo procesar el pago',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

function verDetalle(idFactura) {
    const factura = facturasData.find(f => f.pk_factura == idFactura);
    
    if (!factura) return;

    Swal.fire({
        title: `Factura #${factura.numero_factura}`,
        html: `
            <div class="text-start">
                <p><strong>Concepto:</strong> ${factura.concepto}</p>
                <p><strong>Monto:</strong> $${parseFloat(factura.monto).toFixed(2)}</p>
                <p><strong>Fecha de emisión:</strong> ${formatearFecha(factura.fecha_emision)}</p>
                <p><strong>Fecha de vencimiento:</strong> ${formatearFecha(factura.fecha_vencimiento)}</p>
                <p><strong>Estado:</strong> ${factura.estado}</p>
                ${factura.proveedor !== 'N/A' ? `<p><strong>Proveedor:</strong> ${factura.proveedor}</p>` : ''}
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

function exportarFacturas() {
    Swal.fire({
        title: 'Exportar Facturas',
        text: 'Selecciona el formato de exportación',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-file-excel"></i> Excel',
        cancelButtonText: '<i class="fas fa-file-pdf"></i> PDF',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            exportarExcel();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            exportarPDF();
        }
    });
}

function exportarExcel() {
    Swal.fire('Excel', 'Funcionalidad en desarrollo', 'info');
}

function exportarPDF() {
    Swal.fire('PDF', 'Funcionalidad en desarrollo', 'info');
}