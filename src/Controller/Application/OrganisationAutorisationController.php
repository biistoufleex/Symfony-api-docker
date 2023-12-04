<?php

namespace App\Controller\Application;

use App\Form\Type\OrganisationAutorisationType;
use App\Service\Application\DataTableService;
use App\Service\Common\OrganisationAutorisationService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/habilitation')]
class OrganisationAutorisationController extends AbstractController
{
    const MESSAGE = 'message';
    private LoggerInterface $logger;
    private DataTableService $dataTableService;
    private OrganisationAutorisationService $organisationAutorisationService;

    public function __construct(
        LoggerInterface $logger,
        DataTableService $dataTableService,
        OrganisationAutorisationService $organisationAutorisationService
    )
    {
        $this->logger = $logger;
        $this->dataTableService = $dataTableService;
        $this->organisationAutorisationService = $organisationAutorisationService;
    }

    #[Route('/', name: 'app_organisation_autorisation')]
    public function index(Request $request): Response
    {
        $dataTable = $this->dataTableService->getOrganisationAutorisationDataTable();
        if ($dataTable->handleRequest($request)->isCallback()) {
            return $dataTable->getResponse();
        }

        return $this->render('organisation_autorisation/index.html.twig', [
            'datatable' => $dataTable,
        ]);
    }

    #[Route('/update/{id}', name: 'app_organisation_autorisation_update')]
    public function update(string $id, Request $request): Response
    {
        $this->logger->info('Update habilitation organisation', ['id' => $id]);

        $organisationAutorisation = $this->organisationAutorisationService->getOrganisationAutorisationById($id);
        if (empty($organisationAutorisation)) {
            return $this->render('errors.html.twig',
                [self::MESSAGE => 'L\'habilitation n\'existe pas ou n\'est pas accessible.']);
        }

        $form = $this->createForm(OrganisationAutorisationType::class, $organisationAutorisation);

        if ($request->isMethod('POST')) {
            $allValues = $request->request->all();
            $form->submit($allValues[$form->getName()]);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $request->request->all();
                try {
                    $this->organisationAutorisationService->updateOrganisationAutorisation($id, $data['organisation_autorisation']);
                } catch (Exception $e) {
                    return $this->render('errors.html.twig',
                        [self::MESSAGE => $e->getMessage()]);
                }

                $this->addFlash('success', 'L\'habilitation a bien été modifiée.');
                return $this->redirectToRoute('app_organisation_autorisation');
            }
        }

        return $this->render('organisation_autorisation/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_organisation_autorisation_delete')]
    public function delete(string $id): Response
    {
        $this->logger->info('Delete habilitation organisation', ['id' => $id]);
        try {
            $this->organisationAutorisationService->deleteOrganisationAutorisation($id);
        } catch (Exception $e) {
            return $this->render('errors.html.twig',
                [self::MESSAGE => $e->getMessage()]);
        }

        $this->addFlash('success', 'L\'habilitation a bien été supprimée.');
        return $this->redirectToRoute('app_organisation_autorisation');
    }
}