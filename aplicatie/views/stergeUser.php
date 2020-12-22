<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

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
                <li><a href="<?= PUBLIC_ROOT ?>Cont\vizualizareComenzi" >Vizualizeaza comenzi</a></li>
            <?php } ?>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\logout" >Logout</a></li>
        </ul>
    </div>
    <div id="content_area">
        <p><?php echo !empty($this->data['msg']) ? implode("</br>",$this->data['msg']) : ''; ?> </p>
        <p><?php echo !empty($this->data['erori']) ? implode("</br>", $this->data['erori']) : ''; ?> </p>
        <div class="col-md-12">
            <h2 class="h3 mb-5 text-black">Sterge user</h2>
        </div>
        <div class="col-md-12 form-app">
            <form action="stergeUser" method="post">

                <div class="p-3 p-lg-5 border">              
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="prenume" class="text-black">Introduceti adresa de email a contului pe care doriti sa-l inchideti<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="email" placeholder="" > 
                        </div>
                    </div>
                    <div class="form-group row ">
                        <div class="col-lg-12">
                            <input type="submit" name="buton" class="btn btn-primary btn-lg btn-block form-app" value="Salveaza">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php') ?> 