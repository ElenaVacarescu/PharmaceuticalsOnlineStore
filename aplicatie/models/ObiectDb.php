<?php

namespace models;

use lib\Db;
use lib\FormBuilders;
use lib\MyException;
use lib\Validator;

class ObiectDb {

    /**
     * aici tinem corespondentele in denumirele claselor si ale tabelelor corespondente
     */
    static $corespondentaClaseDb = array(
        'models\Subcategorie' => 'subcategorie',
        'models\Categorie' => 'categorie',
        'models\Produs' => 'produs',
        'models\Comanda' => 'comanda',
        'models\Utilizator' => 'utilizator',
        'models\Cos' => 'cos',
        'models\Stoc' => 'stoc',
    );
    public $table;
    public $id;
    public $dataUpdate;
    public $dataAdd;
    public $link;
    public $vld;
    public $fbld;
    public $excp;

    function __construct() {
        // preluam numele clasei curente get_class($this)
        $this->table = self::$corespondentaClaseDb[get_class($this)];

        $db = Db::getInstance();
        $this->link = $db->getConnection();

        $this->vld = new Validator();
        $this->fbld = new FormBuilders();
        $this->excp = new MyException();
    }

    public function setDataAdaugare() {
        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $this->dataAdd = $dat->format('Y-m-d H:i:s');
    }

    public function setDataModificare() {
        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $this->dataUpdate = $dat->format('Y-m-d H:i:s');
    }

    /**
     * returneaza inregistrare din tabel in functie de id
     * @param type $id
     * @return $this
     */
    function findById($id = '') {

        $sql = 'SELECT * FROM ' . $this->table . " WHERE id=$id LIMIT 1";

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($d = $result->fetch_assoc()) {
            foreach ($this as $k => $v) {
                if (array_key_exists($k, $d))
                    $this->$k = $d[$k];
            }
        }
        $result->free_result();

        return $this;
    }

    /**
     * returneaza un array cu inregistrarile din baza de date pe id-ul respectiv,conform conditiei impuse
     * @param type $condition
     * @return type array
     */
    function findByCondition($condition = '1') {

        $rezultat = array();

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $condition;

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        while ($d = $result->fetch_assoc()) {
            $rezultat[] = $d;
        }

        if (isset($rezultat)) {
            return $rezultat;
        }

        return array();
    }

    /**
     * returneaza un array cu toate inregistrarile din tabelul corespunzator clasei curente
     * @return type array
     */
    function findAll() {


        $sql = 'SELECT * FROM ' . $this->table;

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        $switch = true;
        while ($d = $result->fetch_assoc()) {
            if ($switch) {
                $rezultat[] = array_keys($d);
                $switch = false;
            }

            $rezultat[] = $d;
        }

        if (isset($rezultat))
            return $rezultat;

        return array();
    }

    /**
     * returneaza un array asociativ cu toate inregistrarile din tabelul corespunzator clasei curente
     * @return type array
     */
    function findAllAssoc() {

        $sql = 'SELECT * FROM ' . $this->table;

        $result = $this->link->query($sql); 


        while ($d = $result->fetch_assoc()) {

            $rezultat[] = $d;
        }

        if (isset($rezultat))
            return $rezultat;

        return array();
    }

    /**
     * metoda ce returneaza toate inregistrarile de pe o coloana
     * @param type $column
     * @return type
     */
    function getColumn($column) {

        $sql = "SELECT $column FROM {$this->table}  WHERE 1";

        $result = $this->link->query($sql) or die($this->link->error);

        while ($d = $result->fetch_assoc()) {
            $rezultat[] = $d[$column];
        } //end while

        if (isset($rezultat))
            return $rezultat;

        return array();
    }

    /**
     * returneaza valoarea de pe o coloana pentru un anumit id
     * @param type $column
     * @param type $id
     * @return string
     */
    function getColumnById($column, $id) {

        $sql = "SELECT $column FROM {$this->table} ";

        if ($id)
            $sql .= "WHERE id=$id";

        $result = $this->link->query($sql) or die($this->link->error);

        $d = $result->fetch_assoc();

        if ($d)
            return $d;

        return '';
    }

}
