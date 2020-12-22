<?php

namespace lib;

/**
 * in aceasta clasa sunt definite drepturile minime de acces pe paginile din aplicatie
 * de exemplu, doar userii cu drept de admin, au acces la paginile 'adaugaUser' si 'stergeUser'
 */
class Rol {

    private $drepturiNecesarePagini = array(
        'index' => 'normal',
        'produseFemei' => 'normal',
        'produseBarbati' => 'normal',
        'toateProdusele' => 'normal',
        'serviciiClienti' => 'normal',
        'produseSubcategorie' => 'normal',
        'produseCategorii' => 'normal',
        'detaliiProdus' => 'normal',
        'contact' => 'normal',
        'infoLogare' => 'normal',
        'adaugaCont' => 'normal',
        'afiseazaComanda' => 'normal',
        'schimbaParola' => 'normal',
        'cont' => 'normal',
        'infoCont' => 'normal',
        'editareCont' => 'normal',
        'cosCumparaturi' => 'normal',
        'stergeCont' => 'normal',
        'login' => 'normal',
        'vizualizareIstoricComenziPersonale' => 'normal',
        'operatiuni' => array('operator', 'admin'),
        'adaugaProdus' => 'operator',
        'vizualizareComenzi' => 'operator',
        'vizualizareStocuri' => 'operator',
        'adaugaUser' => 'admin',
        'stergeUser' => 'admin'
    );
    private $drepturiUser;

    function setDrepturi(array $drepturi) {
        // un array cu privilegiile curente
        $this->drepturiUser = $drepturi;
    }

    function areDrepturiAcces($pagina) {
        if (array_key_exists($pagina, $this->drepturiNecesarePagini)) {
            $drepturiNecesare = $this->drepturiNecesarePagini[$pagina];
        } else {
            $drepturiNecesare = 'normal'; //ii setez drepturi minime daca nu exista pagina setata 
        }

        // verificam daca dreptul de acces corespunzator paginii se afla printre drepturile userului curent
        if (is_array($this->drepturiUser)) {
            if(is_array($drepturiNecesare)) {
                foreach($drepturiNecesare as $drept) {
                    if(in_array($drept, $this->drepturiUser)) {
                        return true;
                    }
                }
            }
            else {
                 if(in_array($drepturiNecesare, $this->drepturiUser)) {
                        return true;
                    }
            }
        }
        return false;
    }

}
