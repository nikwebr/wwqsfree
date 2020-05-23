@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">

        <div class="panel-body">

          @section('content')
          <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('status'))
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
              {{ session('status') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
              {!! session('error') !!}
            </div>
            @endif
            <!-- Content Start -->

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#appsettings" aria-controls="appsettings" role="tab" data-toggle="tab">Application</a>
              </li>
              <li role="presentation">
                <a href="#others" aria-controls="others" role="tab" data-toggle="tab">Other Settings</a>
              </li>
              <li role="presentation">
                <a href="#monetize" aria-controls="monetize" role="tab" data-toggle="tab">Monetize</a>
              </li>
              <li role="presentation">
                <a href="#email" aria-controls="email" role="tab" data-toggle="tab">Email</a>
              </li>
              <li role="presentation">
                <a href="#aws" aria-controls="aws" role="tab" data-toggle="tab">AWS (S3)</a>
              </li>
              <li role="presentation">
                <a href="#blackbaze" aria-controls="blackbaze" role="tab" data-toggle="tab">Backblaze (B2)</a>
              </li>
              <li role="presentation">
                <a href="#googledrive" aria-controls="googledrive" role="tab" data-toggle="tab">Google Drive</a>
              </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

              <!-- App Settings -->
              <div role="tabpanel" class="tab-pane active" id="appsettings">

                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/appsettings">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Name</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Applications Name" required=""
                          name="appName" value="{{ $settings['APP_NAME']['value'] }}">
                      </div>
                    </div>
                    <div class="form-group has-success">
                      <label class="col-sm-4 control-label">Use Storage</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appStorage">
                          <option value="">Select...</option>
                          <option value="s3"
                            {{ ($settings['ZIPFILEME_USE_STORAGE']['value'] == 's3') ? 'selected' : '' }}>Amazon AWS
                            (S3)</option>
                          <option value="google"
                          {{ ($settings['ZIPFILEME_USE_STORAGE']['value'] == 'google') ? 'selected' : '' }}>Google Drive</option>
                          <option value="blackblaze"
                            {{ ($settings['ZIPFILEME_USE_STORAGE']['value'] == 'blackblaze') ? 'selected' : '' }}>
                            Backblaze (B2)</option>
                          <option value="local"
                            {{ ($settings['ZIPFILEME_USE_STORAGE']['value'] == 'local') ? 'selected' : '' }}>Local
                            Storage</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group has-success">
                      <label class="col-sm-4 control-label">Send Email After Upload</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appSendEmailAfterUpload">
                          <option value="true"
                            {{ ($settings['APP_SEND_EMAIL_AFTER_UPLOAD']['value'] == 'true') ? 'selected' : '' }}>Enable
                          </option>
                          <option {{ ($settings['APP_SEND_EMAIL_AFTER_UPLOAD']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Debugging</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appDebug">
                          <option value="true" {{ ($settings['APP_DEBUG']['value'] == 'true') ? 'selected' : '' }}>
                            Enable</option>
                          <option {{ ($settings['APP_DEBUG']['value'] == 'false') ? 'selected' : '' }} value="false">
                            Disable</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Domain</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Applications domain name" required=""
                          name="appDomain" value="{{ $settings['APP_URL']['value'] }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">App Email</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control"
                          placeholder="Email id which will be used as sent from and reply to" required=""
                          name="appEmail" value="{{ $settings['APP_EMAIL']['value'] }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Timezone</label>
                      <div class="col-sm-8">
                        <select class="form-control selectpicker" data-live-search="true" required=""
                          name="appTimezone">
                          <option>Select...</option>
                          @foreach($timezones as $t)
                          <option value="{{$t['zone']}}"
                            {{ ($settings['APP_TIMEZONE']['value'] == $t['zone']) ? 'selected' : '' }}>
                            {{$t['diff_from_GMT'] . ' - ' . $t['zone'] }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Firewall</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appFirewall">
                          <option value="true"
                            {{ ($settings['FIREWALL_ENABLED']['value'] == 'true') ? 'selected' : '' }}>Enable</option>
                          <option {{ ($settings['FIREWALL_ENABLED']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group has-success">
                      <label class="col-sm-4 control-label">Maximum Share Validity (Days)</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="In Days" required=""
                          name="appShareUploadvalidity" value="{{ $settings['DELETE_FILES_IN_DAYS']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Maximum Allowed File Size (MB)</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Megabytes" required="" name="appFileSize"
                          value="{{ $settings['APP_MAX_FILE_SIZE']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Maximum Allowed File Number (Single Share)</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Number of files allowed in single upload"
                          required="" name="appFileQty" value="{{ $settings['APP_MAX_FILE_QTY']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Maximum Upload timeout (Millisecond)</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Milliseconds" required=""
                          name="appUploadTimeOut" value="{{ $settings['APP_UPLOAD_TIMEOUT']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save App Settings</button>
                      </div>
                    </div>
                  </form>

                  <p class="text-center" style="padding:20px;">
                    <b style="padding:20px;width:100%; float: left;">Please make sure to update your php.ini
                      settings</b>
                    <code
                      style="text-align:center;width:100%;float:left;padding: 20px; border:1px solid #ccc;">{!!$phpIniSettings!!}</code>
                  </p>
                </div>

              </div>

              <!-- Other Settings -->
              <div role="tabpanel" class="tab-pane" id="others">
                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/other">
                    {{ csrf_field() }}

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Site Logo (340x100)</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="/img/main-logo.png"
                          name="site_logo" value="{{ isset($otherSettings['site_logo']) ? $otherSettings['site_logo'] : '/img/main-logo.png' }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Title</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Site Title"
                          name="site_title" value="{{ isset($otherSettings['site_title']) ? $otherSettings['site_title'] : $settings['APP_NAME']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Keyword</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Site Keywords"
                          name="site_keywords" value="{{ isset($otherSettings['site_keywords']) ? $otherSettings['site_keywords'] : '' }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Description</label>
                      <div class="col-sm-8">
                        <textarea class="form-control" rows="5" name="site_desc">{!!  isset($otherSettings['site_desc']) ? $otherSettings['site_desc'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Google Analytics ID</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="UA-XXXXX-X"
                          name="site_google_an_id" value="{{ isset($otherSettings['site_google_an_id']) ? $otherSettings['site_google_an_id'] : '' }}">
                      </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Additional Header Scripts</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="extra_top_header">{!!  isset($otherSettings['extra_top_header']) ? $otherSettings['extra_top_header'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Additional Footer Scripts</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="extra_bottom_header">{!!  isset($otherSettings['extra_bottom_header']) ? $otherSettings['extra_bottom_header'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">About Page Content</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="20" name="about_page">{!!  isset($otherSettings['about_page']) ? $otherSettings['about_page'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Legal Page Content</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="20" name="legal_page">{!!  isset($otherSettings['legal_page']) ? $otherSettings['legal_page'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Help Page Content</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="20" name="help_page">{!!  isset($otherSettings['help_page']) ? $otherSettings['help_page'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save App Settings</button>
                      </div>
                    </div>

                  </form>

                </div>
              </div>


              <!-- Monetize Settings -->
              <div role="tabpanel" class="tab-pane" id="monetize">
                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/monetize">
                    {{ csrf_field() }}

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Link Monetization</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appLinkMonitize">
                          <option value="adf.ly"
                            {{ ($settings['ENABLE_LINK_MONETIZATION']['value'] == 'adf.ly') ? 'selected' : '' }}>adf.ly
                          </option>
                          <option value="shorte.st"
                            {{ ($settings['ENABLE_LINK_MONETIZATION']['value'] == 'shorte.st') ? 'selected' : '' }}>shorte.st
                          </option>
                          <option value="linkbucks"
                            {{ ($settings['ENABLE_LINK_MONETIZATION']['value'] == 'linkbucks') ? 'selected' : '' }}>linkbucks
                          </option>
                          <option {{ ($settings['ENABLE_LINK_MONETIZATION']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">adf.ly UID</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="adf.ly Account UID"
                          name="adflyUID" value="{{ $settings['ADFLY_UID']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">adf.ly API KEY</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="adf.ly API KEY"
                          name="adflyAPIKEY" value="{{ $settings['ADFLY_API_KEY']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Shorte.st API KEY</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Shorte.st API KEY"
                          name="shortestAPIKEY" value="{{ $settings['SHORTEST_API_KEY']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">LinkBucks API Username</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="LinkBucks API Username"
                          name="linkBucksUserName" value="{{ $settings['LINKBUCKS_API_USER']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">LinkBucks API KEY</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="LinkBucks API KEY"
                          name="linkBucksAPIKey" value="{{ $settings['LINKBUCKS_API_KEY']['value'] }}">
                      </div>
                    </div>

                    <hr />

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Enable Left Ad</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appEnableLeftAd">
                          <option value="true"
                            {{ ($settings['ENABLE_LEFT_COLUMN_AD']['value'] == 'true') ? 'selected' : '' }}>Enable
                          </option>
                          <option {{ ($settings['ENABLE_LEFT_COLUMN_AD']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Left Column Ad Html</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="adLeftCol">{!! isset($otherSettings['ad_left']) ? $otherSettings['ad_left'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr />

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Enable Right Ad</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appEnableRightAd">
                          <option value="true"
                            {{ ($settings['ENABLE_RIGHT_COLUMN_AD']['value'] == 'true') ? 'selected' : '' }}>Enable
                          </option>
                          <option {{ ($settings['ENABLE_RIGHT_COLUMN_AD']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Right Column Ad Html</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="adRightCol">{!! isset($otherSettings['ad_right']) ? $otherSettings['ad_right'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr />

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Enable Top Ad</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appEnableTopAd">
                          <option value="true"
                            {{ ($settings['ENABLE_TOP_NAV_AD']['value'] == 'true') ? 'selected' : '' }}>Enable
                          </option>
                          <option {{ ($settings['ENABLE_TOP_NAV_AD']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Top Ad Html</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="adTopCol">{!! isset($otherSettings['ad_top']) ? $otherSettings['ad_top'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <hr />

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Enable Bottom Ad</label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appEnableBottomAd">
                          <option value="true"
                            {{ ($settings['ENABLE_BOTTOM_AD']['value'] == 'true') ? 'selected' : '' }}>Enable
                          </option>
                          <option {{ ($settings['ENABLE_BOTTOM_AD']['value'] == 'false') ? 'selected' : '' }}
                            value="false">Disable</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Bottom Ad Html</label>
                      <div class="col-sm-8">
                        <textarea class="form-control textEditor" rows="5" name="adBottomCol">{!! isset($otherSettings['ad_bottom']) ? $otherSettings['ad_bottom'] : ''  !!}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save Monetization Settings</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>


              <!-- Email Settings -->
              <div role="tabpanel" class="tab-pane" id="email">

                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/emailsettings">
                    {{ csrf_field() }}

                    <div class="form-group has-success">
                      <label class="col-sm-4 control-label">Send Email Using <i style="color:red;">(Save to see options)</i></label>
                      <div class="col-sm-8">
                        <select class="form-control" required="" name="appMailSendUsing">
                          <option value="local"
                            {{ ($settings['APP_USE_MAIL_TYPE']['value'] == 'local') ? 'selected' : '' }}>Local ( php
                            mail() )</option>
                          <option value="smtp"
                            {{ ($settings['APP_USE_MAIL_TYPE']['value'] == 'smtp') ? 'selected' : '' }}>SMTP</option>
                          <option value="sendgrid-api"
                            {{ ($settings['APP_USE_MAIL_TYPE']['value'] == 'sendgrid-api') ? 'selected' : '' }}>SendGrid
                            API</option>
                          <option value="mailgun-api"
                            {{ ($settings['APP_USE_MAIL_TYPE']['value'] == 'mailgun-api') ? 'selected' : '' }}>MailGun
                            API</option>
                        </select>
                      </div>
                    </div>



                    <div class="form-group">
                      <label class="col-sm-4 control-label">Sent From Email</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Sent From Email address" required=""
                          name="appSentFromEmail" value="{{ $settings['MAIL_FROM_ADDRESS']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Sent From Name</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Sent From Name" required=""
                          name="appSentFromName" value="{{ $settings['MAIL_FROM_NAME']['value'] }}">
                      </div>
                    </div>

                    @if(($settings['APP_USE_MAIL_TYPE']['value'] == 'mailgun-api'))
                    <div class="form-group">
                      <label class="col-sm-4 control-label">App Domain</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Verified App Domain"
                          name="mailgunDomain" value="{{ $settings['APP_USE_MAIL_APP_DOMAIN']['value'] }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">API KEY</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="MailGun Api Key"
                          name="mailgunApiKey" value="{{ $settings['APP_USE_MAIL_PROVIDER_KEY']['value'] }}">
                      </div>
                    </div>

                    @endif

                    @if(($settings['APP_USE_MAIL_TYPE']['value'] == 'sendgrid-api'))
                    <div class="form-group">
                      <label class="col-sm-4 control-label">API KEY</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="MailGun Api Key"
                          name="sendgridApiKey" value="{{ $settings['APP_USE_MAIL_PROVIDER_KEY']['value'] }}">
                      </div>
                    </div>
                    @endif


                    @if(($settings['APP_USE_MAIL_TYPE']['value'] == 'smtp'))
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Host</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="SMTP Host" name="appSMTPHost"
                          value="{{ $settings['MAIL_HOST']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Port</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="SMTP Port" name="appSMTPPort"
                          value="{{ $settings['MAIL_PORT']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">User Name</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="SMTP User name" name="appSMTPUserName"
                          value="{{ $settings['MAIL_USERNAME']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Password</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="SMTP Password" name="appSMTPPassword"
                          value="{{ $settings['MAIL_PASSWORD']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Encryption</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="SMTP Encryption Type"
                          name="appSMTPEncryption" value="{{ $settings['MAIL_ENCRYPTION']['value'] }}">
                      </div>
                    </div>

                    @endif



                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save Email Settings</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>


              <!-- AWS Settings -->
              <div role="tabpanel" class="tab-pane" id="aws">

                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/awssettings">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Access Key</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="AWS Access Key" required=""
                          name="appAWSaccesskey" value="{{ $settings['AWS_ACCESS_KEY_ID']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Access Key Secret</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="AWS Access key secret" required=""
                          name="appAWSaccesskeysecret" value="{{ $settings['AWS_SECRET_ACCESS_KEY']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Region</label>
                      <div class="col-sm-8">
                        <select class="form-control selectpicker" data-live-search="true" required=""
                          name="appAWSregion">
                          <option>Select...</option>
                          <option value="us-east-2"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "us-east-2") ? 'selected' : '' }}>US East
                            (Ohio)</option>
                          <option value="us-east-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "us-east-1") ? 'selected' : '' }}>US East
                            (N. Virginia)</option>
                          <option value="us-west-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "us-west-1") ? 'selected' : '' }}>US West
                            (N. California)</option>
                          <option value="us-west-2"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "us-west-2") ? 'selected' : '' }}>US West
                            (Oregon)</option>
                          <option value="ca-central-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ca-central-1") ? 'selected' : '' }}>Canada
                            (Central)</option>
                          <option value="ap-south-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-south-1") ? 'selected' : '' }}>Asia
                            Pacific (Mumbai)</option>
                          <option value="ap-northeast-2"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-northeast-2") ? 'selected' : '' }}>Asia
                            Pacific (Seoul)</option>
                          <option value="ap-northeast-3"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-northeast-3") ? 'selected' : '' }}>Asia
                            Pacific (Osaka-Local) </option>
                          <option value="ap-southeast-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-southeast-1") ? 'selected' : '' }}>Asia
                            Pacific (Singapore)</option>
                          <option value="ap-southeast-2"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-southeast-2") ? 'selected' : '' }}>Asia
                            Pacific (Sydney)</option>
                          <option value="ap-northeast-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "ap-northeast-1") ? 'selected' : '' }}>Asia
                            Pacific (Tokyo)</option>
                          <option value="cn-north-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "cn-north-1") ? 'selected' : '' }}>China
                            (Beijing)</option>
                          <option value="cn-northwest-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "cn-northwest-1") ? 'selected' : '' }}>China
                            (Ningxia)</option>
                          <option value="eu-central-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "eu-central-1") ? 'selected' : '' }}>EU
                            (Frankfurt)</option>
                          <option value="eu-west-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "eu-west-1") ? 'selected' : '' }}>EU
                            (Ireland)</option>
                          <option value="eu-west-2"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "eu-west-2") ? 'selected' : '' }}>EU
                            (London)</option>
                          <option value="eu-west-3"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "eu-west-3") ? 'selected' : '' }}>EU (Paris)
                          </option>
                          <option value="sa-east-1"
                            {{ ($settings['AWS_DEFAULT_REGION']['value'] == "sa-east-1") ? 'selected' : '' }}>South
                            America (São Paulo)</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Bucket Name</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="AWS bucket name" required=""
                          name="appAWSbucket" value="{{ $settings['AWS_BUCKET']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save AWS Settings</button>
                      </div>
                    </div>
                  </form>

                </div>

              </div>


              <!-- Blackbaze Settings -->
              <div role="tabpanel" class="tab-pane" id="blackbaze">

                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/b2settings">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Account Id</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Blackbaze B2 account ID" required=""
                          name="appB2accountid" value="{{ $settings['B2_ACCOUNT_ID']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">App Key</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Blackbaze B2 App Key" required=""
                          name="appB2appkey" value="{{ $settings['B2_APP_KEY']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Bucket Name</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Blackbaze B2 bucket name" required=""
                          name="appB2bucket" value="{{ $settings['B2_BUCKET']['value'] }}">
                      </div>
                    </div>

                    <hr/>

                    <p class="text-center" style="padding:20px;">
                      <b style="padding:20px;width:100%; float: left;">Getting Backblaze B2 APP Key</b>
                      <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/FqjsGSdlO4s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </p>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save Blackbaze Settings</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

              <!-- Google Drive Settings -->
              <div role="tabpanel" class="tab-pane" id="googledrive">

                <div class="col-md-8 col-md-offset-2 well profile-section">
                  <form class="form-horizontal" method="post" action="/admin/settings/update/googledrive">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="col-sm-4 control-label">CLIENT ID</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Google Drive Client ID" required=""
                          name="appGoogleDClientID" value="{{ $settings['GOOGLE_DRIVE_CLIENT_ID']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">CLIENT SECRET</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Google Drive Client Secret" required=""
                          name="appGoogleDClientSecret" value="{{ $settings['GOOGLE_DRIVE_CLIENT_SECRET']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">REFRESH TOKEN</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Google Drive Refresh Token" required=""
                          name="appGoogleDClientRefreshToken" value="{{ $settings['GOOGLE_DRIVE_REFRESH_TOKEN']['value'] }}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">GOOGLE DRIVE FOLDER ID</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Google Drive Folder ID" required=""
                          name="appGoogleDClientFolderID" value="{{ $settings['GOOGLE_DRIVE_FOLDER_ID']['value'] }}">
                      </div>
                    </div>

                    <hr/>

                    <p class="text-center" style="padding:20px;">
                      <b style="padding:20px;width:100%; float: left;">Getting Client ID and Client Secret For Google Drive API</b>
                      <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/GP9CE05yTew" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </p>

                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-right">Save Google Drive Settings</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>



            </div>

            <!-- Content Ends -->


          </div>
          @stop


          @push('scripts')
          <script>
          $(function() {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function(e) {
              $(this).tab('show');
              var scrollmem = $('body').scrollTop();
              window.location.hash = this.hash;
              $('html,body').scrollTop(scrollmem);
            });

            $('.textEditor').each(function(index, elem){
                  CodeMirror.fromTextArea(elem, {
                    lineNumbers: true,
                    lineWrapping: true,
                    mode: "htmlmixed"
                  });
            });
          });
          </script>
          @endpush
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
