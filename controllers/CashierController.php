<?php
require_once BASE_PATH . '/utils/View.php';
require_once BASE_PATH . '/models/AccountModel.php';
require_once BASE_PATH . '/models/TransactionModel.php';
require_once BASE_PATH . '/entitys/TransactionEntity.php';

class CashierController
{
    public function index()
    {

        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 3) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        $dashboardData = ['name' => $_SESSION['user_person_name'] ?? 'Cajero'];
        View::render("Cashier/IndexView", ["dashboard" => $dashboardData]);
    }

    /**
     * API: Devuelve el historial de transacciones para la DataTable
     * URL: /pagina/cashier/readHistoryJson
     */
    public function readHistoryJson()
    {
        $model = new TransactionModel();
        // Asegúrate de tener el método readAllHistory en TransactionModel
        $data = $model->readAllHistory();

        header('Content-Type: application/json');
        echo json_encode(['transactions' => $data]);
        exit;
    }

    /**
     * API: Valida si una cuenta existe y devuelve el dueño
     * URL: /pagina/cashier/validateAccount
     * ⚠️ ESTA ES LA FUNCIÓN QUE TE FALTABA O FALLABA
     */
    public function validateAccount()
    {
        header('Content-Type: application/json');

        // 1. Validar que llegó el ID
        if (!isset($_POST['account_id'])) {
            echo json_encode(['status' => 0, 'message' => 'Ingrese una cuenta']);
            exit;
        }

        $id = $_POST['account_id'];

        // 2. Buscar en el Modelo
        $model = new AccountModel();
        // Asegúrate de tener el método getAccountOwner en AccountModel
        $data = $model->getAccountOwner($id);

        if ($data) {
            // 3. Éxito: Devolvemos nombre y estado
            echo json_encode([
                'status' => 1,
                'owner' => $data['person'] . ' ' . $data['first_name'] . ' ' . $data['last_name'],
                'state' => $data['state']
            ]);
        } else {
            // 4. Fallo: No existe
            echo json_encode(['status' => 0, 'message' => 'Cuenta no encontrada']);
        }
        exit;
    }

    /**
     * API: Procesa Depósito o Retiro
     * URL: /pagina/cashier/processTransaction
     */
    public function processTransaction()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['pk_account']) || !isset($_POST['amount'])) {
            echo json_encode(['status' => 0, 'message' => 'Datos incompletos']);
            exit;
        }

        $pkAccount = $_POST['pk_account'];
        $type = $_POST['type'];
        $amount = floatval($_POST['amount']);

        if($amount <= 0) {
            echo json_encode(['status' => 0, 'message' => 'Monto inválido']);
            exit;
        }

        $txEntity = new TransactionEntity();
        $txEntity->setFkAccount($pkAccount);
        $txEntity->setType($type);
        $txEntity->setAmount($amount);
        $txEntity->setDescription("Ventanilla");

        $txModel = new TransactionModel();
        $result = $txModel->makeTransaction($txEntity);

        echo json_encode(['status' => $result['status'] ? 1 : 0, 'message' => $result['message']]);
        exit;
    }
    public function facturas()
    {

        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 3) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        $dashboardData = ['name' => $_SESSION['user_person_name'] ?? 'Cajero'];
        View::render("Cashier/CreateFacturas", ["dashboard" => $dashboardData]);
    }
}