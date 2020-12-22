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
        <h3>Datele mele</h3>

        <table class=" site-blocks-table form-app">
            <tbody>
                <tr>
                    <td>Nume</td>
                    <td class="bg-light"><?= $data['nume'] ?></td>
                </tr>
                <tr>
                    <td>Prenume</td>
                    <td class="bg-light"><?= $data['prenume'] ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td class="bg-light"><?= $data['email'] ?></td>
                </tr>
                <tr>
                    <td>Oras</td>
                    <td class="bg-light"><?= $data['oras'] ?></td>
                </tr>
                <tr>
                    <td>Tara</td>
                    <td class="bg-light"><?= $data['tara'] ?></td>
                </tr>
                <tr>
                    <td>Adresa</td>
                    <td class="bg-light"><?= $data['adresa'] ?></td>
                </tr>
                <tr>
                    <td>Telefon</td>
                    <td class="bg-light"><?= $data['telefon'] ?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
<?php include_once(VIEWS_PATH . 'tpl/footer.tpl.php') ?>



