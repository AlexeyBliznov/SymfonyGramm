<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Aws\S3\S3Client;

class S3Uploader
{
    private string $accessKey;
    private string $secretAccessKey;

    public function __construct(string $accessKey, string $secretAccessKey)
    {
        $this->accessKey = $accessKey;
        $this->secretAccessKey = $secretAccessKey;
    }

    public function putObject(string $key, string $tmpFileLocation): string
    {
        $client = $this->buildClient();

        $result = $client->putObject([
            'Bucket' => 'symfonygramm',
            'Key'    => $key,
            'SourceFile' => $tmpFileLocation,
            'ContentType' =>'image/jpeg',
            'ContentDisposition' => 'inline; filename=filename.jpg'
        ]);

        return $result->get('ObjectURL');
    }

    public function changeObject(string $oldFileKey, string $newKey, string $newFileTmp): string
    {
        $client = $this->buildClient();

        $client->deleteObject([
            'Bucket' => 'symfonygramm',
            'Key'    => $oldFileKey
        ]);

        $result = $client->putObject([
            'Bucket' => 'symfonygramm',
            'Key'    => $newKey,
            'SourceFile' => $newFileTmp,
            'ContentType' =>'image/jpeg',
            'ContentDisposition' => 'inline; filename=filename.jpg'
        ]);
        return $result->get('ObjectURL');
    }

    private function buildClient(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => $this->accessKey,
                'secret' => $this->secretAccessKey
            ]
        ]);
    }
}
