<?php
include_once BASE_DIR . '/public/start.php';

if (!empty($this->data)) {
    $rez = $this->data;
} else {
    $msg[] = 'Nu sunt categorii de afisat.';
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>
<div class="site-blocks-cover" style="background-image: url('../public/css/img/css_img/bg_1.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto order-lg-2 align-self-center">
                <div class="site-block-cover-content text-center">
                    <h2 class="sub-title"></h2>
                    <h1></h1>
                    <p>
                        <a href="<?= PUBLIC_ROOT ?>Produse/toateProdusele" class="btn btn-primary px-5 py-3">Cumpara acum</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row align-items-stretch section-overlap">
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap bg-primary h-100">
                    <a href="#" class="h-100">
                        <h5>Transport <br> gratuit</h5>
                        <p>

                            <strong>Oferim transport gratuit indiferent de valoarea comenzii.</strong>
                        </p>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap h-100">
                    <a href="#" class="h-100">
                        <h5>Sezon <br> reduceri de pana la 20%</h5>
                        <p>

                            <strong>Oferim reduceri importante la o gama larga de produse.</strong>
                        </p>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap bg-primary h-100">
                    <a href="#" class="h-100">
                        <h5>Cumpara <br> un card cadou</h5>
                        <p>

                            <strong>Oferim posibilitatea de a achizitiona carduri cadou.</strong>
                        </p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">Categorii produse</h2>
            </div>
        </div>         
        <div class="row">
            <?php
            if (!empty($rez) && is_array($rez)) {
                foreach ($rez as $v) {
                    foreach ($v as $k => $val) {
                        ${$k} = $v["$k"]; //am creat variabile cu denumire similara cu denumirea coloanei din BD
                    }
                    ?>
                    <div class="col-sm-6 col-lg-4 text-center item mb-4">
                        <a href="<?= PUBLIC_ROOT ?>Produse/viewByIdSubcategorie?idSubcateg=<?= $id ?>" > <img src="../public/img/subcategorii/<?= $foto ?>.jpg" alt="Image"></a>
                        <h3 class="text-dark"><a href="<?= PUBLIC_ROOT ?>Produse/viewByIdSubcategorie?idSubcateg=<?= $id ?>"><?= $nume ?></a></h3>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="<?= PUBLIC_ROOT ?>Produse/toateProdusele" class="btn btn-primary px-4 py-3">Vedeti toate produsele</a>
            </div>
        </div>
    </div>
</div>

<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>



