<?php

namespace models;

use models\ObiectDb;
use models\Cos;

class Comanda extends ObiectDb {

    private $produsId;
    private $comandaId;
    private $clientId;
    private $cantitate;
    public $dataAdd;
    private $cosId;

    /**
     * adaugare comanda in baza de date
     *
     * Facem 2 inserturi
     * 1. in tabela comenzi
     * 2. in tabela detalii_comanda
     * @global type $$this->excp
     */
    public function add($clientId) {

        $this->clientId = $clientId;

        $cos = new Cos();
        $cosId = $cos->cosExists($clientId); //prea id_ul cosului necomandat pt acest client
        $cos->setCosId($cosId);
        $cos->updateComandat();

        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        $this->link->autocommit(FALSE); // nu se executa query-urile pe db la comanda $smt->$stmtUpdImg->execute()

        $commit = true;

        $stmtCmd = $this->link->stmt_init();
        $stmtDetCmd = $this->link->stmt_init();
        $stmtUpdStoc = $this->link->stmt_init();
        
        //prepared statments
        $sqlCmd = "INSERT INTO comanda (cos_id, client_id) VALUES (?,?)";

        $sqlDetCmd = "INSERT INTO detalii_comanda (produs_id,cantitate,comanda_id,date_add)
                SELECT produs_id,cantitate,?,'$now' FROM detalii_cos WHERE cos_id=?";

        $sqlUpdStoc = "UPDATE stoc s INNER JOIN detalii_cos dc ON s.id_produs = dc.produs_id
                      SET s.stoc = s.stoc - dc.cantitate
                      where dc.cos_id=?";
        
        //prepare        
        if (!$stmtCmd->prepare($sqlCmd)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtCmd->error);
        }

