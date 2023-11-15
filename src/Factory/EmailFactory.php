<?php

namespace App\Factory;

use Symfony\Component\Mime\Email;

class EmailFactory implements EmailInterface
{

    public function createEmail(string $from, string $to, string $subject, string $content): Email
    {
        return (new Email())
            ->from($from)
            ->to($to)
            ->subject(htmlentities($subject))
            ->html(sprintf('<p>%s</p>', htmlentities($content)));
    }
}