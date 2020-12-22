<?php

namespace lib;

use lib\Rol;

class Login {

    private $rol;

    function __get($atribut) {
        return $this->$atribut;
    }

    function __set($atribut, $val) {
        $this->$atribut = $val;
        return $this;
    }

    function __construct() {
        $this->rol = new Rol();
    }

    function setPrivilegii(array $drepturi) {
        $this->rol->setDrepturi($drepturi);
    }

    function areDrepturiAcces($pagina) {
        return $this->rol->areDrepturiAcces($pagina);
    }

    /**
     * functia verifica daca utilizatorul este logat
     * @return boolean
     */
    function esteLogat() {
        if (isset($_SESSION['uid'])) {
            return true;
        }
        return false;
    }

}
