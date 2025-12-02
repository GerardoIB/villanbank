<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        /* Solo necesitamos esto para centrar */
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card shadow border-0">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Sistema Bancario</h3>
                <p class="text-muted small">Ingresa tus credenciales</p>
            </div>

            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="inputPhone" name="number_phone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="inputPassword" name="password" required>
                </div>

                <div class="d-grid mb-3">
                    <button class="btn btn-primary" type="button" id="btnLogin">Entrar</button>
                </div>

                <div class="text-center">
                    <a href="<?= app_url ?>/auth/create" class="small text-decoration-none">Registrarme</a>
                    <span class="mx-2 text-muted">|</span>
                    <a href="<?= app_url ?>/auth/forget" class="small text-decoration-none text-muted">Recuperar contraseña</a>
                </div>
            </form>

        </div>
    </div>

    <div class="text-center mt-3 text-muted small">
        &copy; 2024 Proyecto Escolar
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/auth.js?v=3.0"></script>
</body>
</html>