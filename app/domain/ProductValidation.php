<?php

trait ProductValidation
{
    public static function validateName($name, $errors)
    {
        if (empty($name)) {
            $errors[] = 'El nombre del producto es requerido';
        }

        if (strlen($name) < MIN_LENGTH_NAME) {
            $errors[] = 'La longitud mínima del nombre tiene que ser de 3 caracteres';
        }
        return $errors;
    }

    public static function validateDescription($description, $errors)
    {
        if (empty($description)) {
            $errors[] = 'La description del producto es necesaria';
        }

        return $errors;
    }

    public static function validatePrice($price, $errors)
    {
        if (!is_numeric($price)) {
            $errors[] = 'El precio tiene que ser un valor numérico';
        }

        if ($price < MIN_VALUE_PRICE) {
            $errors[] = 'El precio no puede ser negativo';
        }

        return $errors;
    }

    public static function validateDiscount($discount, $errors)
    {
        if (!is_numeric($discount)) {
            $errors[] = 'El descuento del producto debe de ser un número';
        }
        if ($discount < 0) {
            $errors[] = 'El descuento del producto o puede ser un número';
        }
        return $errors;
    }

    public static function validateSendPrice($send, $errors)
    {
        if (!is_numeric($send)) {
            $errors[] = 'Los gastos de envío del producto deben de ser numéricos';
        }
        return $errors;
    }

    public static function validateDiscountLowerThanPrice($discount, $price, $errors)
    {
        if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
            $errors[] = 'El descuento no puede ser mayor que el precio';
        }
        return $errors;
    }

    public static function validatePublishedDate($published, $errors)
    {
        if (!Validate::date($published)) {
            $errors[] = 'La fecha o su formato no es correcto';
        } elseif (!Validate::dateDiff($published)) {
            $errors[] = 'La fecha de publicación no puede ser anterior a hoy';
        }
        return $errors;
    }

    public static function validateImage($file, $cleanFileName, $errors = []): array
    {

        if (Validate::notHasImage($cleanFileName)) {
            $errors[] = 'No he recibido la imagen';
            return $errors;
        }

        if (!Validate::hasCorrectImageFormat($file, $cleanFileName)) {
            $errors[] = 'El formato de imagen no es aceptado';
            return $errors;
        }

        if ($errors) {
            return $errors;
        }

        if (!is_uploaded_file($file)) {
            $errors[] = 'Error al subir el archivo de imagen';
            return $errors;
        }

        move_uploaded_file($file, 'img/' . $cleanFileName);
        Validate::resizeImage($cleanFileName);

        return $errors;
    }
}