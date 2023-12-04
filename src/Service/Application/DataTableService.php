<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005;
use App\Entity\Common\OrganisationAutorisation;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;

class DataTableService
{
    private DataTableFactory $dataTableFactory;
    private AmazonS3Service $amazonS3Service;

    public function __construct(DataTableFactory $dataTableFactory, AmazonS3Service $amazonS3Service)
    {
        $this->dataTableFactory = $dataTableFactory;
        $this->amazonS3Service = $amazonS3Service;
    }

    public function createValidationDataTable(bool $validated): DataTable
    {
        $dataTable = $this->dataTableFactory->create();
        $dataTable
            ->setName('mr005_' . ($validated ? 'validated' : 'unvalidated'))
            ->add('dateSoumission', DateTimeColumn::class, [
                'label' => 'Date de soumission', 'format' => 'd/m/Y'
            ])
            ->add('raisonSociale', TextColumn::class, ['label' => 'Raison sociale'])
            ->add('dateAttribution', DateTimeColumn::class, [
                'field' => 'c.dateAttribution', 'label' => 'Date d\'attribution', 'format' => 'd/m/Y'
            ])
            ->add('recepise', TextColumn::class, [
                'label' => 'Numéro de récépissé',
                'field' => 'c.numeroRecepice'
            ])
            ->add('buttonRecepisse', TextColumn::class, [
                'label' => 'Lien vers le récépissé',
                'render' => function ($value, $context) {
                // TODO: preview file
//                    if ($context->getDepotMr005Formulaire() != null) {
//                        try {
//
//                            $filePath = $this->amazonS3Service->downloadFile(
//                                $context->getDepotMr005Formulaire()->getNumeroRecepice(),
//                                $context->getDepotMr005Formulaire()->getFilePath()
//                            );
//                            return '<a href="' . $filePath . '" download="'
//                                . $context->getDepotMr005Formulaire()->getFilePath()
//                                . '">Récépissé</a>';
//                        } catch (Exception $e) {
//                            return '<p href="#">Pas de récépissé</p>';
//                        }
//                    }
                    return '<p href="#">Pas de récépissé</p>';
                },
            ])
            ->add('buttonForm', TextColumn::class, [
                'label' => 'Lien vers le formulaire',
                'render' => function ($value, $context) {
                    return '<a href="/valideur/mr005/demande/' . $context->getId() . '">Valider</a>';
                },
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => DepotMr005::class,
                'query' => function (QueryBuilder $builder) use ($validated) {
                    $builder
                        ->select('e')
                        ->addSelect('c')
                        ->from(DepotMr005::class, 'e')
                        ->leftJoin('e.depotMr005Formulaire', 'c')
                        ->where('e.validated = ' . ($validated ? 'true' : 'false'))
                        ->orderBy('e.dateSoumission', 'ASC');
                },
            ]);
        return $dataTable;
    }


    public function getOrganisationAutorisationDataTable(): DataTable
    {
        $dataTable = $this->dataTableFactory->create();
        $dataTable
            ->setName('organisation_autorisation')
            ->add('identifiantOrganisationPlage', TextColumn::class, ['label' => 'Identifiant organisation plage'])
            ->add('dateDebut', DateTimeColumn::class, [
                'label' => 'Date de début', 'format' => 'd/m/Y'
            ])
            ->add('dateFin', DateTimeColumn::class, [
                'label' => 'Date de fin', 'format' => 'd/m/Y'
            ])
            ->add('perimetre', TextColumn::class, ['label' => 'Périmètre'])
            ->add('typeAutorisation', TextColumn::class, ['label' => 'Type d\'autorisation'])
            ->add('buttonUpdate', TextColumn::class, [
                'label' => 'Modifier',
                'render' => function ($value, $context) {
                    return '<a href="/habilitation/update/' . $context->getId() . '">Modifier</a>';
                },
            ])
            ->add('buttonDelete', TextColumn::class, [
                'label' => 'Supprimer',
                'render' => function ($value, $context) {
                    return '<a href="/habilitation/delete/' . $context->getId() . '">Supprimer</a>';
                },
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => OrganisationAutorisation::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('e')
                        ->from(OrganisationAutorisation::class, 'e')
                        ->orderBy('e.dateDebut', 'ASC');
                },
            ]);
        return $dataTable;
    }
}