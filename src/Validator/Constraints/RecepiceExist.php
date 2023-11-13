<?php

namespace App\Validator\Constraints;

use App\Service\Application\DepotMr005ValidationService;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class RecepiceExist extends Constraint
{
    public string $message = 'Le numero de recepice "{{ string }}" existe deja.';
    public DepotMr005ValidationService $depotMr005ValidationService;
    #[HasNamedArguments]
    public function __construct(
        DepotMr005ValidationService $depotMr005ValidationService,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
        $this->depotMr005ValidationService = $depotMr005ValidationService;
    }
}