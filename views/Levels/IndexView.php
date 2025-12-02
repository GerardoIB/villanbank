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
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <h1>Roles de Usuario</h1>
                    <button class="btn btn-primary" onclick="openModal()">
                        <i class="fas fa-plus me-1"></i> Nuevo Rol
                    </button>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        Niveles de Acceso Disponibles
                    </div>
                    <div class="card-body">
                        <table id="tableLevels" class="table table-striped table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Rol</th>
                                <th class="text-end">Opciones</th>
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

<div class="modal fade" id="levelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Gestión de Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="levelForm">
                    <input type="hidden" id="formMode">

                    <div class="mb-3">
                        <label class="form-label">ID del Rol</label>
                        <input type="number" class="form-control" id="pk_level" placeholder="Automático">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="level_name" placeholder="Ej: Supervisor">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveLevel()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/level-crud.js?v=5.1"></script>
</body>
</html>