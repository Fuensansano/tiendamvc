<?php

class AdminController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Admin');
    }

    public function index()
    {
        $data = [
            'titulo' => 'Administración',
            'menu' => false,
            'data' => [],
        ];

        $this->view('admin/index', $data);
    }

    public function verifyUser()
    {
       $errors = [];
       $dataForm = [];

       if ($_SERVER['REQUEST_METHOD'] == 'post'){

           $user = $_POST['user'] ?? '';
           $password = $_POST['password'] ?? '';
           $dataForm = [
               'user' => $user,
               'password' => $password,
           ];

           if (empty($user)) {
               array_push($errors,"El usuario es requerido");
           }
           if (empty($password)) {
               array_push($errors,"la contraseña es requerida");
           }

           if ( ! $errors) {

               $errors = $this->model->verifyUser($dataForm);

               if ( ! $errors) {

               }
           }
       }

        $data = [
            'titulo' => 'Administración - Inicio',
            'menu' => false,
            'errors' => $errors,
            'admin' => true,
            'data' => [],
        ];

        $this->view('admin/index', $data);
    }
}