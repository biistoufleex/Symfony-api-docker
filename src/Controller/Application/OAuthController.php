<?php

namespace App\Controller\Application;

use App\Security\PlageResourceOwner;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/oauth')]
class OAuthController extends AbstractController
{
    #[Route('/login', name: 'oauth_login')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('plage_oauth') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(["openid", "profile", "email", "address", "phone", "profile_atih"]); // the scopes you want to access
    }

    #[Route('/token', name: 'oauth_token')]
    public function getToken(): RedirectResponse
    {
        return $this->redirect("/etablissement");
    }

    #[Route('/logout', name: 'oauth_logout')]
    public function logoutAction(Security $security): RedirectResponse
    {
        $security->logout(false);

        // TODO: change ???
        return $this->redirect("https://connect-pasrel.atih.sante.fr/cas/oidc/oidcLogout");
    }
}
