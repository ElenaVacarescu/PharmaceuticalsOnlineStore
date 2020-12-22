<?php

namespace lib;

/**
 * Clasa se ocupa de toate cererile din aplicatie; In functie de ruta din url, instantiaza controllerul aferent si apeleaza metoda aderenta
 *
 * @author elena
 */
class Aplicatie {

    private $controller = null;
    private $metoda = null;
    private $args = array();
    public $cerere = null;
    public $raspuns = null;

    public function __construct() {

        $this->cerere = new Cerere();
        $this->raspuns = new Raspuns();
    }

    public function start() {

        $url = $this->cerere->getQueryString("url");
        if (!empty($url)) {

            $url = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));

            $this->controller = !empty($url[0]) ? ucwords($url[0]) . 'Controller' : null;
            $this->metoda = !empty($url[1]) ? $url[1] : null;

            unset($url[0], $url[1]);

            $this->args = !empty($url) ? array_values($url) : [];
            
            if (empty($this->metoda)) {
                $this->metoda = 'index';
            }
        }
        else {
            self::redirect('Home\index');
        }

        if (!self::existaController($this->controller)) {
            $this->controller = DEFAULT_CONTROLLER;
            $this->metoda = 'index';
            self::redirect('Home\index');
        }

        if (!empty($this->controller) && !empty($this->metoda)) {
            $controllerName = $this->controller;
            return $this->setController($controllerName, $this->metoda, $this->args);
        }
    }

    /**
     * instantiaza controlerul si apeleaza metoda aferenta
     * @param string $controller
     * @param type $metoda
     * @param type $args
     * @return type
     */
    private function setController($controller, $metoda = "index", $args = []) {

        //$this->cerere->adaugaParametri(['controller' => $controller, 'action' => $metoda, 'args' => $args]);

        $controller = '\controllers\\' . $controller;

        $this->controller = new $controller($this->cerere, $this->raspuns);

        if (!empty($args)) {
            $rez = call_user_func_array([$this->controller, $metoda], $args);
        } else {
            $rez = $this->controller->{$metoda}();
        }

        if ($rez instanceof Raspuns) {
            return $rez->send();
        }
        return $this->raspuns->send();
    }

    /**
     * verifica daca exista controlerul
     * @param  string $controller
     * @return boolean
     */
    private static function existaController($controller) {

        if (!file_exists(APP . 'controllers/' . $controller . '.php')) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * redirectioneaza catre pagina locatia/ruta trimisa ca si parametru
     * @param type $location
     */
    public static function redirect($location) {
        $path = PUBLIC_ROOT . $location;

        if (!headers_sent()) {
            header('Location: ' . $path);
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $path . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $location . '"/>';
            echo '</noscript>';
            exit;
        }
    }

    /**
     * redirectioneaza catre pagina anterioara
     */
    public static function back() {
        echo '<script type="text/javascript">';
        echo 'window.history.back()';
        echo '</script>';
    }

}
