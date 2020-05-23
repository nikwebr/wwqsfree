<?php

use App\Helpers\CommonHelpers;

Route::get('/admin', function () {
	return redirect('/login');
});

Auth::routes();

// Protected Route
Route::middleware(['check-if-installed', 'auth'])->group(function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/getData', 'HomeController@getZipDownloadData')->name('data');
	Route::get('/delete/code/zip/{code}', 'ShareFileController@deleteShareCode');
	Route::post('/delete/allselectedfiles', 'ShareFileController@deleteShareCodeSelected');
	Route::get('/block/code/ip/{ip}', 'ShareFileController@blockShareIP');
	Route::get('/unblock/code/ip/{ip}', 'ShareFileController@unblockShareIP');
	Route::get('/admin/settings', 'SettingsCtrl@showSettingsPage');
	Route::get('/admin/profile', 'SettingsCtrl@showProfilePage');
	Route::post('/admin/profile/update', 'SettingsCtrl@updateUserInfo');
	Route::post('/admin/profile/update/password', 'SettingsCtrl@updateUserPassword');

	Route::post('/admin/settings/update/appsettings', 'SettingsCtrl@updateAppSettings');
	Route::post('/admin/settings/update/emailsettings', 'SettingsCtrl@updateAppEmailSettings');
	Route::post('/admin/settings/update/awssettings', 'SettingsCtrl@updateAppAWSSettings');
	Route::post('/admin/settings/update/b2settings', 'SettingsCtrl@updateAppBlackbazeSettings');
	Route::post('/admin/settings/update/googledrive', 'SettingsCtrl@updateAppGoogleDriveSettings');
	Route::post('/admin/settings/update/monetize', 'SettingsCtrl@updateAppMonetizeSettings');
	Route::post('/admin/settings/update/other', 'SettingsCtrl@updateAppOtherSettings');

});

// Firewall enabled routes
Route::middleware(['check-if-installed', 'fw-block-blacklisted'])->group(function () {

	// Public Routes
	Route::get('/', 'ShareFileController@showHomePage');
	Route::get('/download/file/{id}', 'ShareFileController@downloadFileForFirstTime');
	Route::get('/download/file/nonstatic/{id}', 'ShareFileController@downloadNonStaticFile');
	Route::post('/file-upload', 'ShareFileController@uploadFiles');
	Route::post('/confirm-upload', 'ShareFileController@confirmUploadingDone');
	Route::get('/download/code/{code}', 'ShareFileController@showDownlodFilesByShareCode');
	Route::get('/share/code/new', 'ShareFileController@getNewShareCode');
	Route::get('/share/code/{code}', 'ShareFileController@showSharePage');
	Route::post('/share/old/files', 'ShareFileController@shareExistingFiles');
	Route::get('/download/code/zip/{code}', 'ShareFileController@downloadAllFilesAsZip');


	// Public View pages
	Route::get('/about', function () {
		return view('front.about');
	});
	Route::get('/help', function () {
		return view('front.help');
	});
	Route::get('/legal', function () {
		return view('front.legal');
	});

	// Throttled route
	Route::group(['middleware' => 'throttle:5'], function () {
		Route::post('/help', 'ShareFileController@sendHelpMail');
	});


	Route::get('/test', function () {
		//return (CommonHelpers::monitizeLink('linkbucks', 'https://www.punith.com/best-url-shortener-websites/', 'Download Your Files :: '));
		Storage::disk('google')->put('testaa.txt', 'Hello World');
	});


});
