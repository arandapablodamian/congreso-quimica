<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventoTipoRepository")
 * @Vich\Uploadable
 */
class EventoTipo
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
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $activo = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evento", mappedBy="eventoTipo", fetch="EAGER",cascade={"persist", "remove"})
     */
    private $eventos;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Congreso", inversedBy="eventoTipos")
     */
    private $congreso;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $visualizacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $orden = 0;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $esVisita = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $esImagen = false;

    public function __construct()
    {
        $this->eventos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection|Evento[]
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos[] = $evento;
            $evento->setEventoTipo($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->eventos->contains($evento)) {
            $this->eventos->removeElement($evento);
            // set the owning side to null (unless already changed)
            if ($evento->getEventoTipo() === $this) {
                $evento->setEventoTipo(null);
            }
        }

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

    public function getCongreso(): ?Congreso
    {
        return $this->congreso;
    }

    public function setCongreso(?Congreso $congreso): self
    {
        $this->congreso = $congreso;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getVisualizacion(): ?string
    {
        return $this->visualizacion;
    }

    public function setVisualizacion(?string $visualizacion): self
    {
        $this->visualizacion = $visualizacion;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getEsVisita(): ?bool
    {
        return $this->esVisita;
    }

    public function setEsVisita(bool $esVisita): self
    {
        $this->esVisita = $esVisita;

        return $this;
    }

    public function getEsImagen(): ?bool
    {
        return $this->esImagen;
    }

    public function setEsImagen(bool $esImagen): self
    {
        $this->esImagen = $esImagen;

        return $this;
    }
}
