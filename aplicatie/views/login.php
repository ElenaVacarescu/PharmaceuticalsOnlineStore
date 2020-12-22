<?php
include_once BASE_DIR . '/public/start.php';

include_once(VIEWS_PATH . 'tpl/header.tpl.php');

?>

<div class="site-section">
    <div class="container">
         <p><?php echo !empty($this->data['msg']) ? implode("</br>", $this->data['msg']) : ''; ?> </p>
         <p><?php echo !empty($this->data['erori']) ? implode("</br>", $this->data['erori']) : ''; ?> </p>
            <div class="col-md-12">
                <h2 class="h3 mb-5 text-black">Formular login</h2>
            </div>
            <div class="col-md-12">

                <form action="login" method="post">

                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="telefon" class="text-black">Parola <span class="text-danger">*</span></label>
                                <input type="password" class="form-control"  name="parola" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="submit" name="buton" class="btn btn-primary btn-lg btn-block form-app" value="Login">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

    </div>
</div>
<?php include_once(VIEWS_PATH . '/tpl/footer.tpl.php'); ?>









