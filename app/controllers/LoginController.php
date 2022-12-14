<?php

class LoginController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Login');
    }

    public function index()
    {
        if (isset($_COOKIE['shoplogin'])) {

            $value = explode('|', $_COOKIE['shoplogin']);
            $dataForm = [
                'user' => $value[0],
                'password' => $value[1],
                'remember' => 'on',
            ];
        } else {
            $dataForm = null; //no refactorizo, me es más cómodo de leer así y además habría que valorar siempre en
            //un if el isset
        }

        $data = [
            'titulo' => 'Login',
            'menu'   => false,
            'data' => $dataForm,
        ];

        $this->view('login', $data);
    }

    public function forgot()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showForgotForm();
            return;
        }

        $email = $_POST['email'] ?? '';

        if ($email == '') {
            $errors[] = 'El email es requerido';
        }
        if( ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] =  'El correo electrónico no es válido';
        }

        if (count($errors) == 0) {
            if ( ! $this->model->existsEmail($email)) {
                $errors[] = 'El correo electrónico no existe en la base de datos';
            } else {
                if ($this->model->sendEmail($email)) {

                    $data = [
                        'titulo' => 'Cambio de contraseña de acceso',
                        'menu' => false,
                        'errors' => [],
                        'subtitle' => 'Cambio de contraseña de acceso',
                        'text' => 'Se ha enviado un correo a <b>' . $email . '</b> para que pueda cambiar su clave de acceso. <br>No olvide revisar su carpeta de spam. <br>Cualquier duda que tenga puede comunicarse con nosotros.',
                        'color' => 'alert-success',
                        'url' => 'login',
                        'colorButton' => 'btn-success',
                        'textButton' => 'Regresar',
                    ];

                    $this->view('mensaje', $data);

                } else {

                    $data = [
                        'titulo' => 'Error con correo',
                        'menu' => false,
                        'errors' => [],
                        'subtitle' => 'Error en el envío del correo electrónico',
                        'text' => 'Existió un problema al enviar el correo electrónico.<br>Por favor, pruebe más tarde o comuníquese con nuestro servicio de soporte',
                        'color' => 'alert-danger',
                        'url' => 'login',
                        'colorButton' => 'btn-danger',
                        'textButton' => 'Regresar',
                    ];

                    $this->view('mensaje', $data);

                }
            }
        }

        if (count($errors) > 0) {
            $data = [
                'titulo' => 'Olvido de la contraseña',
                'menu' => false,
                'errors' => $errors,
                'subtitle' => '¿Olvidaste la contraseña?'
            ];

            $this->view('olvido', $data);
        }

    }

    public function register()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showRegisterForm();
            return;
        }

        // Procesamos la información recibida del formulario
        $firstName = $_POST['first_name'] ?? '';
        $lastName1 = $_POST['last_name_1'] ?? '';
        $lastName2 = $_POST['last_name_2'] ?? '';
        $email = $_POST['email'] ?? '';
        $password1 = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $country = $_POST['country'] ?? '';

        $dataForm = [
            'firstName' => $firstName,
            'lastName1' => $lastName1,
            'lastName2' => $lastName2,
            'email' 	=> $email,
            'password'  => $password1,
            'address'	=> $address,
            'city'		=> $city,
            'state'		=> $state,
            'postcode'	=> $postcode,
            'country'	=> $country
        ];

        if ($firstName == '') {
            $errors[] = 'El nombre es requerido';
        }
        if ($lastName1 == '') {
            $errors[] = 'El primer apellido es requerido';
        }
        if ($lastName2 == '') {
            $errors[] =  'El segundo apellido es requerido';
        }
        if ($email == '') {
            $errors[] = 'El email es requerido';
        }
        if ($password1 == '') {
            $errors[] = 'La contraseña es requerido';
        }
        if ($password2 == '') {
            $errors[] =  'Repetir contraseña es requerido';
        }
        if ($address == '') {
            $errors[] = 'La dirección es requerida';
        }
        if ($city == '') {
            $errors[] = 'La ciudad es requerida';
        }
        if ($state == '') {
            $errors[] =  'La provincia es requerida';
        }
        if ($postcode == '') {
            $errors[] =  'El código postal es requerido';
        }
        if ($country == '') {
            $errors[] = 'El país es requerido';
        }
        if ($password1 != $password2) {
            $errors[] = 'Las contraseñas deben ser iguales';
        }


        if ($errors) {
            $data = [
                'titulo' => 'Registro',
                'menu' => false,
                'errors' => $errors,
                'dataForm' => $dataForm
            ];

            $this->view('register', $data);
            return;
        }

        if (!$this->model->createUser($dataForm)) {
            $data = [
                'titulo' => 'Error',
                'menu' => false,
                'errors' => [],
                'subtitle' => 'Error en el proceso de registro.',
                'text' => 'Probablemente el correo utilizado ya exista. Pruebe con otro',
                'color' => 'alert-danger',
                'url' => 'login',
                'colorButton' => 'btn-danger',
                'textButton' => 'Regresar',
            ];

            $this->view('mensaje', $data);
            return;
        }

        $data = [
            'titulo' => 'Bienvenido',
            'menu' => false,
            'errors' => [],
            'subtitle' => 'Bienvenido/a a nuestra tienda online',
            'text' => 'Gracias por su registro',
            'color' => 'alert-success',
            'url' => 'menu',
            'colorButton' => 'btn-success',
            'textButton' => 'Acceder',
        ];

        $this->view('mensaje', $data);
    }


    public function changePassword($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showChangePasswordRegister($id);
            return;
        }

        $id = $_POST['id'] ?? '';
        $password1 = $_POST['password1'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($id == '') {
            $errors[] = 'El usuario no existe';
        }
        if ($password1 == '') {
            $errors[] = 'La contraseña es requerida';
        }
        if ($password2 == '') {
            $errors[] = 'Repetir contraseña es requerido';
        }
        if ($password1 != $password2) {
            $errors[] = 'Ambas claves deben ser iguales';
        }

        if (count($errors)) {

            $data = [
                'titulo' => 'Cambiar contraseña',
                'menu'   => false,
                'errors' => $errors,
                'data' => $id,
                'subtitle' => 'Cambia tu contraseña de acceso',
            ];

            $this->view('changepassword', $data);
            return;
        }

        if (!$this->model->changePassword($id, $password1)) {
            $data = [
                'titulo' => 'Error al cambiar contraseña',
                'menu'   => false,
                'errors' => [],
                'subtitle' => 'Error al modificar la contraseña de acceso',
                'text' => 'Existió un error al modificar la clave de acceso',
                'color' => 'alert-danger',
                'url' => 'login',
                'colorButton' => 'btn-danger',
                'textButton' => 'Regresar',
            ];

            $this->view('mensaje', $data);
            return;
        }

        $data = [
            'titulo' => 'Cambiar contraseña',
            'menu'   => false,
            'errors' => [],
            'subtitle' => 'Modificación de la contraseña de acceso',
            'text' => 'La contraseña ha sido cambiada correctamente. Bienvenido de nuevo',
            'color' => 'alert-success',
            'url' => 'login',
            'colorButton' => 'btn-success',
            'textButton' => 'Regresar',
        ];

        $this->view('mensaje', $data);
    }

    public function loginClientInShop()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        $email = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? 'on' : 'off';

        $dataForm = [
            'user' => $email,
            'remember' => $remember,
        ];

        $user = $this->model->getUserByEmail($email);

        $errors = [];

        if ( ! $user ) {
            $errors[] = 'El usuario no existe en nuestros registros';
            $data = [
                'titulo' => 'Login',
                'menu'   => false,
                'errors' => $errors,
                'data' => $dataForm,
            ];
            $this->view('login', $data);
            return;
        }

        $errors = $this->model->verifyUserPassword($user, $password);

        if ($errors) {
            $data = [
                'titulo' => 'Login',
                'menu'   => false,
                'errors' => $errors,
                'data' => $dataForm,
            ];
            $this->view('login', $data);
            return;
        }

        $this->setShopLoginCookie($email, $password, $remember);

        $session = new Session();
        $session->login($user);

        header("location:" . ROOT . 'shop');
    }


    public function showForgotForm(): void
    {
        $data = [
            'titulo' => 'Olvido de la contraseña',
            'menu' => false,
            'errors' => [],
            'subtitle' => '¿Olvidaste la contraseña?'
        ];

        $this->view('olvido', $data);
    }


    public function showRegisterForm(): void
    {
        $data = [
            'titulo' => 'Registro',
            'menu' => false,
        ];

        $this->view('register', $data);
    }



    public function showChangePasswordRegister($id): void
    {
        $data = [
            'titulo' => 'Cambiar contraseña',
            'menu' => false,
            'data' => $id,
            'subtitle' => 'Cambia tu contraseña de acceso',
        ];

        $this->view('changepassword', $data);
    }


    public function setShopLoginCookie($email, $password, $remember): void
    {
        $value = $email . '|' . $password;
        if ($remember == 'on') {
            $date = time() + (60 * 60 * 24 * 7);
        } else {
            $date = time() - 1;
        }
        setcookie('shoplogin', $value, $date);
    }
}