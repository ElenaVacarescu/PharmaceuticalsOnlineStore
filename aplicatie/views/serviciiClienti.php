<?php
include_once BASE_DIR . '/public/start.php';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>

<div class="site-blocks-cover inner-page" style="background-image: url('../public/css/img/css_img/bg_1.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto align-self-center">
                <div class=" text-center">
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section bg-light custom-border-bottom" data-aos="fade">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="block-16">
                    <figure>
                        <img src="../public/css/img/css_img/bg_4.jpeg" alt="Image placeholder" class="img-fluid rounded">    
                    </figure>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <div class="site-section-heading pt-3 mb-4">
                    <h2 class="text-black">Despre noi</h2>
                </div>
                <?php echo file_get_contents('../aplicatie/views/fisiere/despre-noi.html') ?>
            </div>
        </div>
    </div>
</div>  

<div class="site-section bg-light custom-border-bottom" data-aos="fade">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-5 mr-auto">
                <div class="site-section-heading pt-3 mb-4">
                    <h2 class="text-black">Info comenzi</h2>
                </div>
                <?php echo file_get_contents('../aplicatie/views/fisiere/comanda-livrare.html') ?>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-6">
                <div class="block-16">
                    <figure>
                        <img src="../public/css/img/css_img/bg_6.jpg" alt="Image placeholder" class="img-fluid rounded">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section site-section-sm site-blocks-1 border-0" data-aos="fade">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
                <div class="icon mr-4 align-self-start">
                    <span class="icon-truck text-primary"></span>
                </div>
                <div class="text">
                    <h2>Retur gratuit</h2>
                    <?php echo file_get_contents('../aplicatie/views/fisiere/politica-retur.html') ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
                <div class="icon mr-4 align-self-start">
                    <span class="icon-refresh2 text-primary"></span>
                </div>
                <div class="text">
                    <h2>Termeni si conditii</h2>
                    <?php echo file_get_contents('../aplicatie/views/fisiere/termeni-si-conditii.html') ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
                <div class="icon mr-4 align-self-start">
                    <span class="icon-help text-primary"></span>
                </div>
                <div class="text">
                    <h2>Politica cookie</h2>
                    <?php echo file_get_contents('../aplicatie/views/fisiere/politica-cookies.html') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>
