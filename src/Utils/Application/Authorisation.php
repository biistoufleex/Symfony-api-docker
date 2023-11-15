<?php

namespace App\Utils\Application;

class Authorisation
{
    /**
     * @param array<string, mixed> $token
     * @param string $niveau
     * @param string $role
     * @param string $domaine
     * @return bool
     */
    public function checkAccess(array $token, string $niveau, string $role, string $domaine): bool
    {
        return $token['niveau'] === $niveau && in_array($role, $token['roles']) && $token['domaine'] === $domaine;
    }
}