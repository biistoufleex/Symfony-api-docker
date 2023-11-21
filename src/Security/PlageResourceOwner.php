<?php

namespace App\Security;

use League\OAuth2\Client\Provider\GenericResourceOwner;

class PlageResourceOwner extends GenericResourceOwner
{
    private array $attributes;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        parent::__construct($response, 'id');
        $this->attributes = $response['attributes'];
    }

    public function getAdresse(): string
    {
        return $this->attributes['address'];
    }

    public function getEmail(): string
    {
        return $this->attributes['email'];
    }

    public function isEmailVerified(): bool
    {
        return $this->attributes['email_verified'];
    }

    public function getNom(): string
    {
        return $this->attributes['family_name'];
    }

    public function getPrenom(): string
    {
        return $this->attributes['given_name'];
    }

    public function getTelephone(): string
    {
        return $this->attributes['phone_number'];
    }

    public function isTelephoneVerified(): bool
    {
        return $this->attributes['phone_number_verified'];
    }

    public function getOAuthClientId(): string
    {
        return $this->attributes['oauthClientId'];
    }

    // TODO: map roles ...
}