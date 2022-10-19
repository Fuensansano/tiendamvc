<?php

class Course implements Validations
{
    use ProductValidation;

    public static function validatePeople($people,$errros)
    {
        if (empty($people)) {
            $errors[] = 'El público objetivo del curso es obligatorio';
        }

        if (strlen($people) < self::MIN_LENGTH_NAME) {
            $errors[] = 'El público objetivo del curso no puede contener menos de ' .
                self::MIN_LENGTH_NAME .' caracteres';
        }
        return $errors;
    }
}