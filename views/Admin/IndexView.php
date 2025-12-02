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
            <a class="nav-link text-secondary" href="<?= app_url ?>/auth/logout">Cerrar Sesión <i class="fas fa-sign-out-alt"></i></a>
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

                <h1 class="mt-4">Panel de Administración</h1>
                <p class="text-muted">Bienvenido, <strong><?php echo htmlspecialchars($dashboard['name'] ?? 'Admin'); ?></strong></p>

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="h4 mb-0" id="totalUsersCount">0</h2>
                                    <div class="small text-white-50">Usuarios</div>
                                </div>
                                <i class="fas fa-users fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="h4 mb-0">3</h2>
                                    <div class="small text-white-50">Roles</div>
                                </div>
                                <i class="fas fa-user-shield fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-table me-1"></i> Lista de Usuarios</span>
                        <button class="btn btn-sm btn-primary" onclick="window.location.href='<?= app_url ?>/auth/create'">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="myTableUsers" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Teléfono</th>
                                <th>Nombre</th>
                                <th>Paterno</th>
                                <th>Materno</th>
                                <th>Rol</th>
                                <th>Acciones</th>
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

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="original_phone" name="original_phone">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_person" name="person">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Paterno</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Materno</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveEditedUser()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/admin-datatable.js?v=5.0"></script>
</body>
</html>