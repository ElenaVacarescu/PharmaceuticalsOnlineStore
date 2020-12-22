<?php

namespace lib;

/**
 * Include metode utile pentru preluarea datelor din controler si afisarea lor in front-end
 *
 * @author elena
 */
class View {

    public $controller;
    public $data;

    public function __construct(Controller $controller) {

        $this->controller = $controller;
    }

    /**
     * returneaza output-ul cu array-ul de date aferent
     *
     * @param  string  $denumireFisier
     * @param  array   $data
     * @return string  output-ul
     *
     */
    public function incarcaPagina($denumireFisier, $data = null) {

        if (file_exists(APP . 'views/' . $denumireFisier)) {

            if (!empty($data)) {
                if ($this->controller->cerere->isAjax()) {
                    $this->data = json_encode($data);
                } else {
                    $this->data = $data;
                }
            }

            //folosim include versus require pentru ca exista posibilitatea sa includem fisierul de mai multe ori
            ob_start();
            include (APP . 'views/' . $denumireFisier);
            $renderedFile = ob_get_clean();

            $this->controller->raspuns->setContinut($renderedFile);
            return $renderedFile;

            die('Pagina - ' . $denumireFisier . ' nu exista.');
        }
    }

    function get_data() {
        return $this->data;
    }

}
