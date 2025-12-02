<?php
require_once BASE_PATH . '/utils/View.php';
require_once BASE_PATH . '/models/LevelModel.php';
require_once BASE_PATH . '/entitys/LevelEntity.php';

class LevelController
{
    public function index()
    {
        // ✅ CAMBIO AQUÍ (Solo Admin puede entrar)
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 2) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        $data = ['name' => $_SESSION['user_person_name'] ?? 'Admin'];
        View::render("Levels/IndexView", ["dashboard" => $data]);
    }


    public function read()
    {
        $model = new LevelModel();
        $levels = $model->read();
        header('Content-Type: application/json');
        echo json_encode(['levels' => $levels]);
    }


    public function create()
    {
        header('Content-Type: application/json');
        $id = $_POST['pk_level'];
        $name = $_POST['level_name'];

        $entity = new LevelEntity($id, $name);
        $model = new LevelModel();
        $status = $model->save('cat_levels', $entity);
        echo json_encode(['status' => $status ? 1 : 0]);
    }

    public function update()
    {
        header('Content-Type: application/json');
        $id = $_POST['pk_level'];
        $name = $_POST['level_name'];

        $model = new LevelModel();
        $status = $model->updateLevel($id, $name);

        echo json_encode(['status' => $status ? 1 : 0]);
    }

    public function delete()
    {
        header('Content-Type: application/json');
        $id = $_POST['pk_level'];

        $model = new LevelModel();
        $status = $model->deleteLevel($id);

        echo json_encode(['status' => $status ? 1 : 0]);
    }

    public function nextId()
    {
        $model = new LevelModel();
        $nextId = $model->getNextId();

        header('Content-Type: application/json');
        echo json_encode(['nextId' => $nextId]);
    }
}