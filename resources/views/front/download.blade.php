@extends('front.layout.main')

@section('content')

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
              <img src="/img/main-logo.png" class="logo img-responsive" />
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

        <div class="col-md-6 col-xs-12">
          <div class="zipfile-data-container">

            <!-- File Download screen -->
            <div class="sub-user-section">
              <img src="/img/download-icon.svg" class="download-icon">
              <h3 class="sending-title">Ready to download</h3>
              <span class="upload-done-sub-text">{{count($downlaodData->files)}} files • {{ $downlaodData->fileSize }}
                • Expires in {{$downlaodData->validity}}</span>
              <ul class="list-unstyled download-filelist-container">
                @foreach($downlaodData->files as $files)
                <li class="download-file-item">
                  <span class="download-file-name pull-left">{{$files->file_original_name}}</span>
                  <a href="{{$files->downloadUrl}}" target="_blank" class="pull-right">
                    <img src="/img/download-icon-small-2.svg" class="download-icon-small">
                  </a>
                </li>
                @endforeach

              </ul>
              @if( config('app.filesystem') != 'google' )
              <button class="btn btn-lg btn-block zfl-button small-button-gap"
                onclick="window.location.href='/download/code/zip/{{$downlaodData->share_code}}'">DOWNLOAD ALL</button>
              @endif

              @if( config('app.filesystem') != 'blackblaze' )
              @if(config('app.app_send_email_after_upload'))
              <button class="btn btn-lg btn-block zfl-cancel-button last-bottom-gap"
                onclick="window.location.href='/share/code/{{$downlaodData->share_code}}'">SHARE</button>
              @endif
              @endif


            </div>
            <!-- File Download screen Ends -->


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
