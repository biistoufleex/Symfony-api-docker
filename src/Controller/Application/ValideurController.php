<?php

namespace App\Controller\Application;

use App\Form\Type\DepotMr005Type;
use App\Service\Api\OrganisationAutorisationService;
use App\Service\Application\AmazonS3Service;
use App\Service\Application\ApplicationMessageService;
use App\Service\Application\DataTableService;
use App\Service\Application\DepotMr005FormulaireService;
use App\Service\Application\DepotMr005Service;
use App\Service\Application\EmailService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/valideur')]
class ValideurController extends AbstractController
{
    private const IPE = 'ipe';
    private const FINESS = 'finess';
    private const RAISON_SOCIALE = 'raisonSociale';
    private const FORM = 'form';
    private const COURRIEL = 'courriel';
    private const READONLY = 'readonly';
    const MESSAGE = 'message';

    private LoggerInterface $logger;
    private DataTableService $validationService;
    private DepotMr005FormulaireService $depotMr005FormulaireService;
    private DepotMr005Service $depotMr005Service;
    private AmazonS3Service $amazonS3Service;
    private EmailService $emailService;
    private OrganisationAutorisationService $organisationAutorisationService;
    private ApplicationMessageService $applicationMessageService;

    public function __construct(
        LoggerInterface                 $logger,
        DataTableService                $validationService,
        DepotMr005FormulaireService     $depotMr005FormulaireService,
        DepotMr005Service               $depotMr005Service,
        AmazonS3Service                 $amazonS3Service,
        EmailService                    $emailService,
        OrganisationAutorisationService $organisationAutorisationService,
        ApplicationMessageService       $applicationMessageService,
        )
    {
        $this->logger                          = $logger;
        $this->validationService               = $validationService;
        $this->depotMr005FormulaireService     = $depotMr005FormulaireService;
        $this->depotMr005Service               = $depotMr005Service;
        $this->amazonS3Service                 = $amazonS3Service;
        $this->emailService                    = $emailService;
        $this->organisationAutorisationService = $organisationAutorisationService;
        $this->applicationMessageService       = $applicationMessageService;
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
    public function mr005_demande(Request $request, $id_demande): Response
    {
        $filepath = null;

        $message = $this->applicationMessageService->getStringMessageByUseCase('validation_depot');

        $formData = $this->depotMr005FormulaireService->getDepotMr005FormulaireById($id_demande);
        if (!$formData) {
            return $this->render('errors.html.twig',
                ['message' => 'La demande n\'existe pas ou n\'est pas accessible.']);
        }

        try {
            $filepath = $this->amazonS3Service->getFileFromS3($formData->getNumeroRecepice(), $formData->getFilePath());
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $form = $this->createForm(DepotMr005Type::class, $formData, ['disabled' => true]);

        // rajouter bouton de validation et de modification
        $validationButton = true;
        $modificationButton = true;

        // rajouter un bouton en logo d'oeil qui fait aparaitre le fichiers dans une modale si il a ete correctement dl
        // check filepath dans le twig

        return $this->render('etablissement/depotRecepice.html.twig', [
            self::MESSAGE => $message,
            self::FORM => $form->createView(),
            self::IPE => $formData->getIpe(),
            self::FINESS => $formData->getFiness(),
            self::RAISON_SOCIALE => $formData->getRaisonSociale(),
            self::READONLY => true,
        ]);
    }

    // TODO: rajouter route update
}
