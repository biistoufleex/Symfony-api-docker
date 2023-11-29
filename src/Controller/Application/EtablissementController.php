<?php

namespace App\Controller\Application;

use App\constants\MessageConstants;
use App\Form\Entity\DepotMr005Form;
use App\Form\Type\DepotMr005Type;
use App\Service\Application\AmazonS3Service;
use App\Service\Application\ApplicationMessageService;
use App\Service\Application\DepotMr005Service;
use App\Service\Application\DepotMr005FormulaireService;
use App\Service\Application\EmailService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/etablissement', name: 'app_etablissement')]
class EtablissementController extends AbstractController
{
    private const DEPOT_MR_005 = 'depot_mr005';
    private const IPE = 'ipe';
    private const FINESS = 'finess';
    private const RAISON_SOCIALE = 'raisonSociale';
    private const MESSAGE = 'message';
    private const FORM = 'form';
    private const MESSAGE_DEPOT_RECEPICE = 'message_depot_recepice';
    private const COURRIEL = 'courriel';
    private const BUTTON = 'button';
    private const PAGE = 'page';
    private const RECEPICE = 'recepice';
    private const READONLY = 'readonly';
    private const IPE_FAKE = '000000022';
    const DEMANDE_NOT_FOUND = 'La demande n\'existe pas ou n\'est pas accessible.';
    private ApplicationMessageService $applicationMessageService;
    private DepotMr005Service $depotMr005Service;
    private EmailService $emailService;
    private LoggerInterface $logger;
    private DepotMr005FormulaireService $depotMr005FormulaireService;
    private AmazonS3Service $amazonS3Service;

    public function __construct(
        LoggerInterface             $logger,
        ApplicationMessageService   $applicationMessageService,
        DepotMr005Service           $depotMr005Service,
        EmailService                $emailService,
        DepotMr005FormulaireService $depotMr005FormulaireService,
        AmazonS3Service             $amazonS3Service,
    )
    {
        $this->logger = $logger;
        $this->applicationMessageService = $applicationMessageService;
        $this->depotMr005Service = $depotMr005Service;
        $this->emailService = $emailService;
        $this->depotMr005FormulaireService = $depotMr005FormulaireService;
        $this->amazonS3Service = $amazonS3Service;
    }

    #[Route('/', name: 'etablissement_index')]
    public function index(Security $security, Request $request): Response
    {
        $ipe = self::IPE_FAKE; // TODO: get from token

        $receipe = $this->depotMr005Service->getOneRecepiceByIpe($ipe);

        $usecase = $receipe ? 'recepice_found' : 'recepice_not_found';

        try {
            $message = $this->applicationMessageService->getEtablissementPageMessage($usecase);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $message = [
                self::PAGE => MessageConstants::PROBLEME_RECUPERATION_MESSAGE,
                self::BUTTON => MessageConstants::PROBLEME_RECUPERATION_MESSAGE,
            ];
        }

        $receipe = $receipe?->getDepotMr005Formulaire()->getNumeroRecepice();
        return $this->render('etablissement/etablissement.html.twig', [
            self::MESSAGE => $message[self::PAGE]->getMessage(),
            self::BUTTON => $message[self::BUTTON]->getMessage(),
            'usecase' => $usecase,
            self::RECEPICE => $receipe,
        ]);

    }

    #[Route('/depot_mr005', name: self::DEPOT_MR_005, methods: ['GET', 'POST'])]
    public function depotMr005(Request $request): Response
    {
        $ipe = random_int(100000000, 999999999); // TODO: get from token
//        $ipe = '000000001';
        $finess = '000000111'; // TODO: get from token
        $raisonSociale = 'test'; // TODO: get from token

        $message = $this->applicationMessageService->getStringMessageByUseCase(self::MESSAGE_DEPOT_RECEPICE);


        $form = $this->createForm(DepotMr005Type::class, new DepotMr005Form(), ['disabled' => false]);
        $readonly = false;

        # Si le formulaire a déjà été déposé, on l'affiche en lecture seule
        $formData = $this->depotMr005FormulaireService->getDepotMr005FormulaireByIpe($ipe);
        if ($formData) {
            $formtype = null;
            try {
                $formtype = $this->depotMr005FormulaireService->toDepotForm($formData);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                return $this->render('errors.html.twig',
                    ['message' => self::DEMANDE_NOT_FOUND]);
            }

            $form = $this->createForm(DepotMr005Type::class, $formtype, ['disabled' => true]);
            $readonly = true;
        }

        if ($request->isMethod('POST')) {
            $allValues = $request->request->all();
            $form->submit($allValues[$form->getName()]);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $request->files->all();
                $data = $request->request->all();

                # Override data with token data
                $data[self::DEPOT_MR_005][self::IPE] = $ipe;
                $data[self::DEPOT_MR_005][self::FINESS] = $finess;
                $data[self::DEPOT_MR_005][self::RAISON_SOCIALE] = $raisonSociale;

                $this->amazonS3Service->saveFileInS3($data, $file);

                $formData = $this->depotMr005FormulaireService->saveFormData($data, $file);

                $idPlage = '123123123'; // TODO: get from token
                $this->depotMr005Service->saveDepot($formData, $idPlage);

                $this->emailService->sendEmailTo($data[self::DEPOT_MR_005][self::COURRIEL]);

                return $this->redirect('/etablissement/depot_mr005');
            }
        }

        return $this->render('etablissement/depotRecepice.html.twig', [
            self::MESSAGE => $message,
            self::FORM => $form->createView(),
            self::IPE => $ipe,
            self::FINESS => $finess,
            self::RAISON_SOCIALE => $raisonSociale,
            self::READONLY => $readonly,
        ]);
    }
}