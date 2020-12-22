<?php
include_once BASE_DIR . '/public/start.php';

if (!$u->areDrepturiAcces(basename($_SERVER['REQUEST_URI']))) {
    \lib\Aplicatie::redirect('Home\noPrivilege');
    exit;
}

if (!$u->esteLogat()) {
    \lib\Aplicatie::redirect('Login\index');
}
else {
    $uid = $_SESSION['uid'];
}

include_once(VIEWS_PATH . 'tpl/header.tpl.php');
?>                    

<div id="content">  
    <div id="sidebar">
     <div id="sidebar_title">Operatiuni</div>
        <ul id="cats">	
            <li><a href="<?= PUBLIC_ROOT ?>Cont\infoCont" >Datele mele</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\editareCont" >Editare cont</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\schimbaParola" >Schimbare parola</a></li>
            <li><a href="<?= PUBLIC_ROOT?>Comenzi/getIstoricComenzi?idUser=<?=$uid ?>" >Istoric comenzi</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\stergeCont" >Stergere cont</a></li>
            <li><a href="<?= PUBLIC_ROOT ?>Cont\logout">Logout</a></li>
        </ul>
    </div>
    <div id="content_area">
        <p><?php echo !empty($this->data['msg']) ? $this->data['msg'] : ''; ?> </p>
        <p><?php echo !empty($this->data['erori']) ? implode("</br>", $this->data['erori']) : ''; ?> </p>
    <div class="col-md-12">
        <h2 class="h3 mb-5 text-black">Schimba parola</h2>
    </div>
    <div class="col-md-12 form-app">
        <form action="schimbaParola" method="post">

            <div class="p-3 p-lg-5 border">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="parola" class="text-black">Parola actuala <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"  name="parola" placeholder=""> 
                    </div>
                </div>
                <div class="form-group row">
                       <div class="col-md-12">
                        <label for="parola_noua1" class="text-black">Noua parola <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"  name="parola_noua1" placeholder=""> 
                    </div>
                </div>
                <div class="form-group row">
                     <div class="col-md-12">
                        <label for="parola_noua2" class="text-black">Confirma noua parola <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"  name="parola_noua2" placeholder=""> 
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-lg-12">
                        <input type="submit" name="schimbare_parola" class="btn btn-primary btn-lg btn-block form-app" value="Salveaza">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php') ?> 