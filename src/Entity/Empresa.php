<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpresaRepository")
 */
class Empresa
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
    private $nombre_comercial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $razon_social;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codigo_postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Presupuesto", mappedBy="empresa")
     */
    private $presupuestos;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $provincia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gasto", mappedBy="empresa")
     */
    private $gastos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Factura", mappedBy="empresa")
     */
    private $facturas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingreso", mappedBy="empresa")
     */
    private $ingresos;

    public function __construct()
    {
        $this->presupuestos = new ArrayCollection();
        $this->gastos = new ArrayCollection();
        $this->facturas = new ArrayCollection();
        $this->ingresos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreComercial(): ?string
    {
        return $this->nombre_comercial;
    }

    public function setNombreComercial(string $nombre_comercial): self
    {
        $this->nombre_comercial = $nombre_comercial;

        return $this;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razon_social;
    }

    public function setRazonSocial(string $razon_social): self
    {
        $this->razon_social = $razon_social;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigo_postal;
    }

    public function setCodigoPostal(string $codigo_postal): self
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    public function getLocalidad(): ?string
    {
        return $this->localidad;
    }

    public function setLocalidad(string $localidad): self
    {
        $this->localidad = $localidad;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Presupuesto[]
     */
    public function getPresupuestos(): Collection
    {
        return $this->presupuestos;
    }

    public function addPresupuesto(Presupuesto $presupuesto): self
    {
        if (!$this->presupuestos->contains($presupuesto)) {
            $this->presupuestos[] = $presupuesto;
            $presupuesto->setEmpresa($this);
        }

        return $this;
    }

    public function removePresupuesto(Presupuesto $presupuesto): self
    {
        if ($this->presupuestos->contains($presupuesto)) {
            $this->presupuestos->removeElement($presupuesto);
            // set the owning side to null (unless already changed)
            if ($presupuesto->getEmpresa() === $this) {
                $presupuesto->setEmpresa(null);
            }
        }

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * @return Collection|Gasto[]
     */
    public function getGastos(): Collection
    {
        return $this->gastos;
    }

    public function addGasto(Gasto $gasto): self
    {
        if (!$this->gastos->contains($gasto)) {
            $this->gastos[] = $gasto;
            $gasto->setEmpresa($this);
        }

        return $this;
    }

    public function removeGasto(Gasto $gasto): self
    {
        if ($this->gastos->contains($gasto)) {
            $this->gastos->removeElement($gasto);
            // set the owning side to null (unless already changed)
            if ($gasto->getEmpresa() === $this) {
                $gasto->setEmpresa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Factura[]
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(Factura $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setEmpresa($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->contains($factura)) {
            $this->facturas->removeElement($factura);
            // set the owning side to null (unless already changed)
            if ($factura->getEmpresa() === $this) {
                $factura->setEmpresa(null);
            }
        }

        return $this;
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
            $ingreso->setEmpresa($this);
        }

        return $this;
    }

    public function removeIngreso(Ingreso $ingreso): self
    {
        if ($this->ingresos->contains($ingreso)) {
            $this->ingresos->removeElement($ingreso);
            // set the owning side to null (unless already changed)
            if ($ingreso->getEmpresa() === $this) {
                $ingreso->setEmpresa(null);
            }
        }

        return $this;
    }
}
