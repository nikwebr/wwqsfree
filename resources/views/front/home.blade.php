@extends('front.layout.main')

@section('content')

<?php
	$maxAllowedFileSize = config('app.app_max_file_size');
	$maxAllowedFileQty = config('app.app_max_file_qty');
?>

<!-- Full Width Container -->
<div class="container-fluid">
  <div class="row">
    <!-- Main Container -->
    <div class="container">

      <!-- logo container -->
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <div class="logo-container">
            <a href="/">
              <img src="{{ (setting('site_logo')) ? setting('site_logo') : '/img/main-logo.png' }}" class="logo img-responsive" />
            </a>
            <h3 class="subtitle">SEND FILES TO ANYONE. FAST & FREE.</h3>
          </div>
        </div>
      </div>
      <!-- logo container ends-->

      <!-- main data container -->
      <div class="row">
        <div class="col-md-3 col-xs-1"></div>
        <div class="col-md-6 col-xs-10">
          <div class="row">
            <ul class="list-unstyled zipfile-page-nav">
              <li>
                <a href="/about">ABOUT</a>
              </li>
              <li>
                <a href="/help">HELP</a>
              </li>
              <li>
                <a href="/legal">LEGAL</a>
              </li>
            </ul>
					</div>

					<!-- Ad Module Nav Top -->
					<div class="row show-ad-space">
            @if(config('app.app_monitize_enable_ad_top'))
              {!! setting('ad_top') !!}
            @endif
					</div>

        </div>
        <div class="col-md-3 col-xs-1"></div>
      </div>

      <!-- main data container -->
      <div class="row last-bottom-gap">

        <!-- Ad Module Left column -->
        <div class="col-md-3 hidden-xs show-ad-space left-ad-module">
            @if(config('app.app_monitize_enable_ad_left'))
              {!! setting('ad_left') !!}
            @endif
        </div>

        <!-- Main Body -->
        <div class="col-md-6 col-xs-12">
          <div class="zipfile-data-container">

            <!-- Preadded files -->

            <!-- ends -->

            <!-- First Screen -->
            <div class="firstScreen">
              <!-- Dropzone -->
              <div class="file-drop-zone">
                <form action="/file-upload" class="dropzone" id="zipfileme-awesome-dropzone">

                  <div class="dz-message">
                    <div class="drag-icon-cph">
                      <i class="upload-logo"></i>
                    </div>
                    <h3 class="dz-pre-upload-title hidden-xs">Drag and drop your files here<br><sub>[Max File size :
                        {{$maxAllowedFileSize}}MB, Max Number of Files : {{$maxAllowedFileQty}}]</sub></h3>
                    <h3 class="dz-pre-upload-title hidden-sm hidden-md hidden-lg">Tap here to add a photo or
                      file<br><sub>[Max File size : {{$maxAllowedFileSize}}MB, Max Number of Files :
                        {{$maxAllowedFileQty}}]</sub></h3>

                  </div>

                </form>
              </div>
              <a style="display: none;" class="add-more-files" id="add-more-file"><img src="/img/upload-icon-small.svg"
                  class="upload-icon-small" />&nbsp;ADD MORE FILES</a>
              <!-- Dropzone Ends-->

              <!-- Recivers data -->

              <div class="sub-user-section">
                @if(config('app.app_send_email_after_upload'))
                <form>
                  <div class="form-group input-group-btn" id="email-container">
                    <label for="reciversEmail" class="zfl-intput-lebel">SEND TO</label>
                    <input type="hidden" name="" id="reciversEmail" value="">
                    <div class="input-group zfl-input-grp">
                      <input type="email" class="form-control zfl-input-style multiple with-button"
                        name="reciversEmail[]" placeholder="Email address" value="">
                      <span class="zfl-input-style-grp input-group-btn">
                        <button class="btn zfl-button add-more-email" type="button"><span
                            class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                      </span>
                    </div>

                  </div>
                  <div class="form-group">
                    <label for="senderEmail" class="zfl-intput-lebel ">FROM</label>
                    <input type="email" class="form-control zfl-input-style " id="senderEmail"
                      placeholder="Your email address"
                      value="{{ (!empty($oldShareData)) ? $oldShareData->sender_email : '' }}">
                  </div>
                  <div class="form-group">
                    <label for="senderNote" class="zfl-intput-lebel">MESSAGE</label>
                    <textarea class="form-control zfl-input-style textbox" rows="3" placeholder="Add a note (optional)"
                      id="senderNote">{{ (!empty($oldShareData)) ? $oldShareData->note : '' }}</textarea>
                  </div>
                </form>
                @else
                <form>
                  <div class="form-group">
                    <label for="senderEmail" class="zfl-intput-lebel ">YOUR EMAIL</label>
                    <input type="email" class="form-control zfl-input-style " id="senderEmail"
                      placeholder="Your email address"
                      value="{{ (!empty($oldShareData)) ? $oldShareData->sender_email : '' }}">
                  </div>
                </form>
                @endif


                @if(config('app.app_send_email_after_upload'))
                <button type="submit" class="btn btn-lg btn-block zfl-button" id="startSendingFiles">SEND</button>
                @else
                <button type="submit" class="btn btn-lg btn-block zfl-button" id="startSendingFiles">UPLOAD</button>
                @endif

              </div>
              <!-- Recivers data end -->

            </div>
            <!-- First Screen ends -->

            <!-- Secound Screen -->
            <div class="secoundScreen" style="display:none;">
              <!-- Uploading screen -->
              <div class="sub-user-section">
                <img src="/img/sending-icon.svg" class="sending-icon hidden-xs">
                <img src="/img/mobile-uploading-icon.svg" class="sending-icon hidden-sm hidden-md hidden-lg">
                <h3 class="sending-title">Sending...</h3>
                <span class="upload-sub-text">Uploading <span class="number-of-files">3</span> files</span>
                <div class="progress-status">
                  <div class="progress">
                    <div class="progress-bar upload-progress-style" role="progressbar" aria-valuenow="0"
                      aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                      <span class="sr-only">0% Complete</span>
                    </div>
                  </div>
                </div>
                <div class="upload-details">
                  <span class="pull-left-data total-uploaded-data">2.3MB/5MB</span>
                  <span class="pull-right-data total-upload-speed-data">456KB/s</span>
                </div>
                <button type="button"
                  class="btn btn-lg btn-block zfl-cancel-button bottom-gap cancelFileUpload">CANCEL</button>
              </div>
              <!-- Uploading screen Ends -->
            </div>
            <!-- Secound Screen ends-->

            <!-- Third Screen -->
            <div class="thirdScreen" style="display:none;">

              <!-- Uploading done screen -->
              <div class="sub-user-section">
                <img src="/img/sent-icon.svg" class="sent-icon" />

                @if(config('app.app_send_email_after_upload'))
                <h3 class="sending-title">Files sent!</h3>
                <span class="upload-done-sub-text" style="margin-bottom:20px;">We have sent an email to <span
                    class="email-sent-to"></span> with a download link. The link will expire in
                  {{config('app.app_delete_files_in_days')}} days</span>

                <div class="form-group input-group-btn" id="email-container" style="padding-bottom:20px;">
                  <div class="input-group zfl-input-grp">
                    <input id="sharing-linkid" type="text"
                      class="form-control zfl-input-style multiple with-button share-link-input"
                      placeholder="Sharing Link" value="" readonly>
                    <span class="zfl-input-style-grp input-group-btn">
                      <button class="btn zfl-button copyclip" type="button"
                        data-clipboard-target="#sharing-linkid"><span class="glyphicon glyphicon-paste"
                          aria-hidden="true"></span></button>
                    </span>
                  </div>

                </div>
                @else
                <h3 class="sending-title">Upload Completed!</h3>
                <span class="upload-done-sub-text" style="margin-bottom:20px;">Files are uploaded and ready to share.
                  The link will expire in {{config('app.app_delete_files_in_days')}} days</span>

                <div class="form-group input-group-btn" id="email-container" style="padding-bottom:20px;">
                  <div class="input-group zfl-input-grp">
                    <input id="sharing-linkid" type="text"
                      class="form-control zfl-input-style multiple with-button share-link-input"
                      placeholder="Sharing Link" value="" readonly>
                    <span class="zfl-input-style-grp input-group-btn">
                      <button class="btn zfl-button copyclip" type="button"
                        data-clipboard-target="#sharing-linkid"><span class="glyphicon glyphicon-paste"
                          aria-hidden="true"></span></button>
                    </span>
                  </div>

                </div>


                @endif
                <button type="button" class="btn btn-lg btn-block zfl-button last-bottom-gap newShareFile"
                  onclick="window.location.href='/'">SEND ANOTHER FILE</button>
              </div>

              <!-- Uploading done screen Ends -->
            </div>
            <!-- Third Screen End-->

					</div>

					<!-- Ad Module Bottom -->
					<div class="row bottom-ad-module show-ad-space">
            @if(config('app.app_monitize_enable_ad_bottom'))
              {!! setting('ad_bottom') !!}
            @endif
					</div>

        </div>

        <!-- Ad Module Right column -->
        <div class="col-md-3 col-xs-12 show-ad-space right-ad-module">
            @if(config('app.app_monitize_enable_ad_right'))
              {!! setting('ad_right') !!}
            @endif
        </div>

      </div>
      <!-- main data container -->

    </div>
    <!-- Main Container ends -->
  </div>
</div>
<!-- Full Width Container -->
@endsection

@section('custome-js')
<script>
window.ZipFileZSRF = "{{ csrf_token() }}";
window.totalFileSize = 0;
window.totalUploaded = 0;
window.totalProgress = 0;
window.totalProgressDone = 0;
window.totalFileCount = 0;
window.getCurrentShareCode = "{{ $shareCode }}";
window.shareCodeData = "";
window.oldShareCode = "";
</script>

@if( !empty($downlaodData) )
<script>
window.shareCodeData = '{!! $downlaodData !!}';
window.oldShareCode = "{{$oldShareCode}}";
</script>
@endif
@endsection
