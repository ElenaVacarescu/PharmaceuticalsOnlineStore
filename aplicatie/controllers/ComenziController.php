<?php

namespace controllers;

use lib\Controller;
use models\Comanda;

/**
 * Description of ComenziController
 *
 * @author elena
 */
class ComenziController extends Controller {

    public function vizualizareComanda() {

        $com = new Comanda();

        if (!empty($this->cerere->query['comandaId']) && !empty($this->cerere->query['idUser'])) {
            $idComanda = $this->cerere->query['comandaId'];
            $uid = $this->cerere->query['idUser'];

            $data['comenzi'] = $com->vizualizareComenziPersonale($uid);
            $data['infoComanda'] = $com->getInformatiiComanda($idComanda);
            $this->view->incarcaPagina('vizualizareIstoricComenziPersonale.php', $data);
        }
    }

    public function getIstoricComenzi() {
        $comanda = new Comanda();

        if (!empty($this->cerere->query['idUser'])) {
            $uid = $this->cerere->query['idUser'];
        }

        $data['comenzi'] = $comanda->vizualizareComenziPersonale($uid);

        $this->view->incarcaPagina('vizualizareIstoricComenziPersonale.php', $data);
    }

    public function updateStatusComanda() {
        $com = new Comanda();
        if (!empty($this->cerere->query['comandaId']) && !empty($this->cerere->query['status']) && !empty($this->cerere->query['idUser'])) {
            $idComanda = $this->cerere->query['comandaId'];
            $status = $this->cerere->query['status'];
            $uid = $this->cerere->query['idUser'];
            
            $com->updateStatusComanda($idComanda, $status);

            $data['comenzi'] = $com->vizualizareComenziPersonale($uid);
            $this->view->incarcaPagina('vizualizareIstoricComenziPersonale.php', $data);
        }
    }

}
