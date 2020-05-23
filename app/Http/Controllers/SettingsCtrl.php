<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use MTL\S3BucketStreamZip\Exception\InvalidParameterException;
use Jackiedo\DotenvEditor\DotenvEditor;

class SettingsCtrl extends Controller
{

    protected $editor;

    public function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }

    public function showSettingsPage(Request $request){
        $content = $this->editor->getKeys();
        $getAllTimeZones = $this->tz_list();
        $getallSettings = $content;

        $maxIniUploadSize = ($getallSettings['APP_MAX_FILE_SIZE']['value'] * 0.5) + $getallSettings['APP_MAX_FILE_SIZE']['value'];
        $maxIniUploadTime = max(($getallSettings['APP_MAX_FILE_SIZE']['value'] * 0.9), 30);

        $makePhpINISettingsView = "
            post_max_size = ".$maxIniUploadSize."M<br />
            memory_limit = 256M<br />
            upload_max_filesize = ".$maxIniUploadSize."M<br />
            max_execution_time = $maxIniUploadTime<br />
        ";

        $settings = \Setting::all();

        return view('settings')->with('timezones', $getAllTimeZones)->with('settings', $getallSettings)->with('phpIniSettings', $makePhpINISettingsView)->with('otherSettings', $settings);
    }

    public function updateAppSettings(Request $request){

        \Artisan::call('config:clear');

    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appName' => 'required',
            'appStorage' => 'required',
            'appDebug' => 'required',
            'appDomain' => 'required',
            'appEmail' => 'required',
            'appTimezone' => 'required',
            'appFirewall' => 'required',
            'appFileSize' => 'required',
            'appFileQty' => 'required',
            'appUploadTimeOut' => 'required',
            'appSendEmailAfterUpload' => 'required',
            'appShareUploadvalidity' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#appsettings')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();
        $content = $this->editor->setKey('APP_NAME', trim($request->input('appName')));
        $content = $this->editor->setKey('ZIPFILEME_USE_STORAGE', trim($request->input('appStorage')));
        $content = $this->editor->setKey('APP_DEBUG', trim($request->input('appDebug')));
        $content = $this->editor->setKey('APP_URL', trim($request->input('appDomain')));
        $content = $this->editor->setKey('APP_EMAIL', trim($request->input('appEmail')));
        $content = $this->editor->setKey('APP_TIMEZONE', trim($request->input('appTimezone')));
        $content = $this->editor->setKey('FIREWALL_ENABLED', trim($request->input('appFirewall')));
        $content = $this->editor->setKey('APP_MAX_FILE_SIZE', trim($request->input('appFileSize')));
        $content = $this->editor->setKey('APP_MAX_FILE_QTY', trim($request->input('appFileQty')));
        $content = $this->editor->setKey('APP_UPLOAD_TIMEOUT', trim($request->input('appUploadTimeOut')));
        $content = $this->editor->setKey('APP_SEND_EMAIL_AFTER_UPLOAD', trim($request->input('appSendEmailAfterUpload')));
        $content = $this->editor->setKey('DELETE_FILES_IN_DAYS', trim($request->input('appShareUploadvalidity')));
        $content = $this->editor->save();

        \Artisan::call('config:clear');

        return redirect('/admin/settings#appsettings')->with('status', 'App Settings has been saved!');
    }

    public function updateAppEmailSettings(Request $request){
        \Artisan::call('config:clear');

    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appMailSendUsing' => 'required',
            'appSMTPHost' => '',
            'appSMTPPort' => '',
            'appSMTPUserName' => '',
            'appSMTPPassword' => '',
            'appSMTPEncryption' => '',
            'mailgunDomain' => '',
            'mailgunApiKey' => '',
            'sendgridApiKey' => '',
            'appSentFromName' => ''
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#email')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();

        if($request->input('appMailSendUsing')) {
            $content = $this->editor->setKey('APP_USE_MAIL_TYPE', trim($request->input('appMailSendUsing')));
        }
        if($request->input('appSMTPHost')) {
            $content = $this->editor->setKey('MAIL_HOST', trim($request->input('appSMTPHost')));
        }
        if($request->input('appSMTPPort')) {
            $content = $this->editor->setKey('MAIL_PORT', (int) trim($request->input('appSMTPPort')));
        }
        if($request->input('appSMTPUserName')) {
            $content = $this->editor->setKey('MAIL_USERNAME', trim($request->input('appSMTPUserName')));
        }
        if($request->input('appSMTPPassword')) {
            $content = $this->editor->setKey('MAIL_PASSWORD', trim($request->input('appSMTPPassword')));
        }
        if($request->input('appSMTPEncryption')) {
            $content = $this->editor->setKey('MAIL_ENCRYPTION', trim($request->input('appSMTPEncryption')));
        }
        if($request->input('appSentFromEmail')) {
            $content = $this->editor->setKey('MAIL_FROM_ADDRESS', trim($request->input('appSentFromEmail')));
        }
        if($request->input('appSentFromName')) {
            $content = $this->editor->setKey('MAIL_FROM_NAME', trim($request->input('appSentFromName')));
        }
        if($request->input('mailgunDomain')) {
            $content = $this->editor->setKey('APP_USE_MAIL_APP_DOMAIN', trim($request->input('mailgunDomain')));
        }
        if($request->input('mailgunApiKey')) {
            $content = $this->editor->setKey('APP_USE_MAIL_PROVIDER_KEY', trim($request->input('mailgunApiKey')));
        }
        if($request->input('sendgridApiKey')) {
            $content = $this->editor->setKey('APP_USE_MAIL_PROVIDER_KEY', trim($request->input('sendgridApiKey')));
        }

        $content = $this->editor->save();

        \Artisan::call('config:clear');

        return redirect('/admin/settings#email')->with('status', 'App Email Settings has been saved!');
    }

    public function updateAppAWSSettings(Request $request){
        \Artisan::call('config:clear');
    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appAWSaccesskey' => 'required',
            'appAWSaccesskeysecret' => 'required',
            'appAWSregion' => 'required',
            'appAWSbucket' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#aws')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();
        $content = $this->editor->setKey('AWS_ACCESS_KEY_ID', trim($request->input('appAWSaccesskey')));
        $content = $this->editor->setKey('AWS_SECRET_ACCESS_KEY', trim($request->input('appAWSaccesskeysecret')));
        $content = $this->editor->setKey('AWS_DEFAULT_REGION', trim($request->input('appAWSregion')));
        $content = $this->editor->setKey('AWS_BUCKET', trim($request->input('appAWSbucket')));
        $content = $this->editor->save();

        \Artisan::call('config:clear');

        return redirect('/admin/settings#aws')->with('status', 'App AWS Settings has been saved!');
    }

    public function updateAppBlackbazeSettings(Request $request){
        \Artisan::call('config:clear');
    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appB2accountid' => 'required',
            'appB2appkey' => 'required',
            'appB2bucket' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#blackbaze')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();
        $content = $this->editor->setKey('B2_ACCOUNT_ID', trim($request->input('appB2accountid')));
        $content = $this->editor->setKey('B2_APP_KEY', trim($request->input('appB2appkey')));
        $content = $this->editor->setKey('B2_BUCKET', trim($request->input('appB2bucket')));
        $content = $this->editor->save();

        \Artisan::call('config:clear');

        return redirect('/admin/settings#blackbaze')->with('status', 'App Blackbaze Settings has been saved!');
    }

    public function updateAppGoogleDriveSettings(Request $request){
        \Artisan::call('config:clear');
    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appGoogleDClientID' => 'required',
            'appGoogleDClientSecret' => 'required',
            'appGoogleDClientRefreshToken' => 'required',
            'appGoogleDClientFolderID' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#googledrive')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();
        $content = $this->editor->setKey('GOOGLE_DRIVE_CLIENT_ID', trim($request->input('appGoogleDClientID')));
        $content = $this->editor->setKey('GOOGLE_DRIVE_CLIENT_SECRET', trim($request->input('appGoogleDClientSecret')));
        $content = $this->editor->setKey('GOOGLE_DRIVE_REFRESH_TOKEN', trim($request->input('appGoogleDClientRefreshToken')));
        $content = $this->editor->setKey('GOOGLE_DRIVE_FOLDER_ID', trim($request->input('appGoogleDClientFolderID')));
        $content = $this->editor->save();

        \Artisan::call('config:clear');

        return redirect('/admin/settings#googledrive')->with('status', 'App Google Drive Settings has been saved!');
    }

    public function showProfilePage(Request $request){
    	$user = \Auth::user();
    	return view('profile')->with('userData', $user);
    }

    public function updateUserInfo(Request $request){

    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'userID' => 'required',
            'userName' => 'required',
            'userEmail' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/profile#password')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $userObj = \App\User::find( $request->input('userID') );

        if($userObj){
        	$userObj->name = trim($request->input('userName'));
        	$userObj->email = trim($request->input('userEmail'));
        	$userObj->save();

        	return redirect('/admin/profile')->with('status', 'Profile updated!');
        }

        return redirect('/admin/profile');
    }

    public function updateUserPassword(Request $request){
    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'userID' => 'required',
            'userOldPass' => 'required|min:4',
            'password' => 'required|confirmed|min:4',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/profile#password')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $userObj = \App\User::find( $request->input('userID') );

        if($userObj){

        	$plainPassword = trim($request->input('userOldPass'));
        	$newPassword =  trim($request->input('password'));
        	$hashedPassword = $userObj->password;

        	if (\Hash::check($plainPassword, $hashedPassword) ) {
			    $userObj->password = \Hash::make($newPassword);
        		$userObj->save();
        		return redirect('/admin/profile#password')->with('status', 'Password has been updated!');
			}else{
				return redirect('/admin/profile#password')->with('error', 'Old password did not matched!');
			}

        }

        return redirect('/admin/profile');
    }

    public function updateAppMonetizeSettings(Request $request){

        \Artisan::call('config:clear');

    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'appLinkMonitize' => 'required',
            'adflyUID' => '',
            'adflyAPIKEY' => '',
            'appEnableLeftAd' => 'required',
            'appEnableRightAd' => 'required',
            'appEnableTopAd' => 'required',
            'appEnableBottomAd' => 'required',
            'adRightCol' => '',
            'adLeftCol' => '',
            'adTopCol' => '',
            'adBottomCol' => '',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#monetize')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        $content = $this->editor->getKeys();
        $content = $this->editor->setKey('ENABLE_LINK_MONETIZATION', trim($request->input('appLinkMonitize')));
        $content = $this->editor->setKey('ADFLY_UID', trim($request->input('adflyUID')));
        $content = $this->editor->setKey('ADFLY_API_KEY', trim($request->input('adflyAPIKEY')));
        $content = $this->editor->setKey('SHORTEST_API_KEY', trim($request->input('shortestAPIKEY')));
        $content = $this->editor->setKey('LINKBUCKS_API_USER', trim($request->input('linkBucksUserName')));
        $content = $this->editor->setKey('LINKBUCKS_API_KEY', trim($request->input('linkBucksAPIKey')));
        $content = $this->editor->setKey('ENABLE_LEFT_COLUMN_AD', trim($request->input('appEnableLeftAd')));
        $content = $this->editor->setKey('ENABLE_RIGHT_COLUMN_AD', trim($request->input('appEnableRightAd')));
        $content = $this->editor->setKey('ENABLE_TOP_NAV_AD', trim($request->input('appEnableTopAd')));
        $content = $this->editor->setKey('ENABLE_BOTTOM_AD', trim($request->input('appEnableBottomAd')));
        $content = $this->editor->save();


        // Save ad data
        \Setting::set('ad_left', ($request->input('adLeftCol') ? $request->input('adLeftCol') : '' ) );
        \Setting::set('ad_right', ($request->input('adRightCol') ? $request->input('adRightCol') : '' ));
        \Setting::set('ad_top', ($request->input('adTopCol') ? $request->input('adTopCol') : '' ));
        \Setting::set('ad_bottom', ($request->input('adBottomCol') ? $request->input('adBottomCol') : '' ));
        \Setting::save();

    	\Artisan::call('config:clear');

        return redirect('/admin/settings#monetize')->with('status', 'App Monetize Settings has been saved!');
    }

    public function updateAppOtherSettings(Request $request){

    	// Input Validation --------------------------------------
        $validator = \Validator::make($request->all(), [
            'site_title' => '',
            'site_logo' => '',
            'site_keywords' => '',
            'site_desc' => '',
            'site_google_an_id' => '',
            'extra_top_header' => '',
            'extra_bottom_header' => ''
        ]);
        if ($validator->fails()) {
            return redirect('/admin/settings#others')->with('error', implode("<br>", $validator->errors()->all()));
        }
        // Ends ---------------------------------------------------

        // Save ad data
        \Setting::set('site_title', ($request->input('site_title') ? $request->input('site_title') : '' ) );
        \Setting::set('site_logo', ($request->input('site_logo') ? $request->input('site_logo') : '' ) );
        \Setting::set('site_keywords', ($request->input('site_keywords') ? $request->input('site_keywords') : '' ));
        \Setting::set('site_desc', ($request->input('site_desc') ? $request->input('site_desc') : '' ));
        \Setting::set('site_google_an_id', ($request->input('site_google_an_id') ? $request->input('site_google_an_id') : '' ));
        \Setting::set('extra_top_header', ($request->input('extra_top_header') ? $request->input('extra_top_header') : '' ));
        \Setting::set('extra_bottom_header', ($request->input('extra_bottom_header') ? $request->input('extra_bottom_header') : '' ));

        \Setting::set('about_page', ($request->input('about_page') ? $request->input('about_page') : '' ) );
        \Setting::set('legal_page', ($request->input('legal_page') ? $request->input('legal_page') : '' ) );
        \Setting::set('help_page', ($request->input('help_page') ? $request->input('help_page') : '' ) );

        \Setting::save();

        return redirect('/admin/settings#others')->with('status', 'App Other Settings has been saved!');
    }

    public function tz_list() {
	  $zones_array = array();
	  $timestamp = time();
	  foreach(timezone_identifiers_list() as $key => $zone) {
	    date_default_timezone_set($zone);
	    $zones_array[$key]['zone'] = $zone;
	    $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
	  }
	  return $zones_array;
	}

}
