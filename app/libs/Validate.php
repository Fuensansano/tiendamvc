<?php

class Validate
{
    public const ALLOWED_FILE_TYPES = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
    private const WIDTH_INDEX = 0;
    private const HEIGH_INDEX = 1;
    private const MIME = 'mime';
    private const JPG_DEFAULT_QUALITY = 80;

    public static function number($string)
    {
        $search = [' ', '€', '$', ','];
        $replace = ['', '', '', ''];

        return str_replace($search, $replace, $string);
    }

    public static function date($string)
    {
        $date = explode('-', $string);

        return checkdate($date[1], $date[2], $date[0]);
    }

    public static function dateDiff($string)
    {
        $now = new DateTime();
        $date = new DateTime($string);

        return ($date > $now);
    }

    // TODO: Evaluar que pasa si mando el formulario sin imagen
    // Si puedo debería poner a lowercase el nombre
    public static function file($string)
    {
        $search = [' ', '*', '!', '@', '?', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü', '¿', '¡'];
        $replace = ['-', '', '', '', '', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u', 'U', '', ''];
        $fileName = str_replace($search,$replace, $string);

        if ($fileName) {
            $fileName = strtolower($string);
        }
        return $fileName;
    }

    public static function resizeImage($imageName, $newWidth = 240): void
    {
        $file = 'img/' . $imageName;

        $info = getimagesize($file);

        if (self::notHasImage($info)) {
            return;
        }

        $width = $info[self::WIDTH_INDEX];
        $height = $info[self::HEIGH_INDEX];
        $type = $info[self::MIME];

        $factor = $newWidth / $width;
        $newHeight = $factor * $height;

        $imageName = imagecreatefromjpeg($file);

        $canvas = imagecreatetruecolor($newWidth, round($newHeight,0,PHP_ROUND_HALF_DOWN));

        imagecopyresampled($canvas, $imageName, 0,0,0,0,$newWidth, $newHeight,$width, $height);

        imagejpeg($canvas, $file, self::JPG_DEFAULT_QUALITY);
    }

    public static function text($string)
    {
        $search = ['^', 'delete', 'drop', 'truncate', 'exec', 'system'];
        $replace = ['-', 'dele*te', 'dr*op', 'trunca*te', 'ex*ec', 'syst*em'];
        $string = str_replace($search, $replace, $string);
        $string = addslashes(htmlentities($string));

        return $string;
    }

    public static function hasCorrectImageFormat($file, $fileName): bool
    {
        if(self::isNotAnImage($file)) { //isImage y si es imagen me da en un array todos los parámetros de medida
            return false;
        }


        $mime_type = mime_content_type($file); //aseguras (la naturaleza del archivo = mime)

        //var_dump($file, $fileName);die();
        /*if (self::hasInCorrectMimeType($mime_type)) { //valora el mimetype
            return false;
        }*/

        return self::hasCorrectImageExtension($file);
    }

    private static function isNotAnImage($file): bool
    {
        return !getimagesize($file);
    }


    public static function hasInCorrectMimeType(bool|string $mime_type): bool
    {
        return !in_array($mime_type, self::ALLOWED_FILE_TYPES, true);
    }

    private static function hasCorrectImageExtension($image): bool
    {
        $imageTypeIndex = 2;
        $imageArray = getimagesize($image);
        $imageType = $imageArray[$imageTypeIndex];

        return in_array($imageType, self::ALLOWED_FILE_TYPES, true);
    }

    public static function notHasImage($imageWithoutSpecialCharacters): bool
    {
        return !$imageWithoutSpecialCharacters;
    }

}