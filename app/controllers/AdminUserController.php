<?php

class AdminUserController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminUser');
    }

    public function index()
    {
        $session = new Session();

        $session->redirectIfNotLogin( ROOT . 'admin');

        $users = $this->model->getUsers();
        $data = [
            'titulo' => 'Administración de Usuarios',
            'menu' => false,
            'admin' => true,
            'users' => $users,
        ];

        $this->view('admin/users/index', $data);
    }

    public function create()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showCreateAdminUserForm();
            return;
        }

        $errors = [];

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password1 = $_POST['password1'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        $dataForm = [
            'name' => $name,
            'email' => $email,
            'password' => $password1,
        ];

        if (empty($name)) {
           $errors[] = 'El nombre de usuario es requerido';
        }
        if (empty($email)) {
            $errors[] = 'El correo electrónico de usuario es requerido';
        }
        if (empty($password1)) {
            $errors[] ='La clave de acceso es requerida';
        }
        if (empty($password2)) {
            $errors[] ='La verificación de clave es requerida';
        }
        if ($password1 != $password2) {
            $errors[] = 'Las claves no coinciden';
        }

        if ( ! $errors) {

            if ($this->model->createAdminUser($dataForm)) {
                header("location:" . ROOT . 'adminUser');
            } else {

                $data = [
                    'titulo' => 'Error en la creación de un usuario administrador',
                    'menu' => false,
                    'errors' => [],
                    'subtitle' => 'Error al crear un nuevo usuario administrador',
                    'text' => 'Se ha producido un error durante el proceso de creación de un usuario administrador',
                    'color' => 'alert-danger',
                    'url' => 'adminUser',
                    'colorButton' => 'btn-danger',
                    'textButton' => 'Volver',
                ];
                $this->view('mensaje', $data);

            }

        } else {

            $data = [
                'titulo' => 'Administración de Usuarios - Alta',
                'menu' => false,
                'admin' => true,
                'errors' => $errors,
                'data' => $dataForm,
            ];

            $this->view('admin/users/create', $data);

        }
    }


    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        } else {

            $user = $this->model->getUserById($id);
            $status = $this->model->getConfig('adminStatus');

            $data = [
                'titulo' => 'Administración de Usuarios - Editar',
                'menu' => false,
                'admin' => true,
                'data' => $user,
                'status' => $status,
            ];

            $this->view('admin/users/update', $data);

        }
    }

    public function delete()
    {
        print 'Eliminación de usuarios';
    }

    public function showCreateAdminUserForm(): void
    {
        $data = [
            'titulo' => 'Administración de Usuarios - Alta',
            'menu' => false,
            'admin' => true,
            'data' => [],
        ];

        $this->view('admin/users/create', $data);
    }
}