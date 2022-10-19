<?php

class Course implements Validations
{
    use ProductValidation;

    public static function validatePeople($people,$errors)
    {
        if (empty($people)) {
            $errors[] = 'El público objetivo del curso es obligatorio';
        }

        if (is_numeric($people)) {
            $errors[] = 'El público objetivo del curso no puede ser un número';
        }

        if (strlen($people) < self::MIN_LENGTH_NAME) {
            $errors[] = 'El público objetivo del curso no puede contener menos de ' .
                self::MIN_LENGTH_NAME .' caracteres';
        }
        return $errors;
    }

    public static function validateObjetives($objetives,$errors)
    {
        if (empty($objetives)) {
            $errors[] = 'Los objetivos del curso son obligatorio';
        }

        if (strlen($objetives) < self::MIN_LENGTH_NAME) {
            $errors[] = 'Los objetivos no pueden contener menos de ' .
                self::MIN_LENGTH_NAME .' caracteres';
        }
        if (is_numeric($objetives)) {
            $errors[] = 'Los objetivos no pueden ser un número';
        }
        return $errors;
    }

    public static function validateNecesites($necesites,$errors)
    {
        if (empty($necesites)) {
            $errors[] = 'Las necesidades del curso son obligatorio';
        }

        if (strlen($necesites) < self::MIN_LENGTH_NAME) {
            $errors[] = 'Las necesidades no pueden contener menos de ' .
                self::MIN_LENGTH_NAME .' caracteres';
        }
        if (is_numeric($necesites)) {
            $errors[] = 'Las necesidades no pueden ser un número';
        }

        return $errors;
    }

}