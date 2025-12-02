<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    // Carga el header (CSS, etc.)
    require_once(BASE_PATH . '/views/partials/header.php');
    ?>
</head>
<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="#">Panel de Profesor</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Mi Perfil</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="http://localhost/pagina/auth/logout">Cerrar Sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <?php
            // --- AHORA CARGA EL NUEVO MENÚ ---
            require_once(BASE_PATH . '/views/partials/menuTeacher.php');
            ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Mi Panel</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard del Profesor</li>
                </ol>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4">Hola, <strong><?php echo htmlspecialchars($dashboard['name'] ?? 'Profesor'); ?></strong></h2>
                        <p class="mb-0 text-muted">Bienvenido a tu panel de gestión.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">Mis Cursos</div>
                            <div class="card-body">
                                <p>Aquí iría la lista de cursos...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">Estudiantes</div>
                            <div class="card-body">
                                <p>Aquí iría la lista de estudiantes...</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <?php
        require_once(BASE_PATH . '/views/partials/footer.php');
        ?>
    </div>
</div>

<?php
require_once(BASE_PATH . '/views/scripts/script.php');
?>
</body>
</html>