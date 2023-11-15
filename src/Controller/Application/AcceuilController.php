<?php

namespace App\Controller\Application;

use App\Utils\Application\Authorisation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/acceuil', name: 'app_acceuil')]
class AcceuilController extends AbstractController
{
    #[Route('/', name: 'acceuil_index')]
    public function index(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if (!$session->has('token')) {
            $token = json_encode([
                'niveau' => 'Etablissement',
                'roles' => ['ROLE_USER', 'Etablissement'],
                'domaine' => 'Administration Plage',
            ]);
            $session->set('token', $token);
        }

//        dd($this->authorisation->checkAccess(
//            json_decode($session->get('token'), true),
//            'Etablissement',
//            'ROLE_USER',
//            'Administration Plage')
//        );

        if ($session->has('token')) {
            $tokenArray = json_decode($session->get('token'), true);
            if (in_array('Etablissement', $tokenArray['roles'])) {
                return $this->redirect('/etablissement');
            } else {
                return $this->redirect('/valideur');
            }
        }

        return $this->render('acceuil/acceuil.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
}
