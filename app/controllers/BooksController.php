<?php

class BooksController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Book');
    }

    public function index()
    {
        $session = new Session();

        $session->redirectIfNotLogin(ROOT);

        $session->getLogin();

        $books = $this->model->getBooks();

        $data = [
            'titulo' => 'Libros',
            'menu' => true,
            'active' => 'books',
            'data' => $books,
        ];

        $this->view('book/index', $data);


    }
}