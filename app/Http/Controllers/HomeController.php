<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getZipDownloadData()
    {
        $getAllShareCode = \App\ShareModel::where('status', 1)->orderBy('id', 'desc')->get();
        foreach ($getAllShareCode as $shareObj) {
            $getAllFiles   = \App\ShareFileModel::where('share_id', $shareObj->id)->where('status', 1)->get();
            $totalFileSize = 0;
            foreach ($getAllFiles as $shareFile) {
                $totalFileSize = (int) $totalFileSize + (int) $shareFile->file_size;
            }

            $reciversEmail = implode(', ', explode(',', $shareObj->reciver_email));
            $sendersEmail = $shareObj->sender_email ? $shareObj->sender_email : 'N/A';
            $shareObj->sender_email = $sendersEmail;
            if($sendersEmail == 'undefined'){
                $shareObj->sender_email = 'N/A';
            }
            $shareObj->reciver_email   =  $reciversEmail ? $reciversEmail : 'N/A';
            if($shareObj->reciver_email == 'undefined'){
                $shareObj->reciver_email = 'N/A';
            }
            $shareObj->totalSize    = CommonHelpers::formatSizeUnits($totalFileSize);
            $shareObj->totalFiles   = count($getAllFiles);
            $shareObj->downloadLink = '/download/code/zip/' . $shareObj->share_code;
            $shareObj->deleteLink = '/delete/code/zip/' . $shareObj->share_code;
            $shareObj->blocklink = '/block/code/ip/' . $shareObj->ip;
            $shareObj->unblocklink = '/unblock/code/ip/' . $shareObj->ip;
            $shareObj->firewall = 'no';

            $blacklisted = \Firewall::isBlacklisted($shareObj->ip);
            if($blacklisted){
                $shareObj->firewall = 'yes';
            }

        }
        return Datatables::of($getAllShareCode)->toJson();
    }


}
