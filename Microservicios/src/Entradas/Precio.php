<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "PRECIO")]
class Precio {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\ManyToOne(targetEntity: Sesion::class, inversedBy: "precios")]
    #[ORM\JoinColumn(name: "sesion_id", referencedColumnName: "id", nullable: false)]
    private Sesion $sesion;
    
    #[ORM\ManyToOne(targetEntity: Zona::class, inversedBy: "precios")]
    #[ORM\JoinColumn(name: "zona_id", referencedColumnName: "id", nullable: false)]
    private Zona $zona;
    
    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $importe;
    
    #[ORM\OneToMany(targetEntity: Entrada::class, mappedBy: "precio")]
    private Collection $entradas;
    
    public function __construct() {
        $this->entradas = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getSesion(): Sesion
    {
        return $this->sesion;
    }
    
    public function setSesion(Sesion $sesion): self
    {
        $this->sesion = $sesion;
        return $this;
    }
    
    public function getZona(): Zona
    {
        return $this->zona;
    }
    
    public function setZona(Zona $zona): self
    {
        $this->zona = $zona;
        return $this;
    }
    
    public function getImporte(): float
    {
        return $this->importe;
    }
    
    public function setImporte(float $importe): self
    {
        $this->importe = $importe;
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
            $entrada->setPrecio($this);
        }
        
        return $this;
    }
    
    public function removeEntrada(Entrada $entrada): self
    {
        if ($this->entradas->removeElement($entrada)) {
            // set the owning side to null (unless already changed)
            if ($entrada->getPrecio() === $this) {
                $entrada->setPrecio(null);
            }
        }
        
        return $this;
    }
}