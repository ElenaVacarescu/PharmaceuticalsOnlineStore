<?php

namespace lib;

/**
 * este o clasa care contine metode necesare pentru construirea  diverselor tag-uri in HTML, pentru construirea formularelor
 */
class Formular {

    /**
     * returneaza tag-ul de inceput al unui formular
     * @param type $method
     * @param type $action
     * @param type $params
     * @return string
     */
    function startForm($method = 'get', $action = '', $params = '') {
        $html = '
            <form method="' . $method . '" action="' . $action . '" ' . $params . '>			
            ' . "\n";

        return $html;
    }

    /**
     * returneaza tag-ul de final al unui formular
     * @return string
     */
    function endForm() {
        $html = '</form>' . "\n";
        return $html;
    }

    /**
     * O functie ce returneaza un tag label.
     * @param type $value
     * @param type $for
     * @param type $params
     * @return string
     */
    function labelTag($value, $for = '', $params = '') {
        if ($for) {
            $rez = '<label for="' . $for . '"';
        } else {
            $rez = '<label';
        }

        if ($params) {
            $rez .= ' ' . $params . '>' . $value . '</label>';
        } else {
            $rez .= '>' . $value . '</label>';
        }

        return $rez;
    }

    /**
     * returneaza un tag input de tip text, email, etc.
     * @param type $name
     * @param type $type
     * @param type $elementData
     * @param type $params
     * @return string
     */
    function inputTag($name, $type = 'text', $elementData = '', $params = '') {
        $field = '<input type="' . $type . '" id ="' . $name . '" name="' . $name . '"';

        $elementData = trim($elementData); // curatam $elementData

        $field .= ' value="' . $elementData . '" '; // il adaugam tagului

        if ($params != '')
            $field .= ' ' . $params;

        $field .= ' />';

        return $field;
    }

    /**
     * returneaza un tag input de tip password.
     * @param type $name
     * @param type $elementData
     * @param type $params
     * @return type
     */
    function parolaInputTag($name, $elementData = '', $params = '') {
        // parola nu se retine pentru o eventuala reafisare
        $field = $this->inputTag($name, 'password', '', $params);

        return $field;
    }

// end form_pass

    /**
     * returneaza un tag input de tip file
     * @param type $name
     * @param type $elementData
     * @param type $params
     * @return type
     */
    function fisierInputTag($name, $elementData = '', $params = '') {
        // parola nu se retine pentru o eventuala reafisare
        $field = $this->inputTag($name, 'file', '', $params);

        return $field;
    }

/// form_select()

    /**
     * returneaza campuri de tip select, checkbox sau radio.
     * @param type $name
     * @param type $multiple
     * @param type $expanded
     * @param array $initData
     * @param type $elementData
     * @param type $params
     * @return string
     */
    function selectFormTag($name, $multiple = false, $expanded = false, Array $initData = [], $elementData = null, $params = '') {

        if (false === $expanded) { // => avem un select
            // adaugam tagul select cu atributele sale
            $field = '<select';

            // daca tipul este multiple aduagam atributul corespunzator
            if (true === $multiple) {
                $field .= ' multiple size=6';
                $name .= '[]';
            }

            $field .= ' name="' . $name . '"';

            // adaugam lista de parametri
            if ($params) {
                $field .= ' ' . $params . '>';
            } else {
                $field .= '>';
            }

            // adaugam tagurile option
            if (!$elementData) {
                $field .= '<option disabled selected value> -- selecteaza o optiune -- </option>';
            } else {
                $field .= '<option disabled value> -- selecteaza o optiune -- </option>';
            }


            // adaugam tagurile option
            foreach ($initData as $k => $v) {

                $field .= '<option value="' . $k . '"';

                if ((is_array($elementData) && in_array($k, $elementData)) || (is_string($elementData) && ($k == $elementData))) {
                    $field .= ' selected';
                }

                $field .= '>' . ucfirst($v) . '</option>';
            } // end foreach

            $field .= '</select>';

            return $field;
        }

        if (true === $expanded) { // => putem avea un radio sau un checkbox   
            if (true === $multiple) { // => checkbox
                $type = 'checkbox';
                $name .= '[]';
            } else { // => radio
                $type = 'radio';
            }

            // adaugam tagurile input
            $return = '<p>';
            foreach ($initData as $k => $v) {
                $return .= '<span>';
                $return .= $this->labelTag(ucfirst($v), $k);

                $return .= '<input type="' . $type . '" id="' . $k . '" name="' . $name . '" value="' . $k . '"';

                if ($params)
                    $return .= " $params";

                if ((is_array($elementData) && in_array($k, $elementData)) || (is_string($elementData) && ($k == $elementData)))
                    $return .= ' checked';

                $return .= ' />';
                $return .= '</span> ';
            } // end foreach        
            $return .= '</p>';
            return $return;
        }
    }

    /**
     * returneaza un tag textarea.
     * @param type $name
     * @param type $elementData
     * @param type $params
     * @return string
     */
    function textareaTag($name, $elementData = '', $params = '') {
        $field = '<textarea name="' . $name . '" id="' . $name . '"';
        if ($params) {
            $field .= " $params>";
        } else {
            $field .= '>';
        }
        $field .= $elementData;
        $field .= '</textarea>';


        return $field;
    }

    /**
     * returneaza un tag input de tip submit.
     * @param type $name
     * @param type $value
     * @param type $type
     * @param type $params
     * @return string
     */
    function buttonFormTag($name, $value, $type = "submit", $params = '') {
        $field = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" ' . $params . ' />';
        return $field;
    }

}
