<?php

namespace App\Tests\Api\Service;

use App\Service\Api\OrganisationAutorisationService;
use App\Tests\Api\data\fakeObject\FakeHabilitationsOrganisations;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrganisationAutorisationServiceTest extends KernelTestCase
{
    public function testParseOrganisationAutorisation(): void
    {
        $organisationAutorisationService = static::getContainer()->get(OrganisationAutorisationService::class);

        $organisationAutorisation = [
            new FakeHabilitationsOrganisations(
                new DateTime('2023-10-18'),
                new DateTime('2023-10-28'),
                'etablissement',
                'ATIH'
            ),
            new FakeHabilitationsOrganisations(
                new DateTime('2023-10-18'),
                new DateTime('2023-10-28'),
                'etablissement',
                'ADMIN'
            ),
            new FakeHabilitationsOrganisations(
                new DateTime('2023-10-18'),
                new DateTime('2023-10-28'),
                'etablissement',
                'OTHER'
            ),
        ];

        $habilitationsOrganisations = $organisationAutorisationService->parseOrganisationAutorisation($organisationAutorisation);

        $this->assertNotNull($habilitationsOrganisations);
        $this->assertIsArray($habilitationsOrganisations);
        $this->assertCount(3, $habilitationsOrganisations);
        $this->assertContains([
            'date_debut' => '18/10/2023',
            'date_fin' => '28/10/2023',
            'perimetre' => 'etablissement',
            'type_autorisation' => 'ATIH',
        ], $habilitationsOrganisations);
        $this->assertContains([
            'date_debut' => '18/10/2023',
            'date_fin' => '28/10/2023',
            'perimetre' => 'etablissement',
            'type_autorisation' => 'ADMIN',
        ], $habilitationsOrganisations);
        $this->assertContains([
            'date_debut' => '18/10/2023',
            'date_fin' => '28/10/2023',
            'perimetre' => 'etablissement',
            'type_autorisation' => 'OTHER',
        ], $habilitationsOrganisations);
    }
}
