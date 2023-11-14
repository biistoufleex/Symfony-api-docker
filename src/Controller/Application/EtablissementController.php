<?php

namespace App\Controller\Application;

use App\constants\MessageConstants;
use App\Form\Entity\DepotMr005Form;
use App\Form\Type\DepotMr005Type;
use App\Form\Type\ShowMr005Type;
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
    private DepotMr005ValidationService $depotMr005ValidationService;
    private AmazonS3Service $amazonS3Service;

    public function __construct(
        LoggerInterface            $logger,
        ApplicationMessageService  $applicationMessageService,
        DepotMr005Service          $depotMr005Service,
        EmailService               $emailService,
        DepotMr005ValidationService $depotMr005ValidationService,
        AmazonS3Service $amazonS3Service,
    )
    {
        $this->logger = $logger;
        $this->applicationMessageService = $applicationMessageService;
        $this->depotMr005Service = $depotMr005Service;
        $this->emailService = $emailService;
        $this->depotMr005ValidationService = $depotMr005ValidationService;
        $this->amazonS3Service = $amazonS3Service;
    }

    // TODO: add security
    #[Route('/', name: 'etablissement_index')]
    public function index(): Response
    {
        $ipe = '000000001'; // TODO: get from token

        $receipe = $this->depotMr005Service->getOneRecepiceByIpe($ipe);

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

        $receipe = $receipe?->getDepotMr005Validation()->getNumeroRecepice();
        return $this->render('etablissement/etablissement.html.twig', [
            'message' => $message['page']->getMessage(),
            'button' => $message['button']->getMessage(),
            'usecase' => $usecase,
            'recepice' => $receipe,
        ]);
    }

    #[Route('/depot_mr005', name: 'depot_mr005', methods: ['GET', 'POST'])]
    public function depotMr005(Request $request): Response
    {

        // TODO: si une demande en cour set readonly


        $ipe = '000000001'; // TODO: get from token
        $finess = '000000111'; // TODO: get from token
        $raisonSocial = 'test'; // TODO: get from token

        $message = $this->applicationMessageService->getStringMessageByUseCase('message_depot_recepice');

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

                $this->amazonS3Service->saveFileInS3($data, $file);

                $formData = $this->depotMr005ValidationService->saveFormData($data, $file);

                $idPlage = '123123123'; // TODO: get from token
                $this->depotMr005Service->saveDepot($formData, $idPlage);

                $this->emailService->sendEmailTo($data['depot_mr005']['courriel']);

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
        $message = $this->applicationMessageService->getStringMessageByUseCase('message_depot_recepice');
        $depotValidation = $this->depotMr005ValidationService->getDepotMr005ValidationByRecepice($recepice);
        $form = $this->createForm(ShowMr005Type::class, $depotValidation);

        return $this->render('etablissement/showRecepice.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }
}