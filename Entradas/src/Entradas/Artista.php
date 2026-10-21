<?php
// src/Entity/Artista.php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "ARTISTA")]
class Artista {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $nombre;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $pais = null;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $genero = null;
    
    #[ORM\OneToMany(targetEntity: Concierto::class, mappedBy: "artista")]
    private Collection $conciertos;
    
    public function __construct() {
        $this->conciertos = new ArrayCollection();
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
    
    public function getPais(): ?string
    {
        return $this->pais;
    }
    
    public function setPais(?string $pais): self
    {
        $this->pais = $pais;
        return $this;
    }
    
    public function getGenero(): ?string
    {
        return $this->genero;
    }
    
    public function setGenero(?string $genero): self
    {
        $this->genero = $genero;
        return $this;
    }
    
    /**
     * @return Collection<int, Concierto>
     */
    public function getConciertos(): Collection
    {
        return $this->conciertos;
    }
    
    public function addConcierto(Concierto $concierto): self
    {
        if (!$this->conciertos->contains($concierto)) {
            $this->conciertos[] = $concierto;
            $concierto->setArtista($this);
        }
        
        return $this;
    }
    
    public function removeConcierto(Concierto $concierto): self
    {
        if ($this->conciertos->removeElement($concierto)) {
            if ($concierto->getArtista() === $this) {
                $concierto->setArtista(null);
            }
        }
        
        return $this;
    }
}