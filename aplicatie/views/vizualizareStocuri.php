<?php
include_once BASE_DIR . '/public/start.php';


if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}

$data = ($this->data['stoc']) ? $this->data['stoc'] : array();
include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>
<div id="content">  
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
    <div id="content_area">
        <?php if (!empty($data)) { ?>
            <table class="table custom-table">
                <thead>
                <th>Id produs </th>
                <th>Denumire </th>
                <th>Descriere </th>
                <th>Stoc </th>
                <th>U.M</th>
                </thead>
                <tbody> 
                    <?php
                    foreach ($data as $stoc) {

                        $id_produs = $stoc['id_produs'];
                        $product_title = $stoc['product_title'];
                        $product_description = $stoc['product_description'];
                        $stocRamas = $stoc['stoc'];
                        $unitateMasura = $stoc['unitateMasura'];
                        ?> 
                        <tr>
                            <td><?= $id_produs ?></td>
                            <td><?= $product_title ?></td>
                            <td><?= $product_description ?></td>
                            <td><?= $stocRamas ?></td>
                            <td><?= $unitateMasura ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
             <?php } else {
            ?>
          <div class="site-blocks-cover inner-page" style="background-image: url('../public/css/img/css_img/bg_1.jpg');">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-7 mx-auto align-self-center">
                              <div class=" text-center">
                                  <p>Nu exista produse in stoc</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
        <?php } ?>
    </div></div>
<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>   