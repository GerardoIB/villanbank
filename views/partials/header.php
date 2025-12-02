<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>NovaBanking | Plataforma Digital</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="<?= app_url ?>/resources/css/styles.css" rel="stylesheet" />
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css" />

<style>
    :root {
        /* Paleta NovaBanking */
        --primary: #4f46e5;       /* Indigo vibrante */
        --primary-hover: #4338ca;
        --bg-body: #f3f4f6;       /* Gris muy claro */
        --text-main: #1f2937;     /* Gris oscuro */
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-main);
    }

    /* Navbar Limpio */
    .sb-topnav {
        background: #ffffff !important;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        height: 64px;
    }
    .navbar-brand {
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: -0.025em;
    }

    /* Sidebar Light Mejorado */
    .sb-sidenav-light {
        background-color: #ffffff !important;
        border-right: 1px solid var(--border-color);
    }
    .sb-sidenav-light .nav-link {
        color: var(--text-muted) !important;
        font-weight: 500;
        padding: 0.8rem 1.5rem;
        transition: all 0.2s;
    }
    .sb-sidenav-light .nav-link:hover {
        color: var(--primary) !important;
        background-color: #f5f3ff !important; /* Fondo indigo muy suave */
        border-left: 3px solid var(--primary);
    }
    .sb-sidenav-light .sb-sidenav-menu-heading {
        color: #9ca3af !important;
        font-weight: 700;
    }

    /* Tarjetas y Contenedores */
    .card {
        border: 1px solid var(--border-color);
        border-radius: 12px; /* Bordes redondeados modernos */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Botones Modernos */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    /* Badges (Etiquetas) Suaves */
    .badge { padding: 0.5em 0.8em; border-radius: 6px; font-weight: 600; font-size: 0.75rem; }

    /* Inputs */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.6rem 1rem;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
</style>