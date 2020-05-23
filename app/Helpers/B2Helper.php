<?php

namespace App\Helpers;
use Illuminate\Contracts\Filesystem\Filesystem;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Log;
use BackblazeB2\Client as B2Client;
use BackblazeB2\Bucket;
use ZipStream\ZipStream;

class B2Helper extends B2Client
{


    /**
     * [getAuthToken description]
     * @return [type] [description]
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }

    public function getAllFilesFromBucketFolder($bucketName, $folderName){

        $fileArray = array();

        $files = $this->listFiles([
            'BucketName' => $bucketName
        ]);

        foreach ($files as $file) {
            if (strpos($file->getName(), $folderName) !== false) {

                $fileInfo = $this->getFile([
                    'FileId' => $file->getId()
                ]);

                $publicFileUrl = $this->getDownloadUrl().'/file/'.$bucketName.'/'.$fileInfo->getName();

                $fileObj = array(
                    "id" => $fileInfo->getId(),
                    "name" => $fileInfo->getName(),
                    "size" => $fileInfo->getSize(),
                    "type" => $fileInfo->getType(),
                    "publicUrl" => $publicFileUrl
                );
                array_push($fileArray, $fileObj);
            }
        }

        return $fileArray;
    }

    public function makeZipFileFromBucketFolder($bucketName, $folderName){

    }


}