<?php

namespace controllers;

use lib\Controller;
use lib\Sesiune;
use models\Produs;
use models\Utilizator;
use models\Comanda;
use models\Stoc;
use models\Subcategorie;

/**
 * Description of ContController
 *
 * @author elena
 */
class ContController extends Controller {

    public function index() {

        $this->view->incarcaPagina('cont.php');
    }

    public function operatiuni() {

        $this->view->incarcaPagina('operatiuni.php');
    }

    public function infoCont() {

        $uid = $_SESSION['uid'];

        if (isset($uid)) {

            $cont = new Utilizator();

            $data = $cont->afiseazaInformatiiCont($uid);

            $this->view->incarcaPagina('infoCont.php', $data);
        }
    }

    public function editareCont() {

        $user = new Utilizator();
        $uid = $_SESSION['uid'];

        if (isset($uid)) {
            $data = $user->afiseazaInformatiiCont($uid);
        }

        if (isset($this->cerere->data["buton"])) {

            $campuriCareNuNecesitaValidare = array('parola', 'drepturi'); //procesare partiala fara drepturi si parola la update

            $erori = $this->valideazaCampuriUser($campuriCareNuNecesitaValidare);

            if (!count($erori)) {

                $user->update($uid, $this->cerere->data);

                $data = $user->afiseazaInformatiiCont($uid);

                $this->view->incarcaPagina('infoCont.php', $data);
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('editareCont.php', $data);
            }
        } else {
            $this->view->incarcaPagina('editareCont.php', $data);
        }
    }

    public function schimbaParola() {

        $user = new Utilizator();
        $uid = $_SESSION['uid'];
        $proceseaza = true;

        if (isset($this->cerere->data["schimbare_parola"])) {

            $parola = $this->cerere->data["parola"];
            $parolaNoua1 = $this->cerere->data["parola_noua1"];
            $parolaNoua2 = $this->cerere->data["parola_noua2"];

            $erori = $this->validareSchimbareParola($parola, $parolaNoua1, $parolaNoua2);
            if(!$user->isValidPassword($uid, $parola)) {
               $erori['parolaInitialaGresita'] = 'Parola curenta este eronata.'; 
            }
            
            foreach ($erori as $eroare) {
                if (!empty($eroare)) {
                    $proceseaza = false;
                }
            }

            if ($proceseaza) {
                $user->updateParola($uid, $parola, $parolaNoua1);
                $data['msg'] = 'Parola a fost schimbata cu succes';
            } else {
                $data['erori'] = $erori;
            }

            $this->view->incarcaPagina('schimbaParola.php', $data);
        } else {
            $this->view->incarcaPagina('schimbaParola.php');
        }
    }

    public function logout() {

        Sesiune::my_session_destroy();

        $data = $this->getSubcategorii();
        $this->view->incarcaPagina('index.php', $data);
    }

    public function getSubcategorii() {
        $subcateg = new Subcategorie();

        $data = $subcateg->findAllAssoc();

        return $data;
    }

    public function adaugaProdus() {

        if (isset($this->cerere->data["buton"])) {

            $prod = new Produs();

            $erori = $this->valideazaCampuriProduse();

            if (!count($erori)) {
                $prod->add($this->cerere->data);

                $data['msg'][] = 'Produsul a fost adaugat cu succes';
                $this->view->incarcaPagina('adaugaProdus.php', $data);
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('adaugaProdus.php', $data);
            }
        } else {
            $this->view->incarcaPagina('adaugaProdus.php');
        }
    }

    public function adaugaUser() {

        if (isset($this->cerere->data["buton"])) {

            $user = new Utilizator();

            $erori = $this->valideazaCampuriUser();

            if (!count($erori)) {
                $user->addUser($this->cerere->data);

                $data['msg'][] = 'Userul a fost adaugat cu succes';
                $this->view->incarcaPagina('adaugaUser.php', $data);
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('adaugaUser.php', $data);
            }
        } else {
            $this->view->incarcaPagina('adaugaUser.php');
        }
    }

