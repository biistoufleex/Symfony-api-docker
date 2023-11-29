<?php

namespace App\Controller\Application;

use App\Service\Application\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
 * Class crée pour tester l'envoi de mail
 */
#[Route('/mail', name: 'app_mail')]
class MailerController extends AbstractController
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/send', name: 'send')]
    public function sendEmail(): Response
    {
        try {
            $this->emailService->sendEmail(
                'from@mail.fr',
                'to@mail.fr',
                'subject',
            );
        } catch (\Exception $e) {
            return new Response('ko');
        }
        return new Response('ok');
    }
}