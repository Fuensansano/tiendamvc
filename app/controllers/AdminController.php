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

    public function verifyUser()
    {
        $errors = [];
        $dataForm = [];
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

        $errors = $this->model->verifyUser($email, $password);

        if ( ! $errors ) {
            $session = new Session();
            $session->login($dataForm);
            header("LOCATION:" . ROOT . 'AdminShop');
        }

        $this->index($dataForm,$errors);

    }
}