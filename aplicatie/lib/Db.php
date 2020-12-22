<?php

namespace lib;

//Singleton class
class Db {

    private static $instance = null;
    private $link;
    private $host = "localhost";
    private $user = "root";
    private $password = "root";
    private $database = "ecommerce";

    private function __construct() {

        try {
            $this->link = new \mysqli($this->host, $this->user, $this->password, $this->database);
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
    }

    // Metoda clone este privata pentru a impiedica duplicarea conexiunii
    private function __clone() {
        
    }

    // conexiunea misqli
    public function getConnection() {
        return $this->link;
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

}
