<?php

namespace App\Service;

use App\constants\MessageConstants;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

class UtilisateurService
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * Get user information from the Devel Plage InfoService API in XML format.
     *
     * This method retrieves user information from the Devel Plage InfoService API based on the provided user ID.
     * It queries the API and returns the information in XML format.
     *
     * @param string $idUser The user ID for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the user information in XML format
     *                            or null if there is an issue with the InfoService API communication.
     *
     * @throws \Exception If there is a problem with the InfoService API communication or if the API returns an exception,
     *                    an exception is thrown, and the issue is logged with details.
     */
    public function getDevelXml(String $idUser): ?SimpleXMLElement
    {
        $plageXml = $this->entityManager->getRepository(Utilisateur::class)->getDevelPlageXml($idUser);

        if ($plageXml === null) {
            $this->logger->error(MessageConstants::PROBLEME_COMMUNICATION_INFOSERVICE_USER, ['idUser' => $idUser]);
            throw new \Exception(MessageConstants::PROBLEME_COMMUNICATION_INFOSERVICE_USER);
        } else if ($plageXml->exception) {
            $this->logger->error($plageXml->exception->libelle, ['idUser' => $idUser]);
            throw new \Exception($plageXml->exception->libelle);
        }

        return $plageXml;
    }
}