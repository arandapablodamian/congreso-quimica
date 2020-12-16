<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UserConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if ('Si' == $value->getDiscapacidad() && empty($value->getDiscapacidadDescripcion())) {
            $this->context->buildViolation('campo_discapacidad_obligatorio')
                ->addViolation();
        }

        if (!$value->getCategoria()) {
            $this->context->buildViolation('campo_categoria_obligatorio')
            ->addViolation();
        }
    }
}
