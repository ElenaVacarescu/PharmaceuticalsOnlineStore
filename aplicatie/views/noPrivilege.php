<?php
include_once BASE_DIR . '/public/start.php';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>

<div id="content">
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <span class="icon-check_circle display-3 text-success"></span>
            <p class="lead mb-5">Nu ai drepturi sa accesezi aceasta pagina!</p>
            <p><a href="<?= PUBLIC_ROOT ?>Produse\toateProdusele" class="btn btn-md height-auto px-4 py-3 btn-primary">Inapoi in magazin</a></p>
          </div>
        </div>
      </div>
    </div>
</div>
    <?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>    