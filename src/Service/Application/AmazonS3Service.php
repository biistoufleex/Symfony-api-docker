<?php

namespace App\Service\Application;

use Aws\S3\S3Client;
use Exception;
use Psr\Log\LoggerInterface;

class AmazonS3Service
{
    private S3Client $s3Client;
    private LoggerInterface $logger;

    public function __construct(S3Client $s3Client, LoggerInterface $logger)
    {
        $this->s3Client = $s3Client;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function uploadFile(string $bucket, string $key, string $filePath): void
    {
        try {
            $this->s3Client->putObject(
                [
                    'Bucket' => $bucket,
                    'Key' => $key,
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
                    'Bucket' => $bucket,
                    'Key' => $key,
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function listFiles(string $bucket): array
    {
        try {
            $result = $this->s3Client->listObjects(
                [
                    'Bucket' => $bucket,
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result->toArray();
    }

    /**
     * @throws Exception
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

    public function saveFileInS3(array $formData, array $fileData): void
    {
        try {
            $this->uploadFile(
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
}