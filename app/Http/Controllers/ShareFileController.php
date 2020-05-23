<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use MTL\S3BucketStreamZip\Exception\InvalidParameterException;
use MTL\S3BucketStreamZip\S3BucketStreamZip;
use App\Helpers\B2Helper;
use ZipStream\ZipStream;

class ShareFileController extends Controller
{

    /**
     * [getNewShareCode description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getNewShareCode(Request $request)
    {
        $getNewShareCode = CommonHelpers::makeUniqShareCode();
        return response()->json(array(
            "code" => $getNewShareCode,
        ), 200);
    }
    /**
     * [showHomePage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function showHomePage(Request $request)
    {
        $getNewShareCode = CommonHelpers::makeUniqShareCode();
        return view('front.home')->with('shareCode', $getNewShareCode);
    }

    /**
     * [shareExistingFiles description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function shareExistingFiles(Request $request)
    {
        $appDeleteFilesInDays = (int) config('app.app_delete_files_in_days');
        // Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'shareCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                "error" => $validator->errors()->all(),
            ), 400); // Bad request
        }
        // Ends ---------------------------------------------------

        $getShareObj = \App\ShareModel::where('share_code', trim($request->input('shareCode')))->first();

        if ($getShareObj) {
            $genarateValidity           = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days"));
            $getShareObj->sender_email  = $request->input('senderEmail');
            $getShareObj->reciver_email = $request->input('reciversEmail');
            //$getShareObj->validity = $genarateValidity;
            $getShareObj->note = $request->input('senderNote');
            $getShareObj->save();

            $getAllFiles = \App\ShareFileModel::where('share_id', $getShareObj->id)->where('status', 1)->get();

            $totalFileSize = 0;
            foreach ($getAllFiles as $file) {
                $totalFileSize = $totalFileSize + (int) $file->file_size;
            }

            $getShareObj->files    = $getAllFiles;
            $getShareObj->fileSize = CommonHelpers::formatSizeUnits($totalFileSize);
            $getShareObj->validity = date("F j, Y", strtotime($getShareObj->validity));


            /// Email send settings
            ///////////////////////////////////////
            $getEmailSendType = config('app.app_use_mail_type');
            $getShouldSendEmailAfterUpload = config('app.app_send_email_after_upload');
            if (($getShouldSendEmailAfterUpload)) {
                if (($getEmailSendType == 'smtp')) {
                    // Send to reciver
                    \Mail::to(trim($getShareObj->reciver_email))
                        ->send(new \App\Mail\FileUploadedShare($getShareObj));

                    // Send to sender
                    \Mail::to(trim($getShareObj->sender_email))
                        ->send(new \App\Mail\ShareFileRecipt($getShareObj));
                } else {
                    // Send to reciver
                    $getView = view('emails.share-file', ['mailData' => $getShareObj])->render();
                    CommonHelpers::sendAppEmail($getShareObj->reciver_email, $getShareObj->sender_email . " shared files with you.", $getView);

                    // Send to reciver
                    $getView = view('emails.confirmation-sender', ['mailData' => $getShareObj])->render();
                    CommonHelpers::sendAppEmail($getShareObj->sender_email, "Your Files are shared with " . $getShareObj->reciver_email, $getView);
                }
            }

            // Ends

            return response()->json(array(
                "uploadStatus" => "Confirmed",
            ), 200);
        }

        return response()->json(array(
            "error" => "ShareCode Invalid",
        ), 400); // Bad request
    }

    /**
     * [deleteShareCode description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function deleteShareCode(Request $request, $shareCode = null)
    {
        $getShareObj = \App\ShareModel::where('share_code', trim($shareCode))->first();
        if ($getShareObj) {

            // Delete folder if exists in local storage
            \Storage::disk('local')->deleteDirectory(trim($shareCode));

            $getShareObj->status = 0;
            $getShareObj->save();
        }
        return redirect('/home');
    }

    public function deleteShareCodeSelected(Request $request)
    {
        foreach ($request->input('codes') as $codeToDelete) {
            $getShareObj = \App\ShareModel::where('share_code', trim($codeToDelete))->first();
            if ($getShareObj) {

                // Delete folder if exists in local storage
                \Storage::disk('local')->deleteDirectory(trim($codeToDelete));

                $getShareObj->status = 0;
                $getShareObj->save();
            }
        }

        return response()->json(array(
            "status" => "success",
            "deleted" => $request->input('codes')
        ), 200);

    }

    public function blockShareIP(Request $request, $IP = null)
    {
        if ($IP) {
            \Firewall::blacklist($IP, true);
            return redirect('/home');
        }
    }

    public function unblockShareIP(Request $request, $IP = null)
    {
        if ($IP) {
            \Firewall::whitelist($IP, true);
            return redirect('/home');
        }
    }

    /**
     * [confirmUploadingDone description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function confirmUploadingDone(Request $request)
    {
        // Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'shareCode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                "error" => $validator->errors()->all(),
            ), 400); // Bad request
        }
        // Ends ---------------------------------------------------

        // Get Default file system
        $defaultFileSystem = config('app.filesystem');

        $appDeleteFilesInDays = (int) config('app.app_delete_files_in_days');

        $getShareObj = \App\ShareModel::where('share_code', trim($request->input('shareCode')))->first();
        if ($getShareObj) {

            // php 7.2 fix
            $oldFiles = [];
            if ($request->oldData) {
                $oldFiles = $request->oldData;
            }

            if (($oldFiles) && (count($oldFiles) > 0)) {
                switch ($defaultFileSystem) {

                    // Upload via s3
                    case 's3':
                        $s3 = \Storage::disk('s3');
                        foreach ($oldFiles as $oldFile) {
                            $getShareIDOfFile = \App\ShareFileModel::find($oldFile['fileID']);
                            if ($getShareIDOfFile) {
                                $getShareObjOldFile = \App\ShareModel::find($getShareIDOfFile->share_id);
                                if ($getShareObjOldFile) {
                                    $images = $s3->allFiles($getShareObjOldFile->share_code);
                                    foreach ($images as $image) {
                                        if ($oldFile['name'] == $image) {
                                            $new_loc = str_replace($getShareObjOldFile->share_code, trim($request->input('shareCode')), $image);
                                            $s3->copy($image, $new_loc);
                                            $genarateValidity                  = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days"));
                                            $createNewFile                     = new \App\ShareFileModel();
                                            $createNewFile->share_id           = $getShareObj->id;
                                            $createNewFile->file_url           = $image;
                                            $createNewFile->file_original_name = $getShareIDOfFile->file_original_name;
                                            $createNewFile->file_size          = $getShareIDOfFile->fize_size;
                                            $createNewFile->validity           = $genarateValidity;
                                            $createNewFile->save();
                                        }
                                    }
                                }
                            }
                        }
                        break;

                        // Upload via blackblaze / b2
                    case 'blackblaze':
                        break;

                    // Upload google
                    case 'google':
                        break;

                        // Upload via localstorage
                    case 'local':

                        $s3 = \Storage::disk('local');
                        foreach ($oldFiles as $oldFile) {
                            $getShareIDOfFile = \App\ShareFileModel::find($oldFile['fileID']);
                            if ($getShareIDOfFile) {
                                $getShareObjOldFile = \App\ShareModel::find($getShareIDOfFile->share_id);
                                if ($getShareObjOldFile) {
                                    $images = $s3->allFiles($getShareObjOldFile->share_code);
                                    foreach ($images as $image) {
                                        if ($oldFile['name'] == $image) {
                                            $new_loc = str_replace($getShareObjOldFile->share_code, trim($request->input('shareCode')), $image);
                                            $s3->copy($image, $new_loc);
                                            $genarateValidity                  = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days"));
                                            $createNewFile                     = new \App\ShareFileModel();
                                            $createNewFile->share_id           = $getShareObj->id;
                                            $createNewFile->file_url           = $image;
                                            $createNewFile->file_original_name = $getShareIDOfFile->file_original_name;
                                            $createNewFile->file_size          = $getShareIDOfFile->fize_size;
                                            $createNewFile->validity           = $genarateValidity;
                                            $createNewFile->file_system        = $defaultFileSystem;
                                            $createNewFile->save();
                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
            }

            $getAllFiles = \App\ShareFileModel::where('share_id', $getShareObj->id)->where('status', 1)->get();

            $totalFileSize = 0;
            foreach ($getAllFiles as $file) {
                $totalFileSize = $totalFileSize + (int) $file->file_size;
            }

            $getShareObj->files    = $getAllFiles;
            $getShareObj->fileSize = CommonHelpers::formatSizeUnits($totalFileSize);
            $getShareObj->validity = date("F j, Y", strtotime($getShareObj->validity));

            $reciversEmail = explode(',', $getShareObj->reciver_email);

            // Check if link monitization enabled
            $monitizeDownloadLink = config('app.app_monitize_download_link');

            if($monitizeDownloadLink){
                $getShareObj->downloadLink = CommonHelpers::monitizeLink($monitizeDownloadLink, config('app.url').'/download/code/'.$getShareObj->share_code, 'Download Your Files :: '.$getShareObj->share_code);
            }else{
                $getShareObj->downloadLink = config('app.url').'/download/code/'.$getShareObj->share_code;
            }

            // Send to reciver

            /// Email send settings
            ///////////////////////////////////////
            $getEmailSendType = config('app.app_use_mail_type');
            $getShouldSendEmailAfterUpload = config('app.app_send_email_after_upload');

            if (($getShouldSendEmailAfterUpload)) {
                if (($getEmailSendType == 'smtp')) {
                    // Send to reciver
                    \Mail::to(($reciversEmail))
                        ->send(new \App\Mail\FileUploadedShare($getShareObj));

                    // Send to sender
                    \Mail::to(trim($getShareObj->sender_email))
                        ->send(new \App\Mail\ShareFileRecipt($getShareObj));
                } else {
                    // Send to reciver
                    $getView = view('emails.share-file', ['mailData' => $getShareObj])->render();
                    CommonHelpers::sendAppEmail($reciversEmail, $getShareObj->sender_email . " shared files with you.", $getView);

                    // Send to reciver
                    $getView = view('emails.confirmation-sender', ['mailData' => $getShareObj])->render();
                    CommonHelpers::sendAppEmail($getShareObj->sender_email, "Your Files are shared with " . $getShareObj->reciver_email, $getView);
                }
            }

            // Ends

            return response()->json(array(
                "uploadStatus" => "Confirmed",
                "data" => $getShareObj
            ), 200);
        }

        return response()->json(array(
            "error" => "ShareCode Invalid",
        ), 400); // Bad request
    }

    /**
     * [uploadFiles description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function uploadFiles(Request $request)
    {
        $maxFileSizeAllowed = (int) config('app.app_max_file_size') * 1024;
        $appDeleteFilesInDays = (int) config('app.app_delete_files_in_days');
        // Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'zipFileData'   => 'required|max:' . $maxFileSizeAllowed,
            'shareCode'     => 'required',
            'reciversEmail' => 'required',
            'senderEmail'   => 'required',
            'senderNote'    => '',
            'fileSize'      => '',
        ]);
        if ($validator->fails()) {
            return response()->json(implode(' ,', $validator->errors()->all()), 400); // Bad request
        }
        // Ends ---------------------------------------------------

        // Get Default file system
        $defaultFileSystem = config('app.filesystem');

        $allFiles       = $request->zipFileData;
        $uploadToS3Link = '';

        switch ($defaultFileSystem) {

                // Upload via s3
            case 's3':
                $uploadToS3Link = CommonHelpers::uploadFilesToServer($allFiles, 'zipfiles', trim($request->input('shareCode')));
                break;

                // Upload via google
            case 'google':
                $allFiles       = $request->file('zipFileData');
                $uploadToS3Link = CommonHelpers::uploadFilesToGoogleServer($allFiles, 'zipfiles', trim($request->input('shareCode')));
                break;

                // Upload via blackblaze / b2
            case 'blackblaze':
                $uploadToS3Link = CommonHelpers::uploadFilesToServerViaB2($allFiles, 'zipfiles', trim($request->input('shareCode')));
                break;

                // Upload via localstorage
            case 'local':
                $uploadToS3Link = CommonHelpers::uploadFilesToServerLocal($allFiles, 'zipfiles', trim($request->input('shareCode')));
                break;
        }

        if (!empty($uploadToS3Link)) {

            $getShareObj      = \App\ShareModel::where('share_code', trim($request->input('shareCode')))->first();
            $genarateValidity = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days"));
            if (empty($getShareObj)) {
                $getShareObj                = new \App\ShareModel();
                $getShareObj->sender_email  = $request->input('senderEmail');
                $getShareObj->reciver_email = $request->input('reciversEmail');
                $getShareObj->share_code    = $request->input('shareCode');
                $getShareObj->validity      = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days"));
                $getShareObj->note          = $request->input('senderNote');
                $getShareObj->ip            = request()->ip();
                $getShareObj->user_agent    = $request->header('User-Agent');
                $getShareObj->save();
            } else {
                $genarateValidity = date('Y-m-d h:i:s', strtotime("+".$appDeleteFilesInDays." days", strtotime($getShareObj->created_at)));
            }



            $createNewFile                     = new \App\ShareFileModel();
            $createNewFile->share_id           = $getShareObj->id;
            $createNewFile->file_url           = $uploadToS3Link['upName'];
            $createNewFile->file_original_name = $uploadToS3Link['originalName'];
            $createNewFile->file_size          = (int) $request->input('fileSize');
            $createNewFile->validity           = $genarateValidity;
            $createNewFile->file_system        = $defaultFileSystem;
            $createNewFile->save();

            // Save files temporarily
            //\Storage::putFileAs($getShareObj->share_code, $request->file('zipFileData'), $uploadToS3Link['originalName']);

            return response()->json(array(
                "uploadStatus" => "Done",
                "fileName"     => $uploadToS3Link['originalName'],
            ), 200);
        }

        return response()->json(array(
            "error" => "Something went wrong, please try again!",
        ), 400); // Bad request

    }

    /**
     * [showDownlodFilesByShareCode description]
     * @param  Request $request [description]
     * @param  [type]  $code    [description]
     * @return [type]           [description]
     */
    public function showDownlodFilesByShareCode(Request $request, $code)
    {
        $getShareObj = \App\ShareModel::where('share_code', trim($code))->first();

        if ($getShareObj) {

            $getAllFiles = \App\ShareFileModel::where('share_id', $getShareObj->id)->where('status', 1)->get();

            $totalFileSize = 0;
            foreach ($getAllFiles as $file) {
                $totalFileSize = $totalFileSize + (int) $file->file_size;

                if (!$file->downloaded) {
                    $url = config('app.url') . '/download/file/' . $file->id;
                } else {
                    $url = CommonHelpers::makeDownloadUrl($file);
                }
                $file->downloadUrl = $url;
            }

            $getDBExpTime          = $getShareObj->validity;
            $getShareObj->files    = $getAllFiles;
            $getShareObj->fileSize = CommonHelpers::formatSizeUnits($totalFileSize);
            $getShareObj->validity = CommonHelpers::getHumanReadableTimeDiff(time(), $getShareObj->validity);

            // Check if validation has been expired
            $nowTime      = \Carbon\Carbon::now();
            $thenTime     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $getDBExpTime);
            $ifPassedTime = ($nowTime->gt($thenTime));
            //dd($ifPassedTime);
            if ($ifPassedTime) {
                return view('front.expired')->with("downlaodData", $getShareObj);
            } else {
                return view('front.download')->with("downlaodData", $getShareObj);
            }
        }

