<?php 

/**
 * DAO Artista
 * @author max
 *
 */
class Artista {
    
    private $id;
    private $nombre;
    private $pais;
    private $genero;
    
    public function __construct($id, $nombre, $pais, $genero) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->pais = $pais;
        $this->genero = $genero;
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