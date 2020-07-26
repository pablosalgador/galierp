<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OportunidadVentaRepository")
 */
class OportunidadVenta
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finalizada;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ganada;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $perdida;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivo_perdida;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ingreso_estimado;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $porcentaje_exito_estimado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ColumnaKanban", inversedBy="oportunidades")
     */
    private $columna_kanban;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="oportunidadesVentas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="oportunidadesVentas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Presupuesto", inversedBy="oportunidadVenta", cascade={"persist", "remove"})
     */
    private $presupuesto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Factura", inversedBy="oportunidadVenta")
     */
    private $factura;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
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

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getFinalizada(): ?\DateTimeInterface
    {
        return $this->finalizada;
    }

    public function setFinalizada(?\DateTimeInterface $finalizada): self
    {
        $this->finalizada = $finalizada;

        return $this;
    }

    public function getGanada(): ?bool
    {
        return $this->ganada;
    }

    public function setGanada(?bool $ganada): self
    {
        $this->ganada = $ganada;

        return $this;
    }

    public function getPerdida(): ?bool
    {
        return $this->perdida;
    }

    public function setPerdida(?bool $perdida): self
    {
        $this->perdida = $perdida;

        return $this;
    }

    public function getMotivoPerdida(): ?string
    {
        return $this->motivo_perdida;
    }

    public function setMotivoPerdida(?string $motivo_perdida): self
    {
        $this->motivo_perdida = $motivo_perdida;

        return $this;
    }

    public function getIngresoEstimado(): ?float
    {
        return $this->ingreso_estimado;
    }

    public function setIngresoEstimado(?float $ingreso_estimado): self
    {
        $this->ingreso_estimado = $ingreso_estimado;

        return $this;
    }

    public function getPorcentajeExitoEstimado(): ?float
    {
        return $this->porcentaje_exito_estimado;
    }

    public function setPorcentajeExitoEstimado(?float $porcentaje_exito_estimado): self
    {
        $this->porcentaje_exito_estimado = $porcentaje_exito_estimado;

        return $this;
    }

    public function getColumnaKanban(): ?ColumnaKanban
    {
        return $this->columna_kanban;
    }

    public function setColumnaKanban(?ColumnaKanban $columna_kanban): self
    {
        $this->columna_kanban = $columna_kanban;

        return $this;
    }

    public function getResponsable(): ?Usuario
    {
        return $this->responsable;
    }

    public function setResponsable(?Usuario $responsable): self
    {
        $this->responsable = $responsable;

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

    public function getPresupuesto(): ?Presupuesto
    {
        return $this->presupuesto;
    }

    public function setPresupuesto(?Presupuesto $presupuesto): self
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(?Factura $factura): self
    {
        $this->factura = $factura;

        // set (or unset) the owning side of the relation if necessary
        /*$newOportunidadVenta = $factura === null ? null : $this;
        if ($newOportunidadVenta !== $factura->getOportunidadVenta()) {
            $factura->setOportunidadVenta($newOportunidadVenta);
        }*/

        return $this;
    }
}
