<?php

class Controller {
    
    public function __construct() {}
    
    public function sanitize($var, $type = "string") {
        $flags = NULL;
        $var = htmlspecialchars(stripslashes(trim($var)));
        //$var = stripslashes(trim($var));
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                $output = filter_var($var, $filter);
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                $output = filter_var($var, $filter);
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
                $flags = FILTER_FLAG_EMAIL_UNICODE;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'string':
            default:
                //$filter = FILTER_SANITIZE_STRING; Deprecated
                $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                $output = filter_var($var, $filter, $flags);
                break;
        }
        return ($output);
    }
    
    public function validateItem($var, $type) {
        $regexes = Array(
            'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
            'amount' => "^[-]?[0-9]+\$",
            'number' => "^[-]?[0-9,]+\$",
            'alfanum' => "^[0-9a-zA-ZñÑàáèéíòóúÀÁÈÉÍÒÓÚçÇ' ,.-_\\s\?\!]*$",
            'not_empty' => "[a-z0-9A-Z]+",
            'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
            'phone' => "^[0-9]{9,11}\$",
            'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
            'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
            'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
            '2digitopt' => "^\d+(\,\d{2})?\$",
            '2digitforce' => "^\d+\,\d\d\$",
            'anything' => "^[\d\D]{1,}\$"
        );
        if (array_key_exists($type, $regexes)) {
            $returnval = filter_var($var, FILTER_VALIDATE_REGEXP, array(
                "options" => array(
                    "regexp" => '!' . $regexes[$type] . '!i'
                )
            )) !== false;
            return ($returnval);
        }
        $filter = false;
        switch ($type) {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
                break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
                break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
                break;
        }
        return ($filter === false) ? false : (filter_var($var, $filter) !== false ? true : false);
    }
    
    public function isElementSimple($element) {
        $numeroDAparicions = substr_count($element, "<");
        return ($numeroDAparicions <= 2);
    }
    
    public function extreuContingut($element, $etiqueta) {
        do {
            if (isElementSimple($element)) {
                $finalEtiquetaApertura = strpos($element, ">", 0);
                $iniciEtiquetaTancament = strpos($element, "<", $finalEtiquetaApertura);
                $longitud = $iniciEtiquetaTancament - $finalEtiquetaApertura - 1;
                return ($longitud < 0) ? "" : trim(substr($element, $finalEtiquetaApertura + 1, $longitud));
            } else {
                
                // Busquem l'etiqueta interna. El sub-element
                $iniciSubElement = strpos($element, "<", 1) + 1;
                $blancFiSubElement = strpos($element, " ", $iniciSubElement);
                $majorFiSubElement = strpos($element, ">", $iniciSubElement);
                $fiSubElement = ($blancFiSubElement != FALSE && $blancFiSubElement < $majorFiSubElement) ? $blancFiSubElement : $majorFiSubElement;
                
                $longitudEtiqueta = $fiSubElement - $iniciSubElement;
                $etiqueta = substr($element, $iniciSubElement, $longitudEtiqueta);
                
                $iniciElementIntern = strpos($element, "<$etiqueta", 0);
                $fiElementIntern = strpos($element, "</$etiqueta>", $iniciElementIntern);
                if ($fiElementIntern == FALSE) {
                    $fiElementIntern = strpos($element, ">", $iniciElementIntern) + 1;
                }
                $elementIntern = substr($element, $iniciElementIntern, $fiElementIntern - $iniciElementIntern + strlen($etiqueta));
                $resultat = extreuContingut($elementIntern, $etiqueta);
                
                if (empty($resultat)) {
                    // elimino el subelement
                    $element = str_replace($elementIntern, "", $element);
                }
            }
        } while (empty($resultat));
        return $resultat;
    }
    
    
    public function extreureFila($element) {
        // Camp nom
        $inici = strpos($element, '<td ', 0);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["nom"] = utf8_encode($text);
        
        // ticker
        $inici = strpos($element, '<td', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["ticker"] = $text;
        
        // mercat
        $inici = strpos($element, '<td', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["mercat"] = $text;
        
        // no tracto
        $inici = strpos($element, '<td', $fi);
        $fi = strpos($element, '</td>', $inici);
        
        // Ultima cotitzacio
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["ultima_coti"] = $text;
        
        // Divisa
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["divisa"] = $text;
        
        // Variació
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["variacio"] = $text;
        
        // Variació percentual
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["percent"] = $text;
        
        // Volum
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["volum"] = $text;
        
        // Minim
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["mínim"] = $text;
        
        // Màxim
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["màxim"] = $text;
        
        // Data
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = extreuContingut($td, "td");
        $resultat["data"] = $text;
        
        // Hora
        $inici = strpos($element, '<td ', $fi);
        $fi = strpos($element, '</td>', $inici) + 5;
        $td = trim(substr($element, $inici, $fi - $inici));
        $text = trim(extreuContingut($td, "td"));
        $resultat["hora"] = substr($text, 0, 5);
        
        return $resultat;
    }
    
    public function webScrappingInversis(){
        //$ruta = "https://www.inversis.com/trans/inversis/SvlCotizaciones?accion=cotizacionesValores&codigoIndice=3";
        $ruta = "https://www.inversis.com/inversiones/productos/cotizaciones-nacionales&pathMenu=3_0&esLH=N";
        $contingut = file_get_contents($ruta);
        $pos = 0;
        
        while (($posIni = strpos($contingut, "<tr id=\"tr_", $pos)) !== FALSE) {
            $posFi = strpos($contingut, "</tr>", $posIni);
            $contingutDeTR = substr($contingut, $posIni, $posFi - $posIni);
            
            $taulaResultat = extreureFila($contingutDeTR);
            $ibex[] = $taulaResultat;
            $pos = $posFi;
        }
        
        return $ibex;
    }
    
    
    
    public function html_generateTable($aParametre) {
        $capcelera = "<tr>\n";
        $cos = "<tbody>\n";
        
        foreach ($aParametre as $key => $value) {
            $cos .= "<tr>";
            foreach ($value as $clau => $valor) {
                if (!isset($resultat)) {
                    $capcelera .= "<th>".ucwords($clau)."</th>";
                }
                if ($clau == "ultima_coti") {
                    if (isset($_SESSION["cotis"])) {
                        if (floatval(str_replace(",",".",$valor)) > floatval(str_replace(",",".",$_SESSION["cotis"][$key]["ultima_coti"]))) {
                            $cos .= "<td class=\"bgGreen\">$valor</td>";
                        } elseif (floatval(str_replace(",",".",$valor)) < floatval(str_replace(",",".",$_SESSION["cotis"][$key]["ultima_coti"]))) {
                            $cos .= "<td class=\"bgRed\">$valor</td>";
                        } else {
                            $cos .= "<td>$valor</td>";
                        }
                    } else {
                        $cos .= "<td>$valor</td>";
                    }
                } elseif ($clau == "variacio" || $clau == "percent") {
                    if (floatval(str_replace(",",".",$valor))<0) {
                        $cos .= "<td class=\"red\">$valor</td>";
                    } elseif (floatval(str_replace(",",".",$valor))>0){
                        $cos .= "<td class=\"green\">$valor</td>";
                    } else {
                        $cos .= "<td>$valor</td>";
                    }
                } else {
                    $cos .= "<td>$valor</td>";
                }
            }
            $cos .= "</tr>\n";
            $resultat = "<table>$capcelera</tr>\n";
        }
        $cos .= "</tr>\n</tbody>";
        $resultat .= "$cos</table>";
        
        return $resultat;
    }
    
    
    /*
     * funció html_generateSelect: a partir d'un array associatiu, genera codi
     * html per la visualització d'un control SELECT-OPTION generand un menú
     * desplegable.
     *
     * paràmetres:
     * * opcions: array associatiu, en el que la clau representa el valor a definir i
     *      el valor serà el text a mostrar.
     * * atributs: (Opcional) Array associatiu amb parelles atribut-valor segons la
     *       definició html.
     *       https://www.w3schools.com/tags/tag_select.asp apartat Attributes
     *       autofocus: boolean
     *       disabled: boolean
     *       form: string
     *       multible: boolean
     *       name: string
     *       required: boolean
     *       size: integer
     *       class: string
     *       id: string
     *       label: string
     *
     * return: El resultat és un string amb el codi html del contol select-option
     */
    
    public function html_generateSelect($opcions, $seleccionat, $atributs) {
        if (isset($atributs)) {
            //atribut autofocus: boolean
            $attAutofocus = (isset($atributs['autofocus']) && $atributs['autofocus']===true) ? "autofocus " : "";
            
            //atribut disabled: boolean
            $attDisabled = (isset($atributs['disabled']) && $atributs['disabled']===true) ? "disabled " : "";
            
            //atribut form: string
            $attForm = isset($atributs['form']) ? "form=\"{$atributs['form']}\"" : "";
            
            //atribut multible: boolean
            $attMultiple = isset($atributs['multiple']) ? "multiple" : "";
            
            //atribut name: string
            $attName = isset($atributs['name']) ? "name=\"{$atributs['name']}\"" : "";
            
            //atribut required: boolean
            $attRequred = isset($atributs['required']) ? "required" : "";
            
            //atribut size: integer
            $attSize = isset($atributs['size']) ? "size=\"{$atributs['size']}\"" : "";
            
            //atribut class: string
            $attClass = isset($atributs['class']) ? "class=\"{$atributs['class']}\"" : "";
            
            //atribut id: string
            $attId = isset($atributs['id']) ? "id=\"{$atributs['id']}\"" : "";
            
            //label no és un atribut, però ho tractarem com si ho fos.
            $attLabel = isset($atributs['label']) ? "<label for='".$atributs['id']."'>".$atributs['label']."</label><br/>\n" : "";
        }
        
        $resultat = $attLabel;
        $resultat .= "<select $attId $attClass $attName $attSize $attForm $attRequred $attMultiple $attDisabled $attAutofocus>\n";
        foreach ($opcions as $key => $value) {
            $resultat .= "<option value=\"$key\"";
            if (isset($seleccionat) && $seleccionat===$key ) {
                $resultat .= " selected";
            }
            $resultat .=">".ucwords($value)."</option>\n";
        }
        $resultat .="</select>\n";
        if ($atributs['span']!="") {
            $resultat .= "<span class=\"error\" >{$atributs['span']}</span>\n";
        }
        
        return $resultat;
    }
    
    /*
     * funció html_generateChekBox: a partir d'un array associatiu, genera codi
     * html per la visualització dels controls CHECK-BOX.
     *
     * paràmetres:
     * * opcions: array associatiu, amb la clau que representa l'identificador html
     *       únic (l'id) i el valor serà un array amb les següents claus:
     *       "name" que representa el valor a definir,
     *       "label" que emmagatzemarà el text a mostrar,
     *       "value" el valor a assignar,
     *       "checked" que emmagatzemarà un valor booleà.
     * * abans: (Per defecte true) Defineix el label abans/dreprés del checkbox
     *
     * return: El resultat és un string amb el codi html del contol select-option
     */
    
    public function html_generateCheckBox($opcions, $abans="true") {
        foreach ($opcions as $key => $value) {
            $bChecked = ($value['checked']) ? true : false;
            unset($value['checked']);
            $label = "<label for=\"{$key}\">{$value['label']}</label><br>\n";
            unset($value['label']);
            
            $value["type"] = "checkbox";
            $value["id"] = $key;
            $input = html_generaInput($value);
            $input = ($bChecked) ? str_replace(">","checked >",$input) : $input;
            
            if ($abans) {
                $resultat .= "$input\n$label";
            } else {
                $resultat .= "$label\n$input";
            }
        }
        
        return $resultat;
    }
    
    /*
     * funció html_generateChekBox: a partir d'un array associatiu, genera codi
     * html per la visualització dels controls CHECK-BOX.
     *
     * paràmetres:
     * * opcions: array associatiu, amb la clau que representa l'identificador html
     *       únic (l'id) i el valor serà un array amb les següents claus:
     *       "name" que representa el valor a definir,
     *       "label" que emmagatzemarà el text a mostrar,
     *       "value" el valor a assignar,
     *       "checked" que emmagatzemarà un valor booleà.
     * * abans: (Per defecte true) Defineix el label abans/dreprés del checkbox
     *
     * return: El resultat és un string amb el codi html del contol select-option
     */
    
    public function html_generateRadioButon($opcions, $abans="true") {
        $resultat = "";
        foreach ($opcions as $key => $value) {
            $bChecked = ($value['checked']) ? true : false;
            unset($value['checked']);
            $label = "<label for=\"{$key}\" class=\"fs-form\">{$value['label']}</label>";
            unset($value['label']);
            
            $value["type"] = "radio";
            $value["id"] = $key;
            $input = html_generaInput($value);
            $input = ($bChecked) ? str_replace(">","checked >",$input) : $input;
            
            
            if ($abans) {
                $resultat .= "<div class='curt' style='display: inline-block;'>$input\n$label</div>";
            } else {
                $resultat .= "$label\n$input";
            }
        }
        
        
        return $resultat;
    }
    
    /*
     * funció html_generateInput: a partir d'un array associatiu, genera codi
     * html per la visualització dels controls INPUT.
     *
     * paràmetres:
     * * opcions: array associatiu, amb la clau que representa l'identificador html
     *       únic (l'id) i el valor serà un array amb les següents claus:
     *       "type"
     *       "name" ,
     *       "placeholder"
     *       "class"
     *       "value"
     *       o qualsevol altre atribut de INPUT
     *
     * return: El resultat és un string amb el codi html del contol select-option
     */
    public function html_generaInput($options) {
        $resultat = "<input ";
        
        foreach ($options as $key => $value) {
            $resultat .= ($key!="span") ? "$key =\"$value\" " : "";
        }
        $resultat .= ">\n";
        if ($options["span"]!="") {
            $resultat .= "<span class=\"error\" >{$options['span']}</span>\n";
        }
        return $resultat;
    }
    
}

