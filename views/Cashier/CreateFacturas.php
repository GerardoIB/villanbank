<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        .preview-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .preview-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .quick-amount-btn {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .account-info-box {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        .account-info-box.error {
            background: #ffe5e5;
            border-left-color: #dc3545;
        }

        .account-info-box.success {
            background: #d4edda;
            border-left-color: #28a745;
        }

        @media (max-width: 768px) {
            .quick-amounts {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-light">
    <a class="navbar-brand" href="#">Ventanilla de Caja</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 text-secondary" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= app_url ?>/auth/logout">Salir</a>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <?php require_once(BASE_PATH . '/views/partials/menuCashier.php'); ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <div>
                        <h1 class="h3 mb-1">
                            <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                            Crear Nueva Factura
                        </h1>
                        <p class="text-muted mb-0">
                            Cajero: <strong><?php echo htmlspecialchars($dashboard['name'] ?? 'Cajero'); ?></strong>
                        </p>
                    </div>
                    <a href="<?= app_url ?>/cashier" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>

                <div class="row">
                    <!-- Formulario -->
                    <div class="col-lg-7">
                        <form id="createFacturaForm">
                            
                            <!-- Sección: Buscar Cliente -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    Datos del Cliente
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Número de Cuenta o Teléfono</label>
                                    <div class="input-group">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="buscarCliente" 
                                            placeholder="Ej: 4658 o 7227495659"
                                        >
                                        <button 
                                            class="btn btn-primary" 
                                            type="button" 
                                            onclick="buscarCliente()"
                                        >
                                            <i class="fas fa-search me-1"></i>Buscar
                                        </button>
                                    </div>
                                </div>

                                <div id="clienteInfo"></div>
                            </div>

                            <!-- Sección: Datos de la Factura -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-file-alt text-primary"></i>
                                    Información de la Factura
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Número de Factura *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="numeroFactura" 
                                            placeholder="FAC-2024-XXX"
                                            required
                                        >
                                        <small class="text-muted">Se generará automáticamente si se deja vacío</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Categoría *</label>
                                        <select class="form-select" id="categoria" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="Servicios">Servicios</option>
                                            <option value="Telecomunicaciones">Telecomunicaciones</option>
                                            <option value="Entretenimiento">Entretenimiento</option>
                                            <option value="Financiero">Financiero</option>
                                            <option value="Seguros">Seguros</option>
                                            <option value="Vivienda">Vivienda</option>
                                            <option value="Deportes">Deportes</option>
                                            <option value="Alimentación">Alimentación</option>
                                            <option value="Educación">Educación</option>
                                            <option value="Salud">Salud</option>
                                            <option value="Automotriz">Automotriz</option>
                                            <option value="Otros">Otros</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Concepto *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="concepto" 
                                        placeholder="Ej: Pago de servicio de luz"
                                        required
                                    >
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea 
                                        class="form-control" 
                                        id="descripcion" 
                                        rows="2"
                                        placeholder="Detalles adicionales (opcional)"
                                    ></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Proveedor</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="proveedor" 
                                        placeholder="Ej: CFE, TelMex, Netflix..."
                                    >
                                </div>
                            </div>

                            <!-- Sección: Montos y Fechas -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-dollar-sign text-primary"></i>
                                    Montos y Fechas
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Monto ($) *</label>
                                    <input 
                                        type="number" 
                                        class="form-control form-control-lg" 
                                        id="monto" 
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        required
                                        oninput="actualizarPreview()"
                                    >
                                    
                                    <div class="quick-amounts mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-amount-btn" onclick="setMonto(100)">$100</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-amount-btn" onclick="setMonto(250)">$250</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-amount-btn" onclick="setMonto(500)">$500</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-amount-btn" onclick="setMonto(1000)">$1,000</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fecha de Emisión *</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            id="fechaEmision"
                                            required
                                        >
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fecha de Vencimiento *</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            id="fechaVencimiento"
                                            required
                                            oninput="actualizarPreview()"
                                        >
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Estado Inicial</label>
                                    <select class="form-select" id="estadoInicial">
                                        <option value="pendiente" selected>Pendiente</option>
                                        <option value="vencida">Vencida</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                                <button type="button" class="btn btn-light px-4" onclick="limpiarFormulario()">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </button>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-check me-2"></i>Crear Factura
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Preview -->
                    <div class="col-lg-5">
                        <div class="sticky-top" style="top: 20px;">
                            <div class="preview-card">
                                <h5 class="mb-3">
                                    <i class="fas fa-eye me-2"></i>Vista Previa
                                </h5>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Cliente:</span>
                                    <span class="preview-value" id="prev_cliente">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Cuenta:</span>
                                    <span class="preview-value" id="prev_cuenta">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Concepto:</span>
                                    <span class="preview-value" id="prev_concepto">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Categoría:</span>
                                    <span class="preview-value" id="prev_categoria">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Monto:</span>
                                    <span class="preview-value" id="prev_monto">$0.00</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Vencimiento:</span>
                                    <span class="preview-value" id="prev_vencimiento">-</span>
                                </div>
                                
                                <div class="preview-item">
                                    <span class="preview-label">Estado:</span>
                                    <span class="preview-value">
                                        <span class="badge bg-warning" id="prev_estado">Pendiente</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Ayuda -->
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        Instrucciones
                                    </h6>
                                    <ol class="small mb-0">
                                        <li>Busca al cliente por cuenta o teléfono</li>
                                        <li>Completa los datos de la factura</li>
                                        <li>Verifica la vista previa</li>
                                        <li>Crea la factura</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php require_once(BASE_PATH . '/views/partials/footer.php'); ?>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/cashier-create-factura.js?v=1.0"></script>
</body>
</html>