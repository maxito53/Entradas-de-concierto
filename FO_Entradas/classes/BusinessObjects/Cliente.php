<?php 

/** 
 * DAO Cliente
 * @author max
 */
class Cliente {
    
    private $id;
    private $dni;
    private $nombre;
    private $apellidos;
    private $email;
    private $telefono;
    
    public function __construct($id, $dni, $nombre, $apellidos, $email) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
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