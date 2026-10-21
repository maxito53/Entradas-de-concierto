<?php

class View {
    
    public function __construct() {}
    
    function formatearFechaAbreviada($fecha) {
        $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
        
        if (!$fechaObj) {
            return 'Fecha inválida';
        }
        
        $mesesAbreviados = [
            1 => 'ene.', 2 => 'feb.', 3 => 'mar.', 4 => 'abr.',
            5 => 'may.', 6 => 'jun.', 7 => 'jul.', 8 => 'ago.',
            9 => 'sep.', 10 => 'oct.', 11 => 'nov.', 12 => 'dic.'
        ];
        
        $dia = $fechaObj->format('d');
        $mes = (int)$fechaObj->format('m');
        $anio = $fechaObj->format('Y');
        
        return "{$dia} de {$mesesAbreviados[$mes]} {$anio}";
    }
    
    function formatearHoraCorta($hora) {
        $horaObj = DateTime::createFromFormat('H:i:s', $hora);
        
        if (!$horaObj) {
            return 'Hora inválida';
        }
        
        return $horaObj->format('H:i');
    }
    
    public function formatearFechaCompleta($fecha) {
        $fechaObj = new DateTime($fecha);
        $meses = [
            '01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril',
            '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto',
            '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
        ];
        
        $dia = $fechaObj->format('d');
        $mes = $meses[$fechaObj->format('m')];
        $año = $fechaObj->format('Y');
        
        return "$dia de $mes de $año";
    }
    
    public function formatearHora($hora) {
        return substr($hora, 0, 5);
    }
    
    
}

