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
use Illuminate\Support\Facades\Storage;
use MTL\S3BucketStreamZip\Exception\InvalidParameterException;
use MTL\S3BucketStreamZip\S3BucketStreamZip;
use Mailgun\Mailgun;

use League\Flysystem\Filesystem as LFsystem;
use Illuminate\Support\Facades\Cache;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;

class CommonHelpers
{
    /**
     * URL Monifization
     */
    public static function monitizeLink($provider = 'shorte.st', $mainUrl, $linkTitle){

        //$mainUrl = \urlencode($mainUrl);

        if($provider == 'adf.ly'){
            // Adfly options.
            $options = [
                'title' => $linkTitle
            ];
            // this will for example echo http://adf.ly/1KMh2Z.
            return \Adfly::create($mainUrl, $options);
        }

        if($provider == 'shorte.st'){
            $apiurl="https://api.shorte.st/v1/data/url";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
            curl_setopt($ch, CURLOPT_URL, $apiurl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('public-api-token: '.env('SHORTEST_API_KEY'),'X-HTTP-Method-Override: PUT'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "urlToShorten=".$mainUrl);
            $data = curl_exec($ch);
            curl_close($ch);
            if(json_decode($data)){
                $obj = json_decode($data);
                $ret=$obj->{'shortenedUrl'};
                // this will for example echo http://gestyy.com/w8wV63
                return $ret;
            }else{
                return 'Wrong link!';
            }

        }

        if($provider == 'linkbucks'){
            $adts = '2';
            $contype = '1';
            $domainss = 'linkbucks.com';
            $postData = array('originalLink' => $mainUrl, 'user' => env('LINKBUCKS_API_USER'), 'apiPassword' => env('LINKBUCKS_API_KEY'), 'contentType' => $contype, 'adType' => $adts, 'domain' => $domainss);
            $jsonData = json_encode($postData);
            $curlObj = curl_init();
            curl_setopt($curlObj, CURLOPT_URL, 'https://www.linkbucks.com/api/createLink/single');
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
            //As the API is on https, set the value for CURLOPT_SSL_VERIFYPEER to false. This will stop cURL from verifying the SSL certificate.
            curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlObj, CURLOPT_HEADER, 0);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
            curl_setopt($curlObj, CURLOPT_POST, 1);
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
            $response = curl_exec($curlObj);

            if(json_decode($response)){
                $json = json_decode($response);
                curl_close($curlObj);
                return $json->link;
            }else{
                return 'Wrong link!';
            }

        }
    }


    /**
     * Send email depending on settings
     */
    public static function sendAppEmail($to, $subject, $msg)
    {
        $getMailFromName = config('app.mail_from_name');
        $getMailFromEmail = config('app.mail_from_address');
        $getEmailSendType = config('app.app_use_mail_type');
        $getEmailApiProvider = config('app.app_use_mail_api_provider');
        $getEmailApiProviderUser = config('app.app_use_mail_provider_username');
        $getEmailApiProviderKey = config('app.app_use_mail_provider_key');
        $getEmailAppDomain = config('app.app_use_mail_app_domain');

        if(is_array($to)){
            $to = implode($to);
        }

        // Send email using mail()
        if ($getEmailSendType == 'local') {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $getMailFromEmail . "\r\n" .
                'Reply-To: ' . $getMailFromEmail . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // Sending email
            if (mail($to, $subject, $msg, $headers)) {
                Log::info("Mail sent using mail()");
                return true;
            } else {
                Log::error("Mail was not sent using mail()");
                return false;
            }
        }

        // Send email using Sendgrid API
        if ($getEmailSendType == 'sendgrid-api') {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($getMailFromEmail, $getMailFromName);
            $email->setSubject($subject);
            $email->addTo($to);
            $email->addContent("text/plain", "This is an html email. Use better client.");
            $email->addContent(
                "text/html", $msg
            );
            $sendgrid = new \SendGrid($getEmailApiProviderKey);
            try {
                $response = $sendgrid->send($email);
                //print $response->statusCode() . "\n";
                //print_r($response->headers());
                //print $response->body() . "\n";
                Log::info("Mail sent using sendgrid()");
                return true;
            } catch (Exception $e) {
                Log::error("Mail was not sent using sendgrid()");
                Log::error("sendgrid() Exception :: ".$e->getMessage());
                return false;
            }
        }

        // Send email using mailgun API
        if ($getEmailSendType == 'mailgun-api') {
            $mgClient = new Mailgun($getEmailApiProviderKey);
            $domain = $getEmailAppDomain;
            try {
                $result = $mgClient->sendMessage($domain, array(
                    'from'	=> $getMailFromName.' <'.$getMailFromEmail.'>',
                    'to'	=> (string) $to . "",
                    'subject' => $subject,
                    'text' => "This is an html email. Use better client.",
                    'html'	=> $msg
                ));
                Log::info("Mail sent using mailgun()");
                return true;
            } catch (Exception $e) {
                Log::error("Mail was not sent using mailgun()");
                Log::error("mailgun() Exception :: ".$e->getMessage());
                return false;
            }
        }
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }


