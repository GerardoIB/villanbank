<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-light">
    <a class="navbar-brand" href="#">Ventanilla de Caja</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 text-secondary" id="sidebarToggle"><i class="fas fa-bars"></i></button>
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
                <h1 class="mt-4">Operaciones</h1>
                <p class="text-muted">Cajero: <strong><?php echo htmlspecialchars($dashboard['name'] ?? 'Cajero'); ?></strong></p>

                <div class="row mb-4">
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg w-100 py-3" onclick="openOperationModal()">
                            <i class="fas fa-cash-register me-2"></i> Realizar Depósito o Retiro
                        </button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-history me-1"></i> Historial de Movimientos (Hoy)
                    </div>
                    <div class="card-body">
                        <table id="cashierHistoryTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Cuenta</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Fecha</th>
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

<div class="modal fade" id="operationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Nueva Transacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="opForm">
                    <div class="mb-3">
                        <label class="form-label">Número de Cuenta</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="op_account_search" placeholder="Ej: 1001">
                            <button class="btn btn-secondary" type="button" onclick="searchAccountInfo()">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                        <div id="account_feedback" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Movimiento</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="op_type" id="type_dep" value="deposito" checked>
                            <label class="btn btn-outline-success w-50" for="type_dep">Depósito</label>

                            <input type="radio" class="btn-check" name="op_type" id="type_ret" value="retiro">
                            <label class="btn btn-outline-danger w-50" for="type_ret">Retiro</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monto ($)</label>
                        <input type="number" class="form-control form-control-lg" id="op_amount" placeholder="0.00">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="processOperation()">Procesar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/cashier-transactions.js?v=5.0"></script>
</body>
</html>