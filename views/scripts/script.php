<script>
    // Configuración Global de la URL para que JS sepa dónde está el backend
    const BASE_URL = "<?php echo defined('app_url') ? app_url : ''; ?>";

    // (Opcional) Mensaje de depuración
    // console.log("Conectando a API en:", BASE_URL);
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script src="<?= app_url ?>/resources/js/scripts.js?v=1.1"></script>

<script>
    // Configuración para que SweetAlert use estilos de Bootstrap (Azul/Gris)
    const MySwal = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary px-4 mx-2', // Botón Azul
            cancelButton: 'btn btn-secondary px-4 mx-2', // Botón Gris
            popup: 'shadow border-0 rounded-3'
        },
        buttonsStyling: false // Desactiva estilos propios de SweetAlert
    });
    window.Swal = MySwal;
</script>

<style>
    /* Separación pequeña entre botones de exportación */
    .dt-buttons .btn {
        margin-right: 4px;
        font-size: 0.85rem;
    }
    /* Al imprimir, ocultamos botones y paginación para que salga limpio en papel */
    @media print {
        .dt-buttons, .dataTables_filter, .dataTables_info, .dataTables_paginate {
            display: none !important;
        }
    }
</style>