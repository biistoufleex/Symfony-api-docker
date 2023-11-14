<?php

namespace App\Service\Application;

use Aws\S3\S3Client;
use Exception;

class AmazonS3Service
{
    private S3Client $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
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
}