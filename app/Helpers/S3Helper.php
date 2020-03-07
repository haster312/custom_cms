<?php


namespace App\Helpers;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use mysql_xdevapi\Exception;

class S3Helper
{
    public $config;
    public $client;
    public $bucket;

    public function __construct()
    {
        $S3 = config('API.S3');
        $credentials = new Credentials($S3['Key'], $S3['Secret']);

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $S3['Region'],
            'credentials' => $credentials
        ]);

        $this->bucket = $S3['Bucket'];
    }

    public function uploadImage($image, $target, $type)
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $target,
                'SourceFile' => $image,
                'ContentType' => $type,
                'StorageClass' => 'STANDARD'
            ]);

            if (isset($result['ObjectURL'])) {
                return $result['ObjectURL'];
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function storeS3File($path)
    {
        $filename = explode('uploads', $path);
        $filename = last($filename);
        $filename = "uploads{$filename}";

        try {
            $file = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $filename
            ]);

            if ($file->get('ContentType') || $file->get('Body')) {
                $body = $file->get('Body');
                $extension = str_replace('image/', '', $file->get('ContentType'));

                // Store to local
                file_put_contents(public_path($filename), $body);

                return [
                    'target' => $filename,
                    'extension' => $extension
                ];
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return false;
        }
    }

    public function checkS3Path($path)
    {
        if (strpos($path, 'thinhgia.s3') !== false || strpos($path, 'amazonaws.com') !== false) {
            return true;
        } else {
            return false;
        }
    }
}
