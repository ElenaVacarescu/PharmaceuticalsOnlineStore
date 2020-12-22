<?php

namespace controllers;

use lib\Controller;
use lib\FormBuilders;
use lib\Validator;

/**
 *
 * @author elena
 */
class ContactController extends Controller {

    public function index() {

        $this->view->incarcaPagina('contact.php');
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
                $to = 'elena.vacarescu25@gmail.com';
                $subiect = $this->cerere->data['subiect'];
                $mesaj = "\n Mesaj:".$this->cerere->data['message']."\n Nume:".$this->cerere->data['nume']."\n Numar telefon: ". $this->cerere->data['telefon'].
                        "\n Adresa de email: ".$this->cerere->data['email'];
                $headere = "From: " . $this->cerere->data['email'] ."\r\n";

                try {
                    mail($to, $subiect, $mesaj, $headere); 
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

    function validareFormular() {

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
