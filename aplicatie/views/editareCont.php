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

$data = $this->data;

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
    <div class="col-md-12">
        <h2 class="h3 mb-5 text-black">Modificare informatii personale</h2>
    </div>
    <div class="col-md-12 form-app">
        <form action="editareCont" method="post">

            <div class="p-3 p-lg-5 border">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="prenume" class="text-black">Nume <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  name="prenume" placeholder="" value="<?= $data['nume'] ?>"> 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="nume" class="text-black">Prenume <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  name="nume" placeholder="" value="<?= $data['prenume'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" placeholder="" value="<?= $data['email'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="oras" class="text-black">Oras <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  name="oras" placeholder="" value="<?= $data['oras'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="tara" class="text-black">Tara </label>
                        <input type="text" class="form-control" name="tara" value="<?= $data['tara'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="adresa_livrare" class="text-black">Adresa livrare </label>
                        <input type="text" class="form-control"  name="adresa_livrare" placeholder="" value="<?= $data['adresa'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="telefon" class="text-black">Telefon </label>
                        <input type="text" class="form-control"  name="telefon" placeholder="" value="<?= $data['telefon'] ?>">
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-lg-12">
                        <input type="submit" name="buton" class="btn btn-primary btn-lg btn-block  form-app" value="Salveaza">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php') ?> 