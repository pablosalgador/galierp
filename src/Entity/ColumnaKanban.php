<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColumnaKanbanRepository")
 */
class ColumnaKanban
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
     * @ORM\Column(type="integer")
     */
    private $posicion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OportunidadVenta", mappedBy="columna_kanban")
     */
    private $oportunidades;

    public function __construct()
    {
        $this->oportunidades = new ArrayCollection();
    }

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

    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(int $posicion): self
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * @return Collection|OportunidadVenta[]
     */
    public function getOportunidades(): Collection
    {
        return $this->oportunidades;
    }

    public function addOportunidade(OportunidadVenta $oportunidade): self
    {
        if (!$this->oportunidades->contains($oportunidade)) {
            $this->oportunidades[] = $oportunidade;
            $oportunidade->setColumnaKanban($this);
        }

        return $this;
    }

    public function removeOportunidade(OportunidadVenta $oportunidade): self
    {
        if ($this->oportunidades->contains($oportunidade)) {
            $this->oportunidades->removeElement($oportunidade);
            // set the owning side to null (unless already changed)
            if ($oportunidade->getColumnaKanban() === $this) {
                $oportunidade->setColumnaKanban(null);
            }
        }

        return $this;
    }
}
