<?php

namespace lib;

class Html {

    /**
     * returneaza un tag html
     * @param type $tag
     * @param type $attr
     * @param type $content
     * @param type $singular
     * @return type
     */
    function scrieTag($tag, $attr = '', $content = '', $singular = false) {
        $rez = "<$tag";

        if ($attr) {
            $rez .= ' ' . $attr;
        }

        if ($content) {
            $rez .= ">\n$content\n</$tag>\n";
        } else {
            if ($singular) {
                $rez .= " />\n";
            } else {
                $rez .= ">\n</$tag>\n";
            }
        }
        return $rez;
    }

    /**
     * returneaza tagurile de inceput a unei paginii html
     * @param type $lang
     * @param type $title
     * @param type $head_content
     * @return string
     */
    function startHtml($lang = 'ro', $title = 'Titlul paginii', $head_content = '') {
        $html = '<!DOCTYPE html>

        <html lang="' . $lang . '">

        <head>
        <meta charset="utf-8" />
        <title>' . $title . '</title>' . "\n" .
                $head_content
                . "\n</head>" . "\n" . '<body>' . "\n";

        return $html;
    }

    /**
     * returneaza tagurile de inchidere a unei paginii html
     * @return string
     */
    function endHtml() {

        return "\n</body>\n</html>";
    }

}
