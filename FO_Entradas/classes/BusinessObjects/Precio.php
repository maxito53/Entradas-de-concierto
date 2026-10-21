<?php 

/**
 * DAO Precio
 * @author max
 */
class Precio {
    
    private $id;
    private $sesion;
    private $zona;
    private $importe;
    
    public function __construct($id, $sesion, $zona, $importe) {
        $this->id = $id;
        $this->sesion = $sesion;
        $this->zona = $zona;
        $this->importe = $importe;
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