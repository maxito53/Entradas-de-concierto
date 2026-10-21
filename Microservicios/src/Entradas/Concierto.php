<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "CONCIERTO")]
class Concierto {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $nombre;
    
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descripcion = null;
    
    #[ORM\ManyToOne(targetEntity: Artista::class, inversedBy: "conciertos")]
    #[ORM\JoinColumn(name: "artista_id", referencedColumnName: "id", nullable: false)]
    private Artista $artista;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $imagen = null;
    
    #[ORM\OneToMany(targetEntity: Sesion::class, mappedBy: "concierto")]
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
    
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    
    public function getArtista(): Artista
    {
        return $this->artista;
    }
    
    public function setArtista(Artista $artista): self
    {
        $this->artista = $artista;
        return $this;
    }
    
    public function getImagen(): ?string
    {
        return $this->imagen;
    }
    
    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;
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
            $sesion->setConcierto($this);
        }
        
        return $this;
    }
    
    public function removeSesion(Sesion $sesion): self
    {
        if ($this->sesiones->removeElement($sesion)) {
            // set the owning side to null (unless already changed)
            if ($sesion->getConcierto() === $this) {
                $sesion->setConcierto(null);
            }
        }
        
        return $this;
    }
}