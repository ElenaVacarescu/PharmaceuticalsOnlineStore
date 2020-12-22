<?php

namespace models;

use models\ObiectDb;

class Utilizator extends ObiectDb {

    private $uid;
    private $nume;
    private $prenume;
    private $email;
    private $oras;
    private $tara;
    private $adresa_livrare;
    private $telefon;
    private $drepturi = 'normal';

    /**
     * Adauga un utilizator nou in tabela Users, din perspectiva administratorului contului, cel care poate seta si drepturile
     * 
     */
    public function addUser($data) {

        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        $stmt = $this->link->stmt_init();

        $sql = "INSERT INTO utilizator (nume, prenume, email, parola, oras, tara, adresa_livrare, telefon, drepturi, date_add, date_upd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $parolaHash = password_hash($data['parola'], PASSWORD_BCRYPT);
        $drepturi = implode(',', $data['drepturi']);

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('sssssssssss', $data['nume'], $data['prenume'], $data['email'], $parolaHash, $data['oras'], $data['tara'], $data['adresa_livrare'], $data['telefon'], $drepturi, $now, $now);


        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->close();
    }

    /**
     * Se apeleaza cand un utilizator al site-ului care isi creaza un cont
     * @param type $data
     */
    public function addCont($data) {

        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        $stmt = $this->link->stmt_init();

        $sql = "INSERT INTO utilizator (nume, prenume, email, parola, oras, tara, adresa_livrare, telefon, drepturi, date_add, date_upd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $parolaHash = password_hash($data['parola'], PASSWORD_BCRYPT);
        $drepturi = 'normal';     //clientii au drept 'normal' by default

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('sssssssssss', $data['nume'], $data['prenume'], $data['email'], $parolaHash, $data['oras'], $data['tara'], $data['adresa_livrare'], $data['telefon'], $drepturi, $now, $now);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->close();
    }

    /**
     * Modifica detaliile utilizatorului din baza  de date
     * 
     */
    public function update($id, $data) {

        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        // initializeaza un 'prepared statement'
        $stmt = $this->link->stmt_init();
        $sql = "UPDATE utilizator SET nume=?, prenume=?,email=?,oras=?, tara=?, adresa_livrare=?, telefon=?, date_upd=? WHERE uid=$id";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $this->setDataModificare();

        $stmt->bind_param('ssssssss', $data['nume'], $data['prenume'], $data['email'], $data['oras'], $data['tara'], $data['adresa_livrare'], $data['telefon'], $now);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->close();
    }

    /**
     * afiseaza informatiile stocate in baza de date despre utilizator
     * @param type $uid
     * @return type
     */
    public function afiseazaInformatiiCont($uid) {

        $stmt = $this->link->stmt_init();

        $sql = "SELECT nume, prenume,email, oras, tara, adresa_livrare, telefon  FROM utilizator WHERE uid=?";

        if (!$stmt->prepare($sql)) {
            $excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $uid);

        if (!$stmt->execute()) {
            $excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($db_nume, $db_prenume, $db_email, $db_oras, $db_tara, $db_adresa, $db_telefon);

        $stmt->fetch();

        $data = array('nume' => $db_nume, 'prenume' => $db_prenume, 'email' => $db_email, 'oras' => $db_oras, 'tara' => $db_tara, 'adresa' => $db_adresa, 'telefon' => $db_telefon);

        $stmt->free_result();

        return $data;
    }

    /**
     * se verifica daca parola este valida la logare
     * @param type $email
     * @param type $parola
     * @return boolean
     */
    public function isValidParolaLogin($email, $parola) {

        $stmt = $this->link->stmt_init();

        $sql = "SELECT uid, nume, prenume, parola, drepturi  FROM utilizator WHERE email=? ";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($uid, $db_nume, $db_prenume, $db_parola, $db_drepturi);

        $stmt->fetch();

        // $parola este parola introdusa in formular, iar $db_parola este hash-ul  acestei parole in baza de date
        if (password_verify($parola, $db_parola)) {
            // regeneram id-ul de sesiune pentru evitarea session fixation
            \lib\Sesiune::my_session_start(1);
            $_SESSION['uid'] = $uid;
            $_SESSION['nume'] = $db_nume;
            $_SESSION['prenume'] = $db_prenume;
            $_SESSION['email'] = $email;
            $_SESSION['privs'] = explode(',', $db_drepturi);

            $stmt->close();

            return true;
        }
        return false;
    }
    
/**
 * 
 * @param type $uid
 * @param type $parola
 * @return boolean
 */
    public function isValidPassword($uid, $parola) {

        $stmt = $this->link->stmt_init();

        $sql = "SELECT uid, parola FROM utilizator WHERE uid=? ";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $uid);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($uid, $db_parola);

        $stmt->fetch();

        // $parola este parola introdusa in formular, iar $db_parola este hash-ul  acestei parole in baza de date
        if (password_verify($parola, $db_parola)) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    /**
     * sterge un user din perspectiva administratorului de cont, in functie de email
     * @param type $email
     */
    public function stergeUser($email) {

        $stmt = $this->link->stmt_init();

        // extragem emailul din db care trebuie sa fie unic; se valideaza la adaugarea unui nou cont acest lucru
        $sql = "DELETE FROM utilizator WHERE email=? ";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->close();
    }
    
    public function userExists($email){
        
        $stmt = $this->link->stmt_init();

        // extragem emailul din db care trebuie sa fie unic; se valideaza la adaugarea unui nou cont acest lucru
        $sql = "SELECT uid FROM utilizator WHERE email=? ";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('s', $email);

       if (!$stmt->execute()) {
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($uid);

        $stmt->fetch();

        return $uid;

        $stmt->free_result();

        $stmt->close();
    }

    /**
     * sterge un cont al utilizatorului cand acesta solicita acest lucru 
     * @param type $uid
     */
    public function stergeCont($uid) {

        $stmt = $this->link->stmt_init();

        $sql = "DELETE FROM utilizator WHERE uid=?";

        if (!$stmt->prepare($sql)) {
            $excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->bind_param('i', $uid);

        if (!$stmt->execute()) {
            $excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->close();
    }

    /**
     * modificare parola utilizaror
     * @param type $uid
     * @param type $parola
     * @param type $parolaUpd
     */
    public function updateParola($uid, $parola, $parolaUpd) {

        $stmt = $this->link->stmt_init();

        $sql = "SELECT parola  FROM utilizator WHERE uid=?";

        if (!$stmt->prepare($sql)) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }

        $stmt->bind_param('i', $uid);

        if (!$stmt->execute()) {
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $stmt->store_result();

        $stmt->bind_result($dbParola);

        $stmt->fetch();

        $stmt->free_result();

        if (password_verify($parola, $dbParola)) {

            $sql = "UPDATE utilizator SET parola=? where uid= $uid";
            if (!$stmt->prepare($sql)) {
                $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
            }
            $parolaHash = password_hash($parolaUpd, PASSWORD_DEFAULT);
            $stmt->bind_param('s', $parolaHash);
            if (!$stmt->execute()) {
                $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
            }
        }
    }

}
