<?php

namespace Entradas\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ENTRADA")]
class Entrada {
    
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 30)]
    private string $codigo;
    
    #[ORM\ManyToOne(targetEntity: Pago::class, inversedBy: "entradas")]
    #[ORM\JoinColumn(name: "pago_id", referencedColumnName: "id", nullable: false)]
    private Pago $pago;
    
    #[ORM\ManyToOne(targetEntity: Sesion::class, inversedBy: "entradas")]
    #[ORM\JoinColumn(name: "sesion_id", referencedColumnName: "id", nullable: false)]
    private Sesion $sesion;
    
    #[ORM\ManyToOne(targetEntity: Zona::class, inversedBy: "entradas")]
    #[ORM\JoinColumn(name: "zona_id", referencedColumnName: "id", nullable: false)]
    private Zona $zona;
    
    #[ORM\ManyToOne(targetEntity: Asiento::class, inversedBy: "entradas")]
    #[ORM\JoinColumn(name: "asiento_id", referencedColumnName: "id", nullable: true)]
    private ?Asiento $asiento = null;
    
    // Mantenemos estos campos para compatibilidad con código existente
    // pero ahora son derivados de la relación con Asiento
    #[ORM\Column(type: "string", length: 5, nullable: true)]
    private ?string $fila = null;
    
    #[ORM\Column(type: "string", length: 5, nullable: true)]
    private ?string $asiento_numero = null;
    
    #[ORM\ManyToOne(targetEntity: Precio::class, inversedBy: "entradas")]
    #[ORM\JoinColumn(name: "precio_id", referencedColumnName: "id", nullable: false)]
    private Precio $precio;
    
    #[ORM\Column(type: "string", columnDefinition: "ENUM('VALIDA','USADA','CANCELADA','REEMBOLSADA')", options: ["default" => "VALIDA"])]
    private string $estado = 'VALIDA';
    
    public function getCodigo(): string
    {
        return $this->codigo;
    }
    
    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;
        return $this;
    }
    
    public function getPago(): Pago
    {
        return $this->pago;
    }
    
    public function setPago(Pago $pago): self
    {
        $this->pago = $pago;
        return $this;
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
    
    public function getAsiento(): ?Asiento
    {
        return $this->asiento;
    }
    
    public function setAsiento(?Asiento $asiento): self
    {
        $this->asiento = $asiento;
        
        // Actualizar fila y asiento_numero cuando se establece un asiento
        if ($asiento !== null) {
            $this->fila = $asiento->getFila();
            $this->asiento_numero = $asiento->getNumero();
        }
        
        return $this;
    }
    
    public function getFila(): ?string
    {
        return $this->fila;
    }
    
    public function setFila(?string $fila): self
    {
        $this->fila = $fila;
        return $this;
    }
    
    public function getAsientoNumero(): ?string
    {
        return $this->asiento_numero;
    }
    
    public function setAsientoNumero(?string $asiento_numero): self
    {
        $this->asiento_numero = $asiento_numero;
        return $this;
    }
    
    // Método de compatibilidad para código existente
    public function getAsiento_numero(): ?string
    {
        return $this->asiento_numero;
    }
    
    // Método de compatibilidad para código existente
    public function setAsiento_numero(?string $asiento_numero): self
    {
        $this->asiento_numero = $asiento_numero;
        return $this;
    }
    
    public function getPrecio(): Precio
    {
        return $this->precio;
    }
    
    public function setPrecio(Precio $precio): self
    {
        $this->precio = $precio;
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
}