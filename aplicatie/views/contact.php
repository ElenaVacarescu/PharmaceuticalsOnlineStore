<?php
include_once BASE_DIR . '/public/start.php';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

?>

<div class="site-section">
    <div class="container">
        <?php
        if (!empty($this->data['erori'])) {
            echo implode('</br>', $this->data['erori']);
        }
        elseif (!empty($this->data['msg'])) {
            echo $this->data['msg'];
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <h2 class="h3 mb-5 text-black">Formular contact</h2>
            </div>
            <div class="col-md-12">

                <form action="trimiteEmail" method="post">

                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="nume" class="text-black">Nume <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  name="nume" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="telefon" class="text-black">Telefon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  name="telefon" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="subiect" class="text-black">Subiect </label>
                                <input type="text" class="form-control" name="subiect">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="message" class="text-black">Mesaj </label>
                                <textarea name="message" cols="30" rows="7" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="submit" name="buton" class="btn btn-primary btn-lg btn-block form-app" value="Trimite mesaj">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>
