<?php
include_once BASE_DIR . '/public/start.php';

if (!empty($this->data)) {
    $rez = $this->data;
} else {
    $msg[] = 'nu sunt produse de afisat';
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>
<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Sorteaza produsele</h3>
                <select class="sortareProduse">
                    <?php foreach ($componenteSortare as $k => $v) { ?>
                        <option value="<?= $k ?>" ><?= ucfirst($v) ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-6">
                <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
                <form action="#" method="post" id='txtSearchProduse'>
                    <input type="text" class="form-control" name='inputSearchProduse' placeholder="Cauta un cuvand si apasa enter...">
                </form>
            </div>
        </div>
        <div class="row continutRaspunsAjax">
            <?php
            if (!empty($rez) && is_array($rez)) {
                foreach ($rez as $v) {
                    foreach ($v as $k => $val) {
                        ${$k} = $v["$k"]; //am creat variabile cu denumire similara cu denumirea coloanei din BD
                    }
                    ?>
                    <div class="col-sm-6 col-lg-4 text-center item mb-4">
                        <?php if ($product_discount > 0) { ?><span class="tag">Sale</span><?php } ?>
                        <a href="<?= PUBLIC_ROOT ?>Produse/detaliiProdus?idProd=<?= $id ?>" > <img src="../public/img/<?= $product_image ?>.jpg" alt="Image"></a>
                        <h3 class="text-dark"><a href="<?= PUBLIC_ROOT ?>Produse/detaliiProdus?idProd=<?= $id ?>"><?= $product_title ?></a></h3>
                        <p class="price"><?php if ($product_discount > 0) { ?><del><?= $product_price ?></del> &mdash; <?= ($product_price * (1 - $product_discount / 100)) ?> lei
                            <?php } else {
                                echo $product_price ?> lei</p> <?php } ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>

