<?php

namespace App\Controller\Application;

use App\constants\MessageConstants;
use App\Form\Entity\DepotMr005Form;
use App\Form\Type\DepotMr005Type;
use App\Form\Type\ShowMr005Type;
use App\Mapper\Application\DepotMr005ValidationMapper;
use App\Service\Application\AmazonS3Service;
use App\Service\Application\ApplicationMessageService;
use App\Service\Application\DepotMr005Service;
use App\Service\Application\DepotMr005ValidationService;
use App\Service\Application\EmailService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etablissement', name: 'app_etablissement')]
class EtablissementController extends AbstractController
{
    private ApplicationMessageService $applicationMessageService;
    private DepotMr005Service $depotMr005Service;
    private EmailService $emailService;
    private LoggerInterface $logger;
    private String $emailApplication;
    private DepotMr005ValidationMapper $depotMr005ValidationMapper;
    private DepotMr005ValidationService $depotMr005ValidationService;

    private AmazonS3Service $amazonS3Service;

    public function __construct(
        LoggerInterface            $logger,
        ApplicationMessageService  $applicationMessageService,
        DepotMr005Service          $depotMr005Service,
        EmailService               $emailService,
        String                     $emailApplication,
        DepotMr005ValidationMapper $depotMr005ValidationMapper,
        DepotMr005ValidationService $depotMr005ValidationService,
        AmazonS3Service             $amazonS3Service
    )
    {
        $this->logger = $logger;
        $this->applicationMessageService = $applicationMessageService;
        $this->depotMr005Service = $depotMr005Service;
        $this->emailService = $emailService;
        $this->emailApplication = $emailApplication;
        $this->depotMr005ValidationMapper = $depotMr005ValidationMapper;
        $this->depotMr005ValidationService = $depotMr005ValidationService;
        $this->amazonS3Service = $amazonS3Service;

    }

    // TODO: add security
    #[Route('/', name: 'etablissement_index')]
    public function index(): Response
    {
        $ipe = '000000001'; // TODO: get from token

        $receipe = $this->depotMr005Service->getRecepiceByIpe($ipe);

        $usecase = $receipe ? 'recepice_found' : 'recepice_not_found';

        try {
            $message = $this->applicationMessageService->getEtablissementPageMessage($usecase);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $message = [
                'page' => MessageConstants::PROBLEME_RECUPERATION_MESSAGE,
                'button' => MessageConstants::PROBLEME_RECUPERATION_MESSAGE,
            ];
        }

        return $this->render('etablissement/etablissement.html.twig', [
            'message' => $message['page']->getMessage(),
            'button' => $message['button']->getMessage(),
            'usecase' => $usecase,
        ]);
    }

    #[Route('/depot_mr005', name: 'depot_mr005', methods: ['GET', 'POST'])]
    public function depotMr005(Request $request): Response
    {
        $ipe = '000000001'; // TODO: get from token
        $finess = '000000111'; // TODO: get from token
        $raisonSocial = 'test'; // TODO: get from token

        $message = $this->getMessageByUseCase('message_depot_recepice');

        $depotMr005 = new DepotMr005Form();
        $form = $this->createForm(DepotMr005Type::class, $depotMr005);

        if ($request->isMethod('POST')) {
            $allValues = $request->request->all();
            $form->submit($allValues[$form->getName()]);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $request->files->all();
                $data = $request->request->all();

                # Override data with token data
                $data['depot_mr005']['ipe'] = $ipe;
                $data['depot_mr005']['finess'] = $finess;
                $data['depot_mr005']['raisonSociale'] = $raisonSocial;

                $this->saveFileInS3($data, $file);

                $this->saveFormData($data, $file);

                $this->sendEmail($data);

                // TODO: redirect ?
                return $this->redirect('/etablissement/show_mr005/' . $data['depot_mr005']['numeroRecepice']);
            }
        }

        return $this->render('etablissement/depotRecepice.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
            'ipe' => $ipe,
            'finess' => $finess,
            'raisonSociale' => $raisonSocial,
        ]);
    }

    #[Route('/show_mr005/{recepice}', name: 'show_mr005', methods: ['GET'])]
    public function showMr005(string $recepice): Response
    {
        $message = $this->getMessageByUseCase('message_depot_recepice');

        $ipe = '000000001'; // TODO: get from token

        $depotValidation = $this->depotMr005ValidationService->getDepotMr005ValidationByRecepice($recepice);
        $form = $this->createForm(ShowMr005Type::class, $depotValidation);

        return $this->render('etablissement/showRecepice.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    private function saveFileInS3(array $formData, array $fileData): void
    {
        try {
            $this->amazonS3Service->uploadFile(
                $_ENV['AWS_BUCKET'],
                $formData['depot_mr005']['numeroRecepice'] .
                "-" .
                $fileData['depot_mr005']['fileType']->getClientOriginalName(),
                $fileData['depot_mr005']['fileType']->getPathname()
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function saveFormData(array $formData, array $fileData): void
    {
        try {
            $depotMr005Validation = $this->depotMr005ValidationMapper->map(
                $formData['depot_mr005'],
                $fileData['depot_mr005']['fileType']
            );
            $this->depotMr005ValidationService->save($depotMr005Validation);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function sendEmail(array $formData): void
    {
        $emailResonsable = $formData['depot_mr005']['courriel'];
        try {
            $this->emailService->sendEmail(
                $this->emailApplication,
                $emailResonsable,
                MessageConstants::EMAIL_SUBJECT_DEPOT,
                MessageConstants::EMAIL_BODY_DEPOT
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function getMessageByUseCase(string $usecase): string
    {
        try {
            return $this->applicationMessageService->getMessageByUseCase($usecase)->getMessage();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return MessageConstants::PROBLEME_RECUPERATION_MESSAGE;
        }
    }
}