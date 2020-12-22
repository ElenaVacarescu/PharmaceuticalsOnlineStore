<?php

namespace lib;

class Sesiune {

    public static function my_session_start($regenereazaSesiune = 0) {

        global $infoGlobale;
        $loginTime = $infoGlobale['timpLogin'];
        $locatieSalvareSesiune = $infoGlobale['locatieFisiereSesiune'];

        if (isset($_SESSION)) {
            self::my_session_destroy();
        }
        /* folosim doar cookies a.i id-ul de sesiune nu va aparea in url 
          salvam sesiunile in afara folderului aplicatie (cu un nivel mai sus) */
        ini_set('session.use_only_cookies', 1);
        ini_set('session.gc_maxlifetime', $loginTime + 600);
        ini_set('session.save_path', $locatieSalvareSesiune);

        session_set_cookie_params($loginTime);

        session_start();

        /* regeneram id-ul sesiunii din motive de sucuritate , apelam metoda cu acest parametru la login in aplicatie */
        if ($regenereazaSesiune) {
            session_regenerate_id();
        }
        /* rescriem cookie-ul de sesiuneiar efectul va fi ca sesiunea va dura $loginTime de la ultimul click pentru ca acest cookie este trimis prin headere
          celalalt cookie tine din momentul in care se apeleaza session_start */
        setcookie(session_name(), session_id(), time() + $loginTime, '/');
    }

    public static function my_session_destroy() {

        setcookie(session_name(), FALSE, 1, "/"); // sterge cookie-ul de sesiune (1 reprezinta prima secunda din anul 1970, deci mult in trecut)
        $_SESSION = array();
        session_destroy();
    }

}
