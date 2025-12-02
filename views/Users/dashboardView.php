<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    require_once('views/partials/header.php');
    ?>
    <style>
        /* === NUEVO DISEÑO DASHBOARD === */
        body {
            background-color: #f8f9fa; /* Un gris muy claro para un look limpio */
        }

        /* 1. Estilo para las tarjetas principales (gráficos, tablas, bienvenida) */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem; /* Espacio uniforme */
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        /* 2. Nuevo diseño para las tarjetas de estadísticas */
        .stat-card {
            position: relative;
            overflow: hidden; /* Para el ícono de fondo */
        }
        .stat-card .card-body {
            position: relative;
            z-index: 2;
        }
        .stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3.5rem;
            color: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .stat-card-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
            text-transform: uppercase;
        }
        .stat-card-number {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
        }

        /* Colores de los bordes para las tarjetas de estadísticas */
        .border-left-primary { border-left: 5px solid #0d6efd; }
        .border-left-warning { border-left: 5px solid #ffc107; }
        .border-left-success { border-left: 5px solid #198754; }
        .border-left-danger { border-left: 5px solid #dc3545; }

    </style>
</head>
<body class="sb-nav-fixed">
<!-- El Navbar superior no se modifica -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="indexView.php">Mi App</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Configuración</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="login.php">Cerrar Sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <!-- El menú lateral no se modifica -->
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <?php
            require_once('views/partials/menuDashboard.php');
            ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Panel Principal</li>
                </ol>

                <!-- 1. NUEVO PANEL DE BIENVENIDA CON LOS DATOS DEL USUARIO -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4">Bienvenido de vuelta, <strong><?php echo htmlspecialchars($dashboard['name']) . ' ' . htmlspecialchars($dashboard['lastname']); ?></strong></h2>
                        <p class="mb-0 text-muted">Edad registrada: <?php echo htmlspecialchars($dashboard['age']); ?> años. ¡Qué tengas un excelente día!</p>
                    </div>
                </div>

                <!-- 2. TARJETAS DE ESTADÍSTICAS REDISEÑADAS -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-left-primary">
                            <div class="card-body">
                                <div class="stat-card-title">Nuevos Usuarios</div>
                                <div class="stat-card-number">25</div>
                                <i class="fas fa-users stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-left-warning">
                            <div class="card-body">
                                <div class="stat-card-title">Ventas del Mes</div>
                                <div class="stat-card-number">$12,540</div>
                                <i class="fas fa-dollar-sign stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-left-success">
                            <div class="card-body">
                                <div class="stat-card-title">Reportes Generados</div>
                                <div class="stat-card-number">142</div>
                                <i class="fas fa-file-alt stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card border-left-danger">
                            <div class="card-body">
                                <div class="stat-card-title">Incidencias Críticas</div>
                                <div class="stat-card-number">3</div>
                                <i class="fas fa-exclamation-triangle stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos y Tablas con el nuevo estilo de tarjeta -->
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-area me-1"></i>
                                Gráfico de Sesiones
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Actividad Reciente
                            </div>
                            <div class="card-body">
                                <p>Esta es un área para mostrar datos tabulados, como una lista de los últimos usuarios registrados, productos recientes o actividad del sistema.</p>
                                <!-- Puedes agregar una tabla real aquí -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php
        require_once('views/partials/footer.php');
        ?>
    </div>
</div>

<?php
require_once('views/scripts/script.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    // Este script de gráfico de ejemplo no se modifica
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line', data: {
            labels: ["Mar 1", "Mar 2", "Mar 3", "Mar 4", "Mar 5", "Mar 6", "Mar 7", "Mar 8", "Mar 9", "Mar 10", "Mar 11", "Mar 12", "Mar 13"],
            datasets: [{
                label: "Sessions", lineTension: 0.3, backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)", pointRadius: 5, pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)", pointHoverRadius: 5, pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50, pointBorderWidth: 2, data: [10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
            }],
        }, options: {
            scales: {
                xAxes: [{ time: { unit: 'date' }, gridLines: { display: false }, ticks: { maxTicksLimit: 7 } }],
                yAxes: [{ ticks: { min: 0, max: 40000, maxTicksLimit: 5 }, gridLines: { color: "rgba(0, 0, 0, .125)", } }],
            }, legend: { display: false }
        }
    });
</script>
</body>
</html>