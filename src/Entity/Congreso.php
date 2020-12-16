<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CongresoRepository")
 * @Vich\Uploadable
 */
class Congreso
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
    private $titulo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $horaInicio;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $horaFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $activo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="congreso_images", fileNameProperty="image")
     * @Assert\Image(
     *     maxSize = "10M"
     * )
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(
     *  targetEntity="App\Entity\EventoTipo",
     *  mappedBy="congreso",
     *  cascade={"persist"},
     *  fetch="EAGER")
     * @ORM\OrderBy({"orden" = "asc"})
     */
    private $eventoTipos;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $ubicacionMap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categoria", mappedBy="congreso",cascade={"persist"}, orphanRemoval=true)
     */
    private $categorias;

    public function __construct()
    {
        $this->eventoTipos = new ArrayCollection();
        $this->categorias = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->titulo;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getHoraInicio(): ?\DateTimeInterface
    {
        return $this->horaInicio;
    }

    public function setHoraInicio(?\DateTimeInterface $horaInicio): self
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    public function setHoraFin(?\DateTimeInterface $horaFin): self
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(?string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(?string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(?string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return Collection|EventoTipo[]
     */
    public function getEventoTipos(): Collection
    {
        return $this->eventoTipos;
    }

    public function addEventoTipo(EventoTipo $eventoTipo): self
    {
        if (!$this->eventoTipos->contains($eventoTipo)) {
            $this->eventoTipos[] = $eventoTipo;
            $eventoTipo->setCongreso($this);
        }

        return $this;
    }

    public function removeEventoTipo(EventoTipo $eventoTipo): self
    {
        if ($this->eventoTipos->contains($eventoTipo)) {
            $this->eventoTipos->removeElement($eventoTipo);
            // set the owning side to null (unless already changed)
            if ($eventoTipo->getCongreso() === $this) {
                $eventoTipo->setCongreso(null);
            }
        }

        return $this;
    }

    public function getUbicacionMap(): ?string
    {
        return $this->ubicacionMap;
    }

    public function setUbicacionMap(?string $ubicacionMap): self
    {
        $this->ubicacionMap = $ubicacionMap;

        return $this;
    }

    /**
     * @return Collection|Categoria[]
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias[] = $categoria;
            $categoria->setCongreso($this);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        if ($this->categorias->contains($categoria)) {
            $this->categorias->removeElement($categoria);
            // set the owning side to null (unless already changed)
            if ($categoria->getCongreso() === $this) {
                $categoria->setCongreso(null);
            }
        }

        return $this;
    }
}
