<?php

class BookDomain implements Validations
{

    use ProductValidation;

    public static function validateAuthor($author, $errors)
    {
        if (empty($author)) {
            $errors[] = 'El autor del libro es necesario';
        }

        if (strlen($author) < MIN_LENGTH_NAME) {
            $errors[] = 'El nombre del autor tiene que tener mínimo 3 caracteres';
        }
        return $errors;
    }

    public static function validatePublisher($publisher, $errors)
    {
        if (empty($publisher)) {
            $errors[] = 'La editorial del libro es necesaria';
        }
        return $errors;
    }

    public static function validatePages($pages, $errors)
    {
        if (!is_numeric($pages)) {
            //$pages = 0;
            $errors[] = 'La cantidad de páginas de un libro debe de ser un número';
        }

        if (empty($pages)) {
            $errors[] = 'Debe de indicar la cantidad de páginas';
        }
        return $errors;
    }

    /*

if (empty($publisher)) {
    $errors[] = 'La editorial del libro es necesaria';
}
if (!is_numeric($pages)) {
    $pages = 0;
    $errors[] = 'La cantidad de páginas de un libro debe de ser un número';
}
    */
}