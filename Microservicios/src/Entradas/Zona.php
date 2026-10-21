<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "ZONA")]
class Zona {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\Column(type: "string", length: 30)]
    private string $nombre;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $descripcion = null;
    
    #[ORM\OneToMany(targetEntity: Precio::class, mappedBy: "zona")]
    private Collection $precios;
    
    #[ORM\OneToMany(targetEntity: Entrada::class, mappedBy: "zona")]
    private Collection $entradas;
    
    public function __construct() {
        $this->precios = new ArrayCollection();
        $this->entradas = new ArrayCollection();
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
    
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    
    /**
     * @return Collection<int, Precio>
     */
    public function getPrecios(): Collection
    {
        return $this->precios;
    }
    
    public function addPrecio(Precio $precio): self
    {
        if (!$this->precios->contains($precio)) {
            $this->precios[] = $precio;
            $precio->setZona($this);
        }
        
        return $this;
    }
    
    public function removePrecio(Precio $precio): self
    {
        if ($this->precios->removeElement($precio)) {
            // set the owning side to null (unless already changed)
            if ($precio->getZona() === $this) {
                $precio->setZona(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection<int, Entrada>
     */
    public function getEntradas(): Collection
    {
        return $this->entradas;
    }
    
    public function addEntrada(Entrada $entrada): self
    {
        if (!$this->entradas->contains($entrada)) {
            $this->entradas[] = $entrada;
            $entrada->setZona($this);
        }
        
        return $this;
    }
    
    public function removeEntrada(Entrada $entrada): self
    {
        if ($this->entradas->removeElement($entrada)) {
            // set the owning side to null (unless already changed)
            if ($entrada->getZona() === $this) {
                $entrada->setZona(null);
            }
        }
        
        return $this;
    }
}