<?php

class CoursesController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Course');
    }

    public function index()
    {
        $session = new Session();

        $session->redirectIfNotLogin(ROOT); // no me hace falta poner un if porque
        //este método redirige si no está logueado, al redirigir no se va a ejecutar el resto de líneas
        //seguidas de código

       $session->getLogin();

        $courses = $this->model->getCourses();

        $data = [
            'titulo' => 'Cursos en línea',
            'menu' => true,
            'active' => 'courses',
            'data' => $courses,
        ];

        $this->view('courses/index', $data);

    }
}