    /**
     * [makeUniqShareCode description]
     * @return [type] [description]
     */
    public static function makeUniqShareCode()
    {
        $unique_key =   strtoupper(substr(md5(rand(0, 1000000)), 0, 4)) . '-' . rand(10000, 99999);
        $chkuniq    =   \App\ShareModel::where("share_code", $unique_key)->first();
        if ($chkuniq) {
            return static::getouniqid();
        }
        return $unique_key;
    }

    public static function makeZipFileFromDataSet($dataset, $filename, $datasetType, $download = true)
    {
        $zip = new ZipStream($filename);
        foreach ($files as $file) {
            // Get the file name on S3 so we can save it to the zip file using the same name.
            $fileName = basename($file['Key']);
            $zip->addFileFromStream($fileName, $stream);
        }
        // Finalize the zip file.
        $zip->finish();
    }

    /**
     * [downloadFilesToLocal description]
     * @param  [type] $shareCode [description]
     * @return [type]            [description]
     */
    public static function downloadFilesToLocal($shareCode)
    {
        // Get Default file system
        $defaultFileSystem = config('app.filesystem');
        $getShareObj = \App\ShareModel::where('share_code', trim($shareCode))->first();

        if ($getShareObj) {

            switch ($defaultFileSystem) {

                    // Upload via s3
                case 's3':

                    $stream = new S3BucketStreamZip([
                        'key'     => config('services.s3.key'),
                        'secret'  => config('services.s3.secret'),
                        'region'  => config('services.s3.region'),
                        'version' => config('services.s3.version'),
                    ]);

                    if ($getShareObj) {
                        try {
                            $stream->bucket(config('services.s3.bucket'))
                                ->prefix($getShareObj->share_code)
                                ->send($getShareObj->share_code . '.zip');
                        } catch (InvalidParameterException $e) {
                            // handle the exception
                            echo $e->getMessage();
                        } catch (S3Exception $e) {
                            // handle the exception
                            echo $e->getMessage();
                        }
                    }

                    break;

                    // Local storage
                case 'local':
                    # create a new zipstream object
                    $getAllFiles = glob(storage_path("app/" . $getShareObj->share_code . "/*"));
                    $zip = new \ZipStream\ZipStream($getShareObj->share_code . '.zip');
                    foreach ($getAllFiles as $file) {
                        $fileName = basename($file);
                        $zip->addFileFromPath($fileName, $file);
                    }

                    // Clean up
                    $zip->finish();
                    break;

                    // Upload via blackblaze / b2
                case 'blackblaze':

                    $n3c = new B2Helper(config('services.b2.account_id'), config('services.b2.app_key'));
                    $getAllFiles = $n3c->getAllFilesFromBucketFolder(config('services.b2.bucket'), $getShareObj->share_code);

                    if (count($getAllFiles) > 0) {
                        # create a new zipstream object
                        $zip = new \ZipStream\ZipStream($getShareObj->share_code . '.zip');
                        foreach ($getAllFiles as $file) {
                            $fileName = basename($file['name']);
                            $tempImage = storage_path() . '/app/' . $getShareObj->share_code . '/' . $fileName;
                            Storage::makeDirectory($getShareObj->share_code);
                            copy($file['publicUrl'], $tempImage);
                            $zip->addFileFromPath($fileName, $tempImage);
                        }

                        // Clean up
                        Storage::deleteDirectory($getShareObj->share_code);
                        $zip->finish();
                    }


                    break;
            }
        }
    }

    public static function makeDownloadUrl($fileObject)
    {
        if ($fileObject) {
            $defaultFileSystem = $fileObject->file_system;
            $downloadGenLink = config('app.url') . '/download/file/nonstatic/' . $fileObject->id;
            switch ($defaultFileSystem) {

                    // Upload via s3
                case 's3':
                    // Nothing special
                    break;

                    // Upload via blackblaze / b2
                case 'blackblaze':
                    // Nothing special
                    break;
            }
        }

        return $downloadGenLink;
    }

