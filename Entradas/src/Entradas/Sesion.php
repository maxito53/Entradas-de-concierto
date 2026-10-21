<?php
// src/Entity/Sesion.php
namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "SESION")]
class Sesion {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\ManyToOne(targetEntity: Concierto::class, inversedBy: "sesiones")]
    #[ORM\JoinColumn(name: "concierto_id", referencedColumnName: "id", nullable: false)]
    private Concierto $concierto;
    
    #[ORM\ManyToOne(targetEntity: Lugar::class, inversedBy: "sesiones")]
    #[ORM\JoinColumn(name: "lugar_id", referencedColumnName: "id", nullable: false)]
    private Lugar $lugar;
    
    #[ORM\Column(type: "date")]
    private DateTime $fecha;
    
    #[ORM\Column(type: "time")]
    private DateTime $hora;
    
    #[ORM\Column(type: "string", columnDefinition: "ENUM('PROGRAMADO','CANCELADO','COMPLETADO','POSPUESTO')", options: ["default" => "PROGRAMADO"])]
    private string $estado = 'PROGRAMADO';
    
    #[ORM\OneToMany(targetEntity: Precio::class, mappedBy: "sesion")]
    private Collection $precios;
    
    #[ORM\OneToMany(targetEntity: Entrada::class, mappedBy: "sesion")]
    private Collection $entradas;
    
    public function __construct() {
        $this->precios = new ArrayCollection();
        $this->entradas = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getConcierto(): Concierto
    {
        return $this->concierto;
    }
    
    public function setConcierto(Concierto $concierto): self
    {
        $this->concierto = $concierto;
        return $this;
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
    
    public function getFecha(): DateTime
    {
        return $this->fecha;
    }
    
    public function setFecha(DateTime $fecha): self
    {
        $this->fecha = $fecha;
        return $this;
    }
    
    public function getHora(): DateTime
    {
        return $this->hora;
    }
    
    public function setHora(DateTime $hora): self
    {
        $this->hora = $hora;
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
            $precio->setSesion($this);
        }
        
        return $this;
    }
    
    public function removePrecio(Precio $precio): self
    {
        if ($this->precios->removeElement($precio)) {
            // set the owning side to null (unless already changed)
            if ($precio->getSesion() === $this) {
                $precio->setSesion(null);
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
            $entrada->setSesion($this);
        }
        
        return $this;
    }
    
    public function removeEntrada(Entrada $entrada): self
    {
        if ($this->entradas->removeElement($entrada)) {
            // set the owning side to null (unless already changed)
            if ($entrada->getSesion() === $this) {
                $entrada->setSesion(null);
            }
        }
        
        return $this;
    }
}