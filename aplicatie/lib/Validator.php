<?php

namespace lib;

/**
 * returneaza regexuri pentru validarea diferitelor date preluate de la utilizatori
 */
class Validator
{
    
    // validare nume oras - permitem cratima litere si spatii
   public $reg_oras = '/(?i)^[-a-z\s]+$/';

    // validare nume-prenume - permitem cratima litere si spatii
   public $reg_nume = "/(?i)^[-a-z\s\']+$/";

    // validare utilizator - permitem cratima litere cifre si spatii
   public $reg_username = '/(?i)^[-_a-z0-9]+$/';

    // validare parola - permitem cratima litere cifre  virgula punct si spatii intre 6 si 16 caractere
   public $reg_parola = '/(?i)^[-_a-z0-9,.]{6,16}$/';

    // validare email
   public $reg_email = '/(?i)^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';

    // validare data introdusa
   public $reg_data = '/^(19|20)\d\d[-\/.]([1-9]|0[1-9]|1[012])[-\/.]([1-9]|0[1-9]|[12][0-9]|3[01])$/';

    // validare nume sau cale catre folder
   public $reg_folder = '/(?i)^[-a-z\s_0-9:\/\\.]+$/';

    // validare subcategorie/categorie/nume produs
   public $reg_b = '/^(?i)[a-z\s]{2,30}$/';
    
    // validare descriere
   public $reg_desc = '/^(?i)[-a-z0-9.,\s]{1,140}$/';
   
    // validare cat client (m sau f)
   public $reg_drepturi = '/^(?i)[a-z,\s]{5,50}$/';
   
    // validare pret
   public $reg_pret = '/^(?i)\d{1,5}$/';
   
    // validare discount
   public $reg_discount = '/^(?i)\d{1,2}$/';
   
    // validare an
   public $reg_an = '/^(?i)\d{4}$/';
   
    // validare denumire poza - permitem cratima litere cifre  punct si spatii intre 5 si 30 de caractere
   public $reg_poza = '/(?i)^[-_a-z0-9.]{5,30}$/';

  //validare numar telefon
   public $reg_tel = '/^(?i)\d{9,11}$/';
    


   /**
    * 
    * @param type $tipValidare tipul validarii
    * @param type $str ce trebuie validat
    * @return boolean  preg_match returneaza 1 daca stringul are formatul regex-ului, 0 in caz contrar
    */
    function isValid($tipValidare,$deValidat)
    {
        return preg_match($tipValidare,$deValidat);

    }
    
}