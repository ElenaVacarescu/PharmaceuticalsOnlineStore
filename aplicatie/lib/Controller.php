<?php

namespace lib;

use lib\View;

class Controller {

    protected $view;
    public $cerere;
    public $raspuns;
    protected $formularBuilder;
    protected $validator;

    public function __construct(Cerere $cerere = null, Raspuns $raspuns = null) {

        $this->cerere = $cerere !== null ? $cerere : new Cerere();
        $this->raspuns = $raspuns !== null ? $raspuns : new Raspuns();
        $this->view = new View($this);

        $this->formularBuilder = new FormBuilders();
        $this->validator = new Validator();
    }

    /**
     * metoda magica pentru a accesa modelul autoloaded
     * @param  string 
     * @return object
     */
    public function __get($name) {
        return $this->loadModel($name);
    }

    /**
     * incarca modelul
     * @param string 
     * @return object
     */
    public function loadModel($model) {
        $_model = ucwords($model);
        $_modelE = '\models\\' . $_model;
        return $this->{$model} = new $_modelE();
    }

}