    public static function genarateDownload($fileObject)
    {

        if ($fileObject) {
            $defaultFileSystem = $fileObject->file_system;
            switch ($defaultFileSystem) {

                    // Upload via s3
                case 's3':
                    $uploadToS3Link = \Storage::disk('s3')->temporaryUrl($fileObject->file_url, now()->addMinutes(1440));
                    return $uploadToS3Link;
                    break;

                case 'google':
                    $gclient = new \Google_Client();
                    $gclient->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
                    $gclient->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
                    $gclient->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                    $gservice = new \Google_Service_Drive($gclient);
                    $adapter    = new GoogleDriveAdapter($gservice, env('GOOGLE_DRIVE_FOLDER_ID'));
                    $uploadToS3Link = $adapter->getUrl($fileObject->file_url);
                    return $uploadToS3Link;
                    break;

                    // Localstorage
                case 'local':
                    $uploadToS3Link = \Storage::disk('local')->get($fileObject->file_url);
                    $getFileSize = \Storage::disk('local')->size($fileObject->file_url);
                    $getFileType = \Storage::disk('local')->mimeType($fileObject->file_url);
                    return response($uploadToS3Link)
                        ->header('Content-Type', $getFileType)
                        ->header('Content-Transfer-Encoding', 'binary')
                        ->header('Content-Disposition', 'attachment; filename=' . $fileObject->file_original_name)
                        ->header('Content-Length', $getFileSize);
                    break;

                    // Upload via blackblaze / b2
                case 'blackblaze':
                    $b2client = new B2Client(config('services.b2.account_id'), config('services.b2.app_key'));
                    $fileInfo = $b2client->getFile([
                        'BucketName' => config('services.b2.bucket'),
                        'FileName' => $fileObject->file_url
                    ]);
                    if ($fileInfo) {
                        $fileContent = $b2client->download([
                            'FileId' => $fileInfo->getId()
                        ]);

                        return response($fileContent)
                            ->header('Content-Type', $fileInfo->getType())
                            ->header('Content-Transfer-Encoding', 'binary')
                            ->header('Content-Disposition', 'attachment; filename=' . $fileInfo->getName())
                            ->header('Content-Length', $fileInfo->getSize());
                    }
                    break;
            }
        }

        return null;
    }

    /**
     * [uploadFilesToServerViaB2 description]
     * @param  [type]  $fileObject [description]
     * @param  [type]  $type       [description]
     * @param  string  $folder     [description]
     * @param  boolean $isPublic   [description]
     * @return [type]              [description]
     */
    public static function uploadFilesToServerViaB2($fileObject, $type, $folder = '')
    {

        if ($fileObject && $type) {
            $type           =   str_slug($type, '-');
            $filename       =   urlencode(basename( str_slug($fileObject->getClientOriginalName()) ));
            $fileext        =   pathinfo($fileObject->getClientOriginalName(), PATHINFO_EXTENSION);
            $newfilename    =   uniqid(md5(rand(000000, 999999) . time())) . '.' . $fileext;
            if ($folder) {
                $filePath       =   $folder . '/' . $filename . '_' . $newfilename; // /folder/userid/file.ext
            } else {
                $filePath       =   $filename . '_' . $newfilename; // /folder/userid/file.ext
            }

            $n3c = new B2Helper(config('services.b2.account_id'), config('services.b2.app_key'));
            $fileContent = fopen($fileObject->getPathName(), 'r');

            $file = $n3c->upload([
                'BucketName' => config('services.b2.bucket'),
                'FileName' => $filePath,
                'Body' => $fileContent
            ]);
            $fileContent = null;
            $n3c = null;

            //$b2UploadObj    =   $b2Obj->put($filePath, file_get_contents($fileObject));

            #$b2client = new B2Client(env('B2_ACCOUNT_ID'), env('B2_APP_KEY'));
            #$buckets = $b2client->listBuckets();

            return array(
                "upName" => $filePath,
                "originalName" => $filename
            );
        }

        return "";
    }

    /**
     * [uploadFilesToServer description]
     * @param  [type]  $fileObject [description]
     * @param  [type]  $type       [description]
     * @param  boolean $isPublic   [description]
     * @return [type]              [description]
     */
    public static function uploadFilesToServer($fileObject, $type, $folder = '', $isPublic = false)
    {

        $accessType = 'public';
        if (!$isPublic) {
            $accessType = 'private';
        }

        if ($fileObject && $type) {
            $type           =   str_slug($type, '-');
            $s3Obj          =   \Storage::disk('s3');
            $filename       =   urlencode(basename($fileObject->getClientOriginalName()));
            $fileext        =   pathinfo($fileObject->getClientOriginalName(), PATHINFO_EXTENSION);
            $newfilename    =   uniqid(md5(rand(000000, 999999) . time())) . '.' . $fileext;
            if ($folder) {
                $filePath       =   $folder . '/' . $filename . '_' . $newfilename; // /folder/userid/file.ext
            } else {
                $filePath       =   $filename . '_' . $newfilename; // /folder/userid/file.ext
            }
            $s3UploadObj    =   $s3Obj->putFileAs($folder, $fileObject, $filename . '_' . $newfilename, $accessType);
            return array(
                "upName" => $filePath,
                "originalName" => $filename
            );
        }

        return "";
    }

