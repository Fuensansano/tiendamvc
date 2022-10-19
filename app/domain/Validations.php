<?php

interface Validations
{
    const MIN_LENGTH_NAME = 3;
    public static function validateName($name, $errors);
    public static function validateDescription($description, $errors);
    public static function validatePrice($price, $errors);
    public static function validateDiscount($discount, $errors);
    public static function validateSendPrice($send, $errors);
    public static function validateDiscountLowerThanPrice($discount, $price, $errors);
    public static function validatePublishedDate($published, $errors);
}