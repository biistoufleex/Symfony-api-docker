<?php

namespace App\DataFixtures\Api;

use App\Entity\Api\OrganisationAutorisation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class OrganisationAutorisationFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['identifiant_organisation_plage' => 'SYNERPA', 'date_debut' => '2023-01-01',
                'date_fin' => null, 'perimetre' => 'Finance', 'type_autorisation' => 'PDH'],

            ['identifiant_organisation_plage' => 'DGCS', 'date_debut' => '2023-02-01',
                'date_fin' => null, 'perimetre' => 'Médico-Social', 'type_autorisation' => 'Autorisation en propre'],

            ['identifiant_organisation_plage' => 'ATIH', 'date_debut' => '2023-03-01',
                'date_fin' => null, 'perimetre' => 'Finance', 'type_autorisation' => 'PDH'],

            ['identifiant_organisation_plage' => 'ATIH', 'date_debut' => '2023-03-01',
                'date_fin' => null, 'perimetre' => 'Médico-Social', 'type_autorisation' => 'Accs permanent'],

            ['identifiant_organisation_plage' => 'SYNERPA', 'date_debut' => '2023-04-01',
                'date_fin' => null, 'perimetre' => 'Ressources Humaines', 'type_autorisation' => 'PDH'],

            ['identifiant_organisation_plage' => 'DGCS', 'date_debut' => '2023-05-01',
                'date_fin' => null, 'perimetre' => 'Institution', 'type_autorisation' => 'Autorisation en propre'],

            ['identifiant_organisation_plage' => 'ATIH', 'date_debut' => '2023-06-01',
                'date_fin' => null, 'perimetre' => 'Institution', 'type_autorisation' => 'Finance'],

            ['identifiant_organisation_plage' => 'SYNERPA', 'date_debut' => '2023-07-01',
                'date_fin' => null, 'perimetre' => 'Ressources Humaines', 'type_autorisation' => 'Institution'],

            ['identifiant_organisation_plage' => 'DGCS', 'date_debut' => '2023-08-01',
                'date_fin' => null, 'perimetre' => 'Médico-Social', 'type_autorisation' => 'Accès permanent'],

            ['identifiant_organisation_plage' => 'ATIH', 'date_debut' => '2023-09-01',
                'date_fin' => null, 'perimetre' => 'Finance', 'type_autorisation' => 'PDH'],

            ['identifiant_organisation_plage' => 'SYNERPA', 'date_debut' => '2023-10-01',
                'date_fin' => null, 'perimetre' => 'Institution', 'type_autorisation' => 'Accès permanent'],

        ];

        foreach ($data as $row) {
            $organisationAutorisation = new OrganisationAutorisation();
            $organisationAutorisation->setIdentifiantOrganisationPlage($row['identifiant_organisation_plage']);
            $organisationAutorisation->setDateDebut(new DateTime($row['date_debut']));
            $organisationAutorisation->setDateFin($row['date_fin'] ? new DateTime($row['date_fin']) : null);
            $organisationAutorisation->setPerimetre($row['perimetre']);
            $organisationAutorisation->setTypeAutorisation($row['type_autorisation']);
            $manager->persist($organisationAutorisation);
        }
        $manager->flush();
    }
}
