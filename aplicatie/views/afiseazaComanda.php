<?php
include_once BASE_DIR . '/public/start.php';

$afisare = '';
$content = array();
$pret_total = 0;

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

$pret_total = 0;
$data = $this->data;
?>
<div id="content">  
    <div id="content_area">
        <?php if (!empty($data)) { ?>
            <div class="col-md-12">
                <h2 class="h3 mb-5 text-black"></h2>
                <h2 class="h3 mb-5 text-black">Continut comanda</h2>
                <h2 class="h3 mb-5 text-black"></h2>
            </div>
            <table class="table custom-table">
                <thead>
                <th>Denumire produs</th>
                <th>Descriere</th>
                <th>Cantitate</th>
                <th>Pret(lei)</th>
                </thead>
                <tbody> 
                    <?php
                    foreach ($data as $produs) {
                        $comanda_id = $produs['comanda_id'];
                        $product_title = $produs['product_title'];
                        $product_description = $produs['product_description'];
                        $cantitate = $produs['cantitate'];
                        $pret = (int) $produs['Pret'];
                        $pret_total += $pret;
                        ?> 
                        <tr>
                            <td><?= $product_title ?></td>
                            <td><?= $product_description ?></td>
                            <td><?= $cantitate ?></td>
                            <td><?= $pret ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan='3' ><b>Total de plata</b></td>
                        <td ><b><?= $pret_total ?> lei</b></td>
                    </tr> 
                </tbody>
            </table>
        <?php } ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <span class="icon-check_circle display-3 text-success"></span>
                    <h2 class="display-3 text-black">Multumim pentru comanda!</h2>
                    <p class="lead mb-5">Comanda fost plasata cu succes si va ajunge la dumneavoastra in cel mai scurt timp!</p>
                    <p>Codul de identificare al comenzii este <?=$comanda_id?></p>
                    <p><a href="<?= PUBLIC_ROOT ?>Produse/toateProdusele" class="btn btn-md height-auto px-4 py-3 btn-primary">Inapoi in magazin</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>   