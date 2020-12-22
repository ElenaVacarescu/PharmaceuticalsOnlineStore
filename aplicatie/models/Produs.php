<?php

namespace models;

use models\ObiectDb;

class Produs extends ObiectDb {

    private $idSubcateg;
    private $idCateg;
    private $denumireProdus;
    private $descriereProdus;
    private $categorieProdus;
    private $pretProdus;
    private $discountProdus;
    private $imagineProdus;
    private static $tipSortare;

    /**
     * adauga un produs in baza de date
     */
    public function add($data) {

        $dat = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
        $now = $dat->format('Y-m-d H:i:s');

        $this->link->autocommit(FALSE); // nu se executa query-urile pe db la comanda $smt->$stmtUpdImg->execute()
        $commit = true;
        
        $stmt = $this->link->stmt_init();
        $stmt1 = $this->link->stmt_init();
        
        $sql = "INSERT INTO produs (id_subcateg,product_title,product_description,category_p,product_price,product_discount,product_image,date_add) VALUES (?,?,?,?,?,?,?,?)";
        $sql1 = "INSERT INTO stoc (id_produs,um,stoc,date_add) VALUES (?,?,?,?)";

        if (!$stmt->prepare($sql)) {
            $commit = false;
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        
        if (!$stmt1->prepare($sql1)) {
            $commit = false;
            $$this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        
        $categoriiClienti = implode(',',$data['category_p']);
        $denumireProdus = explode('.',$data['product_image']['name']);
     
        $stmt->bind_param('isssddss', $data['id_subcateg'], $data['product_title'], $data['product_description'], $categoriiClienti, $data['product_price'], $data['product_discount'], $denumireProdus[0], $now);
        
        if (!$stmt->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmt->error);
        }
        $prodId = $stmt->insert_id; //id_ul produsului din 'produs'
        $um = 'buc';
        $stocZero = 0;
        $stmt1->bind_param('isis', $prodId, $um, $stocZero, $now);
        if (!$stmt1->execute()) {
            $commit = false;
            $this->excp->myHandleError(__FILE__, __LINE__, $stmtDetCmd->error);
        }
        //folosim tranzactii
        if ($commit) {
            $this->link->commit();
        } else {
            $this->link->rollback();
        }
        $stmt->close();
        $stmt1->close();
    }

    /**
     * mapeaza informatiile din formular cu obiectul aferent
     * @param type $data
     */
    public function mapFieldsFormToObj($data) {

        foreach ($this->fbld->produseBuilder as $k => $camp) {
            if ('choice' == $camp['type']) {
                if (property_exists($this, $k)) {
                    $this->$k = $data[$camp['name']];
                }
            } elseif ('file' == $camp['type']) {
                if (property_exists($this, $k)) {
                    $this->$k = $data[$camp['name']]['name'];
                }
            } else {
                if (property_exists($this, $k)) {
                    $this->$k = $data[$camp['name']];
                }
            }
        }
    }

    /**
     * primeste ca parametru id-ul subcategoriei si returneaza produsele din acea categorie
     */
    public function viewByIdSubcategorie($idSubcategorie) {
        $produs = array();

        $sql = "SELECT 
              b.nume,p.id,p.id_subcateg, p.product_title, p.product_description, p.product_price, p.product_discount, p.product_image
              FROM
              produs p
              INNER JOIN subcategorie b  ON b.id=p.id_subcateg
              WHERE b.id='$idSubcategorie'";

        $result = $this->link->query($sql) or $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {

            while ($d = $result->fetch_assoc()) {

                $produs[] = $d;
            }
        }
        $result->free_result();
        return $produs;
    }

    /**
     * primeste ca parametru id-ul categoriei si returneaza produsele din acea categorie
     */
    public function viewByCategory($id_cat) {
        $produs = array();

        $sql = "SELECT
          c.cat_denumire, p.id,p.id_cat, p.product_title, p.product_description, p.product_price, p.product_discount, p.product_image
          FROM
          produs p
          INNER JOIN categorie c  ON c.id=p.id_cat
          WHERE c.id='$id_cat'";

        $result = $this->link->query($sql) or $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {

            while ($d = $result->fetch_assoc()) {

                $produs[] = $d;
            }
        }
        $result->free_result();
        return $produs;
    }

    /**
     * sortare array de produse in functie de parametrul tip sortare
     * @param type $data
     * @param type $tipSortare
     * @return array de produse cu elementele sortate
     */
    public static function sortare(&$data, $tipSortare) {
        self::$tipSortare = $tipSortare;
        usort($data, array('self', 'cmp'));
        return $data;
    }

    public static function cmp($a, $b) {

        $tipSortare = self::$tipSortare;
        $key = 'product_price';

        if ($tipSortare == "pretD") {
            return ($a["$key"] > $b["$key"]) ? '-1' : '1';
        }

        if ($tipSortare == "pretC") {
            return ($a["$key"] < $b["$key"]) ? '-1' : '1';
        }
    }

    public static function cautare(&$data, $cautare) {
        $dataTemp = array();
        if (empty($cautare)) {
            return $dataTemp;
        }

        foreach ($data as $produs) {
            if (strpos(strtolower($produs['product_title']), strtolower($cautare)) !== false || strpos(strtolower($produs['product_description']), strtolower($cautare)) !== false) {
                $dataTemp[] = $produs;
            }
        }
        return $dataTemp;
    }

    public function getInfoProdusSiStoc($idProdus) {
        $produs = array();

        $sql = "SELECT
          p.*, s.stoc
          FROM
          produs p
          LEFT JOIN stoc s ON p.id=s.id_produs
          WHERE p.id='$idProdus'";

        $result = $this->link->query($sql) or $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {

            while ($d = $result->fetch_assoc()) {

                $produs[] = $d;
            }
        }
        $result->free_result();
        return $produs;
    }

}