        return response()->json(array(
            "error" => "ShareCode Invalid",
        ), 400); // Bad request

    }

    /**
     * [downloadNonStaticFile description]
     * @param  Request $request [description]
     * @param  [type]  $fielID  [description]
     * @return [type]           [description]
     */
    public function downloadNonStaticFile(Request $request, $fielID)
    {
        $getFiles = \App\ShareFileModel::find((int) $fielID);
        $shouldAllowDownload = false;
        if ($getFiles) {
            $getShareObj = \App\ShareModel::find($getFiles->share_id);
            if ($getShareObj) {

                $getDBExpTime          = $getShareObj->validity;

                // Check if validation has been expired
                $nowTime      = \Carbon\Carbon::now();
                $thenTime     = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $getDBExpTime);
                $ifPassedTime = ($nowTime->gt($thenTime));

                if ($ifPassedTime) {
                    return back();
                } else {
                    $shouldAllowDownload = true;
                }
            }
            $resObj = CommonHelpers::genarateDownload($getFiles);
            if ($resObj) {
                if (gettype($resObj) == 'object') {
                    return $resObj;
                } else {
                    return redirect($resObj);
                }
            }
        }
        //return back();
    }

    /**
     * [downloadFileForFirstTime description]
     * @param  Request $request [description]
     * @param  [type]  $fielID  [description]
     * @return [type]           [description]
     */
    public function downloadFileForFirstTime(Request $request, $fielID)
    {
        $getFiles = \App\ShareFileModel::find((int) $fielID);
        if ($getFiles) {
            if (!$getFiles->downloaded) {
                $getFiles->downloaded = 1;
                $getFiles->save();

                $getShareObj = \App\ShareModel::find($getFiles->share_id);
                if ($getShareObj) {

                    if ($getShareObj->downloaded < 1) {

                        $getShareObj->downloaded = 1;
                        $getShareObj->save();

                        $getAllFiles   = \App\ShareFileModel::where('share_id', $getShareObj->id)->where('status', 1)->get();
                        $totalFileSize = 0;
                        foreach ($getAllFiles as $file) {
                            $totalFileSize     = $totalFileSize + (int) $file->file_size;
                        }
                        $getShareObj->files    = $getAllFiles;
                        $getShareObj->fileSize = CommonHelpers::formatSizeUnits($totalFileSize);
                        $getShareObj->validity = CommonHelpers::getHumanReadableTimeDiff(time(), $getShareObj->validity);

                        /// Email send settings
                        ///////////////////////////////////////
                        $getEmailSendType = config('app.app_use_mail_type');
                        $getShouldSendEmailAfterUpload = config('app.app_send_email_after_upload');

                        if (($getShouldSendEmailAfterUpload)) {
                            if (($getEmailSendType == 'smtp')) {
                                // Send to sender
                                \Mail::to(trim($getShareObj->sender_email))
                                    ->send(new \App\Mail\FileDownloadedMail($getShareObj));
                            } else {
                                // Send to reciver
                                $getView = view('emails.downloaded-file-sender', ['mailData' => $getShareObj])->render();
                                CommonHelpers::sendAppEmail($getShareObj->sender_email, "Your Files are downloaded", $getView);
                            }
                        }

                        // Ends

                    }
                }
            }

            $resObj = CommonHelpers::genarateDownload($getFiles);

            if ($resObj) {
                if (gettype($resObj) == 'object') {
                    return $resObj;
                } else {
                    return redirect($resObj);
                }
            }
        }
        return back();
    }

    /**
     * [showSharePage description]
     * @param  Request $request   [description]
     * @param  [type]  $shareCode [description]
     * @return [type]             [description]
     */
    public function showSharePage(Request $request, $shareCode)
    {
        $defaultFileSystem = config('app.filesystem');

        if ($defaultFileSystem == 'blackblaze') {
            return redirect('/')
                ->with('status', '')
                ->with('msg', '');
        }

        $getShareObj = \App\ShareModel::where('share_code', trim($shareCode))->first();

        if ($getShareObj) {
            $getAllFiles     = \App\ShareFileModel::where('share_id', $getShareObj->id)->where('status', 1)->get();
            $getNewShareCode = CommonHelpers::makeUniqShareCode();

            $getShareObj->note     = base64_decode($getShareObj->note);
            $getShareObj->allFiles = $getAllFiles;

            return view('front.home')
                ->with("downlaodData", json_encode($getShareObj))
                ->with("oldShareData", ($getShareObj))
                ->with('oldShareCode', trim($shareCode))
                ->with('shareCode', $getNewShareCode);
        }

        return response()->json(array(
            "error" => "ShareCode Invalid",
        ), 400); // Bad request
    }

    /**
     * [downloadAllFilesAsZip description]
     * @param  Request $request   [description]
     * @param  [type]  $shareCode [description]
     * @return [type]             [description]
     */
    public function downloadAllFilesAsZip(Request $request, $shareCode = null)
    {
        $getShareObj = \App\ShareModel::where('share_code', trim($shareCode))->first();

        if ($getShareObj) {
            CommonHelpers::downloadFilesToLocal($shareCode);
        } else {
            return response()->json(array(
                "error" => "ShareCode Invalid",
            ), 400); // Bad request
        }
    }

    /**
     * [sendHelpMail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendHelpMail(Request $request)
    {
        // Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'userEmail'   => 'required|max:155',
            'userMsg'     => 'required|max:1000'
        ]);
        if ($validator->fails()) {
            return redirect('/help')
                ->with('status', 'error')
                ->with('msg', implode(', ', $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $mailObj = (object) [];
        $mailObj->sender_email = trim($request->input('userEmail'));
        $mailObj->sender_msg = clean($request->input('userMsg'));

        /// Email send settings
        ///////////////////////////////////////
        $getEmailSendType = config('app.app_use_mail_type');
        $getShouldSendEmailAfterUpload = config('app.app_send_email_after_upload');
        if (($getEmailSendType == 'smtp')) {
            // Send mail
            \Mail::to(trim(config('app.app_email')))
                ->send(new \App\Mail\SendHelpMail($mailObj));
        } else {
            // Send to reciver
            $getView = view('emails.help-mail', ['mailData' => $mailObj])->render();
            CommonHelpers::sendAppEmail(trim(config('app.app_email')), config('app.app_email') . " sent you an email", $getView);
        }
        // Ends

        return redirect('/help')
            ->with('status', 'ok')
            ->with('msg', 'done');
    }
}
