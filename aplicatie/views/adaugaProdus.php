<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

use models\Subcategorie;
use models\Categorie;

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
        <p><?php echo!empty($this->data['msg']) ? implode("</br>", $this->data['msg']) : ''; ?> </p>
        <p><?php echo!empty($this->data['erori']) ? implode("</br>", $this->data['erori']) : ''; ?> </p>
        <div class="col-md-12">
            <h2 class="h3 mb-5 text-black">Adauga produs</h2>
        </div>
        <div class="col-md-12 form-app">
            <form action="adaugaProdus" method="post" enctype="multipart/form-data">

                <div class="p-3 p-lg-5 border">
                    <?php
                    foreach ($fbld->produseBuilder as $val) {
                        if ('text' == $val['type']) {
                            ?>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  name="<?= $val['name'] ?>" placeholder="""> 
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                        if ('choice' == $val['type'] AND 'id_subcateg' == $val['name']) {
                            $subcateg = new Subcategorie();
                            $b = $subcateg->getColumn('id');
                            $b1 = $subcateg->getColumn('nume');
                            $val['init_data'] = array_combine($b, $b1);
                            ?> 
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <?php echo $form->selectFormTag($val['name'], $val['multiple'], $val['expanded'], $val['init_data'], $val['data']); ?>
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                        if ('choice' == $val['type'] and 'id_cat' == $val['name']) {
                            $cat = new Categorie();
                            $c = $cat->getColumn('id');
                            $c1 = $cat->getColumn('cat_denumire');
                            $val['init_data'] = array_combine($c, $c1);
                            ?> 
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <?php echo $form->selectFormTag($val['name'], $val['multiple'], $val['expanded'], $val['init_data'], $val['data']); ?>
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                        if ('choice' == $val['type']) {
                            ?> 
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <?php echo $form->selectFormTag($val['name'], $val['multiple'], $val['expanded'], $val['init_data'], $val['data']); ?>
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                        if ('file' == $val['type']) {
                            ?> 
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <?php echo $form->fisierInputTag($val['name'], '', 'accept="image"'); ?>
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                        if ('textarea' == $val['type']) {
                            ?> 
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <input type="textarea" class="form-control"  name="<?= $val['name'] ?>" placeholder=""> 
                                </div>
                            </div>
                            <?php
                            continue;
                        }
                    }
                    ?>
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
