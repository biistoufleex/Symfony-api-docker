<?php

namespace App\Tests\Application\Service;

use App\Service\Application\ApplicationMessageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApplicationMessageServiceTest extends KernelTestCase
{

    public function testGetMessageByUseCase()
    {
        $applicationMessageService = static::getContainer()->get(ApplicationMessageService::class);

        $useCase = 'recepice_found';

        $applicationMessage = $applicationMessageService->getMessageByUseCase($useCase);

        $this->assertNotNull($applicationMessage);
        $this->assertEquals($useCase, $applicationMessage->getUsecase());
        $this->assertEquals('/etablissement', $applicationMessage->getUri());
        $this->assertEquals('Consulter son récépissé MR-005', $applicationMessage->getMessage());
    }

    public function testGetMessageByUri()
    {
        $applicationMessageService = static::getContainer()->get(ApplicationMessageService::class);

        $uri = '/etablissement';

        $applicationMessages = $applicationMessageService->getMessageByUri($uri);

        $this->assertNotNull($applicationMessages);
        $this->assertCount(3, $applicationMessages);
        $this->assertEquals($uri, $applicationMessages[0]->getUri());
        $this->assertEquals('recepice_not_found', $applicationMessages[0]->getUsecase());
    }

    public function testGetEtablissementPageMessage()
    {
        $applicationMessageService = static::getContainer()->get(ApplicationMessageService::class);

        $buttonUseCase = 'recepice_found';

        $applicationMessages = $applicationMessageService->getEtablissementPageMessage($buttonUseCase);

        $this->assertNotNull($applicationMessages);
        $this->assertCount(2, $applicationMessages);
        $this->assertEquals('/etablissement', $applicationMessages['page']->getUri());
        $this->assertEquals('page_etablissement_message', $applicationMessages['page']->getUsecase());
        $this->assertEquals('/etablissement', $applicationMessages['button']->getUri());
        $this->assertEquals('recepice_found', $applicationMessages['button']->getUsecase());
    }
}