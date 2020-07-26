<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GastoRepository")
 */
class Gasto
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
    private $descripcion;

    /**
     * @ORM\Column(type="float")
     */
    private $base;

    /**
     * @ORM\Column(type="float")
     */
    private $iva;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $justificante;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="gastos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Proveedor", inversedBy="gastos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proveedor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getBase(): ?float
    {
        return $this->base;
    }

    public function setBase(float $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(float $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getJustificante(): ?string
    {
        return $this->justificante;
    }

    public function setJustificante(?string $justificante): self
    {
        $this->justificante = $justificante;

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

    public function getProveedor(): ?Proveedor
    {
        return $this->proveedor;
    }

    public function setProveedor(?Proveedor $proveedor): self
    {
        $this->proveedor = $proveedor;

        return $this;
    }
}
