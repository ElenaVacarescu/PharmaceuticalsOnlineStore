<?php

function my_autoloader($className) {

    if (file_exists(APP . $className . '.php')) {
        require_once(APP . $className . '.php');
    } elseif (file_exists(APP . 'controllers' . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(APP . 'controllers' . DIRECTORY_SEPARATOR . $className . '.php');
    } elseif (file_exists(APP . 'lib' . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(APP . 'lib' . DIRECTORY_SEPARATOR . $className . '.php');
    }
}

spl_autoload_register('my_autoloader');
