<?php

namespace App\Controller\Application;

use App\Entity\Application\Form\DepotMr005Form;
use App\Form\Type\DepotMr005Type;
use App\Service\Application\ApplicationMessageService;
use App\Service\Application\DepotMr005Service;
use App\Service\Application\EmailService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function __construct(
        LoggerInterface           $logger,
        ApplicationMessageService $applicationMessageService,
        DepotMr005Service         $depotMr005Service,
        EmailService              $emailService,
        String                    $emailApplication
    )
    {
        $this->logger = $logger;
        $this->applicationMessageService = $applicationMessageService;
        $this->depotMr005Service = $depotMr005Service;
        $this->emailService = $emailService;
        $this->emailApplication = $emailApplication;
    }

    // TODO: add security
    #[Route('/', name: 'etablissement_index')]
    public function index(): Response
    {
        $message = null;
        $ipe = '000000001'; // TODO: get from token

        $receipe = $this->depotMr005Service->getRecepiceByIpe($ipe);

        $usecase = $receipe ? 'recepice_found' : 'recepice_not_found';

        try {
            $message = $this->applicationMessageService->getEtablissementPageMessage($usecase);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['ipe' => $ipe]);
            $response = $this->render('errors.html.twig', [
                'exception' => $exception,
            ]);

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $response;
        }

        return $this->render('etablissement/index.html.twig', [
            'message' => $message['page']->getMessage(),
            'button' => $message['button']->getMessage(),
        ]);
    }

    #[Route('/depot_mr005', name: 'depot_mr005', methods: ['GET', 'POST'])]
    public function depotMr005(Request $request): Response
    {
        $message = null;
        $ipe = '000000001'; // TODO: get from token
        $finess = '000000111'; // TODO: get from token
        $raisonSocial = 'test'; // TODO: get from token

        try {
            $message = $this->applicationMessageService->getMessageByUseCase('message_depot_recepice');
        } catch (Exception $exception) {
            $response = $this->render('errors.html.twig', [
                'exception' => $exception,
            ]);

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $response;
        }

        $depotMr005 = new DepotMr005Form();
        $form = $this->createForm(DepotMr005Type::class, $depotMr005);

        if ($request->isMethod('POST')) {
            $allValues = $request->request->all();
            $form->submit($allValues[$form->getName()]);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $request->files->all();
                $data = $request->request->all();
                $data['depot_mr005']['ipe'] = $ipe;
                $data['depot_mr005']['finess'] = $finess;
                $data['depot_mr005']['raisonSociale'] = $raisonSocial;
                // stock in s3 database

                $emailResonsable = $data['depot_mr005']['courriel'];
                try {
                    $this->emailService->sendEmail(
                        $this->emailApplication,
                        $emailResonsable,
                        'depot de recepisse',
                        'un depot de recepisse a ete effectue'
                    );
                } catch (Exception $e) {
                    return new Response($e->getMessage()); // TODO: change
                }
                return new Response('ok');// TODO: change
            }
        }

        return $this->render('etablissement/depotRecepice.html.twig', [
            'message' => $message->getMessage(),
            'form' => $form->createView(),
            'ipe' => $ipe,
            'finess' => $finess,
            'raisonSociale' => $raisonSocial,
        ]);
    }

    // create route avec formulaire en lecture seul
}