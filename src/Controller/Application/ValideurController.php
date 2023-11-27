<?php

namespace App\Controller\Application;

use App\Service\Application\DataTableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/valideur')]
class ValideurController extends AbstractController
{
    private DataTableService $validationService;

    public function __construct(DataTableService $validationService)
    {
        $this->validationService = $validationService;
    }

    #[Route('', name: 'app_valideur')]
    public function index(): Response
    {
        // TODO: controller droits user

        //TODO: rajouter du style
        return $this->render('valideur/valideur.html.twig', [
            'button_habilitation_mr005' => 'Gestion des habilitations MR-005',
            'button_autre_habilitation' => 'Gestion des autres habilitations',
        ]);
    }

    #[Route('/mr005', name: 'app_valideur_mr005')]
    public function mr005(Request $request): Response
    {
        $tableUnvalidated = $this->validationService->createValidationDataTable(false);
        if ($tableUnvalidated->handleRequest($request)->isCallback()) {
            return $tableUnvalidated->getResponse();
        }

        $tableValidated = $this->validationService->createValidationDataTable(true);
        if ($tableValidated->handleRequest($request)->isCallback()) {
            return $tableValidated->getResponse();
        }

        return $this->render('valideur/datatable_validation_mr005.html.twig', [
            'datatable_unvalidated' => $tableUnvalidated,
            'datatable_validated' => $tableValidated,
        ]);
    }

    #[Route('/mr005/demande/{id_demande}', name: 'app_valideur_mr005_demande')]
    public function mr005_demande($id_demande): Response
    {
        return $this->render('valideur/mr005_demande.html.twig', [
            'controller_name' => 'ValideurController',
            'id_demande' => $id_demande,
        ]);
    }

    #[Route('/fourniture', name: 'app_valideur_fourniture')]
    public function fourniture(): Response
    {
        return $this->render('valideur/fourniture.html.twig', [
            'controller_name' => 'ValideurController',
        ]);
    }
}