    public static function uploadFilesToGoogleServer($fileObject, $type, $folder = '', $isPublic = false)
    {
        $gclient = new \Google_Client();
        $gclient->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $gclient->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $gclient->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
        $gservice = new \Google_Service_Drive($gclient);
        $adapter    = new GoogleDriveAdapter($gservice, env('GOOGLE_DRIVE_FOLDER_ID'));
        $filesystem = new LFsystem($adapter);

        $accessType = 'public';
        if (!$isPublic) {
            $accessType = 'private';
        }
        if ($fileObject && $type) {
            $type           =   str_slug($type, '-');
            $filename       =   urlencode(basename($fileObject->getClientOriginalName()));
            $fileext        =   pathinfo($fileObject->getClientOriginalName(), PATHINFO_EXTENSION);
            $newfilename    =   uniqid(md5(rand(000000, 999999) . time())) . '.' . $fileext;
            $filePath       =   $filename . '_' . $newfilename; // /folder/userid/file.ext
            $fileObject->storeAs('tmp', $filePath);
            $read = Storage::get('tmp/'.$filePath);
            $filesystem->write($filePath, $read);
            Storage::delete('tmp/'.$filePath);
            $getMetaData = $adapter->getMetadata($filePath);
            return array(
                "upName" => $getMetaData['path'],
                "originalName" => $filename
            );
        }

        return "";
    }

    public static function uploadFilesToServerLocal($fileObject, $type, $folder = '', $isPublic = false)
    {

        $accessType = 'public';
        if (!$isPublic) {
            $accessType = 'private';
        }

        if ($fileObject && $type) {
            $type           =   str_slug($type, '-');
            $s3Obj          =   \Storage::disk('local');
            $filename       =   urlencode(basename($fileObject->getClientOriginalName()));
            $fileext        =   pathinfo($fileObject->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($fileext) {
                $newfilename    =   uniqid(md5(rand(000000, 999999) . time())) . '.' . $fileext;
            } else {
                $newfilename    =   uniqid(md5(rand(000000, 999999) . time()));
            }
            $mkFileNam       =   $filename . '_' . $newfilename; // /folder/userid/file.ext
            $s3UploadObj    =   $s3Obj->putFileAs($folder, $fileObject, $mkFileNam);
            return array(
                "upName" => $folder . '/' . $mkFileNam,
                "originalName" => $filename
            );
        }

        return "";
    }

    public static function uploadFilesToServerDirect($fileObject, $name, $isPublic = false)
    {

        $accessType = 'public';
        if (!$isPublic) {
            $accessType = 'private';
        }

        if ($fileObject && $name) {
            $s3Obj          =   \Storage::disk('s3');
            $filePath       =   $name . '.zip'; // /folder/userid/file.ext
            $s3UploadObj    =   $s3Obj->put($filePath, $fileObject, $accessType);
            return array(
                "upName" => $filePath,
                "originalName" => $filePath
            );
        }

        return "";
    }



    /**
     * [getHumanReadableTimeDiff description]
     * @param  [type]  $time1     [description]
     * @param  [type]  $time2     [description]
     * @param  integer $precision [description]
     * @return [type]             [description]
     */
    public static function getHumanReadableTimeDiff($time1, $time2, $precision = 2)
    {
        // If not numeric then convert timestamps
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }
        // If time1 > time2 then swap the 2 values
        if ($time1 > $time2) {
            list($time1, $time2) = array($time2, $time1);
        }
        // Set up intervals and diffs arrays
        $intervals = array('year', 'month', 'day', 'hour', 'minute', 'second');
        $diffs = array();
        foreach ($intervals as $interval) {
            // Create temp time from time1 and interval
            $ttime = strtotime('+1 ' . $interval, $time1);
            // Set initial values
            $add = 1;
            $looped = 0;
            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
                // Create new temp time from time1 and interval
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }
            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }
        $count = 0;
        $times = array();
        foreach ($diffs as $interval => $value) {
            // Break if we have needed precission
            if ($count >= $precision) {
                break;
            }
            // Add value and interval if value is bigger than 0
            if ($value > 0) {
                if ($value != 1) {
                    $interval .= "s";
                }
                // Add value and interval to times array
                $times[] = $value . " " . $interval;
                $count++;
            }
        }
        // Return string with times
        return implode(", ", $times);
    }
}
