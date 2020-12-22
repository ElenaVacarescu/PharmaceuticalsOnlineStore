<?php

namespace lib;

/**
 * metode care gestioneaza cererea care se initiaza in browser 
 * @author elena
 */
class Cerere {
    /* public $parametri = [
      "controller" => null, "action" => null, "args" => null
      ]; */

    public $data = [];
    public $query = [];
    public $url = null;

    /**
     * Creare cerere noua, folosinf array-uririle superglobale $_GET, $_POST, $_FILES
     * @param type $config
     */
    public function __construct($config = []) {

        $this->data = $this->getDataFromPOST($_POST, $_FILES);
        $this->query = $_GET;
        //$this->parametri += isset($config["params"]) ? $config["params"] : [];
        $this->url = $this->fullUrl();
    }

    /**
     * ia informatiile din array-urile globale $_POST si $_FILES si le comaseaza intr-un singur array
     * @param  array $post
     * @param  array $fisiere
     * @return array 
     */
    private function getDataFromPOST(array $post, array $fisiere) {
        foreach ($post as $k => $v) {
            if (is_string($v)) {
                $post[$k] = trim($v);
            }
        }
        return array_merge($fisiere, $post);
    }

    /**
     * returneaza un element din array-ul data, in functie de cheie
     * @param  string   $arrayKey
     * @return mixed
     */
    public function getData($arrayKey) {
        return array_key_exists($arrayKey, $this->data) ? $this->data[$arrayKey] : null;
    }

    /*
     * returneaza un element din array-ul query, in functie de cheie
     * @param  string   $key
     * @return mixed
     */

    public function getQueryString($arrayKey) {
        return array_key_exists($arrayKey, $this->query) ? $this->query[$arrayKey] : null;
    }

    /**
     * returneaza url-ul 
     * @return string
     */
    public function fullUrl() {

        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, '?') !== false) {
            list($uri) = explode('?', $uri, 2);
        }

        $queryString = "";
        $queryArr = $this->query;
        unset($queryArr['url']);
        unset($queryArr['redirect']);

        if (!empty($queryArr)) {
            $queryString .= '?' . http_build_query($queryArr, null, '&');
        }
        return $uri . $queryString;
    }

    public function getRoot() {
        return APP;
    }

    /**
     * Returneaza base URL
     *
     * Exemple:
     *  * http://localhost/                         returneaza un string gol
     *  * http://localhost/ecommerce/public/index      returneaza ecommerce
     *  * http://localhost/ecommerce/produse/barbati/1   returneaza ecommerce
     *
     * @return string
     */
    public function getBaseUrl() {

        $baseUrl = str_replace(['public', '/'], ['', '\\'], dirname($_SERVER['SCRIPT_NAME']));
        return $baseUrl;
    }

    /**
     * Returneaza calea catre folderul principal al aplicatiei
     * @return string
     */
    public function root() {
        return 'http:\\' . '\localhost' . $this->getBaseUrl();
    }

    /**
     * detect if request is Ajax
     *
     * @return boolean
     */
    public function isAjax() {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        }
        return false;
    }

}
