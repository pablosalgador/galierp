<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PresupuestoRepository")
 */
class Presupuesto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero_presupuesto;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha_emision;

    /**
     * @ORM\Column(type="integer")
     */
    private $dias_validez;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OportunidadVenta", mappedBy="presupuesto", cascade={"persist", "remove"})
     */
    private $oportunidadVenta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LineaPresupuesto", mappedBy="presupuesto", orphanRemoval=true)
     */
    private $lineas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="presupuestos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="presupuestos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;

    public function __construct()
    {
        $this->lineas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroPresupuesto(): ?string
    {
        return $this->numero_presupuesto;
    }

    public function setNumeroPresupuesto(string $numero_presupuesto): self
    {
        $this->numero_presupuesto = $numero_presupuesto;

        return $this;
    }

    public function getFechaEmision(): ?\DateTimeInterface
    {
        return $this->fecha_emision;
    }

    public function setFechaEmision(\DateTimeInterface $fecha_emision): self
    {
        $this->fecha_emision = $fecha_emision;

        return $this;
    }

    public function getDiasValidez(): ?int
    {
        return $this->dias_validez;
    }

    public function setDiasValidez(int $dias_validez): self
    {
        $this->dias_validez = $dias_validez;

        return $this;
    }

    public function getOportunidadVenta(): ?OportunidadVenta
    {
        return $this->oportunidadVenta;
    }

    public function setOportunidadVenta(?OportunidadVenta $oportunidadVenta): self
    {
        if($oportunidadVenta !== $this->oportunidadVenta){
          if($this->oportunidadVenta !== null)$this->oportunidadVenta->setPresupuesto(null);
        }

        $this->oportunidadVenta = $oportunidadVenta;

        if($oportunidadVenta !== null)$oportunidadVenta->setPresupuesto($this);


        return $this;
    }

    /**
     * @return Collection|LineaPresupuesto[]
     */
    public function getLineas(): Collection
    {
        return $this->lineas;
    }

    public function addLinea(LineaPresupuesto $linea): self
    {
        if (!$this->lineas->contains($linea)) {
            $this->lineas[] = $linea;
            $linea->setPresupuesto($this);
        }

        return $this;
    }

    public function removeLinea(LineaPresupuesto $linea): self
    {
        if ($this->lineas->contains($linea)) {
            $this->lineas->removeElement($linea);
            // set the owning side to null (unless already changed)
            if ($linea->getPresupuesto() === $this) {
                $linea->setPresupuesto(null);
            }
        }

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }



    public function getSubtotal(): ?float
    {
      $subtotal = 0;
      foreach($this->getLineas() as $linea)
      {
        $subtotal+=($linea->getPrecio() * $linea->getCantidad());
      }
      return $subtotal;
    }
    public function getTotal(): ?float
    {
      $total = 0;
      foreach($this->getLineas() as $linea)
      {
        $total+=$linea->getTotal();
      }
      return $total;
    }

    public function getIVA(): ?float
    {
        $iva = 0;
        foreach($this->getLineas() as $linea)
        {
          $iva+=($linea->getIVA() * $linea->getPrecio());
        }
        return $iva;
    }


}
