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
     * @return array<string>
     * @throws Exception
     */
    public function listFiles(string $bucket): array
    {
        $this->logger->info('S3 list files');
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
     * @return array<string>
     * @throws Exception
     */
    public function listBucket(): array
    {
        $this->logger->info('S3 list bucket');

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
        $this->logger->info('S3 save file');

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

    /**
     * @throws Exception
     */
    public function uploadFile(string $bucket, string $key, string $filePath): void
    {
        $this->logger->info('S3 upload file');

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
    public function downloadFile(string $numeroRecepice, string $filename): ?string
    {
        $this->logger->info('S3 get file');

        $savedPath = $this->projectDir . '/public/tmp/' . $numeroRecepice . "-" . $filename;
        $this->s3Client->getObject(
            [
                self::BUCKET => $_ENV['AWS_BUCKET'],
                self::KEY => $numeroRecepice . "-" . $filename,
                'SaveAs' => $savedPath,
            ]
        );
        return $savedPath;
    }

    public function deleteFileFromS3(string $numeroRecepice, string $filename): void
    {
        $this->logger->info('S3 delete file');

        try {
            $this->s3Client->deleteObject(
                [
                    self::BUCKET => $_ENV['AWS_BUCKET'],
                    self::KEY => $numeroRecepice . "-" . $filename,
                ]
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param string $numeroRecepice
     * @param string $filename
     * @return string
     */
    public function generatePresignedUrl(string $numeroRecepice, string $filename): string //TODO: replace download by this
    {
        $this->logger->info('S3 generate presigned url');

        try {
            $cmd = $this->s3Client->getCommand(
                'GetObject',
                [
                    self::BUCKET => $_ENV['AWS_BUCKET'],
                    self::KEY => $numeroRecepice . "-" . $filename,
                ]
            );
            $request = $this->s3Client->createPresignedRequest($cmd, '+20 minutes');
            return (string)$request->getUri();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return '';
    }
}