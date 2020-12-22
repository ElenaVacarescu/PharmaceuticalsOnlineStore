<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

$pret_total = 0;
$data = $this->data;
?>
        <?php if (!empty($data)) { ?>
             <div class="col-md-12">
                <h2 class="h3 mb-5 text-black"></h2>
                <h2 class="h3 mb-5 text-black">Produse cos cumparaturi</h2>
                <h2 class="h3 mb-5 text-black"></h2>
            </div>
            <table class="table custom-table">
                <thead>
                <th>Denumire produs</th>
                <th>Descriere</th>
                <th>Cantitate</th>
                <th>Pret(lei)</th>
                <th>Adauga</th>
                <th>Elimina</th>
                </thead>
                <tbody> 
                    <?php
                    foreach ($data as $produs) {
                        $produs_id = $produs['id'];
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
                            <td><a href=<?= PUBLIC_ROOT ?>CosCumparaturi/adaugaCantitate?idProdus=<?= $produs_id ?> ><button type='button'> + </button></a></td>
                            <td><a href=<?= PUBLIC_ROOT ?>CosCumparaturi/stergeCantitate?idProdus=<?= $produs_id ?> ><button type='button'> - </button></a></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan='5' ><b>Total de plata</b></td>
                        <td ><b><?= $pret_total ?> lei</b></td>
                    </tr> 
                </tbody>
            </table>
          <form action="adaugaComanda" method="post">
            <div class="form-group row ">
                <div class="col-lg-12">
                    <input type="submit" name="adaugaComanda" class="btn btn-primary btn-lg btn-block  form-app" value="Trimite comanda">
                </div>
            </div>
          </form>
          <p><a href="<?= PUBLIC_ROOT ?>Produse\toateProdusele" class="btn btn-md height-auto px-4 py-3 btn-primary">Inapoi in magazin</a></p>
        <?php } else {
            ?>
          <div class="site-blocks-cover inner-page" style="background-image: url('../public/css/img/css_img/bg_1.jpg');">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-7 mx-auto align-self-center">
                              <div class=" text-center">
                                  <p>Nu exista produse in cosul de cumparaturi</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
        <?php } ?>

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>   