    public function stergeUser() {

        if (isset($this->cerere->data["buton"])) {

            $user = new Utilizator();
            $email = $this->cerere->data['email'];
            $erori = $this->validareCamp($email, 'email');
            if(empty($erori) && !$user->userExists($email)) {
                $erori['userInexistent'] = 'Utilizatorul cu email '.$email.' nu exista in baza de date';
            }
            if (!count($erori)) {
                $user->stergeUser($email);

                $data['msg'][] = 'Contul userului ' . $email . ' a fost sters cu succes';
                $this->view->incarcaPagina('stergeUser.php', $data);
            } else {
                $data['erori'] = $erori;
                $this->view->incarcaPagina('stergeUser.php', $data);
            }
        } else {
            $this->view->incarcaPagina('stergeUser.php');
        }
    }

    public function stergeCont() {

        $uid = $_SESSION['uid'];
        $user = new Utilizator();

        if (isset($_POST['da'])) {
            $user->stergeCont($uid);
            Sesiune::my_session_destroy();

            $data = $this->getSubcategorii();
            $this->view->incarcaPagina('index.php', $data);
        } elseif (isset($_POST['nu'])) {

            $data = $user->afiseazaInformatiiCont($uid);
            $this->view->incarcaPagina('infoCont.php', $data);
        } else {
            $this->view->incarcaPagina('stergeCont.php');
        }
    }

    public function vizualizareComenzi() {

        $com = new Comanda();
        $data['comenzi'] = $com->vizualizareComenzi();
        $this->view->incarcaPagina('vizualizareComenzi.php', $data);
    }
    
    public function vizualizareStocuri() {

        $stoc = new Stoc();
        $data['stoc'] = $stoc->vizualizareProduseStoc();
        $this->view->incarcaPagina('vizualizareStocuri.php', $data);
    }

    public function vizualizareComanda() {

        $com = new Comanda();
        //var_dump($this->cerere->query['comandaId']);

        if (!empty($this->cerere->query['comandaId'])) {
            $idComanda = $this->cerere->query['comandaId'];

            $data['comenzi'] = $com->vizualizareComenzi();
            $data['infoComanda'] = $com->getInformatiiComanda($idComanda);
            $this->view->incarcaPagina('vizualizareComenzi.php', $data);
        }
    }

    public function updateStatusComanda() {
        $com = new Comanda();
        if (!empty($this->cerere->query['comandaId']) && !empty($this->cerere->query['status'])) {
            $idComanda = $this->cerere->query['comandaId'];
            $status = $this->cerere->query['status'];
            $com->updateStatusComanda($idComanda, $status);

            $data['comenzi'] = $com->vizualizareComenzi();
            $this->view->incarcaPagina('vizualizareComenzi.php', $data);
        }
    }

    private function valideazaCampuriProduse() {

        $erori = array();

        foreach ($this->formularBuilder->produseBuilder as &$camp) {
            if ('choice' == $camp['type']) {
                 $camp['data'] = isset($this->cerere->data[$camp['name']]) ? $this->cerere->data[$camp['name']] : null;
                if (null === $camp['data'] && 1 == $camp['required']) {
                    $erori[$camp['name']] = $camp['label'] . ': Alege cel putin o varianta!';
                }
            } elseif ('file' == $camp['type']) {
                if (isset($this->cerere->data[$camp['name']])) {
                    $file = $this->cerere->data[$camp['name']];
                    $maxsize = 1000 * 5000; // 5.000.000 = 5MB
                    $acceptable = array(
                        'image/jpeg',
                        'image/jpg',
                        'image/gif',
                        'image/png');

                    if ('' === $this->cerere->data[$camp['name']] && 1 === $camp['required']) {
                        $erori[$camp['name']] = 'Va rugam sa incarcati o imagine';
                    } elseif (isset($file['size']) && $file['size'] >= $maxsize) {
                        $erori[$camp['name']] = 'Fisierul este prea mare. Acesta trebuie sa aiba mai putin de 5 MB.';
                    } elseif (isset($file['type']) && !in_array($file['type'], $acceptable) && (!empty($this->cerere->data[$camp['name']]['type']))) {
                        $erori[$camp['name']] = 'Format invalid. Acceptam doar format JPG, GIF si PNG';
                    }

                    if (empty($erori[$camp['name']])) {
                        $uploadPath = BASE_DIR . '/public/img/';
                        $destination = $uploadPath . basename($file['name']);
                        move_uploaded_file($file['tmp_name'], $destination);
                    }
                }
            } elseif ('text' == $camp['type'] || 'textarea' == $camp['type']) {

                $camp['init_data'] = $this->cerere->data[$camp['name']];

                if ($this->cerere->data[$camp['name']] === '' && $camp['required'] === 1) {
                    $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" este obligatoriu!';
                } elseif (!$this->validator->isValid($this->validator->{$camp['regex']}, $this->cerere->data[$camp['name']])) {
                    $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" nu este valid!';
                }
            }
        }
        return $erori;
    }

