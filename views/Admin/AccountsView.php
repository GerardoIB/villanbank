<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-light">
    <a class="navbar-brand" href="#">Sistema Bancario</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 text-secondary" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= app_url ?>/auth/logout">Salir</a>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <?php require_once(BASE_PATH . '/views/partials/menuAdmin.php'); ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Gestión de Cuentas</h1>
                <p class="text-muted">Administración de saldos y estados de cuenta.</p>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-list me-1"></i> Listado de Cuentas
                    </div>
                    <div class="card-body">
                        <table id="adminAccountsTable" class="table table-bordered table-hover">
                            <thead class="table-light">
                            <tr>
                                <th># Cuenta</th>
                                <th>Titular</th>
                                <th class="text-end">Saldo</th>
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

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/admin-accounts.js?v=5.3"></script>
</body>
</html>