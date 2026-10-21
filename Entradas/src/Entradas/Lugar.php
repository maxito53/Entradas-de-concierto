<?php
// src/Entity/Lugar.php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "LUGAR")]
class Lugar {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $nombre;
    
    #[ORM\Column(type: "string", length: 150)]
    private string $direccion;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $ciudad;
    
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $capacidad = null;
    
    #[ORM\OneToMany(targetEntity: Sesion::class, mappedBy: "lugar")]
    private Collection $sesiones;
    
    public function __construct() {
        $this->sesiones = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getNombre(): string
    {
        return $this->nombre;
    }
    
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }
    
    public function getDireccion(): string
    {
        return $this->direccion;
    }
    
    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;
        return $this;
    }
    
    public function getCiudad(): string
    {
        return $this->ciudad;
    }
    
    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;
        return $this;
    }
    
    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }
    
    public function setCapacidad(?int $capacidad): self
    {
        $this->capacidad = $capacidad;
        return $this;
    }
    
    /**
     * @return Collection<int, Sesion>
     */
    public function getSesiones(): Collection
    {
        return $this->sesiones;
    }
    
    public function addSesion(Sesion $sesion): self
    {
        if (!$this->sesiones->contains($sesion)) {
            $this->sesiones[] = $sesion;
            $sesion->setLugar($this);
        }
        
        return $this;
    }
    
    public function removeSesion(Sesion $sesion): self
    {
        if ($this->sesiones->removeElement($sesion)) {
            // set the owning side to null (unless already changed)
            if ($sesion->getLugar() === $this) {
                $sesion->setLugar(null);
            }
        }
        
        return $this;
    }
}