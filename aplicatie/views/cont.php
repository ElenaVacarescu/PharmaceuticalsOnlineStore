<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}
else {
    $uid = $_SESSION['uid'];
}

$drepturi = $_SESSION['privs'];

$content = array();
$afisare = '';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>

        <div id="sidebar">
            <div id="sidebar_title">Contul meu</div>

            <ul id="cats">	
                <li><a href="<?= PUBLIC_ROOT?>Cont\infoCont" >Datele mele</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\editareCont" >Editare cont</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\schimbaParola" >Schimbare parola</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Comenzi/getIstoricComenzi?idUser=<?=$uid ?>" >Istoric comenzi</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\stergeCont" >Stergere cont</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\logout">Logout</a></li>
            </ul>
        </div>
            <div class=" text-center">
                    <figure>
                        <img src="../public/css/img/css_img/bg_11.jpg" alt="Image placeholder" class="img-fluid rounded">
                    </figure>
                <p>
                    <a href="<?= PUBLIC_ROOT ?>Produse\toateProdusele"class="btn btn-primary px-5 py-3">Cumpara acum</a>
                </p>   
            </div>     

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>   



