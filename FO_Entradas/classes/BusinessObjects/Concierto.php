<?php 

/**
 * DAO Concierto
 * @author max
 */
class Concierto {
    
    private $id;
    private $nombre;
    private $descripcion;
    private $imagen;
    private $artista;
    
    public function __construct($id, $nombre, $descripcion, $imagen, $artista) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
        $this->artista = $artista;
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