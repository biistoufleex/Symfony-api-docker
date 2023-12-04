<?php

namespace App\Controller\Application;

use App\Form\Type\DepotMr005Type;
use App\Service\Application\AmazonS3Service;
use App\Service\Application\ApplicationMessageService;
use App\Service\Application\DataTableService;
use App\Service\Application\DepotMr005FormulaireService;
use App\Service\Application\EmailService;
use App\Service\Common\OrganisationAutorisationService;
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
    private const READONLY = 'readonly';
    private const MESSAGE = 'message';
    private const DEMANDE_NOT_FOUND = 'La demande n\'existe pas ou n\'est pas accessible.';
    private LoggerInterface $logger;
    private DataTableService $validationService;
    private DepotMr005FormulaireService $depotMr005FormulaireService;
    private AmazonS3Service $amazonS3Service;
    private EmailService $emailService;
    private OrganisationAutorisationService $organisationAutorisationService;
    private ApplicationMessageService $applicationMessageService;

    public function __construct(
        LoggerInterface                 $logger,
        DataTableService                $validationService,
        DepotMr005FormulaireService     $depotMr005FormulaireService,
        AmazonS3Service                 $amazonS3Service,
        EmailService                    $emailService,
        OrganisationAutorisationService $organisationAutorisationService,
        ApplicationMessageService       $applicationMessageService,
    )
    {
        $this->logger = $logger;
        $this->validationService = $validationService;
        $this->depotMr005FormulaireService = $depotMr005FormulaireService;
        $this->amazonS3Service = $amazonS3Service;
        $this->emailService = $emailService;
        $this->organisationAutorisationService = $organisationAutorisationService;
        $this->applicationMessageService = $applicationMessageService;
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
            $this->logger->error(self::DEMANDE_NOT_FOUND);
            return $this->render('errors.html.twig',
                [self::MESSAGE => self::DEMANDE_NOT_FOUND]);
        }

        try {
            $filepath = $this->amazonS3Service->downloadFile($formData->getNumeroRecepice(), $formData->getFilePath());
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $formtype = null;
        try {
            $formtype = $this->depotMr005FormulaireService->toDepotForm($formData);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->render('errors.html.twig',
                [self::MESSAGE => self::DEMANDE_NOT_FOUND]);
        }

        $disabled = $formData->getDepotMr005()->isValidated();
        $form = $this->createForm(DepotMr005Type::class, $formtype,
            ['disabled' => $disabled, 'method' => 'PUT']);

        $buttonDowloadFile = [
            'label' => 'Télécharger le fichier',
            'filename' => basename($filepath),
        ];

        if ($request->isMethod("POST")) {
            $allValues = $request->request->all();
            $form->submit($allValues[$form->getName()]);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $request->files->all();
                $data = $request->request->all();

                try {
                    $this->depotMr005FormulaireService->updateAndValidateDepotMr005Formulaire($formData, $data, $file);
                } catch (Exception $e) {
                    $this->logger->error($e->getMessage());
                    return $this->render('errors.html.twig',
                        [self::MESSAGE => "Une erreur est survenue lors de la validation de la demande."]);
                }

                # update le fichier dans le S3
                $this->amazonS3Service->deleteFileFromS3($formData->getNumeroRecepice(), basename($filepath));
                $this->amazonS3Service->saveFileInS3($data, $file);

                try {
                    $this->emailService->sendEmail(
                        $formData->getDepotMr005()->getCourriel(),
                        "Validation demande MR005",
                        "Votre demande de validation de récépissé MR005 a été validée.
                         Votre numéro de récépissé est le suivant: " . $formData->getNumeroRecepice() . "."
                    );
                } catch (Exception $e) {
                    $this->logger->error($e->getMessage());
                }

                try {
                    $this->organisationAutorisationService->createOrganisationAutorisation($data);
                } catch (Exception $e) {
                    $this->logger->error($e->getMessage());
                    $this->depotMr005FormulaireService->unvalidDepotForm($formData->getNumeroRecepice());
                    return $this->render('errors.html.twig',
                        [self::MESSAGE => "Une erreur est survenue lors de la création de l'habilitation, 
                            veuillez revalider le récépissé. <a href='/valideur/mr005/demande/" . $id_demande . "'>Revalider</a>"
                        ]);
                }

                return $this->redirect('/valideur/mr005/');
            }
        }

        return $this->render('etablissement/depotRecepice.html.twig', [
            self::MESSAGE => $message,
            self::FORM => $form->createView(),
            self::IPE => $formData->getIpe(),
            self::FINESS => $formData->getFiness(),
            self::RAISON_SOCIALE => $formData->getRaisonSociale(),
            self::READONLY => $disabled,
            'button_dowload_file' => $buttonDowloadFile,
        ]);
    }
}
