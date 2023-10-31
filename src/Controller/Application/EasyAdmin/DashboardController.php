<?php

namespace App\Controller\Application\EasyAdmin;

use App\Entity\Application\ApplicationMessage;
use App\Entity\Application\DepotMr005;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('EasyAdmin/dashboard.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony Api Docker');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('DepotMr005', 'fas fa-list', DepotMr005::class);
        yield MenuItem::linkToCrud('ApplicationMessage', 'fas fa-list', ApplicationMessage::class);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(100)
            ->setDateFormat('medium')
            ->setTimeFormat('short')
            ->setNumberFormat('%.2d')
            ->setTimezone('Europe/Madrid')
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
            ;
    }
}
