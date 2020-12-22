<?php

include_once BASE_DIR . '/public/start.php';

if (!empty($this->data)) {
    $rez = $this->data;
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

$drepturi = (!empty($_SESSION['privs'])) ? $_SESSION['privs'] : array();

?>
    <div class="site-section">
      <div class="container">
          <?php
          if (!empty($rez) && is_array($rez)) {
              foreach ($rez as $v) {
                  foreach ($v as $k => $val) {
                      ${$k} = $v["$k"]; //am creat variabile cu denumire similara cu denumirea coloanei din BD
                  }
                  ?>
        <div class="row">
          <div class="col-md-5 mr-auto">
            <div class="border text-center">
              <img src="../public/img/<?=$product_image?>.jpg" alt="Image" class="img-fluid p-5">
            </div>
          </div>
          <div class="col-md-6">
            <h2 class="text-black"><?=$product_title?></h2>
            <p><?=$product_description?></p>
            <?php if($product_discount > 0) {?><del><?=$product_price?></del><strong class="text-primary h4"><?=($product_price * (1 - $product_discount / 100))?> lei</strong>
                    <?php } else { echo $product_price ?> lei</p> <?php } ?>
          </br></br>
          <p><?php echo (!empty($stoc) && $stoc >0) ? 'Numarul total de produse disponibile in stoc este '.$stoc.'.': 'Ne pare rau, produsul este indisponibil momentan.' ?></p>
          <p><?php echo (empty($drepturi) || in_array('normal', $drepturi)) ? '': 'Ne pare rau, nu puteti efectua cumparaturi deoarece nu aveti alocat rolul necesar.' ?></p>
          </br></br>
          <?php if((!empty($stoc) && $stoc >0) && (!empty($drepturi) && in_array('normal', $drepturi))) { ?>
                <p><a href="<?=PUBLIC_ROOT?>CosCumparaturi/adaugaProdus?idProd=<?=$id?>" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary">Adauga in cos</a></p>
          <?php } ?>
          </div>
        </div>
          <?php
              }
          }
          ?>
      </div>
    </div>

<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>