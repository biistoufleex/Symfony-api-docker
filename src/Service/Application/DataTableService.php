<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;

class DataTableService
{
    private DataTableFactory $dataTableFactory;

    public function __construct(DataTableFactory $dataTableFactory)
    {
        $this->dataTableFactory = $dataTableFactory;
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
            ->add('recepise', TextColumn::class, ['label' => 'Numéro de récépissé', 'field' => 'c.numeroRecepice'])
            ->add('buttonRecepisse', TextColumn::class, [
                'label' => 'Lien vers le récépissé',
                'render' => function ($value, $context) {
                    // TODO: changer le lien
                    return '<a href="/valideur/mr005/demande/' . $context->getId() . '">Lien</a>';
                },
            ])
            ->add('buttonForm', TextColumn::class, [
                'label' => 'Lien vers le formulaire',
                'render' => function ($value, $context) {
                    // TODO: changer le lien
                    return '<a href="/valideur/mr005/demande/' . $context->getId() . '">Valider</a>';
                },
            ])
            ->add('validated', TextColumn::class, [
                'label' => 'Validé',
                'render' => function ($value, $context) {
                    return $value ? 'Oui' : 'Non';
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
}