        if (!$stmtDetCmd->prepare($sqlDetCmd)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtDetCmd->error);
        }
        
        if (!$stmtUpdStoc->prepare($sqlUpdStoc)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtUpdStoc->error);
        }
        //bind & execute
        $stmtCmd->bind_param('ii', $cosId, $clientId);
        if (!$stmtCmd->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtCmd->error);
        }
        
        $this->comandaId = $stmtCmd->insert_id; //id_ul comenzii din 'comanda'
        $stmtDetCmd->bind_param('ii', $this->comandaId, $cosId);
        if (!$stmtDetCmd->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtDetCmd->error);
        }
        
       $stmtUpdStoc->bind_param('i', $cosId);
        if (!$stmtUpdStoc->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtUpdStoc->error);
        }

        //folosim tranzactii
        if ($commit) {
            $this->link->commit();
        } else {
            $this->link->rollback();
        }

        $stmtCmd->close();
        $stmtDetCmd->close();
        
        return $this->comandaId;
    }

    /**
     * returneaza detaliile unde comenzi in functie de id-ul comenzii
     * @param type $comandaId
     * @return type
     */
    public function getInformatiiComanda($idComanda) {

        $infoComanda = array();
        $sql = "SELECT dc.comanda_id,
                   p.product_title,
                   p.product_description,
                   dc.cantitate,
                   p.product_price*(1-p.product_discount/100)*dc.cantitate AS Pret,
                   ac.status as statusPreluare,
                   CASE
                        WHEN c.status = 0 THEN 'Nepreluata'
                        WHEN c.status = 1 THEN 'Trimisa'
                        WHEN c.status = 2 THEN 'Finalizata'
                        WHEN c.status = 3 THEN 'Anulata'
                   END as status
            FROM detalii_comanda dc INNER JOIN produs p
                        ON dc.produs_id=p.id
                                    INNER JOIN comanda c
                        ON c.comanda_id=dc.comanda_id
                                    LEFT JOIN administrare_comanda ac
                    ON c.comanda_id=ac.comanda_id
            WHERE dc.comanda_id = $idComanda
            GROUP BY dc.produs_id";

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {
            while ($d = $result->fetch_assoc()) {
                $infoComanda[] = $d;
            }
        }
        $result->free_result();
        return $infoComanda;
    }

    /**
     * returneaza un array cu informatii despre comanda si utilizatorul care a comandat 
     * @return type
     */
    public function vizualizareComenzi() {

        $infoComenzi = array();
        $sql = "SELECT c.comanda_id,
                   u.nume,
                   u.prenume,
                   u.email,
                   u.telefon,
                   u.oras,
                   u.tara,
                   u.adresa_livrare,
                   dc.date_add,
                   ac.status as statusPreluare, 
                   CASE
                        WHEN c.status = 0 THEN 'Nepreluata'
                        WHEN c.status = 1 THEN 'Trimisa'
                        WHEN c.status = 2 THEN 'Finalizata'
                        WHEN c.status = 3 THEN 'Anulata'
                    END as status
            FROM comanda c INNER JOIN utilizator u
                    ON c.client_id=u.uid
                           INNER JOIN  detalii_comanda dc 
                    ON c.comanda_id=dc.comanda_id
                           LEFT OUTER JOIN administrare_comanda as ac
                    ON c.comanda_id=ac.comanda_id      
            GROUP BY c.comanda_id ORDER BY c.comanda_id asc";

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {
            while ($d = $result->fetch_assoc()) {
                $infoComenzi[] = $d;
            }
        }
        $result->free_result();
        return $infoComenzi;
    }
    
        public function vizualizareComenziPersonale($uid) {

        $infoComenzi = array();
        $sql = "SELECT c.comanda_id,
                   u.nume,
                   u.prenume,
                   u.email,
                   u.telefon,
                   u.oras,
                   u.tara,
                   u.adresa_livrare,
                   dc.date_add,
                   CASE
                        WHEN c.status = 0 THEN 'Nepreluata'
                        WHEN c.status = 1 THEN 'Trimisa'
                        WHEN c.status = 2 THEN 'Finalizata'
                        WHEN c.status = 3 THEN 'Anulata'
                    END as status
            FROM comanda c INNER JOIN utilizator u
                    ON c.client_id=u.uid
                           INNER JOIN  detalii_comanda dc 
                    ON c.comanda_id=dc.comanda_id
                     WHERE u.uid=".$uid."
            GROUP BY c.comanda_id";

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {
            while ($d = $result->fetch_assoc()) {
                $infoComenzi[] = $d;
            }
        }
        $result->free_result();
        return $infoComenzi;
    }
    
    public function updateStatusComanda($comanda_id, $status) {
        
        $stmt = $this->link->stmt_init();
        $sql = "UPDATE comanda SET status=$status WHERE comanda_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $comanda_id);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->close();
    }
    
    public function updateAccesareInactiva($idUser) {
        $statusInactiv =0;
        
        $stmt = $this->link->stmt_init();
        $sql = "UPDATE administrare_comanda SET status=$statusInactiv WHERE user_id = ?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $idUser);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->close();  
        
    }
    public function updateStatusComandaAccesata($comandaId, $idUser) {
        
        $statusActiv =1;
        $statusInactiv =0;
        $idCom = (int)$comandaId;
        $uid = (int)$idUser;
        
        $this->link->autocommit(FALSE); // nu se executa query-urile pe db la comanda $smt->$stmtUpdImg->execute()
        $commit = true;
        
        $stmt1 = $this->link->stmt_init();
        $stmt2 = $this->link->stmt_init();
        $sql1 = "UPDATE administrare_comanda SET status=$statusInactiv WHERE user_id = ?";
        $sql2 = "UPDATE administrare_comanda SET status=$statusActiv, user_id=? WHERE comanda_id=? and status = 0 ";
        
      //prepare        
        if (!$stmt1->prepare($sql1)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        if (!$stmt2->prepare($sql2)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        //bind & execute
        $stmt1->bind_param('i', $uid);
        if (!$stmt1->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        $stmt2->bind_param('ii', $uid, $idCom);
        if (!$stmt2->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        if ($commit) {
            $this->link->commit();
        } else {
            $this->link->rollback();
        }

        $stmt1->close();
        $stmt2->close();
    }
    
    public function insertStatusComandaAccesata($comandaId, $idUser) {
        $stmt = $this->link->stmt_init();
        $status = 1;
        $statusInactiv = 0;
        $idCom = (int)$comandaId;
        $uid = (int)$idUser;
        
        $this->link->autocommit(FALSE); // nu se executa query-urile pe db la comanda $smt->$stmtUpdImg->execute()
        $commit = true;
        
        $stmt1 = $this->link->stmt_init();
        $stmt2 = $this->link->stmt_init();
        
        $sql1 = "UPDATE administrare_comanda SET status=$statusInactiv WHERE user_id = ?";
        $sql2 = "INSERT INTO administrare_comanda (user_id, comanda_id, status) VALUES (?,?,?)";
        
       //prepare        
        if (!$stmt1->prepare($sql1)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        if (!$stmt2->prepare($sql2)) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        //bind & execute
        $stmt1->bind_param('i', $uid);
        if (!$stmt1->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt1->error);
        }

        $stmt2->bind_param('iii', $uid, $idCom, $status);
        if (!$stmt2->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt2->error);
        }

        if ($commit) {
            $this->link->commit();
        } else {
            $this->link->rollback();
        }

        $stmt1->close();
        $stmt2->close();

    }
    
    public function comandaAccesataExista($comandaId) {

        $id = 0;
        var_dump(comanda_id);
        $stmt = $this->link->stmt_init();

        $sql = "SELECT id FROM administrare_comanda WHERE comanda_id=?";

        if (!$stmt->prepare($sql)) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $comandaId);

        if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($id);

        $stmt->fetch();

        return $id;

        $stmt->free_result();

        $stmt->close();
    }

}
