<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005Formulaire;
use App\Form\Entity\DepotMr005Form;
use App\Mapper\Application\DepotMr005FormulaireMapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class DepotMr005FormulaireService
{
    private const DEPOT_MR_005 = 'depot_mr005';
    private const FILE_PATH = 'filePath';
    private const NUMERO_RECEPICE = 'numeroRecepice';
    private const IPE = 'ipe';
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private DepotMr005FormulaireMapper $depotMr005FormulaireMapper;


    public function __construct
    (
        LoggerInterface            $logger,
        EntityManagerInterface     $entityManager,
        DepotMr005FormulaireMapper $depotMr005FormulaireMapper
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->depotMr005FormulaireMapper = $depotMr005FormulaireMapper;
    }

    public function getDepotMr005FormulaireByRecepice(string $recepice): ?DepotMr005Formulaire
    {
        return $this->entityManager
            ->getRepository(DepotMr005Formulaire::class)
            ->findOneBy([self::NUMERO_RECEPICE => $recepice]);
    }

    public function getDepotMr005FormulaireByIpe(string $recepice): ?DepotMr005Formulaire
    {
        return $this->entityManager
            ->getRepository(DepotMr005Formulaire::class)
            ->findOneBy([self::IPE => $recepice]);
    }

    public function getDepotMr005FormulaireById(string $id): ?DepotMr005Formulaire
    {
        return $this->entityManager
            ->getRepository(DepotMr005Formulaire::class)
            ->find($id);
    }

    public function existByRecepice(string $recepice): bool
    {
        return (bool)$this
            ->entityManager
            ->getRepository(DepotMr005Formulaire::class)
            ->findOneBy([self::NUMERO_RECEPICE => $recepice]);
    }

    /**
     * @param array<string, mixed> $formData
     * @param array<string, mixed> $fileData
     * @return DepotMr005Formulaire|null
     */
    public function saveFormData(array $formData, array $fileData): ?DepotMr005Formulaire
    {
        $depotMr005Formulaire = null;
        try {
            $depotMr005Formulaire = $this->depotMr005FormulaireMapper->map(
                $formData[self::DEPOT_MR_005],
                $fileData[self::DEPOT_MR_005][self::FILE_PATH]
            );
            $this->save($depotMr005Formulaire);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $depotMr005Formulaire;
    }

    public function save(DepotMr005Formulaire $depotMr005Formulaire): void
    {
        $this->entityManager->persist($depotMr005Formulaire);
        $this->entityManager->flush();
    }

    /**
     * @param DepotMr005Formulaire $depotMr005Formulaire
     * @param string|null $localFilePath
     * @return DepotMr005Form
     * @throws Exception
     */
    public function toDepotForm(DepotMr005Formulaire $depotMr005Formulaire, string $localFilePath = null): DepotMr005Form
    {
        return $this->depotMr005FormulaireMapper->toDepotForm($depotMr005Formulaire, $localFilePath);
    }

    /**
     * @param DepotMr005Formulaire $depotMr005Formulaire
     * @param array<string, mixed> $data
     * @param array<string, mixed> $file
     * @throws Exception
     */
    public function updateAndValidateDepotMr005Formulaire(
        DepotMr005Formulaire $depotMr005Formulaire,
        array                $data,
        array                $file
    ): void
    {
        $this->depotMr005FormulaireMapper->update($depotMr005Formulaire, $data, $file);

        $depotMr005Formulaire->getDepotMr005()->setValidated(true);
        $depotMr005Formulaire->getDepotMr005()->setDateSoumission(new \DateTime());

        $this->save($depotMr005Formulaire);
    }

    public function unvalidDepotForm(string $numeroRecepice): void
    {
        $depotFormulaire = $this->entityManager
            ->getRepository(DepotMr005Formulaire::class)
            ->findOneBy([self::NUMERO_RECEPICE => $numeroRecepice]);

        if ($depotFormulaire) {
            $depotFormulaire->getDepotMr005()->setValidated(false);
            $this->save($depotFormulaire);
        }
    }
}