<?php

namespace controllers;

use lib\Controller;
use models\Produs;

/**
 * Controller pentru produse
 * @author elena
 */
class ProduseController extends Controller {

    public function toateProdusele() {
        $prod = new Produs();
        $data = $prod->findAllAssoc();
        $this->view->incarcaPagina('toateProdusele.php', $data);
    }

    public function produseFemei() {
        $prod = new Produs();
        $data = $prod->findByCondition("category_p LIKE '%f%'");
        $this->view->incarcaPagina('produseFemei.php', $data);
    }

    public function produseBarbati() {
        $prod = new Produs();
        $data = $prod->findByCondition("category_p LIKE '%m%'");

        $this->view->incarcaPagina('produseBarbati.php', $data);
    }

    public function viewByIdSubcategorie() {
        $idSubcategorie = $this->cerere->getQueryString('idSubcateg');
        if (is_numeric($idSubcategorie)) {
            $prod = new Produs();
            $data = $prod->viewByIdSubcategorie($idSubcategorie);
        }
        $this->view->incarcaPagina('produseSubcategorie.php', $data);
    }

    public function detaliiProdus() {

        $idProdus = $this->cerere->getQueryString('idProd');
        if (is_numeric($idProdus)) {
            $prod = new Produs();
            $data = $prod->getInfoProdusSiStoc($idProdus);
        }

        $this->view->incarcaPagina('detaliiProdus.php', $data);
    }

    public function sortare() {

        if (isset($_POST['actiune']) && $_POST['actiune'] == 'sortare') {
            $prod = new Produs();
            if ($_POST['pagina'] == 'produseFemei.php') {
                $data = $prod->findByCondition("category_p LIKE '%f%'");
            } elseif ($_POST['pagina'] == 'produseBarbati.php') {
                $data = $prod->findByCondition("category_p LIKE '%m%'");
            } elseif ($_POST['pagina'] == 'toateProdusele.php') {
                $data = $prod->findAllAssoc();
            } elseif ($_POST['pagina'] =='produseSubcategorie.php') {
                $idSubcategorie = $_POST['idSubcateg'];
                $data = $prod->viewByIdSubcategorie($idSubcategorie);              
            }

            switch ($_POST['name']) {
                case 'pretC' :
                    $data = Produs::sortare($data, 'pretC');
                    echo json_encode(array("produseSortate" => $data));
                    break;
                case 'pretD':
                    $data = Produs::sortare($data, 'pretD');
                    echo json_encode(array("produseSortate" => $data));
                    break;
                default:
                    $this->view->incarcaPagina($_POST['pagina'], $data);
            }
        }
    }

    public function cautare() {
        if (isset($_POST['actiune']) && $_POST['actiune'] == 'cautare') {
            $prod = new Produs();
            if ($_POST['pagina'] == 'toateProdusele.php') {
                $data = $prod->findAllAssoc();
            }
            //daca cauta '', returnam tot
            $dataRezultat = (!empty($_POST['name'])) ? Produs::cautare($data, $_POST['name']) : $data;
            echo json_encode(array("produseSortate" => $dataRezultat));
        }
    }

}
