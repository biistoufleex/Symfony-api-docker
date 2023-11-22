<?php

namespace App\Security;

use App\Service\Common\UtilisateurService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PlageAuthenticator extends AbstractAuthenticator
{
    private LoggerInterface $logger;
    private ClientRegistry $clientRegistry;
    private UtilisateurService $utilisateurService;

    public function __construct(
        LoggerInterface $logger,
        ClientRegistry $clientRegistry,
        UtilisateurService $utilisateurService,
    )
    {
        $this->logger = $logger;
        $this->clientRegistry = $clientRegistry;
        $this->utilisateurService = $utilisateurService;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->query->has('code') && $request->query->has('state');
    }

    public function authenticate(Request $request): Passport
    {
        $this->logger->info('Authenticating with Plage');

        $provider = $this->clientRegistry
            ->getClient('plage_oauth')
            ->getOAuth2Provider();

        try {
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
        } catch (IdentityProviderException $e) {
            dd($e);
        }

        /** @var PlageResourceOwner $resourceOwner */
        $resourceOwner = $provider->getResourceOwner($accessToken);

        $userIdentifier = $resourceOwner->getId();

//        $user = new UserBadge($userIdentifier, function () use ($userIdentifier) {
//            return $this->utilisateurService->getUserInfo($userIdentifier);
//        });

        $user = new UserBadge($userIdentifier);
        return new SelfValidatingPassport($user);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $this->logger->error('Authentication failed', [
            'exception' => $exception,
        ]);

        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getBaseAuthorizationUrl(): string
    {
        return "https://connect-pasrel.atih.sante.fr/cas/oidc/authorize?client_id=gestauth&response_type=code&state=l0JieRgestauth&scope=profile_atih";
    }
}