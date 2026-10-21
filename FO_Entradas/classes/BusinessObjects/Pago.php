<?php 

/**
 * DAO Pago
 * @author max
 */
class Pago {
    
    private $id;
    private $cliente;
    private $fecha;
    private $total;
    private $metodo;
    private $referencia;
    private $estado;
    
    public function __construct($id, $cliente, $fecha, $total, $estado) {
        $this->id = $id;
        $this->cliente = $cliente;
        $this->fecha = $fecha;
        $this->total = $total;
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