<?php

/**
 * definire constante care indica locatiile fisierelor din aplicatie
 */
define('BASE_DIR', str_replace("\\", "/", dirname(__DIR__)));
define('APP', BASE_DIR . "/aplicatie/");
define('VIEWS_PATH', APP . "views/");

/**
 * autoload folosit pentru includerea automata a claselor nou adaugate 
 */
include_once APP . "/autoload.php";

/**
 * definire constante si variabile globale folosite in aplicatie
 */
//daca MEDIU_RULARE_APP este 'dev' afisam toate erorile, daca este 'prod' logam toate erorile 
define('MEDIU_RULARE_APP', 'dev');


$infoGlobale = array();
$infoGlobale['timpLogin'] = 60 * 60; // 3600 secunde
$infoGlobale['locatieFisiereSesiune'] = BASE_DIR . '/sesiuni/';

//daca se apeleaza un controler inexistent, se considera un controller predefinit, care va fi instantiat
define('DEFAULT_CONTROLLER', 'HomeController');


/* fisierul in care vom tine logurile cu erori
  este pe acelasi nivel cu aplicatia deci nu va fi vizibil public. */
define('MY_ERROR_FILE', __DIR__ . '/../logs/error_file');


/* start sesiune
 * pornim sesiunea pentru toate paginile mai putin cea de login.php deoarece in aceasta pagina pornim sesiunea
 * cu regenerearea id-ului de sesiune pentru evitatea session fixation
 */

if (session_status() == PHP_SESSION_NONE && basename($_SERVER['PHP_SELF']) != 'login.php') {
    \lib\Sesiune:: my_session_start();
}

/**
 * pornire aplicatie
 * odata ce avem o instanta a aplicatiei, putem controla fluxul de cereri si de raspuns catre client
 */
$aplicatie = new \lib\Aplicatie();

define('PUBLIC_ROOT', $aplicatie->cerere->root());

$aplicatie->start();

