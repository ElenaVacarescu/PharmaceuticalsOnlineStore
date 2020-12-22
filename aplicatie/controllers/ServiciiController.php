<?php

namespace controllers;

use lib\Controller;
use models\Utilizator;

/**
 * controller pentru apelurile care vin din tab-ul de servicii
 * @author elena
 */
class ServiciiController extends Controller {

    public function index() {

        $this->view->incarcaPagina('serviciiClienti.php');
    }

    public function contulMeu() {

        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : '';

        if (!empty($uid)) {

            $cont = new Utilizator();

            $data = $cont->afiseazaInformatiiCont($uid);

            $this->view->incarcaPagina('infoCont.php', $data);
        } else {
            \lib\Aplicatie::redirect('Login\index');
        }
    }

    public function trimiteEmail() {

        $proceseaza = false;
        $erori = array();
        $data = array();

        if (isset($this->cerere->data["buton"])) {

            $erori = $this->validareFormular();

            if (!count($erori)) {
                $proceseaza = true;
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('contact.php', $data);
            }

            if ($proceseaza) {
                $to = 'elena.badoi86@gmail.com';
                $subiect = $this->cerere->data['subiect'];
                $mesaj = $this->cerere->data['message'];
                $headere = 'From: ' . $this->cerere->data['email'];

                try {
                    /* este necesara instalarea unui server de email pentru a putea transmite emailul */
                    //mail($to, $subiect, $mesaj, $headere); 
                    $data['msg'] = 'Multumim pentru mesaj.Revenim in cel mai scurt timp cu un raspuns.';
                    $this->view->incarcaPagina('contact.php', $data);
                } catch (Exception $e) {
                    $data['msg'] = 'Eroare la transmiterea mesajului.';
                    $this->view->incarcaPagina('contact.php', $data);
                }
            }
        } else {
            $this->view->incarcaPagina('contact.php');
        }
    }

    private function validareFormular() {

        $erori = array();

        foreach ($this->formularBuilder->contactBuilder as &$camp) {
            if ($this->cerere->data[$camp['name']] === '' && $camp['required'] === 1) {
                $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" este obligatoriu!';
            } elseif (!$this->validator->isValid($this->validator->{$camp['regex']}, $this->cerere->data[$camp['name']])) {
                $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" nu este valid!';
            }
        }
        return $erori;
    }

}
