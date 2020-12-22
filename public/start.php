<?php

use lib\Html;
use lib\Formular;
use lib\FormBuilders;
use lib\Login;
use lib\Db;
use models\Subcategorie;
use models\Categorie;

/**
 * fisierul start.php este un fisier pe care il inludem in toate paginile din folderul View;
 * presupune instantiere diverse obiecte de care avem nevoie in paginile respective
 */
$db = Db::getInstance();
$link = $db->getConnection();


$html = new Html();
$form = new Formular();
$fbld = new FormBuilders();

$subcateg = new Subcategorie();
$subcateg = $subcateg->findAllAssoc();

$categ = new Categorie();
$categs = $categ->findAllAssoc();

/* setam drepturile de acces si variabilele de care avem nevoie in toate paginile
  aici creez si obiectul privilegii - vezi clasa User */

$u = new Login();

if ($u->esteLogat()) {
    $u->prenume = $_SESSION['prenume'];
    $u->nume = $_SESSION['nume'];
    $name = $u->prenume . ' ' . $u->nume;

    // setam privilegiile userului pe care le luam din $_SESSION
    $u->setPrivilegii($_SESSION['privs']);
} else {
    // daca nu este logat cream un user anonim care va avea voie pe paginile cu dreptul 'normal'
    $_SESSION['privs'] = array('normal');
    $u->setPrivilegii(array('normal'));
    $name = '';
}

/* campuri sortare */
$componenteSortare = array('' => 'Selecteaza o optiune', 'pretC' => 'Pret crescator', 'pretD' => 'Pret descrescator');