    private function valideazaCampuriUser($campuriCareNuNecesitaValidare = array()) {

        $erori = array();
        foreach ($this->formularBuilder->userBuilder as &$camp) {
            if (!empty($campuriCareNuNecesitaValidare) && in_array($camp['name'], $campuriCareNuNecesitaValidare)) {
                continue;
            }
            if ('choice' == $camp['type']) {
                $camp['data'] = isset($this->cerere->data[$camp['name']]) ? $this->cerere->data[$camp['name']] : null;
                if (null === $camp['data'] && 1 == $camp['required']) {
                    $erori[$camp['name']] = $camp['label'] . ': Alege cel putin o varianta!';
                }
            } else if ('text' == $camp['type'] || 'password' == $camp['type']) {

                if ($this->cerere->data[$camp['name']] === '' && $camp['required'] === 1) {
                    $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" este obligatoriu!';
                } elseif (!$this->validator->isValid($this->validator->{$camp['regex']}, $this->cerere->data[$camp['name']])) {
                    $erori[$camp['name']] = 'Campul "' . $camp['label'] . '" nu este valid!';
                }
            }
        }
        return $erori;
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

    private function validareSchimbareParola($parola, $parola1, $parola2) {
        $erori = array();
        if ('' === $parola) {
            $erori['parola'] = "\nCampul 'Parola actuala' este obligatoriu!";
        } else if (!$this->validator->isValid($this->validator->reg_parola, $parola)) {
            $erori['parola'] = "\nCampul 'Parola actuala' nu este valid.Permitem cratima,litere,cifre,virgula, punct si spatii intre 6 si 16 caractere";
        }
        if ('' === $parola1) {
            $erori['parola1'] = "\nCampul 'Parola noua' este obligatoriu!";
        } else if (!$this->validator->isValid($this->validator->reg_parola, $parola1)) {
            $erori['parola1'] = "\nCampul 'Noua parola' nu este valid. Permitem cratima,litere,cifre,virgula, punct si spatii intre 6 si 16 caractere";
        }
        if ('' === $parola2) {
            $erori['parola2'] = 'Va rugam sa reintroduceti noua parola. Campul este obligatoriu!';
        } else if (!$this->validator->isValid($this->validator->reg_parola, $parola2)) {
            $erori['parola2'] = "\nCampul de reconfirmare parola nu este valid.Permitem cratima,litere,cifre,virgula, punct si spatii intre 6 si 16 caractere";
        } else if ($parola1 !== $parola2) {
            $erori['parola2'] = "\nCele doua parole nu coincid!";
        }
        return $erori;
    }

    public function accesareComanda() {
        if (isset($_POST['actiune']) && $_POST['actiune'] == 'comandaAccesata') {
            $comanda = new Comanda();
            if (!empty($_POST['idComanda']) && !empty($_SESSION['uid'])) {
                $idComanda = $_POST['idComanda'];
                $idUser = $_SESSION['uid'];
                if($comanda->comandaAccesataExista($idComanda)) {
                    $comanda->updateStatusComandaAccesata($idComanda, $idUser);
                }
                else {
                    $comanda->insertStatusComandaAccesata($idComanda, $idUser);
                }
            }
            echo json_encode(array("rezultat" => 'Update cu succes'));
        }
    }
    
        public function accesareInactiva() {
        if (isset($_POST['actiune']) && $_POST['actiune'] == 'accesareInactiva') {
            $comanda = new Comanda();
            if (!empty($_SESSION['uid'])) {
                $idUser = $_SESSION['uid'];
                $comanda->updateAccesareInactiva($idUser);
            }
            echo json_encode(array("rezultat" => 'Update cu succes'));
        }
    }

}
