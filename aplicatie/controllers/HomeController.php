<?php

namespace controllers;

use lib\Controller;
use models\Subcategorie;

class HomeController extends Controller {

    /**
     * arata pagina principala 
     *
     */
    public function index() {
        $subcateg = new Subcategorie();

        $data = $subcateg->findAllAssoc();

        $this->view->incarcaPagina('index.php', $data);
    }

    /**
     * daca utilizatorul nu are drepturi este directionat catre aceasta pagina
     */
    public function noPrivilege() {
        
        $this->view->incarcaPagina('noPrivilege.php');
    }

}
