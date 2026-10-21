<?php 

/**
 * DAO Sesion
 * @author max
 */
class Sesion {
    
    private $id;
    private $concierto;
    private $lugar;
    private $fecha;
    private $hora;
    private $estado;
    
    public function __construct($id, $concierto, $lugar, $fecha, $hora, $estado) {
        $this->id = $id;
        $this->concierto = $concierto;
        $this->lugar = $lugar;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->estado = $estado;
    }
    
    public function __get($propiedad) {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
        return null;
    }
    
    public function __set($propiedad, $valor) {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $valor;
        }
    }
    
}