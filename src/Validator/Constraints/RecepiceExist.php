<?php

namespace App\Validator\Constraints;

use App\Service\Application\DepotMr005FormulaireService;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class RecepiceExist extends Constraint
{
    public string $message = 'Le numero de recepice "{{ string }}" existe deja.';
    public DepotMr005FormulaireService $depotMr005FormulaireService;
    #[HasNamedArguments]
    public function __construct(
        DepotMr005FormulaireService $depotMr005FormulaireService,
        array                       $groups = null,
        mixed                       $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
        $this->depotMr005FormulaireService = $depotMr005FormulaireService;
    }
}