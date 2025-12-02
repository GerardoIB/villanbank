<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        body {
            background-color: #f0f2f5; /* Mismo gris que el login */
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .card-register {
            max-width: 600px;
            margin: 0 auto;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-register">
        <div class="card-header bg-white text-center py-3">
            <h3 class="fw-bold text-primary m-0">Crear Cuenta Nueva</h3>
        </div>
        <div class="card-body p-4">

            <form id="registerForm">
                <h6 class="text-muted mb-3 border-bottom pb-2">Información Personal</h6>

                <div class="mb-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" class="form-control" id="inputName" name="name" required>
                    <small id="nameHelp" class="text-danger"></small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="inputPaterno" name="paterno" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="inputMaterno" name="materno" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Género</label>
                        <select class="form-select" id="inputGender" name="gender">
                            <option value="" selected disabled>Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="inputBirthday" name="birthday">
                    </div>
                </div>

                <h6 class="text-muted mb-3 border-bottom pb-2 mt-3">Seguridad</h6>

                <div class="mb-3">
                    <label class="form-label">Teléfono (ID de usuario)</label>
                    <input type="tel" class="form-control" id="inputPhone" name="phone" maxlength="10" placeholder="10 dígitos" required>
                    <small id="phoneHelp" class="text-danger"></small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" required>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-primary btn-lg" type="button" id="btnRegister">
                        Registrarse
                    </button>
                </div>
            </form>

        </div>
        <div class="card-footer text-center bg-white py-3">
            <div class="small">
                <a href="<?= app_url ?>/auth/auth" class="text-decoration-none">¿Ya tienes cuenta? Iniciar Sesión</a>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
<script src="<?= app_url ?>/resources/js/register.js?v=3.0"></script>
</body>
</html>