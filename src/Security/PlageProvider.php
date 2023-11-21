<?php

namespace App\Security;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class PlageProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return "https://connect-pasrel.atih.sante.fr/cas/oidc/authorize?client_id=gestauth&response_type=code&state=l0JieRgestauth&scope=profile_atih";
    }

    /**
     * @param array<string, mixed> $params
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return "https://connect-pasrel.atih.sante.fr/cas/oidc/oidcAccessToken";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return "https://connect-pasrel.atih.sante.fr/cas/oidc/oidcProfile";
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ["public"];
    }

    protected function getScopeSeparator(): string
    {
        return " ";
    }

    /**
     * @param array<string, mixed>|string $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() !== 200) {
            $errorDescription = $response->getReasonPhrase();
            $error = '';
            if (\is_array($data) && !empty($data)) {
                $error = $data['error'];
            }
            throw new IdentityProviderException(
                sprintf("%d - %s: %s", $response->getStatusCode(), $error, $errorDescription),
                $response->getStatusCode(),
                $data
            );
        }
    }

    /**
     * @param array<string, mixed> $response
     */
    protected function createResourceOwner(array $response, AccessToken $token): PlageResourceOwner
    {
        return new PlageResourceOwner($response);
    }

}