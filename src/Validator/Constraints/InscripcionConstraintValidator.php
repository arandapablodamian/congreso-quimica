<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Inscripcion;
use App\Entity\Evento;

use App\Controller\CustomEntityManagerController;
use Doctrine\DBAL\LockMode;

/**
 * @Annotation
 */
class InscripcionConstraintValidator extends ConstraintValidator
{
    private $em;
    private $cem;

    public function __construct(
        EntityManagerInterface $em,
        CustomEntityManagerController $cem
    ) {
        $this->em = $em;
        $this->cem = $cem;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value->getId()) {
            // controlar la fecha
            if ($value->getEvento()->estaHabilitado()) {
                // si el evento es visita y el usuario no realiza visitas
                if (
                    $value->getEvento()->getEventoTipo()->getEsVisita() &&
                    !$value->getUser()->getRealizaVisitas()
                ) {
                    // controlar que el evento seleccionado sea visita y el usuario realiza visita
                    $this->context->buildViolation('inscripcion_error_usuario_no_visita')
                        ->addViolation();
                }
                $usuarioEnTipoEvento = $this->em->getRepository(Inscripcion::class)
                    ->usuarioEnTipoEvento(
                        $value->getUser()->getId(),
                        $value->getEvento()->getEventoTipo()->getId()
                    );
                if (!empty($usuarioEnTipoEvento)) {
                    // controlar que el evento seleccionado sea de distintipo evento tipo
                    $this->context->buildViolation('inscripcion_error_tipoevento_usuario')
                        ->addViolation();
                } else {
                    // controlo si tiene cupo
                    $resUpdateCupo = $this->updateCupo($value->getEvento()->getId());

                    if ($resUpdateCupo === 'termino') {
                        $this->context->buildViolation('inscripcion_intente_nuevamente')
                            ->addViolation();
                    } else if (!$resUpdateCupo) {
                        $this->context->buildViolation('inscripcion_sin_cupo')
                            ->addViolation();
                    }
                }
            } else {
                $this->context->buildViolation('evento_deshabilitado')
                    ->addViolation();
            }
        }
    }

    public function updateCupo(int $eventoId)
    {
        $callback = function() use ($eventoId) {
            // Get repository inside callable to make sure EntityManager is valid
            $eventos = $this->cem->getRepository(Evento::class);
          
            // Fetch account with FOR UPDATE write lock
            $evento = $eventos->find(
                $eventoId,
                LockMode::PESSIMISTIC_WRITE
            );
          
            // We are protected from concurrent access here
            if (!$evento->tieneCupo())
                return false;
          
            $evento->sumaCupo();
            return true;
        };
      
        return $this->cem->transactional($callback);
    }
}
