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

        $session->redirectIfNotLogin( ROOT . 'admin');

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
        $price = Validate::number((float) ($_POST['price'] ?? 0.0));
        $discount = Validate::number((float) ($_POST['discount'] ?? 0.0));
        $send = Validate::number((float) ($_POST['send'] ?? 0.0));
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
        $errors = Course::validateName($name,$errors);
        $errors = Course::validateDescription($description,$errors);
        $errors = Course::validatePrice($price,$errors);
        $errors = Course::validateDiscount($discount,$errors);
        $errors = Course::validateSendPrice($send,$errors);
        $errors = Course::validateSendPrice($send,$errors);
        $errors = Course::validateDiscountLowerThanPrice($discount,$price,$errors);
        $errors = Course::validatePublishedDate($published,$errors);
        $errors = Course::validatePeople($people, $errors);
        $errors = Course::validateObjetives($objetives, $errors);
        $errors = Course::validateNecesites($necesites, $errors);

        if ($image) {
            if (Validate::imageFile($_FILES['image']['tmp_name'])) {

                $image = strtolower($image);

                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
                    Validate::resizeImage($image, 240);
                } else {
                    array_push($errors, 'Error al subir el archivo de imagen');
                }
            } else {
                array_push($errors, 'El formato de imagen no es aceptado');
            }
        } else {
            array_push($errors, 'No he recibido la imagen');
        }

        // Creamos el array de datos
        $dataForm = [
            'type'  => $type,
            'name'  => $name,
            'description' => $description,
            'people'    => $people,
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

        if ( $errors ) {
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        $errors = $this->model->createProduct($dataForm);

        if ( ! $errors ) {
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
        $image = Validate::file($_FILES['image']['name']);
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
        $errors = Book::validateName($name,$errors);
        $errors = Book::validateDescription($description,$errors);
        $errors = Book::validatePrice($price,$errors);
        $errors = Book::validateDiscount($discount,$errors);
        $errors = Book::validateSendPrice($send,$errors);
        $errors = Book::validateSendPrice($send,$errors);
        $errors = Book::validateDiscountLowerThanPrice($discount,$price,$errors);
        $errors = Book::validatePublishedDate($published,$errors);
        $errors = Book::validateAuthor($author,$errors);
        $errors = Book::validatePublisher($publisher,$errors);
        $errors = Book::validatePages($pages,$errors);


        if ($image) {
            if (Validate::imageFile($_FILES['image']['tmp_name'])) {

                $image = strtolower($image);

                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
                    Validate::resizeImage($image, 240);
                } else {
                    array_push($errors, 'Error al subir el archivo de imagen');
                }
            } else {
                array_push($errors, 'El formato de imagen no es aceptado');
            }
        } else {
            array_push($errors, 'No he recibido la imagen');
        }

        // Creamos el array de datos
        $dataForm = [
            'type'  => $type,
            'name'  => $name,
            'description' => $description,
            'author'    => $author,
            'publisher' => $publisher,
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


        if ( $errors ) {
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        $errors = $this->model->createProduct($dataForm);

        if ( ! $errors ) {
            $errors[] = 'Se ha producido un errpr en la inserción en la BD';
            $this->viewCreateForm($errors, $dataForm);
            return;
        }

        header('location:' . ROOT . 'AdminProduct');
    }

    public function update($id)
    {

    }

    public function delete($id)
    {

    }
}