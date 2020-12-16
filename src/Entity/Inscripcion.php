<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InscripcionRepository")
 * @ORM\Table(uniqueConstraints={
 *  @ORM\UniqueConstraint(
 *      name="search_user_evento",
 *      columns={"user_id", "evento_id"}
 *  )
 * })
 * @UniqueEntity(
 *     fields={"user", "evento"},
 *     errorPath="evento",
 *     message="inscripcion_error_evento_usuario_existe"
 * )
 * @AppAssert\InscripcionConstraint
 */
class Inscripcion
{
    public static $ESTADO_VALOR = [
        0 => 'Inscripto',
        1 => 'Asistio',
        2 => 'Ausente',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="inscripcions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evento", inversedBy="inscripcions")
     */
    private $evento;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $estado = 0;

    public function asistioQr()
    {
        $asistioQr = null;
        if ($this->estado == 0) {
            $asistioQr = $this->id;
        }
        return $asistioQr;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstadoTexto()
    {
        return self::$ESTADO_VALOR[$this->estado];
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(?int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
