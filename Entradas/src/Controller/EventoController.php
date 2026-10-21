<?php

class EventoController {
    
    private SesionModel $sesionModel;
    
    public function __construct(SesionModel $sesionModel) {
        $this->sesionModel = $sesionModel;
    }
    
    /**
     * genera y muestra un XML con los eventos de una fecha en especifico
     *
     * @param string $fecha fecha en formato YYYY-MM-DD
     * @return void
     */
    public function generarXML(string $fecha): void {
        
        if (!$this->validarFormatoFecha($fecha)) {
            http_response_code(400);
            echo "Formato de fecha incorrecto. Utilice YYYY-MM-DD.";
            return;
        }
        
        // sacar de db eventos de la fecha solicitada
        $eventos = $this->sesionModel->obtenerEventosPorFecha($fecha);
        
        // se crea estructura XML
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><eventos></eventos>');
        $xml->addAttribute('fecha', $fecha);
        
        // si no hay eventos, he puesto que salga un mensaje informativo
        if (empty($eventos)) {
            $xml->addChild('info', 'No hay eventos programados para esta fecha');
        } else {
            
            // se agrega cada evento al XML
            foreach ($eventos as $evento) {
                
                $eventoElement = $xml->addChild('evento');
                $eventoElement->addAttribute('id', $evento['id']);
                
                $eventoElement->addChild('titulo', htmlspecialchars($evento['titulo'], ENT_XML1, 'UTF-8'));
                $eventoElement->addChild('descripcion', htmlspecialchars($evento['descripcion'] ?? '', ENT_XML1, 'UTF-8'));
                
                $artistaElement = $eventoElement->addChild('artista');
                $artistaElement->addChild('nombre', htmlspecialchars($evento['artista'], ENT_XML1, 'UTF-8'));
                $artistaElement->addChild('genero', htmlspecialchars($evento['genero'] ?? '', ENT_XML1, 'UTF-8'));
                
                $fechaHoraElement = $eventoElement->addChild('fechaHora');
                $fechaHoraElement->addChild('fecha', htmlspecialchars($evento['fecha'], ENT_XML1, 'UTF-8'));
                $fechaHoraElement->addChild('hora', htmlspecialchars($evento['hora'], ENT_XML1, 'UTF-8'));
                
                $lugarElement = $eventoElement->addChild('lugar');
                $lugarElement->addChild('nombre', htmlspecialchars($evento['lugar']['nombre'], ENT_XML1, 'UTF-8'));
                $lugarElement->addChild('direccion', htmlspecialchars($evento['lugar']['direccion'], ENT_XML1, 'UTF-8'));
                $lugarElement->addChild('ciudad', htmlspecialchars($evento['lugar']['ciudad'], ENT_XML1, 'UTF-8'));
                $lugarElement->addChild('capacidad', htmlspecialchars($evento['lugar']['capacidad'] ?? '', ENT_XML1, 'UTF-8'));
                
                $eventoElement->addChild('estado', htmlspecialchars($evento['estado'], ENT_XML1, 'UTF-8'));
                
                $preciosElement = $eventoElement->addChild('precios');
                foreach ($evento['precios'] as $precioInfo) {
                    $precioElement = $preciosElement->addChild('precio');
                    $precioElement->addChild('zona', htmlspecialchars($precioInfo['zona'], ENT_XML1, 'UTF-8'));
                    $precioElement->addChild('descripcion', htmlspecialchars($precioInfo['descripcion'] ?? '', ENT_XML1, 'UTF-8'));
                    $precioElement->addChild('importe', number_format($precioInfo['importe'], 2, '.', ''));
                }
                
            }
            
        }
        
        // cabeceras XML
        header('Content-Type: application/xml; charset=utf-8');
        echo $xml->asXML();
        exit;
        
    }
    
    /**
     * checkea que la fecha tenga el formato YYYY-MM-DD
     *
     * @param string $fecha fecha a validar
     * @return bool true si el formato es correcto
     */
    private function validarFormatoFecha(string $fecha): bool {
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return false;
        }
        
        $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
        return $fechaObj && $fechaObj->format('Y-m-d') === $fecha;
        
    }
}