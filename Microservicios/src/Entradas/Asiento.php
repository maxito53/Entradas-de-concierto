<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "ASIENTO")]
class Asiento {
    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Lugar::class, inversedBy: "asientos")]
    #[ORM\JoinColumn(name: "lugar_id", referencedColumnName: "id", nullable: false)]
    private Lugar $lugar;

    #[ORM\ManyToOne(targetEntity: Zona::class, inversedBy: "asientos")]
    #[ORM\JoinColumn(name: "zona_id", referencedColumnName: "id", nullable: false)]
    private Zona $zona;

    #[ORM\Column(type: "string", length: 5)]
    private string $fila;

    #[ORM\Column(type: "string", length: 5)]
    private string $numero;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('DISPONIBLE','NO DISPONIBLE')", options: ["default" => "DISPONIBLE"])]
    private string $estado = 'DISPONIBLE';

    #[ORM\OneToMany(targetEntity: Entrada::class, mappedBy: "asiento")]
    private Collection $entradas;

    public function __construct() {
        $this->entradas = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLugar(): Lugar
    {
        return $this->lugar;
    }

    public function setLugar(Lugar $lugar): self
    {
        $this->lugar = $lugar;
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

    public function getFila(): string
    {
        return $this->fila;
    }

    public function setFila(string $fila): self
    {
        $this->fila = $fila;
        return $this;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
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
            $this->entradas->add($entrada);
            $entrada->setAsiento($this);
        }

        return $this;
    }

    public function removeEntrada(Entrada $entrada): self
    {
        if ($this->entradas->removeElement($entrada)) {
            // set the owning side to null (unless already changed)
            if ($entrada->getAsiento() === $this) {
                $entrada->setAsiento(null);
            }
        }

        return $this;
    }
}