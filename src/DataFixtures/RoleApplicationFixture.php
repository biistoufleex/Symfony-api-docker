<?php

namespace App\DataFixtures;

use App\Entity\RoleApplication;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleApplicationFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roleApplication = new RoleApplication();
        $roleApplication->setRoleApplication('Lecteur Institution Médico-Social');
        $roleApplication->setHabilitationDomainePerimetre('Médico-Social');
        $roleApplication->setHabilitationOrganisationPerimetre('Institution');
        $manager->persist($roleApplication);

        $roleApplication = new RoleApplication();
        $roleApplication->setRoleApplication('Lecteur institution Finance');
        $roleApplication->setHabilitationDomainePerimetre('Finance');
        $roleApplication->setHabilitationOrganisationPerimetre('Institution');
        $manager->persist($roleApplication);

        $roleApplication = new RoleApplication();
        $roleApplication->setRoleApplication('Lecteur Test');
        $roleApplication->setHabilitationDomainePerimetre('Test');
        $roleApplication->setHabilitationOrganisationPerimetre('Test');
        $manager->persist($roleApplication);

        $manager->flush();
    }
}
