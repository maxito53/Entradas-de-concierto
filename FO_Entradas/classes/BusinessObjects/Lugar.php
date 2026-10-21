<?php 

/**
 * DAO Lugar
 * @author max
 */
class Lugar {
    
    private $id;
    private $nombre;
    private $direccion;
    private $ciudad;
    private $capacidad;
    
    public function __construct($id, $nombre, $direccion, $ciudad, $capacidad) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->capacidad = $capacidad;
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