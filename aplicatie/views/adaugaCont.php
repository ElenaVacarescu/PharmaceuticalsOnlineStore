<?php

include_once BASE_DIR . '/public/start.php';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

?>

<div class="site-section">
    <div class="container">
         <p><?php echo!empty($this->data['msg']) ? implode("</br>", $this->data['msg']) : ''; ?> </p>
         <p><?php echo!empty($this->data['erori']) ? implode("</br>", $this->data['erori']) : ''; ?> </p>
        <div class="col-md-12">
             <h2 class="h3 mb-5 text-black">Introduceti datele dumneavoastra</h2>
        </div>
        <div class="col-md-12 form-app">
            <form action="salvareCont" method="post">

                <div class="p-3 p-lg-5 border">
                    <?php
                    foreach ($fbld->userBuilder as $val) {
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
                        if ('password' == $val['type']) {
                            ?>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="<?= $val['name'] ?>" class="text-black"><?= $val['label'] ?><span class="text-danger">*</span></label>
                                    <input type="password" class="form-control"  name="<?= $val['name'] ?>" placeholder="" >
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
<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>


