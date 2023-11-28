<?php

namespace App\Service\Application;

use Aws\S3\S3Client;
use Exception;
use Psr\Log\LoggerInterface;

class AmazonS3Service
{
    private const DEPOT_MR_005 = 'depot_mr005';
    private const FILE_PATH = 'filePath';
    private const NUMERO_RECEPICE = 'numeroRecepice';
    private const BUCKET = 'Bucket';
    private const KEY = 'Key';
    private S3Client $s3Client;
    private LoggerInterface $logger;
    private string $projectDir;

    public function __construct(S3Client $s3Client, LoggerInterface $logger, string $projectDir)
    {
        $this->s3Client = $s3Client;
        $this->logger = $logger;
        $this->projectDir = $projectDir;
    }

    /**
     * @throws Exception
     */
    public function uploadFile(string $bucket, string $key, string $filePath): void
    {
        try {
            $this->s3Client->putObject(
                [
                    self::BUCKET => $bucket,
                    self::KEY => $key,
                    'Body' => fopen($filePath, 'r'),
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function deleteFile(string $bucket, string $key): void
    {
        try {
            $this->s3Client->deleteObject(
                [
                    self::BUCKET => $bucket,
                    self::KEY => $key,
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return array<string>
     * @throws Exception
     */
    public function listFiles(string $bucket): array
    {
        try {
            $result = $this->s3Client->listObjects(
                [
                    self::BUCKET => $bucket,
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result->toArray();
    }

    /**
     * @throws Exception
     * @return array<string>
     */
    public function listBucket(): array
    {
        try {
            $result = $this->s3Client->listBuckets();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result->toArray();
    }

    /**
     * @param array<string>|array<array<string>> $formData
     * @param array<string>|array<array<string>> $fileData
     * @return void
     */
    public function saveFileInS3(array $formData, array $fileData): void
    {
        // test array value
        try {
            $this->uploadFile(
                $_ENV['AWS_BUCKET'],
                $formData[self::DEPOT_MR_005][self::NUMERO_RECEPICE] .
                "-" .
                $fileData[self::DEPOT_MR_005][self::FILE_PATH]->getClientOriginalName(),
                $fileData[self::DEPOT_MR_005][self::FILE_PATH]->getPathname(),
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function getFileFromS3(string $numeroRecepice, string $filename): ?string
    {
        $savedPath = $this->projectDir . '/public/tmp/' . $filename;
        $this->s3Client->getObject(
            [
                self::BUCKET => $_ENV['AWS_BUCKET'],
                self::KEY    => $numeroRecepice . "-" . $filename,
                'SaveAs'     => $savedPath,
            ]
        );
        return $savedPath;
    }
}