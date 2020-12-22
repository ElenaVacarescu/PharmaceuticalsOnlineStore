<?php

namespace controllers;

use lib\Controller;
use models\Cos;
use models\Comanda;

/**
 * include metodele apelate la nivelul cosului de cumparaturi
 * @author elena
 */
class CosCumparaturiController extends Controller {

    public function index() {

        isset($_SESSION['uid']) ? $uid = $_SESSION['uid'] : '';
        if($uid) {
        $cos = new Cos();
        $data = $cos->afiseazaDetaliiCos($uid);

        $this->view->incarcaPagina('cosCumparaturi.php', $data);
        }
        else {
            \lib\Aplicatie::redirect('Login\index');
        }
    }

    public function adaugaCantitate() {
        $uid = $_SESSION['uid'];
        $idProdus = $this->cerere->getQueryString('idProdus');
        if (is_numeric($idProdus)) {
            $cosUpd = new Cos();
            ;
            $cosUpd->updateCantitate($idProdus, $uid);
        }

        $cos = new Cos();
        $data = $cos->afiseazaDetaliiCos($uid);
        $this->view->incarcaPagina('cosCumparaturi.php', $data);
    }

    public function stergeCantitate() {

        $uid = $_SESSION['uid'];
        $idProdus = $this->cerere->getQueryString('idProdus');
        if (is_numeric($idProdus)) {
            $cosUpd = new Cos();

            if ($cosUpd->selecteazaCantitate($idProdus, $uid) > 1) {
                $cosUpd->stergeCantitate($idProdus, $uid);
            } else {
                $cosUpd->stergeProdus($idProdus, $uid);
            }
        }
        $cos = new Cos();
        $data = $cos->afiseazaDetaliiCos($uid);
        $this->view->incarcaPagina('cosCumparaturi.php', $data);
    }

    public function adaugaProdus() {

        $uid = (isset($_SESSION['uid'])) ? $_SESSION['uid'] : "";

        if (!empty($uid)) { //utilizatorul este logat
            $idProdus = $this->cerere->getQueryString('idProd');

            $cos = new Cos();
            if (!$cos->cosExists($uid)) {
                $cos->add($idProdus, $uid);
            } elseif ($cos->selecteazaProdus($idProdus, $uid)) {
                $cos->updateCantitate($idProdus, $uid);
            } else {
                $cos->adaugaProdus($idProdus, $uid);
            }
            \lib\Aplicatie::back();
        } else {
            $this->view->incarcaPagina('infoLogare.php');
        }
    }

    public function adaugaComanda() {

        $uid = $_SESSION['uid'];

        if (isset($this->cerere->data['adaugaComanda'])) {

            $comanda = new Comanda();

            $idComanda= $comanda->add($uid);

            $data = $comanda->getInformatiiComanda($idComanda);

            $this->view->incarcaPagina('afiseazaComanda.php', $data);
        }
    }

}
