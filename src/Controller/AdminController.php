<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Http\Responses\Status;
use App\Service\AdminService;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{
    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
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
            return $this->json($this->adminService->getUserInfo($id));
        } catch (\Exception $e) {
            return $this->json([
                'retour' => Status::error($e->getMessage())->toArray()
            ]);
        }
    }
}
