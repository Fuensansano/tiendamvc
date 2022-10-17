<?php


interface ValidProduct
{
    public function validateName($name);
}

trait Validations
{
    public function validateName($name) {
        // validamos
    }
}


class Book implements ValidProduct
{
    use Validations;
}

class Course implements ValidProduct
{
    use Validations;
}