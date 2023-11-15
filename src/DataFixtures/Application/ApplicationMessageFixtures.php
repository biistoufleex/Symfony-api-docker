<?php

namespace App\DataFixtures\Application;

use App\Entity\Application\ApplicationMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationMessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['useCase' => 'recepice_not_found', 'uri' => '/etablissement', 'message' => 'Déposer un récépissé MR-005'],
            ['useCase' => 'recepice_found', 'uri' => '/etablissement', 'message' => 'Consulter son récépissé MR-005'],
            ['useCase' => 'acces_interdit', 'uri' => '/acces_interdit',
                'message' => 'Votre profil Plage ne vous permet pas d’accéder à cet application. 
                              Il faut être Administrateur Plage de votre établissement pour y accéder'
            ],
            ['useCase' => 'page_etablissement_message', 'uri' => '/etablissement', 'message' => 'Texte From Bdd'],
            ['useCase' => 'message_depot_recepice', 'uri' => '/etablissement/depot_mr005',
                'message' => '<p>L\'accès aux données détaillées d\'activité par vos utilisateurs 
                    nécessite la <strong>déclaration de conformité de votre organisme à la 
                    <a href="https://www.atih.sante.fr/sites/default/files/public/content/2941/mode_demploi_-_mr-005.pdf">
                       Méthodologie de référence MR-005</a>
                    </strong>.
                    Le <u>récépissé de la MR005</u> est à envoyer à l\'ATIH via le formulaire ci-dessous. 
                    Si vous n\'avez pas effectué cette déclaration de conformité, vous pouvez en faire la demande 
                    <a href="https://www.atih.sante.fr/sites/default/files/public/content/2941/mode_demploi_-_mr-005.pdf">ici</a>.
                    </p>']
        ];

        foreach ($data as $row) {
            $messageApplication = new ApplicationMessage();
            $messageApplication->setUsecase($row['useCase']);
            $messageApplication->setUri($row['uri']);
            $messageApplication->setMessage($row['message']);

            $manager->persist($messageApplication);
        }
        $manager->flush();
    }
}
