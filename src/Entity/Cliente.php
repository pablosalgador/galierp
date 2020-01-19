<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $razon_social;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_comercial;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $persona_contacto;

    /**
     * @ORM\Column(type="integer")
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $provincia;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_baja;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OportunidadVenta", mappedBy="cliente", orphanRemoval=true)
     */
    private $oportunidadesVentas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Presupuesto", mappedBy="cliente", orphanRemoval=true)
     */
    private $presupuestos;

    public function __construct()
    {
        $this->oportunidadesVentas = new ArrayCollection();
        $this->presupuestos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRazonSocial(): ?string
    {
        return $this->razon_social;
    }

    public function setRazonSocial(string $razon_social): self
    {
        $this->razon_social = $razon_social;

        return $this;
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

    public function getPersonaContacto(): ?string
    {
        return $this->persona_contacto;
    }

    public function setPersonaContacto(?string $persona_contacto): self
    {
        $this->persona_contacto = $persona_contacto;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getComentarios(): ?string
    {
        return $this->comentarios;
    }

    public function setComentarios(?string $comentarios): self
    {
        $this->comentarios = $comentarios;

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

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fecha_baja;
    }

    public function setFechaBaja(?\DateTimeInterface $fecha_baja): self
    {
        $this->fecha_baja = $fecha_baja;

        return $this;
    }

    /**
     * @return Collection|OportunidadVenta[]
     */
    public function getOportunidadesVentas(): Collection
    {
        return $this->oportunidadesVentas;
    }

    public function addOportunidadesVenta(OportunidadVenta $oportunidadesVenta): self
    {
        if (!$this->oportunidadesVentas->contains($oportunidadesVenta)) {
            $this->oportunidadesVentas[] = $oportunidadesVenta;
            $oportunidadesVenta->setCliente($this);
        }

        return $this;
    }

    public function removeOportunidadesVenta(OportunidadVenta $oportunidadesVenta): self
    {
        if ($this->oportunidadesVentas->contains($oportunidadesVenta)) {
            $this->oportunidadesVentas->removeElement($oportunidadesVenta);
            // set the owning side to null (unless already changed)
            if ($oportunidadesVenta->getCliente() === $this) {
                $oportunidadesVenta->setCliente(null);
            }
        }

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
            $presupuesto->setCliente($this);
        }

        return $this;
    }

    public function removePresupuesto(Presupuesto $presupuesto): self
    {
        if ($this->presupuestos->contains($presupuesto)) {
            $this->presupuestos->removeElement($presupuesto);
            // set the owning side to null (unless already changed)
            if ($presupuesto->getCliente() === $this) {
                $presupuesto->setCliente(null);
            }
        }

        return $this;
    }
}
