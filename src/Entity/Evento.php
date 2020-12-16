<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventoRepository")
 * @Vich\Uploadable
 */
class Evento
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $disertante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ubicacion;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $activo = false;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $cupo = 0;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $cupoUtilizado = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventoTipo", inversedBy="eventos", cascade={"remove"})
     * @ORM\JoinColumn(name="evento_tipo_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $eventoTipo;

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
     * @Vich\UploadableField(mapping="evento_images", fileNameProperty="image")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Inscripcion", mappedBy="evento")
     */
    private $inscripcions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $requisitos;

    public function __construct()
    {
        $this->inscripcions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->titulo;
    }

    public function estaHabilitado()
    {
        $now = new \DateTime();

        if (
            $this->activo && $this->eventoTipo->getActivo() &&
            (
                $this->eventoTipo->getFechaInicio() <= $now
                && $this->eventoTipo->getFechaFin() >= $now
            )
        ) {
            return true;
        }

        return false;
    }

    public function tieneCupo()
    {
        return $this->cupo > $this->cupoUtilizado;
    }

    public function sumaCupo()
    {
        $this->cupoUtilizado = $this->cupoUtilizado + 1;

        return $this;
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

    public function getDisertante(): ?string
    {
        return $this->disertante;
    }

    public function setDisertante(string $disertante): self
    {
        $this->disertante = $disertante;

        return $this;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(?string $ubicacion): self
    {
        $this->ubicacion = $ubicacion;

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

    public function getCupo(): ?int
    {
        return $this->cupo;
    }

    public function setCupo(int $cupo): self
    {
        $this->cupo = $cupo;

        return $this;
    }

    public function getCupoUtilizado(): ?int
    {
        return $this->cupoUtilizado;
    }

    public function setCupoUtilizado(int $cupoUtilizado): self
    {
        $this->cupoUtilizado = $cupoUtilizado;

        return $this;
    }

    public function getEventoTipo(): ?EventoTipo
    {
        return $this->eventoTipo;
    }

    public function setEventoTipo(?EventoTipo $eventoTipo): self
    {
        $this->eventoTipo = $eventoTipo;

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

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return Collection|Inscripcion[]
     */
    public function getInscripcions(): Collection
    {
        return $this->inscripcions;
    }

    public function addInscripcion(Inscripcion $inscripcion): self
    {
        if (!$this->inscripcions->contains($inscripcion)) {
            $this->inscripcions[] = $inscripcion;
            $inscripcion->setEvento($this);
        }

        return $this;
    }

    public function removeInscripcion(Inscripcion $inscripcion): self
    {
        if ($this->inscripcions->contains($inscripcion)) {
            $this->inscripcions->removeElement($inscripcion);
            // set the owning side to null (unless already changed)
            if ($inscripcion->getEvento() === $this) {
                $inscripcion->setEvento(null);
            }
        }

        return $this;
    }

    public function getRequisitos(): ?string
    {
        return $this->requisitos;
    }

    public function setRequisitos(?string $requisitos): self
    {
        $this->requisitos = $requisitos;

        return $this;
    }
}
