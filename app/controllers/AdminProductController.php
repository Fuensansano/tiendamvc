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

    public function create()
    {
        $errors = [];
        $dataForm = [];
        $type = $this->model->getConfig('productType');

        $data = [
            'titulo' => 'Administración de Productos - Alta',
            'menu' => false,
            'admin' => true,
            'type' => $type,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/products/create', $data);
    }

    public function update($id)
    {

    }

    public function delete($id)
    {

    }
}