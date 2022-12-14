<?php

class AdminProductController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminProduct');
    }

    public function index()
    {
        $session = new Session();

        $session->redirectIfNotLogin(ROOT . 'admin');

        $products = $this->model->getProducts();
        $type = $this->model->getConfig('productType');

        $data = [
            'titulo' => 'Administración de Productos',
            'menu' => false,
            'admin' => true,
            'type' => $type,
            'products' => $products,
        ];

        $this->view('admin/products/index', $data);


    }

    public function viewCreateForm($errors = [], $dataForm = [])
    {
        $type = $this->model->getConfig('productType');
        $status = $this->model->getConfig('productStatus');
        $catalogue = $this->model->getCatalogue();
        $data = [
            'titulo' => 'Administración de Productos - Alta',
            'menu' => false,
            'admin' => true,
            'type' => $type,
            'status' => $status,
            'catalogue' => $catalogue,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/products/create', $data);
    }


    public function createCourse()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->viewCreateForm();
            return;
        }

        $type = $_POST['type'] ?? '';
        $name = addslashes(htmlentities($_POST['name'] ?? ''));
        $description = addslashes(htmlentities($_POST['description'] ?? ''));
        $price = Validate::number((float)($_POST['price'] ?? 0.0));
        $discount = Validate::number((float)($_POST['discount'] ?? 0.0));
        $send = Validate::number((float)($_POST['send'] ?? 0.0));
        $image = Validate::file($_FILES['image']['name']);
        $published = $_POST['published'] ?? '';
        $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
        $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
        $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
        $mostSold = isset($_POST['mostSold']) ? '1' : '0';
        $new = isset($_POST['new']) ? '1' : '0';
        $status = $_POST['status'] ?? '';

        //Courses
        $people = addslashes(htmlentities($_POST['people'] ?? ''));
        $objetives = addslashes(htmlentities($_POST['objetives'] ?? ''));
        $necesites = addslashes(htmlentities($_POST['necesites'] ?? ''));

        // Validamos la información
        $errors = CourseDomain::validateName($name, $errors);
        $errors = CourseDomain::validateDescription($description, $errors);
        $errors = CourseDomain::validatePrice($price, $errors);
        $errors = CourseDomain::validateDiscount($discount, $errors);
        $errors = CourseDomain::validateSendPrice($send, $errors);
        $errors = CourseDomain::validateSendPrice($send, $errors);
        $errors = CourseDomain::validateDiscountLowerThanPrice($discount, $price, $errors);
        $errors = CourseDomain::validatePublishedDate($published, $errors);
        $errors = CourseDomain::validatePeople($people, $errors);
        $errors = CourseDomain::validateObjetives($objetives, $errors);
        $errors = CourseDomain::validateNecesites($necesites, $errors);

        if ($image) {
            if (Validate::hasCorrectImageFormat($_FILES['image']['tmp_name'])) {

                $image = strtolower($image);

                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
                    Validate::resizeImage($image, 240);
                } else {
                    $errors[] = 'Error al subir el archivo de imagen';
                }
            } else {
                $errors[] = 'El formato de imagen no es aceptado';
            }
        } else {
            $errors[] = 'No he recibido la imagen';
        }

        // Creamos el array de datos
        $dataForm = [
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'people' => $people,
            'objetives' => $objetives,
            'necesites' => $necesites,
            'price' => $price,
            'discount' => $discount,
            'send' => $send,
            'published' => $published,
            'image' => $image,
            'mostSold' => $mostSold,
            'new' => $new,
            'relation1' => $relation1,
            'relation2' => $relation2,
            'relation3' => $relation3,
            'status' => $status,
        ];

        if ($errors) {
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        $errors = $this->model->createProduct($dataForm);

        if (!$errors) {
            $errors[] = 'Se ha producido un errpr en la inserción en la BD';
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        header('location:' . ROOT . 'AdminProduct');
    }

    public function createBook()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->viewCreateForm();
            return;
        }

        $type = $_POST['type'] ?? '';
        $name = addslashes(htmlentities($_POST['name'] ?? ''));
        $description = addslashes(htmlentities($_POST['description'] ?? ''));
        $price = Validate::number((float)($_POST['price'] ?? ''));
        $discount = Validate::number((float)($_POST['discount'] ?? ''));
        $send = Validate::number((float)($_POST['send'] ?? ''));
        $tempImage = $_FILES['image']['tmp_name'];
        $imageWithoutSpecialCharacters = Validate::file($_FILES['image']['name']);
        $published = $_POST['published'] ?? '';
        $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
        $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
        $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
        $mostSold = isset($_POST['mostSold']) ? '1' : '0';
        $new = isset($_POST['new']) ? '1' : '0';
        $status = $_POST['status'] ?? '';

        //Books
        $author = Validate::text($_POST['author'] ?: '');
        $publisher = Validate::text($_POST['publisher'] ?: '');
        $pages = Validate::number($_POST['pages'] ?: '');

        // Validamos la información
        $errors = BookDomain::validateName($name, $errors);
        $errors = BookDomain::validateDescription($description, $errors);
        $errors = BookDomain::validatePrice($price, $errors);
        $errors = BookDomain::validateDiscount($discount, $errors);
        $errors = BookDomain::validateSendPrice($send, $errors);
        $errors = BookDomain::validateSendPrice($send, $errors);
        $errors = BookDomain::validateDiscountLowerThanPrice($discount, $price, $errors);
        $errors = BookDomain::validatePublishedDate($published, $errors);
        $errors = BookDomain::validateAuthor($author, $errors);
        $errors = BookDomain::validatePublisher($publisher, $errors);
        $errors = BookDomain::validatePages($pages, $errors);

        $errors = BookDomain::validateImage($tempImage, $imageWithoutSpecialCharacters, $errors);

        // Creamos el array de datos
        $dataForm = [
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'author' => $author,
            'publisher' => $publisher,
            'price' => $price,
            'discount' => $discount,
            'send' => $send,
            'pages' => $pages,
            'published' => $published,
            'image' => $imageWithoutSpecialCharacters,
            'mostSold' => $mostSold,
            'new' => $new,
            'relation1' => $relation1,
            'relation2' => $relation2,
            'relation3' => $relation3,
            'status' => $status,
        ];


        if ($errors) {
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        $errors = $this->model->createProduct($dataForm);

        if (!$errors) {
            $errors[] = 'Se ha producido un error en la inserción en la BD';
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        header('location:' . ROOT . 'AdminProduct');
    }

    public function update($id)
    {
        $errors = [];
        $typeConfig = $this->model->getConfig('productType');
        $statusConfig = $this->model->getConfig('productStatus');
        $catalogue = $this->model->getCatalogue();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $type = $_POST['type'] ?? '';
            $name = Validate::text($_POST['name'] ?? '');
            $description = Validate::text($_POST['description'] ?? '');
            $price = Validate::number((float)($_POST['price'] ?? 0.0));
            $discount = Validate::number((float)($_POST['discount'] ?? 0.0));
            $send = Validate::number((float)($_POST['send'] ?? 0.0));
            $image = Validate::file($_FILES['image']['name']);
            $published = $_POST['published'] ?? '';
            $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
            $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
            $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
            $mostSold = isset($_POST['mostSold']) ? '1' : '0';
            $new = isset($_POST['new']) ? '1' : '0';
            $status = $_POST['status'] ?? '';
            //Books
            $author = Validate::text($_POST['author'] ?: 'Pepe');
            $publisher = Validate::text($_POST['publisher'] ?: 'José');
            $pages = Validate::number($_POST['pages'] ?: '100');
            //Courses
            $people = Validate::text($_POST['people'] ?? '');
            $objetives = Validate::text($_POST['objetives'] ?? '');
            $necesites = Validate::text($_POST['necesites'] ?? '');

            // Validamos la información
            if (empty($name)) {
                $errors[] = 'El nombre del producto es requerido';
            }
            if (empty($description)) {
                $errors[] = 'La descripción del producto es requerida';
            }
            if (!is_numeric($price)) {
                $errors[] = 'El precio del producto debe de ser un número';
            }
            if (!is_numeric($discount)) {
                $errors[] = 'El descuento del producto debe de ser un número';
            }
            if (!is_numeric($send)) {
                $errors[] = 'Los gastos de envío del producto deben de ser numéricos';
            }
            if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
                $errors[] = 'El descuento no puede ser mayor que el precio';
            }
            if (!Validate::date($published)) {
                $errors[] = 'La fecha o su formato no es correcto';
            } elseif (!Validate::dateDiff($published)) {
                $errors[] = 'La fecha de publicación no puede ser anterior a hoy';
            }
            if ($type == 1) {
                if (empty($people)) {
                    $errors[] = 'El público objetivo del curso es obligatorio';
                }
                if (empty($objetives)) {
                    $errors[] = 'Los objetivos del curso son necesarios';
                }
                if (empty($necesites)) {
                    $errors[] = 'Los requisitos del curso son necesarios';
                }
            } elseif ($type == 2) {
                if (empty($author)) {
                    $errors[] = 'El autor del libro es necesario';
                }
                if (empty($publisher)) {
                    $errors[] = 'La editorial del libro es necesaria';
                }
                if (!is_numeric($pages)) {
                    $pages = 0;
                    $errors[] = 'La cantidad de páginas de un libro debe de ser un número';
                }
            } else {
                $errors[] = 'Debes seleccionar un tipo válido';
            }

            BookDomain::validateImage($_FILES['image']['tmp_name'], $errors);

            if ($image) {
                if (Validate::hasCorrectImageFormat($_FILES['image']['tmp_name'])) {

                    $image = strtolower($image);

                    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
                        Validate::resizeImage($image, 240);
                    } else {
                        $errors[] = 'Error al subir el archivo de imagen';
                    }
                } else {
                    $errors[] = 'El formato de imagen no es aceptado';
                }
            }

            // Creamos el array de datos
            $dataForm = [
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'author' => $author,
                'publisher' => $publisher,
                'people' => $people,
                'objetives' => $objetives,
                'necesites' => $necesites,
                'price' => $price,
                'discount' => $discount,
                'send' => $send,
                'pages' => $pages,
                'published' => $published,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
                'relation1' => $relation1,
                'relation2' => $relation2,
                'relation3' => $relation3,
                'status' => $status,
            ];

            if (!$errors) {

                if (count($this->model->updateProduct($dataForm)) == 0) {

                    header('location:' . ROOT . 'AdminProduct');

                }
                $errors[] = 'Se ha producido un error en la inserción en la BD';
            }

        }

        $product = $this->model->getProductById($id);

        $data = [
            'titulo' => 'Administración de Productos - Edición',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'status' => $statusConfig,
            'catalogue' => $catalogue,
            'errors' => $errors,
            'product' => $product,
        ];

        $this->view('admin/products/update', $data);
    }

    public function delete($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = $this->model->delete($id);

            if (empty($errors)) {
                header('location:' . ROOT . 'AdminProduct');
            }

        }

        $product = $this->model->getProductById($id);
        $typeConfig = $this->model->getConfig('productType');

        $data = [
            'titulo' => 'Administración de Productos - Eliminación',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'product' => $product,
        ];

        $this->view('/admin/products/delete', $data);
    }
}