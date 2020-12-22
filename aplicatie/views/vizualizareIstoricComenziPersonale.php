<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}
else {
    $uid = $_SESSION['uid'];
}

$pret_total = 0;

$data = ($this->data['comenzi']) ? $this->data['comenzi'] : array();
$dataComanda = isset($this->data['infoComanda']) ? $this->data['infoComanda'] : array();

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>

<div id="content">  
    <div id="sidebar">
        <div id="sidebar_title">Operatiuni</div>
          <ul id="cats">
                <li><a href="<?= PUBLIC_ROOT?>Cont\infoCont" >Datele mele</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\editareCont" >Editare cont</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\schimbaParola" >Schimbare parola</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Comenzi/getIstoricComenzi?idUser=<?=$uid ?>" >Istoric comenzi</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\stergeCont" >Stergere cont</a></li>
                <li><a href="<?= PUBLIC_ROOT?>Cont\logout">Logout</a></li>
          </ul>
    </div>
    <div id="content_area">
        <?php if (!empty($data)) { ?>
            <table class="table custom-table">
                <thead>
                <th>Id comanda </th>
                <th>Nume </th>
                <th>Prenume </th>
                <th>Email </th>
                <th>Telefon </th>
                <th>Oras </th>
                <th>Tara </th>
                <th>Adresa livrare </th>
                <th>Data comanda </th>
                <th>Status </th>
                </thead>
                <tbody> 
                    <?php
                    foreach ($data as $comanda) {

                        $comandaId = $comanda['comanda_id'];
                        $nume_client = $comanda['nume'];
                        $prenume_client = $comanda['prenume'];
                        $email_client = $comanda['email'];
                        $telefon_client = $comanda['telefon'];
                        $oras_client = $comanda['oras'];
                        $tara_client = $comanda['tara'];
                        $adresa_client = $comanda['adresa_livrare'];
                        $data_comanda = $comanda['date_add'];
                        $status = $comanda['status'];
                        ?> 
                        <tr>
                            <td><a href=<?= PUBLIC_ROOT ?>Comenzi/vizualizareComanda?idUser=<?=$uid?>&comandaId=<?= $comandaId ?> > <?= $comandaId ?> </td>
                            <td><?= $nume_client ?></td>
                            <td><?= $prenume_client ?></td>
                            <td><?= $email_client ?></td>
                            <td><?= $telefon_client ?></td>
                            <td><?= $oras_client ?></td>
                            <td><?= $tara_client ?></td>
                            <td><?= $adresa_client ?></td>
                            <td><?= $data_comanda ?></td>
                            <td><?= $status ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if (!empty($dataComanda)) { ?>
                <p>Comanda nr <?= $dataComanda[0]['comanda_id'] ?> </p>
                <table class="table custom-table">
                    <thead>
                    <th>Denumire produs </th>
                    <th>Descriere </th>
                    <th>Cantitate </th>
                    <th>Pret(lei) </th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataComanda as $comanda) {
                            $denumire = $comanda['product_title'];
                            $desc = $comanda['product_description'];
                            $cantitate = $comanda['cantitate'];
                            $pret = (int) $comanda['Pret'];
                            $pret_total += $pret;
                            ?> 
                            <tr>
                                <td><?= $denumire ?></td>
                                <td><?= $desc ?></td>
                                <td><?= $cantitate ?></td>
                                <td><?= $pret ?></td>
                            </tr>
                        <?php } ?>    
                        <tr>
                            <td colspan='3' >Pret total</td>
                            <td ><?= $pret_total ?></td>
                        </tr> 
                    </tbody>
                </table>
                <?php if($dataComanda[0]['status'] == 'Nepreluata') {?>
                <a href=<?= PUBLIC_ROOT ?>Comenzi/updateStatusComanda?status=3&idUser=<?=$uid?>&comandaId=<?= $dataComanda[0]['comanda_id'] ?> class="btn btn-md height-auto px-4 py-3 btn-primary">Anuleaza comanda</a>
                </p>
                <?php } 
                } ?>
           <?php } else {
            ?>
          <div class="site-blocks-cover inner-page" style="background-image: url('../public/css/img/css_img/bg_1.jpg');">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-7 mx-auto align-self-center">
                              <div class=" text-center">
                                  <p>Nu exista comenzi.</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
        <?php } ?>
    </div></div>
<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php'); ?>    

