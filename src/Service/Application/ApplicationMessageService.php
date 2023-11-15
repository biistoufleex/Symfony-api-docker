<?php

namespace App\Service\Application;

use App\constants\MessageConstants;
use App\Entity\Application\ApplicationMessage;
use App\Repository\Application\ApplicationMessageRepository;
use Exception;
use Psr\Log\LoggerInterface;

class ApplicationMessageService
{
    private LoggerInterface $logger;
    private ApplicationMessageRepository $applicationMessageRepository;

    public function __construct(
        LoggerInterface $logger,
        ApplicationMessageRepository $applicationMessageRepository,
    ) {
        $this->logger = $logger;
        $this->applicationMessageRepository = $applicationMessageRepository;
    }

    /**
     * @throws Exception
     */
    public  function getMessageByUseCase(string $useCase) : ?ApplicationMessage
    {
        $applicationMessage = $this->applicationMessageRepository->findOneBy(['usecase' => $useCase]);
        if ($applicationMessage === null) {
            $this->logger->error(MessageConstants::PROBLEME_AUCUN_MESSAGE_USECASE . $useCase, ['useCase' => $useCase]);
            throw new Exception(MessageConstants::PROBLEME_AUCUN_MESSAGE_USECASE . $useCase);
        }
        return $applicationMessage;
    }

    public  function getStringMessageByUseCase(string $useCase) : string
    {
        $applicationMessage = $this->applicationMessageRepository->findOneBy(['usecase' => $useCase]);
        if ($applicationMessage === null) {
            $this->logger->error(MessageConstants::PROBLEME_AUCUN_MESSAGE_USECASE . $useCase, ['useCase' => $useCase]);
            return MessageConstants::PROBLEME_RECUPERATION_MESSAGE;
        }
        return $applicationMessage->getMessage();
    }

    /**
     * @throws Exception
     * @return array<string, mixed>
     */
    public function getMessageByUri(string $uri) : array
    {
        $message = $this->applicationMessageRepository->findBy(['uri' => $uri]);
        if (!$message) {
            $this->logger->error(MessageConstants::PROBLEME_AUCUN_MESSAGE_URI . $uri, ['uri' => $uri]);
            throw new Exception(MessageConstants::PROBLEME_AUCUN_MESSAGE_URI . $uri);
        }
        return $message;
    }

    /**
     * @throws Exception
     * @return array<string, mixed>
     */
    public function getEtablissementPageMessage(string $buttonUseCase): array
    {
        $messages = [];
        $messages['button'] = $this->getMessageByUseCase($buttonUseCase);
        $messages['page'] = $this->getMessageByUseCase('page_etablissement_message');

        return $messages;
    }
}
