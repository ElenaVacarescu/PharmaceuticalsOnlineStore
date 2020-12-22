
<?php

use lib\Login;

$u = new Login();
$logout = $html->scrieTag('a', 'style="padding:5px" href="' . PUBLIC_ROOT . 'Cont\logout"', 'Logout');
$info_cont = $html->scrieTag('a', 'style="padding:5px" href="' . PUBLIC_ROOT . 'Cont\index"', 'Contul meu');
$login = $html->scrieTag('a', 'style="padding:5px" href="' . PUBLIC_ROOT . 'Login\index"', 'Login');
$creare_cont = $html->scrieTag('a', 'style="padding:5px" href="' . PUBLIC_ROOT . 'Login/adaugaCont"', 'Creare cont');
$operatiuni = $html->scrieTag('a', 'style="padding:5px" href="' . PUBLIC_ROOT . 'Cont\operatiuni"', 'Operatiuni');
(!empty($_SESSION['privs'])) ? $drepturi = $_SESSION['privs'] : '';

$links = '<link rel="stylesheet" type="text/css"  href="../public/css/style.css" />'
        . '<link rel="stylesheet" type="text/css"  href="../public/css/bootstrap.css" />.'
        . '<link rel="stylesheet" type="text/css"  href="../public/css/bootstrap.min.css" />';


echo $html->startHtml('ro', 'ecommerce', $links);
?>

<div class="site-wrap">

    <div class="site-navbar py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="logo">
                    <div class="site-logo">
                        <a href="<?= PUBLIC_ROOT ?>Home/index" class="js-logo-clone">Drogheria Galanthus</a>
                    </div>
                </div>
                <div class="main-nav d-none d-lg-block">
                    <nav class="site-navigation text-right text-md-center" role="navigation">
                        <ul class="site-menu js-clone-nav d-none d-lg-block">
                            <li class="active"><a  href="<?= PUBLIC_ROOT . "Home/index"; ?>">Home</a></li>
                            <li class="has-children">
                                <a href="#">Produse</a>
                                <ul class="dropdown">
                                    <?php
                                    if (isset($categs)) {
                                        foreach ($categs as $cat) {
                                            ?>
                                            <li class="has-children">
                                                <a href="#"><?= $cat['cat_denumire'] ?></a><?php if (isset($subcateg)) { ?>
                                                    <ul class="dropdown">
                                                        <?php
                                                        foreach ($subcateg as $v) {
                                                            if ($cat['id'] !== $v['idCateg']) {
                                                                continue;
                                                            }
                                                            foreach ($v as $k => $val) {
                                                                ${$k} = $v["$k"];
                                                            }
                                                            ?>
                                                            <li><a href="<?= PUBLIC_ROOT ?>Produse/viewByIdSubcategorie?idSubcateg=<?= $id ?>" ><?= $nume ?></a></li>
                                                        <?php }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                            <li class="has-children">
                                <a href="#">Categorii</a>
                                <ul class="dropdown">
                                    <li><a href="<?= PUBLIC_ROOT . "Produse/toateProdusele"; ?>">Toate produsele</a></li>
                                    <li><a href="<?= PUBLIC_ROOT . "Produse/produseFemei"; ?>">Pentru ea</a></li>
                                    <li><a href="<?= PUBLIC_ROOT . "Produse/produseBarbati"; ?>">Pentru el</a></li>
                                </ul>
                            </li>
                            <li><a href="<?= PUBLIC_ROOT . "Servicii/index"; ?>">Despre noi</a></li>
                            <li><a href="<?= PUBLIC_ROOT . "Contact/index"; ?>">Contact</a></li>
                            <li><a href="<?= PUBLIC_ROOT . "CosCumparaturi/index"; ?>" class="icons-btn d-inline-block bag"><img src="../public/css/img/css_img/shopping-cart.png" width="40px" height="40px" alt="no img"/></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="main-nav d-none d-lg-block">
            <nav class="site-navigation text-right text-md-center" role="navigation">
                <ul class="site-menu js-clone-nav d-none d-lg-block">
                    <li style="float: left"><?php if ($name) echo 'Salut, ' . $name ?></li>
                    <li><a href="#"><?php echo ($u->esteLogat()) ? $logout : $login ?></a></li> 
                    <li><a href="#"><?php echo ($u->esteLogat()) ? $info_cont : $creare_cont ?></a></li>                                     
                    <?php if (!empty($drepturi) && (in_array('admin', $drepturi) || in_array('operator', $drepturi))) { ?>
                        <li><a href="#"><?php echo $operatiuni ?></a></li>  
                    <?php } ?>
                </ul>
            </nav>
        </div>    
    </div>
</div>
