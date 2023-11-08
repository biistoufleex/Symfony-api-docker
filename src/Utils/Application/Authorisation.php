<?php

namespace App\Utils\Application;

class Authorisation
{
    public function checkAccess(array $token, string $niveau, string $role, string $domaine): bool
    {
        return $token['niveau'] === $niveau && in_array($role, $token['roles']) && $token['domaine'] === $domaine;
    }
}