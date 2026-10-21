<?php 

/**
 * DAO Entrada
 * @author max
 */
class Entrada {
    
    private $codigo;
    private $pago;
    private $sesion;
    private $asiento;
    private $precio;
    private $estado;
    
    public function __construct($codigo, $pago, $sesion, $asiento, $precio, $estado) {
        $this->codigo = $codigo;
        $this->pago = $pago;
        $this->sesion = $sesion;
        $this->asiento = $asiento;
        $this->precio = $precio;
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