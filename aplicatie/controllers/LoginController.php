<?php

namespace controllers;

use lib\Controller;
use models\Utilizator;
use models\Subcategorie;

class LoginController extends Controller {

    public function index() {

        $this->view->incarcaPagina('login.php');
    }

    public function adaugaCont() {

        $this->view->incarcaPagina('adaugaCont.php');
    }

    public function login() {

        $proceseaza = 1;

        if (isset($this->cerere->data["buton"])) {
            $email = isset($this->cerere->data["email"]) ? $this->cerere->data["email"] : '';
            $parola = isset($this->cerere->data["parola"]) ? $this->cerere->data["parola"] : '';

            $erori = array_merge($this->validareCamp($email, 'email'), $this->validareCamp($parola, 'parola'));

            foreach ($erori as $eroare) {
                if (!empty($eroare)) {
                    $proceseaza = 0;
                }
            }
            if ($proceseaza) {
                $u = new Utilizator;
                if (!$u->isValidParolaLogin($email, $parola)) {
                    $erori[] = 'Email sau parola invalida';
                    $erori[] = 'In cazul in care nu aveti cont, va rugam sa va creati unul';
                    $data['erori'] = $erori;
                }
                else {
                     $subcateg = new Subcategorie();
                     $data = $subcateg->findAllAssoc();
                }
                isset($data['erori']) ? $this->view->incarcaPagina('login.php', $data) : $this->view->incarcaPagina('index.php', $data);
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('login.php', $data);
            }
        } else {
            $this->view->incarcaPagina('login.php');
        }
    }

    public function salvareCont() {

        $proceseaza = false;
        $email = '';
        $parola = '';

        if (isset($this->cerere->data["buton"])) {

            $user = new Utilizator();

            $campuriCareNuNecesitaValidare[] = 'drepturi';

            $erori = $this->valideazaConditiiCreareCont($campuriCareNuNecesitaValidare);

            if (!count($erori)) {
                $user->addCont($this->cerere->data);
                $this->view->incarcaPagina('login.php');
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('adaugaCont.php', $data);
            }
        } else {
            $this->view->incarcaPagina('login.php');
        }
    }

    private function validareCamp($camp, $tipValidare) {

        $erori = array();
        $vldTip = 'reg_' . $tipValidare;

        $vld = new \lib\Validator();

        if ('' === $camp) {
            $erori[$tipValidare] = 'Campul ' . $tipValidare . ' este obligatoriu!';
        } else if (!$vld->isValid($vld->$vldTip, $camp)) {
            // resetam 
            $username = '';
            $erori[$tipValidare] = 'Campul ' . $tipValidare . ' nu este valid!';
        }

        return $erori;
    }

    private function valideazaConditiiCreareCont($campuriCareNuNecesitaValidare = array()) {

        $user = new Utilizator();
        $erori = array();
        foreach ($this->formularBuilder->userBuilder as &$camp) {

            if (!empty($campuriCareNuNecesitaValidare) && in_array($camp['name'], $campuriCareNuNecesitaValidare)) {
                continue;
            }
            if ($this->cerere->data[$camp['name']] === '' && $camp['required'] === 1) {
                $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" este obligatoriu!';
            } elseif (!$this->validator->isValid($this->validator->{$camp['regex']}, $this->cerere->data[$camp['name']])) {
                $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" nu este valid!';
            }

            if ($camp['name'] == 'email' && in_array($this->cerere->data[$camp['name']], $user->getColumn('email'))) {

                $erori[$camp['name']] = $camp['label'] . '-ul este inregistrat in baza de date!';
            }
        }
        return $erori;
    }

}
