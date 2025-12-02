<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        .factura-card {
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .factura-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .factura-card.vencida {
            border-left: 4px solid #dc3545;
        }

        .factura-card.proxima {
            border-left: 4px solid #ffc107;
        }

        .factura-card.normal {
            border-left: 4px solid #28a745;
        }

        .factura-amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-item {
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
        }

        .stats-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
    </style>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-light">
    <a class="navbar-brand" href="#">Banca en Línea</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 text-secondary" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= app_url ?>/auth/logout">Cerrar Sesión</a>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <?php require_once(BASE_PATH . '/views/partials/menuGuest.php'); ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">

                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <div>
                        <h1 class="h3 text-dark">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Mis Facturas
                        </h1>
                        <p class="text-muted mb-0">Administra tus pagos pendientes</p>
                    </div>
                    <a href="<?= app_url ?>/guest" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                    </a>
                </div>

                <!-- Estadísticas -->
                <div class="stats-box">
                    <div class="row">
                        <div class="col-md-3 stats-item border-end">
                            <span class="stats-number" id="totalFacturas">0</span>
                            <span class="stats-label">Total Facturas</span>
                        </div>
                        <div class="col-md-3 stats-item border-end">
                            <span class="stats-number text-warning" id="totalPendientes">0</span>
                            <span class="stats-label">Pendientes</span>
                        </div>
                        <div class="col-md-3 stats-item border-end">
                            <span class="stats-number text-danger" id="totalVencidas">0</span>
                            <span class="stats-label">Vencidas</span>
                        </div>
                        <div class="col-md-3 stats-item">
                            <span class="stats-number" id="totalMonto">$0.00</span>
                            <span class="stats-label">Monto Total</span>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Filtrar por estado</label>
                                <select id="filtroEstado" class="form-select">
                                    <option value="">Todas las facturas</option>
                                    <option value="pendiente">Pendientes</option>
                                    <option value="pagada">Pagadas</option>
                                    <option value="vencida">Vencidas</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Buscar</label>
                                <input type="text" id="buscarFactura" class="form-control" placeholder="Número o concepto...">
                            </div>
                            <div class="col-md-4 text-end mt-4">
                                <button class="btn btn-outline-primary" onclick="exportarFacturas()">
                                    <i class="fas fa-download me-2"></i>Exportar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Facturas -->
                <div id="facturasContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-3">Cargando facturas...</p>
                    </div>
                </div>

                <!-- Tabla detallada (opcional) -->
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <i class="fas fa-table me-1"></i> Vista Detallada
                    </div>
                    <div class="card-body">
                        <table id="facturasTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Concepto</th>
                                <th class="text-end">Monto</th>
                                <th class="text-center">Vencimiento</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
        <?php require_once(BASE_PATH . '/views/partials/footer.php'); ?>
    </div>
</div>

<!-- Modal Pagar Factura -->
<div class="modal fade" id="modalPagar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card me-2"></i>Pagar Factura
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Factura #<span id="modalFacturaNum"></span></strong><br>
                    <span id="modalFacturaConcepto"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Monto a Pagar</label>
                    <input type="text" id="modalMonto" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Método de Pago</label>
                    <select class="form-select" id="metodoPago">
                        <option value="saldo">Saldo de Cuenta</option>
                        <option value="tarjeta">Tarjeta de Débito</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarPago()">
                    <i class="fas fa-check me-2"></i>Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/guest-facturas.js?v=1.1"></script>
</body>
</html>