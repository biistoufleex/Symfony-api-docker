<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailControllerTest extends WebTestCase
{
    public function testMailIsSentAndContentIsOk(): void
    {
        $client = static::createClient();
        $client->request('GET', '/mail/send');
        $this->assertResponseIsSuccessful();

        $this->assertEmailCount(1);

        $this->assertStringContainsString('ok', $client->getResponse()->getContent());
    }
}