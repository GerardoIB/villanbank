<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(BASE_PATH . '/views/partials/header.php'); ?>
    <style>
        body.auth-body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card { border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2); border: none; }
        .brand-logo { font-size: 1.8rem; font-weight: 800; color: #333; letter-spacing: -1px; }
        .brand-logo span { color: #667eea; }
    </style>
</head>
<body class="auth-body">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card auth-card">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="brand-logo mb-2">IDB <span>Financial</span></div>
                        <h4 class="fw-bold text-dark">Recuperar Acceso</h4>
                        <p class="text-muted small">Ingresa tu teléfono y te enviaremos instrucciones.</p>
                    </div>

                    <form id="recoveryForm">
                        <div class="form-floating mb-4">
                            <input type="tel" class="form-control" id="number_phone_recovery" name="number_phone" placeholder="Teléfono" required>
                            <label><i class="fas fa-mobile-alt me-2 text-muted"></i>Número de Teléfono</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3 rounded-pill fw-bold" id="btnSendInstructions">
                                Enviar Instrucciones <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light border-0">
                    <div class="small">
                        <a href="http://localhost/pagina/auth/auth" class="text-decoration-none fw-bold" style="color: #667eea;">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once(BASE_PATH . '/views/scripts/script.php'); ?>
</body>
</html>