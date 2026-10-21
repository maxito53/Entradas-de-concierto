<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "CLIENTE")]
class Cliente {
    
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;
    
    #[ORM\Column(type: "string", length: 15)]
    private string $dni;
    
    #[ORM\Column(type: "string", length: 50)]
    private string $nombre;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $apellidos;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $email;
    
    #[ORM\Column(type: "string", length: 15, nullable: true)]
    private ?string $telefono = null;
    
    #[ORM\Column(type: "string", length: 100)]
    private string $password;
    
    #[ORM\OneToMany(targetEntity: Pago::class, mappedBy: "cliente")]
    private Collection $pagos;
    
    public function __construct() {
        $this->pagos = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getDni(): string
    {
        return $this->dni;
    }
    
    public function setDni(string $dni): self
    {
        $this->dni = $dni;
        return $this;
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
    
    public function getApellidos(): string
    {
        return $this->apellidos;
    }
    
    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;
        return $this;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }
    
    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @return Collection<int, Pago>
     */
    public function getPagos(): Collection
    {
        return $this->pagos;
    }
    
    public function addPago(Pago $pago): self
    {
        if (!$this->pagos->contains($pago)) {
            $this->pagos[] = $pago;
            $pago->setCliente($this);
        }
        
        return $this;
    }
    
    public function removePago(Pago $pago): self
    {
        if ($this->pagos->removeElement($pago)) {
            // set the owning side to null (unless already changed)
            if ($pago->getCliente() === $this) {
                $pago->setCliente(null);
            }
        }
        
        return $this;
    }
}