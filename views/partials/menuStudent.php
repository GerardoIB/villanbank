<div class="sb-sidenav-menu">
    <div class="nav">

        <div class="px-3 py-2 text-white">
            <div class="fw-bold fs-5">
                <i class="fas fa-user-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['user_person_name'] ?? 'Usuario'); ?>
            </div>
            <div class="small text-muted" style="padding-left: 2.25rem;">
                <?php echo htmlspecialchars($_SESSION['user_level_name'] ?? 'Visitante'); ?>
            </div>
        </div>

        <div class="sb-sidenav-menu-heading">Menu Estudiante</div>

        <a class="nav-link" href="<?= app_url ?>/student">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
        <a class="nav-link" href="#">
            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
            Mis Cursos
        </a>
        <a class="nav-link" href="#">
            <div class="sb-nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
            Mis Calificaciones
        </a>
        <a class="nav-link" href="#">
            <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
            Mi Horario
        </a>
    </div>
</div>