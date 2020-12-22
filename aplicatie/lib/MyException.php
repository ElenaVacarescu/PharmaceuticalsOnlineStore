<?php

namespace lib;

/**
 * se ocupa de logarea erorilor de tip exceptie; daca suntem pe mediu de dezvoltare le afiseaza pe ecran, daca suntem pe mediu de productie le logheaza in fisier.
 */
class MyException extends \Exception {

    function myHandleError($fisier, $linie, $mesaj) {
        $time = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));

        $eroare = $time->format('Y-m-d H:i:s') . "\t" . 'Mesaj: ' . $mesaj . "\t" . 'File: ' . $fisier . "\t" . 'Line: ' . $linie . "\t\n";

        if (MEDIU_RULARE_APP == 'dev') {
            die($eroare);
        } else {
            error_log($eroare, 3, MY_ERROR_FILE);
            die('Eroare.');
        }
    }

}
