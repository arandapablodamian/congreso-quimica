<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Ya hay una cuenta con este correo electrónico.")
 * @AppAssert\UserConstraint
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellido;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("numeric")
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $manoHabil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $obraSocial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grupoSanguineo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $restriccionAlimentaria;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alergias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carreras;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Asociacion", inversedBy="users")
     */
    private $asociacion;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $active = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscripcion", mappedBy="user")
     */
    private $inscripcions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("numeric")
     */
    private $anioCursado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enfermedadesCronicas;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $discapacidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discapacidadDescripcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="users")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $categoria;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $realizaVisitas = false;

    public function __construct()
    {
        $this->inscripcions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombreCompleto();
    }

    public function descripcionQr()
    {
        return "{$this->apellido}, {$this->nombre}<br>
                DNI: {$this->dni}<br>
                Teléfono: {$this->telefono}<br>
                País: {$this->pais}<br>
                Mano hábil: {$this->manoHabil}<br>
                Obra social: {$this->obraSocial}<br>
                Grupo sanguíneo: {$this->grupoSanguineo}<br>
                Restricción alimentaria: {$this->restriccionAlimentaria}<br>
                Alergias: {$this->alergias}<br>
                Carreras: {$this->carreras}<br>
                Asociación: {$this->asociacion}<br>
                Año cursado: {$this->anioCursado}<br>
                Enfermedades crónicas: {$this->enfermedadesCronicas}<br>
                Discapacidad: {$this->discapacidad} - {$this->discapacidadDescripcion}<br>
                Categoria: {$this->categoria}";
    }
    public function nombreCompleto()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(?string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getManoHabil(): ?string
    {
        return $this->manoHabil;
    }

    public function setManoHabil(?string $manoHabil): self
    {
        $this->manoHabil = $manoHabil;

        return $this;
    }

    public function getObraSocial(): ?string
    {
        return $this->obraSocial;
    }

    public function setObraSocial(?string $obraSocial): self
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    public function getGrupoSanguineo(): ?string
    {
        return $this->grupoSanguineo;
    }

    public function setGrupoSanguineo(?string $grupoSanguineo): self
    {
        $this->grupoSanguineo = $grupoSanguineo;

        return $this;
    }

    public function getRestriccionAlimentaria(): ?string
    {
        return $this->restriccionAlimentaria;
    }

    public function setRestriccionAlimentaria(?string $restriccionAlimentaria): self
    {
        $this->restriccionAlimentaria = $restriccionAlimentaria;

        return $this;
    }

    public function getAlergias(): ?string
    {
        return $this->alergias;
    }

    public function setAlergias(?string $alergias): self
    {
        $this->alergias = $alergias;

        return $this;
    }

    public function getCarreras(): ?string
    {
        return $this->carreras;
    }

    public function setCarreras(?string $carreras): self
    {
        $this->carreras = $carreras;

        return $this;
    }

    public function getAsociacion(): ?Asociacion
    {
        return $this->asociacion;
    }

    public function setAsociacion(?Asociacion $asociacion): self
    {
        $this->asociacion = $asociacion;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
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
            $inscripcion->setUser($this);
        }

        return $this;
    }

    public function removeInscripcion(Inscripcion $inscripcion): self
    {
        if ($this->inscripcions->contains($inscripcion)) {
            $this->inscripcions->removeElement($inscripcion);
            // set the owning side to null (unless already changed)
            if ($inscripcion->getUser() === $this) {
                $inscripcion->setUser(null);
            }
        }

        return $this;
    }

    public function getAnioCursado(): ?int
    {
        return $this->anioCursado;
    }

    public function setAnioCursado(?int $anioCursado): self
    {
        $this->anioCursado = $anioCursado;

        return $this;
    }

    public function getEnfermedadesCronicas(): ?string
    {
        return $this->enfermedadesCronicas;
    }

    public function setEnfermedadesCronicas(?string $enfermedadesCronicas): self
    {
        $this->enfermedadesCronicas = $enfermedadesCronicas;

        return $this;
    }

    public function getDiscapacidad(): ?string
    {
        return $this->discapacidad;
    }

    public function setDiscapacidad(?string $discapacidad): self
    {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    public function getDiscapacidadDescripcion(): ?string
    {
        return $this->discapacidadDescripcion;
    }

    public function setDiscapacidadDescripcion(?string $discapacidadDescripcion): self
    {
        $this->discapacidadDescripcion = $discapacidadDescripcion;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getRealizaVisitas(): ?bool
    {
        return $this->realizaVisitas;
    }

    public function setRealizaVisitas(bool $realizaVisitas): self
    {
        $this->realizaVisitas = $realizaVisitas;

        return $this;
    }
}
