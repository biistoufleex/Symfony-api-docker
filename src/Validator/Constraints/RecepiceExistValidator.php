<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RecepiceExistValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof RecepiceExist) {
            throw new UnexpectedTypeException($constraint, RecepiceExist::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$constraint->depotMr005ValidationService->existByRecepice($value)) {
            return;
        }

        // the argument must be a string or an object implementing __toString()
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}