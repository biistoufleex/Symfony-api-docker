<?php

namespace App\Service\Application;

use App\constants\MessageConstants;
use App\Factory\EmailFactory;
use App\Factory\EmailInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private LoggerInterface $logger;
    private MailerInterface $mailer;
    private EmailFactory $emailFactory;

    public function __construct(
        LoggerInterface $logger,
        MailerInterface $mailer,
        EmailFactory $emailFactory
    )
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->emailFactory = $emailFactory;
    }

    /**
     * @throws Exception
     */
    public function sendEmail(string $from, string $to, string $subject, string $content): void
    {
        $this->logger->info('Sending email to ' . $to);

        $email = $this->emailFactory->createEmail(
            $from,
            $to,
            $subject,
            $content
        );

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new Exception(MessageConstants::ERROR_EMAIL_SENDING);
        }
    }
}