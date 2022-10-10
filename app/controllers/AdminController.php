<?php

class AdminController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Admin');
    }

    public function index($dataForm = [],$errors = [])
    {
        $data = [
            'titulo' => 'Administración',
            'menu' => false,
            'data' => $dataForm,
            'errors' => $errors,
        ];

        $this->view('admin/index', $data);
    }

    public function loginAdminInShop()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        $email = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        $dataForm = [
            'user' => $email,
            'password' => $password,
        ];

        $errors = [];
        if(empty($email)) {
            $errors[] = 'El usuario es requerido';
        }
        if(empty($password)) {
            $errors[] = 'La contraseña es requerida';
        }

        if ($errors) {
            $this->index($dataForm,$errors);
            return;
        }

        $admin = $this->model->findAdminByEmail($email);
        if ( ! $admin ) {
            $errors[] = 'El usuario no existe en nuestros registros';
            $this->index($dataForm,$errors);
            return;
        }

        $errors = $this->model->verifyAdminPassword($admin, $password);

        if ($errors) {
            $this->index($dataForm,$errors);
            return;
        }

        $session = new Session();
        $session->login($dataForm);

        $errors = $this->model->updateLastLogin($admin);
        if ($errors) {
            $this->index($dataForm,$errors);
            return;
        }

        header("LOCATION:" . ROOT . 'AdminShop');
    }
}