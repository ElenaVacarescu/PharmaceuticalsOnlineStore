<?php

namespace models;

use models\ObiectDb;

class Cos extends ObiectDb {

    private $cantitate = 1;
    private $produsId;
    private $clientId;
    private $cosId;
    private $comandat;

    
    public function getCosId() {
        return $this->cosId;
    }
    
    public function setCosId($cosId) {
        $this->cosId = $cosId ;
    }
    /**
     * Adauga obiectul curent in tabelele cos si detalii_cos daca nu exista deja cos pe acel client
     *
     */
    public function add($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;

        // data curenta
        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        // initializam 2 'prepare statements'-adaugare in cos si adaugare in cos_cumaparturi
        $stmt1 = $this->link->stmt_init();
        $stmt2 = $this->link->stmt_init();

        $sql1 = "INSERT INTO cos (client_id) VALUES (?)";

        $sql2 = "INSERT INTO detalii_cos (produs_id,cos_id, cantitate,date_add) VALUES (?,?,?,?)";


        if (!$stmt1->prepare($sql1)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        if (!$stmt2->prepare($sql2)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        $stmt1->bind_param('i', $clientId);

        if (!$stmt1->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        $idCos = $stmt1->insert_id;
        $cantitate = 1;

        $stmt2->bind_param('iiis', $produsId, $idCos, $cantitate, $now);


        if (!$stmt2->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        $stmt1->close();

        $stmt2->close();
    }

    /**
     * folosim functia cand adaugam produse doar in detalii_cos pe un cos care exista deja
     */
    public function adaugaProdus($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;
        $cantitate = 1;

        $cosId = $this->cosExists($clientId);
        // data curenta
        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        $stmt = $this->link->stmt_init();

        $sql = "INSERT INTO detalii_cos (produs_id,cos_id,cantitate,date_add,date_upd) VALUES (?,?,?,?,?)";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('iiiss', $produsId, $cosId, $cantitate, $now, $now);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
    }

    /**
     * returneaza cantitatea pentru un produs, daca exista produsul intr-un cos necomandat apartinand unui anumit client
     * daca imi afiseaza un rezultat, updatam/stergem cantitatea pe acel produs
     */
    public function selecteazaCantitate($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;

        $stmt = $this->link->stmt_init();

        $sql = "SELECT cantitate FROM detalii_cos cc INNER JOIN cos c
                ON cc.cos_id=c.cos_id
                WHERE c.comandat=0 AND cc.produs_id=? AND c.client_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('ii', $produsId, $clientId);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->store_result();

        $stmt->bind_result($cantitate);

        $stmt->fetch();

        return $cantitate;

        $stmt->free_result();

        $stmt->close();
    }

    /**
     * verifica daca exista produsul adaugat in detalii_cos pentru un cos, netrimis spre comanda
     * daca exista updatam cantitatea, daca nu exista adaugam produsul
     * @param type $produsId
     * @param type $clientId
     * @return type
     */
    public function selecteazaProdus($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;

        $stmt = $this->link->stmt_init();

        $sql = "SELECT produs_id FROM detalii_cos cc INNER JOIN cos c
                ON cc.cos_id=c.cos_id
                WHERE c.comandat=0 AND cc.produs_id=? AND c.client_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('ii', $produsId, $clientId);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->store_result();

        $stmt->bind_result($produsId);

        $stmt->fetch();

        return $produsId;

        $stmt->free_result();

        $stmt->close();
    }

    /**
     * updateaza cantitatea pentru produsele apartinand unui cos al unui utilizator 
     *
     */
    public function updateCantitate($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;

        $idCos = $this->cosExists($clientId);

        $stmt = $this->link->stmt_init();
        $sql = "UPDATE detalii_cos dc left join stoc s 
                ON dc.produs_id = s.id_produs
                SET dc.cantitate=dc.cantitate+1 
                WHERE 
                s.stoc is not null 
                AND
                s.stoc > dc.cantitate
                AND
                dc.produs_id=? 
                AND
                dc.cos_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('ii', $produsId, $idCos);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->close();
    }

    /**
     * stergem cantitatea pentru obiectul curent bazei de date

     */
    public function stergeCantitate($produsId, $clientId) {

        $this->produsId = $produsId;
        $this->clientId = $clientId;

        $idCos = $this->cosExists($clientId);

        $stmt = $this->link->stmt_init();

        $sql = "UPDATE detalii_cos SET cantitate=cantitate-1 WHERE produs_id=? AND cos_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('ii', $produsId, $idCos);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->close();
    }

    /**
     * daca cantitatea este 0, stergem produsul din cos cumparaturi 
     * @param type $produsId
     * @param type $clientId
     */
    public function stergeProdus($produsId, $clientId) {


        $this->produsId = $produsId;
        $this->clientId = $clientId;

        $idCos = $this->cosExists($clientId);

        $stmt = $this->link->stmt_init();

        $sql = "DELETE FROM detalii_cos WHERE produs_id=? AND cos_id=?";


        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('ii', $produsId, $idCos);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->close();
    }

    /**
     * returneaza detaliile produselor din cos
     * @param type $uid
     * @return type
     */
    public function afiseazaDetaliiCos($uid) {

        $detaliiCos = array();

        $sql = "SELECT p.id,
                   p.product_title,
                   p.product_description,
                   cc.cantitate,
                   p.product_price*(1-p.product_discount/100)*cc.cantitate AS Pret
            FROM detalii_cos cc INNER JOIN produs p
                        ON cc.produs_id=p.id
                                INNER JOIN cos c 
                        ON cc.cos_id=c.cos_id
            WHERE c.client_id=$uid AND c.comandat=0
            GROUP BY cc.produs_id";

        $result = $this->link->query($sql) or $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {

            while ($d = $result->fetch_assoc()) {
                $detaliiCos[] = $d;
            }
        }
        $result->free_result();
        return $detaliiCos;
    }

    /**
     * verifica daca exista cos de cumparaturi pentru un anumit id de client
     * @param type $clientId
     * @return type
     */
    public function cosExists($clientId) {


        $this->clientId = $clientId;

        $stmt = $this->link->stmt_init();

        $sql = "SELECT cos_id FROM cos WHERE comandat=0 AND client_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $clientId);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($cosId);

        $stmt->fetch();

        return $cosId;

        $stmt->free_result();

        $stmt->close();
    }

    /**
     * returneaza un array care contine produsul si cantitatea din cos
     * @param type $id
     * @return type
     */
    public function getDetaliiCos($id) {


        $detalii_cos = array();

        // initializeaza un 'prepared statement'
        $stmt = $this->link->stmt_init();

        $sql = "SELECT produs_id, cantitate FROM detalii_cos
                WHERE cos_id=?";

        // preparam query-ul
        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        // aici se face legatura intre ? din $sql si variabilele noastre
        $stmt->bind_param('i', $id);


        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_result($produs_id, $cantitate);


        while ($stmt->fetch()) {
            $detalii_cos[] = ['produs_id' => $produs_id, 'cantitate' => $cantitate];
        }
        $stmt->close();
        return $detalii_cos;
    }

    /**
     * modifica coloana 'comandat' din 0 in 1 (din necomandat in comandat)
     * 
     */
    public function updateComandat() {
        $stmt = $this->link->stmt_init();
        $sql = "UPDATE cos SET comandat='1' WHERE cos_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $this->cosId);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->close();
    }

}
