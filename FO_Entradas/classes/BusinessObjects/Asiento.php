<?php 

/**
 * DAO Asiento
 * @author max
 *
 */
class Asiento {
    
    private $id;
    private $lugar;
    private $zona;
    private $fila;
    private $numero;
    private $estado;
    
    public function __construct($id, $lugar, $zona, $fila, $numero, $estado) {
        $this->id = $id;
        $this->lugar = $lugar;
        $this->zona = $zona;
        $this->fila = $fila;
        $this->numero = $numero;
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