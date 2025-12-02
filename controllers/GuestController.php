<?php
// controllers/GuestController.php
require_once BASE_PATH . '/utils/View.php';
require_once BASE_PATH . '/models/AccountModel.php';
require_once BASE_PATH . '/models/TransactionModel.php';

class GuestController
{
    public function index()
    {
        // 1. Seguridad: Solo nivel 1 (Guest)
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 0) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        // 2. Obtener datos básicos
        $phone = $_SESSION['user_phone'];
        $name = $_SESSION['user_person_name'] ?? 'Cliente';

        // 3. Buscar la cuenta bancaria de este usuario
        $accountModel = new AccountModel();
        $myAccount = $accountModel->getAccountByPhone($phone);

        // 4. Guardar ID de cuenta para la API de historial
        if ($myAccount) {
            $_SESSION['my_account_id'] = $myAccount['pk_account'];
        }

        // 5. Preparar datos para la vista (Evita pantalla blanca si no tiene cuenta)
        $dashboardData = [
            'name' => $name,
            'balance' => $myAccount ? $myAccount['balance'] : 0.00,
            'account_number' => $myAccount ? $myAccount['pk_account'] : 'Sin Asignar',
            'state' => $myAccount ? $myAccount['state'] : 'inactive'
        ];

        // 6. Renderizar
        View::render("Guest/IndexView", ["dashboard" => $dashboardData]);
    }

    /**
     * API para llenar la tabla de movimientos vía AJAX
     */
    public function readMyHistory()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['my_account_id'])) {
            echo json_encode(['transactions' => []]);
            exit;
        }

        $model = new TransactionModel();
        $data = $model->getHistoryByAccount($_SESSION['my_account_id']);

        echo json_encode(['transactions' => $data]);
        exit;
    }
    public function readMyFacturas()
    {
        header('Content-Type: application/json');
        

        if (!isset($_SESSION['user_phone'])) {
            echo json_encode(['facturas' => []]);
            exit;
        }

        $model = new TransactionModel();
        $data = $model->getFacturas($_SESSION['user_phone']);

        echo json_encode(['facturas' => $data]);
    
        exit;
    }
    public function facturas()
    {
        // 1. Seguridad: Solo nivel 1 (Guest)
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 0) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        // 2. Obtener datos básicos
        $name = $_SESSION['user_person_name'] ?? 'Cliente';

        // 3. Preparar datos para la vista
        $data = [
            'name' => $name
        ];

        // 4. Renderizar
        View::render("Guest/FacturasView", $data);
    }
   public function pagarFactura()
{
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 0) {
        echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
        exit;
    }

    // Leer JSON
    $data = json_decode(file_get_contents("php://input"), true);

    $facturaId  = $data['idFactura'] ?? null;
    $metodoPago = $data['metodoPago'] ?? null;

    if (!$facturaId || !$metodoPago) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos.',
            'debug' => $data  // opcional para ver qué llegó
        ]);
        exit;
    }

    $model = new TransactionModel();
    $result = $model->payFactura($facturaId);

    echo json_encode(['success' => true, 'message' => 'Factura pagada exitosamente.']);
    exit;
}

}