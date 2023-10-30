<?php

namespace App\Controller\Api;

use App\Controller\Api\Http\Responses\Status;
use App\Service\Common\UtilisateurService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{
    private UtilisateurService $utilisateurService;

    public function __construct(UtilisateurService $utilisateurService)
    {
        $this->utilisateurService = $utilisateurService;
    }

    #[Route('/habilitation/{identifiant_plage_utilisateur}/{token}', name: 'app_habilitation', methods: ['GET'])]
    public function index(String $identifiant_plage_utilisateur, String $token): JsonResponse
    {
        $response = new JsonResponse();
        $response->setData([
            'identifiant_plage_utilisateur' => $identifiant_plage_utilisateur,
            'token' => $token,
        ]);
        return $response;
    }

    #[Route('/user/{id}', name: 'app_user', methods: ['GET'])]
    public function getUserInfo(String $id): JsonResponse
    {
        try {
            return $this->json($this->utilisateurService->getUserInfo($id));
        } catch (Exception $e) {
            return $this->json([
                'retour' => Status::error($e->getMessage())->toArray()
            ]);
        }
    }

    #[Route('/test', name: 'app_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json([
            'env' => $_ENV['ENV_FILE'],
            'database' => $_ENV['DATABASE_URL'],
            'test' => $_ENV['TEST']
        ]);
    }
}
