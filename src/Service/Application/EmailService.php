<?php

namespace App\Service\Application;

use App\constants\MessageConstants;
use App\Factory\EmailFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private LoggerInterface $logger;
    private MailerInterface $mailer;
    private EmailFactory $emailFactory;
    private string $emailApplication;

    public function __construct(
        LoggerInterface $logger,
        MailerInterface $mailer,
        EmailFactory    $emailFactory,
        string          $emailApplication
    )
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->emailFactory = $emailFactory;
        $this->emailApplication = $emailApplication;
    }

    public function sendEmailTo(string $responsable): void
    {
        try {
            $this->sendEmail(
                $this->emailApplication,
                $responsable,
                MessageConstants::EMAIL_SUBJECT_DEPOT,
                MessageConstants::EMAIL_BODY_DEPOT
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
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