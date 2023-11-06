<?php

namespace App\Factory;

use Symfony\Component\Mime\Email;

interface EmailInterface
{
    public function createEmail(string $from, string $to, string $subject, string $content): Email;
}