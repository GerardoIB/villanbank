<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        /* Estilos para el panel de saldo */
        .balance-section {
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .big-money {
            font-size: 3.5rem;
            font-weight: 700;
            color: #198754; /* Verde Bootstrap */
            letter-spacing: -1px;
            line-height: 1;
        }

        .account-details-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .account-details-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f8f9fa;
            font-size: 0.95rem;
        }

        .account-details-list li:last-child {
            border-bottom: none;
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
                        <h1 class="h3 text-dark">Resumen de Cuenta</h1>
                        <p class="text-muted mb-0">Bienvenido, <strong><?php echo htmlspecialchars($dashboard['name']); ?></strong></p>
                    </div>
                    <span class="badge bg-light text-dark border p-2">
                            <?php echo date('d/m/Y'); ?>
                        </span>
                </div>

                <div class="balance-section">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-end">
                            <p class="text-uppercase text-muted small fw-bold mb-2">Saldo Disponible Total</p>
                            <div class="big-money">
                                $ <?php echo number_format($dashboard['balance'], 2); ?>
                            </div>
                            <p class="text-muted small mt-2">
                                <i class="fas fa-check-circle text-success me-1"></i> Actualizado al momento
                            </p>
                        </div>

                        <div class="col-md-6 ps-md-5">
                            <ul class="account-details-list">
                                <li>
                                    <span class="text-muted">Número de Cuenta</span>
                                    <span class="fw-bold text-dark font-monospace">
                                            **** <?php echo htmlspecialchars($dashboard['account_number']); ?>
                                        </span>
                                </li>
                                <li>
                                    <span class="text-muted">Tipo de Cuenta</span>
                                    <span class="fw-bold text-dark">Ahorro / Nómina</span>
                                </li>
                                <li>
                                    <span class="text-muted">Estado</span>
                                    <?php if($dashboard['state'] == 'active'): ?>
                                        <span class="badge bg-success bg-opacity-25 text-success">Activa</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-25 text-danger">Bloqueada</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <i class="fas fa-history me-1"></i> Últimos Movimientos
                    </div>
                    <div class="card-body">
                        <table id="myHistoryTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-end">Importe</th>
                                <th class="text-end">Fecha</th>
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

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/guest-dashboard.js?v=2.0"></script>
</body>
</html>