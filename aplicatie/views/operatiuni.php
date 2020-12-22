<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
} else {
    $uid = $_SESSION['uid'];
}

$drepturi = $_SESSION['privs'];

$content = array();
$afisare = '';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>
<div id="sidebar">
    <div id="sidebar_title">Operatiuni</div>

    <ul id="cats">

        <?php
        //interfata are functionalitati diferite in functie de privilegiile userului 
        if (in_array('admin', $drepturi)) {
            ?>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\adaugaUser" >Adauga user</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\stergeUser" >Sterge user</a></li>
        <?php } if (in_array('operator', $drepturi)) { ?>   
            <li><a href="<?= PUBLIC_ROOT ?>Cont\adaugaProdus" >Adauga produs</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\vizualizareStocuri" >Vizualizeaza produse</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\vizualizareComenzi" >Vizualizeaza comenzi</a></li>
        <?php } ?>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\logout" >Logout</a></li>
        </ul>
    </div>
    <div class=" text-center">              
        <figure>
            <img src="../public/css/img/css_img/bg_11.jpg" alt="Image placeholder" class="img-fluid rounded">
        </figure>
        <p>
            <a href="<?= PUBLIC_ROOT ?>Produse/toateProdusele"class="btn btn-primary px-5 py-3">Cumpara acum</a>
        </p>   
    </div>      

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>   

