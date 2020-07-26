<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacturaRepository")
 */
class Factura
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
    private $serie;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero_factura;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_emision;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $irpf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LineaFactura", mappedBy="factura", orphanRemoval=true)
     */
    private $lineas;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OportunidadVenta", mappedBy="factura", cascade={"persist", "remove"})
     */
    private $oportunidadVenta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingreso", mappedBy="factura")
     */
    private $ingresos;


    public function __construct()
    {
        $this->lineas = new ArrayCollection();
        $this->ingresos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(string $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    public function getNumeroFactura(): ?int
    {
        return $this->numero_factura;
    }

    public function setNumeroFactura(int $numero_factura): self
    {
        $this->numero_factura = $numero_factura;

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

    public function getIrpf(): ?float
    {
        return $this->irpf;
    }

    public function setIrpf(?float $irpf): self
    {
        $this->irpf = $irpf;

        return $this;
    }

    /**
     * @return Collection|LineaFactura[]
     */
    public function getLineas(): Collection
    {
        return $this->lineas;
    }

    public function addLinea(LineaFactura $linea): self
    {
        if (!$this->lineas->contains($linea)) {
            $this->lineas[] = $linea;
            $linea->setFactura($this);
        }

        return $this;
    }

    public function removeLinea(LineaFactura $linea): self
    {
        if ($this->lineas->contains($linea)) {
            $this->lineas->removeElement($linea);
            // set the owning side to null (unless already changed)
            if ($linea->getFactura() === $this) {
                $linea->setFactura(null);
            }
        }

        return $this;
    }

    public function getOportunidadVenta(): ?OportunidadVenta
    {
        return $this->oportunidadVenta;
    }

    public function setOportunidadVenta(?OportunidadVenta $oportunidadVenta): self
    {

        if($oportunidadVenta==null && $this->oportunidadVenta != null){
          $this->oportunidadVenta->setFactura(null);
        }
        $this->oportunidadVenta = $oportunidadVenta;
        if($this->oportunidadVenta!=null){
          $this->oportunidadVenta->setFactura($this);
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

    /**
     * @return Collection|Ingreso[]
     */
    public function getIngresos(): Collection
    {
        return $this->ingresos;
    }

    public function addIngreso(Ingreso $ingreso): self
    {
        if (!$this->ingresos->contains($ingreso)) {
            $this->ingresos[] = $ingreso;
            $ingreso->setFactura($this);
        }

        return $this;
    }

    public function removeIngreso(Ingreso $ingreso): self
    {
        if ($this->ingresos->contains($ingreso)) {
            $this->ingresos->removeElement($ingreso);
            // set the owning side to null (unless already changed)
            if ($ingreso->getFactura() === $this) {
                $ingreso->setFactura(null);
            }
        }

        return $this;
    }
}
