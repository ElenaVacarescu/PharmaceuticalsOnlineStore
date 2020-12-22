<?php

namespace lib;

/**
 * gestioneaza raspunsul primit de la server si il trimite in interfata
 *
 * @author elena
 */
class Raspuns {

    public $headers;
    private $continut;
    private $statusCod;
    private $fisier = null;
    private $charset;
    private $versiune;
    private $statusRaspuns = [
        200 => 'OK',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    /**
     * Constructor.
     *
     * @param string $content 
     * @param int    $status  
     * @param array  $headers 
     */
    public function __construct($content = '', $status = 200, $headers = array()) {

        $this->continut = $content;
        $this->statusCod = $status;
        $this->headers = $headers;
        $this->statusText = $this->statusRaspuns[$status];
        $this->versiune = '1.0';
        $this->charset = 'UTF-8';
    }

    /**
     * trimite headerele HTTP si continutul in interfata
     *
     */
    public function send() {

        $this->trimiteHeadere();
        if ($this->fisier) {
            $this->readFile();
        } else {
            $this->trimiteContinut();
        }
        flush();
        return $this;
    }

    /**
     * trimite headere HTTP.
     * @return Response
     */
    private function trimiteHeadere() {

        if (headers_sent()) {
            return $this;
        }
        // seteaza header de status
        header(sprintf('HTTP/%s %s %s', $this->versiune, $this->statusCod, $this->statusText), true, $this->statusCod);

        // seteaza header de Content-Type
        if (!array_key_exists('Content-Type', $this->headers)) {
            header('Content-Type: ' . 'text/html; charset=' . $this->charset);
        }
        // seteaza alte headere
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, true, $this->statusCod);
        }
        return $this;
    }

    /**
     * Trimite continutul
     *
     * @return Response
     */
    private function trimiteContinut() {
        echo $this->continut;
        return $this;
    }

    /**
     * Seteaza continutul
     * @param string $continut 
     * @return Raspuns
     */
    public function setContinut($continut = "") {
        $this->continut = $continut;
        return $this;
    }

    /**
     * citeste fisier

     * @return Raspuns
     */
    private function readFile() {
        readfile($this->fisier);
        return $this;
    }

    /**
     * seteaza codul de status si mesajul aferent
     * @param int $statusCod HTTP status cod
     * @return Raspuns
     */
    public function setStatusCode($statusCod) {

        $this->statusCod = (int) $statusCod;
        $this->statusText = isset($this->statusTexts[$statusCod]) ? $this->statusTexts[$statusCod] : '';

        return $this;
    }

    /**
     * goleste output buffer
     * @return void
     */
    public function clearBuffer() {
        if (ob_get_level() > 0) {
            return ob_clean();
        }
    }

}
