<?php

namespace models;

use models\ObiectDb;

/**
 * Extinde ObiectDb, deci se pot uriliza toate metodele definite in clasa parinte
 * reprezinta modelul aferent tabelei stoc, unde sunt tinute informatii cu privire la stocul produselor
 * @author elena
 */
class Stoc extends ObiectDb {
    
    /**
     * 
     * @return array
     */
    public function vizualizareProduseStoc() {

        $infoStoc = array();
        $sql = "SELECT p.id as id_produs,
                   p.product_title,
                   p.product_description,
                   coalesce(s.stoc, 0) as stoc,
                   coalesce(s.um, 'buc') as unitateMasura
            FROM stoc s RIGHT JOIN produs p
                    ON s.id_produs=p.id
                           ORDER BY s.stoc asc";

        $result = $this->link->query($sql) or $ $this->excp->myHandleError(__FILE__, __LINE__, $this->link->error);

        if ($result->num_rows) {
            while ($d = $result->fetch_assoc()) {
                $infoStoc[] = $d;
            }
        }
        $result->free_result();
        return $infoStoc;
    }
   
}
