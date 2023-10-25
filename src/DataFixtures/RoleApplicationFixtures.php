<?php

namespace App\DataFixtures;

use App\Entity\RoleApplication;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleApplicationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['role_application' => 'Lecteur Médico-Social', 'habilitation_organisation_perimetre' => 'Médico-Social',
                'habilitation_domaine_perimetre' => 'Médico-Social'],

            ['role_application' => 'Lecteur Fincances', 'habilitation_organisation_perimetre' => 'Finance',
                'habilitation_domaine_perimetre' => 'Médico-Social'],

            ['role_application' => 'Lecteur Institution', 'habilitation_organisation_perimetre' => 'Institution',
                'habilitation_domaine_perimetre' => 'Médico-Social'],

            ['role_application' => 'Lecteur Institution-finance', 'habilitation_organisation_perimetre' => 'Institution',
                'habilitation_domaine_perimetre' => 'Finances'],

            ['role_application' => 'Lecteur RH-Institution', 'habilitation_organisation_perimetre' => 'Institution',
                'habilitation_domaine_perimetre' => 'RH'],
        ];

        foreach ($data as $row) {
            $roleApplication = new RoleApplication();
            $roleApplication->setRoleApplication($row['role_application']);
            $roleApplication->setHabilitationOrganisationPerimetre($row['habilitation_organisation_perimetre']);
            $roleApplication->setHabilitationDomainePerimetre($row['habilitation_domaine_perimetre']);

            $manager->persist($roleApplication);
        }
        $manager->flush();
    }
}
