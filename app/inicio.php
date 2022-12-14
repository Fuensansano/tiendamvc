<?php

ini_set('display_errors', 1);

// Constantes iniciales
define('ROOT', DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('URL', '/var/www/tiendamvc/');
define('VIEWS', URL . APP . 'views/');
define('IMG_FOLDER', ROOT . 'public/img' . DIRECTORY_SEPARATOR);
define('ENCRIPTKEY', 'elperrodesanroque');
define('MIN_LENGTH_NAME',3);
define('MIN_VALUE_PRICE', 0);

// Carga las clases iniciales
require_once('libs/Mysqldb.php');
require_once('libs/MySQL.php');
require_once('libs/MysqlReturnTypes.php');
require_once('libs/Controller.php');
require_once('libs/Application.php');
require_once ('libs/Session.php');
require_once('libs/Validate.php');
require_once('domain/index.php');